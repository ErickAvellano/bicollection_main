<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Customer;
use App\Models\ShopDesign;
use App\Models\Application;
use App\Models\Order;
use App\Models\Favorite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $query = $request->input('search');
        $searchResults = collect();
        $favorites = [];
        $products = Product::with('merchant.shop')->get();

        if (!$user) {
            //redirect to home or login page
            return redirect()->route('home');
        }

        if ($user->isAdmin()) {
            if ($request->route()->getName() !== 'admin.dashboard') {
                return redirect()->route('admin.dashboard');
            }
        }

        if ($user->isMerchant()) {
            $shop = Shop::where('merchant_id', $user->user_id)->first();
            // Fetch only products with ratings for the "Popular Products" section
            $productsWithRatings = Product::whereHas('reviews', function ($query) {
                $query->where('rating', '>', 0);
            })->with('merchant.shop', 'reviews')->get();

            // Fallback to random products if no products with ratings
            if ($productsWithRatings->isEmpty()) {
                $products = Product::inRandomOrder()->take(10)->with('merchant.shop')->get();
            } else {
                $products = $productsWithRatings;
            }
            if ($shop) {
                // Check the registration step
                switch ($shop->registration_step) {
                    case 'Step1':
                        return $this->handleMerchantStep1($shop, $products ?? [], $recentlyAddedProducts ?? [], $searchResults);

                    case 'Completed':
                        return redirect()->route('mystore');
                }

                // Fetch merchant's favorite products
                $favorites = Favorite::where('merchant_id', $user->user_id)
                    ->pluck('product_id')
                    ->toArray();

                $favoriteProducts = Product::whereIn('id', $favorites)->get();
            }

            return view('dashboard', compact('shop', 'products', 'favorites', 'favoriteProducts'));
        } else {
            // Fetch customer favorites
            $favorites = Favorite::where('customer_id', $user->user_id)
                ->pluck('product_id')
                ->toArray();
        }

        // Fetch all products for the "All Products" section
        $allProducts = Product::with('merchant.shop')->get();

        // Fetch only products with ratings for the "Popular Products" section
        $productsWithRatings = Product::whereHas('reviews', function ($query) {
            $query->where('rating', '>', 0);
        })->with('merchant.shop', 'reviews')->get();

        // Fallback to random products if no products with ratings
        if ($productsWithRatings->isEmpty()) {
            $products = Product::inRandomOrder()->take(10)->with('merchant.shop')->get();
        } else {
            $products = $productsWithRatings;
        }

        // Fetch recently added products
        $recentlyAddedProducts = Product::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch featured products logic
        $shop = Shop::where('verification_status', 'Verified')->inRandomOrder()->first();
        $featuredProducts = collect();

        if ($shop) {
            $shopDesign = ShopDesign::where('shop_id', $shop->shop_id)->first();

            if ($shopDesign && $shopDesign->featuredProduct) {
                $featuredProductIds = explode(',', $shopDesign->featuredProduct);
                $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->with('reviews')->take(4)->get();
            }

            if ($featuredProducts->isEmpty()) {
                $featuredProducts = Product::where('merchant_id', $shop->merchant_id)->inRandomOrder()->take(3)->with('reviews')->get();
            }
        }

        return view('dashboard', compact('allProducts', 'products', 'shop', 'featuredProducts', 'recentlyAddedProducts', 'searchResults', 'favorites'));
    }


    // Helper method to handle merchant step 1 logic
    private function handleMerchantStep1($shop, $products, $recentlyAddedProducts, $searchResults)
    {
        $shop = Shop::where('verification_status', 'Verified')->inRandomOrder()->first();

        $featuredProducts = collect();

        // Fetch all products for the "All Products" section
        $allProducts = Product::with('merchant.shop')->get();

        // Fetch only products with ratings for the "Popular Products" section
        $productsWithRatings = Product::whereHas('reviews', function ($query) {
            $query->where('rating', '>', 0);
        })->with('merchant.shop', 'reviews')->get();

        // Fallback to random products if no products with ratings
        if ($productsWithRatings->isEmpty()) {
            $products = Product::inRandomOrder()->take(10)->with('merchant.shop')->get();
        } else {
                $products = $productsWithRatings;
            }

        if ($shop) {
            $shopDesign = ShopDesign::where('shop_id', $shop->shop_id)->first();

            if ($shopDesign && $shopDesign->featuredProduct) {
                $featuredProductIds = explode(',', $shopDesign->featuredProduct);
                $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get();
            }

            if ($featuredProducts->isEmpty()) {
                $featuredProducts = Product::where('merchant_id', $shop->merchant_id)->inRandomOrder()->take(3)->get();
            }
        }

        return view('dashboard', compact('allProducts','products', 'shop', 'featuredProducts', 'recentlyAddedProducts', 'searchResults'));
    }

}

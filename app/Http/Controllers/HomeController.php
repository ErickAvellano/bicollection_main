<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopDesign;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Check if the user
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $query = $request->input('search');
        $searchResults = collect();

        // Fetch all products with their merchant and shop information
        $products = Product::with('merchant.shop')->get();

        $recentlyAddedProducts = Product::whereHas('merchant.shop', function ($query) {
            $query->where('verification_status', 'Verified');
        })->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('created_at', 'desc')
            ->get();

        // Initialize the featuredProducts collection
        $featuredProducts = collect();

          // Fetch all products for the "All Products" section
          $allProducts = Product::with('merchant.shop')
            ->whereHas('merchant.shop', function ($query) {
                $query->where('verification_status', 'Verified');
            })
            ->get();

        // Fetch only products with ratings for the "Popular Products" section
        $productsWithRatings = Product::whereHas('merchant.shop', function ($query) {
            $query->where('verification_status', 'Verified');
        })->whereHas('reviews', function ($query) {
            $query->where('rating', '>', 0);
        })->with('merchant.shop', 'reviews')->get();

        // Fallback to random products if no products with ratings
        if ($productsWithRatings->isEmpty()) {
            $products = Product::inRandomOrder()->take(10)->with('merchant.shop')->get();
        } else {
            $products = $productsWithRatings;
        }

        // Continue with featuredProducts logic
        $shop = Shop::where('verification_status', 'Verified')->inRandomOrder()->first();
        $featuredProducts = collect();

        if ($shop) {
            $shopDesign = ShopDesign::where('shop_id', $shop->shop_id)->first();

            if ($shopDesign && $shopDesign->featuredProduct) {
                $featuredProductIds = explode(',', $shopDesign->featuredProduct);
                $featuredProducts = Product::whereIn('product_id', $featuredProductIds)
                    ->with('reviews')
                    ->take(4)
                    ->get();
            }

            if ($featuredProducts->isEmpty()) {
                $featuredProducts = Product::where('merchant_id', $shop->merchant_id)
                    ->inRandomOrder()
                    ->take(3)
                    ->with('reviews')
                    ->get();
            }


        }

        return view('home', compact('allProducts', 'products', 'shop', 'featuredProducts', 'recentlyAddedProducts', 'searchResults'));
    }
}

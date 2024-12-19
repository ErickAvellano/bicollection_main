<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopDesign;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use App\Models\ShopVisitLog;

class StoreController extends Controller
{
    public function viewStore($shopId)
    {
        // Fetch the shop using shopId
        $shop = Shop::find($shopId);

        $visitorCount = DB::table('shop_visit_logs')
            ->where('shop_id', $shopId)
            ->value('click_count');

        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }
         // Update or Insert the shop visit log
        ShopVisitLog::updateOrInsert(
            ['shop_id' => $shop->shop_id], // Match the shop_id
            ['click_count' => DB::raw('click_count + 1')] // Increment the click count
        );

        // Fetch all products for the shop
        $products = Product::where('merchant_id', $shop->merchant_id)
            ->with('images', 'variations', 'reviews') // Include reviews
            ->get();

        // Add averageRating to each product
        foreach ($products as $product) {
            $product->averageRating = $product->reviews->avg('rating') ?? 0; 
        }

        // Fetch featured products based on IDs
        $featuredProductIds = explode(',', $shop->featuredProduct ?? '');
        $featuredProducts = Product::whereIn('product_id', $featuredProductIds)
            ->with('images', 'reviews') 
            ->get();

        // Add averageRating to each featured product
        foreach ($featuredProducts as $product) {
            $product->averageRating = $product->reviews->avg('rating') ?? 0; 
        }

        // Pass the necessary data to the view
        return view('merchant.viewstore', compact('shop', 'products', 'visitorCount', 'featuredProducts'));
    }



    public function getPartial($nav, $shopId)
    {
        // Map the data-nav values to the actual view names
        $viewMap = [
            'home' => 'customerhome',
            'allproduct' => 'customerallproduct'
        ];

        // Validate the tab name
        if (array_key_exists(strtolower($nav), $viewMap)) {
            $shop = Shop::find($shopId);
            if (!$shop) {
                return response('Shop not found', 404);
            }

            $merchant = $shop->merchant;
            $products = Product::where('merchant_id', $merchant->merchant_id)->with('images')->get();

            $shopDesign = ShopDesign::where('shop_id', $shop->shop_id)->first();
            $featuredProductIds = explode(',', $shopDesign->featuredProduct);
            $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get();


            return view("merchant.partials." . $viewMap[strtolower($nav)], compact('featuredProducts', 'products'));
        }

        // Fallback if the tab is not valid
        return response('Not Found', 404);
    }
    public function showMerchants()
    {
        $shops = Shop::with('applications')
            ->leftJoin('product', 'product.merchant_id', '=', 'shop.merchant_id')
            ->leftJoin('product_reviews', 'product_reviews.product_id', '=', 'product.product_id')
            ->select(
                'shop.shop_id',
                'shop.merchant_id',
                'shop.shop_name',
                'shop.description',
                'shop.shop_img',
                'shop.coverphotopath',
                'shop.shop_street',
                'shop.province',
                'shop.city',
                'shop.barangay',
                'shop.verification_status',
                DB::raw('AVG(product_reviews.merchant_service_rating) AS avg_merchant_service_rating')
            )
            ->where('shop.verification_status', 'Verified')
            ->groupBy(
                'shop.shop_id',
                'shop.merchant_id',
                'shop.shop_name',
                'shop.description',
                'shop.shop_img',
                'shop.coverphotopath',
                'shop.shop_street',
                'shop.province',
                'shop.city',
                'shop.barangay',
                'shop.verification_status'
            )
            ->get();

        if ($shops->isEmpty()) {
            return redirect()->back()->with('error', 'No verified shops found.');
        }

        // Safely process applications and decode categories
        foreach ($shops as $shop) {
            if ($shop->applications) {
                foreach ($shop->applications as $application) {
                    $application->decoded_categories = !empty($application->categories)
                        ? json_decode($application->categories, true)
                        : [];
                }
            }
        }

        // Pass the processed data to the view
        return view('stores.showmerchants', compact('shops'));
    }




}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopDesign;

class StoreController extends Controller
{
    public function viewStore($shopId)
    {
        // Fetch the shop using shopId
        $shop = Shop::find($shopId);

        // Check if the shop exists
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }

        // Fetch products using the merchant_id from the shop
        $products = Product::where('merchant_id', $shop->merchant_id)->with('images', 'variations')->get();

        // Fetch featured products based on IDs (assuming you have this logic)
        $featuredProductIds = explode(',', $shop->featuredProduct ?? ''); // Get featured product IDs
        $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->with('images')->get(); // Fetch featured products

        // Pass the necessary data to the view
        return view('merchant.viewstore', compact('shop', 'products', 'featuredProducts'));
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

            // Fetch featured products based on IDs in shop design
            $shopDesign = ShopDesign::where('shop_id', $shop->shop_id)->first();
            $featuredProductIds = explode(',', $shopDesign->featuredProduct);
            $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get();

            // Use the mapped view name to load the correct partial view
            return view("merchant.partials." . $viewMap[strtolower($nav)], compact('featuredProducts', 'products'));
        }

        // Fallback if the tab is not valid
        return response('Not Found', 404);
    }

}

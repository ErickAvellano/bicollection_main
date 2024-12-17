<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopDesign;
use App\Models\Application;
class StoreController extends Controller
{
    public function viewStore($shopId)
    {
        // Fetch the shop using shopId
        $shop = Shop::find($shopId);

        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }

        $products = Product::where('merchant_id', $shop->merchant_id)->with('images', 'variations')->get();

        // Fetch featured products based on IDs
        $featuredProductIds = explode(',', $shop->featuredProduct ?? '');
        $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->with('images')->get();

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
        // Fetch verified shops with applications
        $shops = Shop::with('applications')
            ->select(
                'shop_id',
                'merchant_id',
                'shop_name',
                'description',
                'shop_img',
                'coverphotopath',
                'shop_street',
                'province',
                'city',
                'barangay',
                'verification_status'
            )
            ->where('verification_status', 'Verified')
            ->get();
    
        if ($shops->isEmpty()) {
            return redirect()->back()->with('error', 'No verified shops found.');
        }
    
        // Safely process applications and decode categories
        foreach ($shops as $shop) {
            foreach ($shop->applications ?? [] as $application) {
                if (!empty($application->categories)) {
                    // Decode categories
                    $decodedCategories = json_decode($application->categories, true);
        
                    // Replace "Products" with an empty string
                    $processedCategories = array_map(function ($category) {
                        return $category === 'Products' ? '' : $category;
                    }, $decodedCategories);
        
                    $application->decoded_categories = $processedCategories;
                } else {
                    $application->decoded_categories = [];
                }
            }
        }
    
        // Pass the processed data to the view
        return view('stores.showmerchants', compact('shops'));
    }
    


}

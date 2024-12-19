<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\ShopDesign;
use App\Models\MerchantMop;
use App\Models\ProductImage;

class ShopController extends Controller
{
    public function showStore()
    {
        $user = Auth::user(); 

        $shop = $user->shop; 

        $merchant = $user->merchant;

        $merchantMop = MerchantMop::where('merchant_id', $merchant->merchant_id)->first();

        // Check if the shop exists
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }

        // Get the shop ID
        $shopId = $shop->shop_id;

        $visitorCount = DB::table('shop_visit_logs')
            ->where('shop_id', $shopId)
            ->value('click_count');

        // Check if a shop design exists for this shop
        $shopDesign = ShopDesign::where('shop_id', $shopId)->first();

        // If no shop design exists, create a new one
        if (!$shopDesign) {
            $shopDesign = new ShopDesign();
            $shopDesign->shop_id = $shopId; 
            $shopDesign->featuredProduct = ''; 
            $shopDesign->save(); 
        }

        // Get the shop design ID
        $shopDesignId = $shopDesign->shop_design_id;

        $display1 = $shopDesign->display1;
        $display2 = $shopDesign->display2;

        // Fetch all products related to the shop
        $products = Product::where('merchant_id', $user->user_id)->with('images', 'variations')->get();

        // Fetch featured products based on IDs
        $featuredProductIds = explode(',', $shopDesign->featuredProduct); 
        $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get();

        // Pass the necessary data to the view
        return view('merchant.mystore', compact('shop', 'merchant', 'merchantMop', 'visitorCount', 'shopId', 'shopDesignId', 'products', 'featuredProducts', 'featuredProductIds', 'display1', 'display2'));
    }
    public function updateImages(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;

        // Validate the uploaded files
        $request->validate([
            'shop_img' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            'cover_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        // Handling profile image
        if ($request->hasFile('shop_img')) {
            // Store the new profile image
            $profileImagePath = $request->file('shop_img')->store('merchant/profile', 'public');

            if ($profileImagePath) {
                // Delete the old profile image if it exists
                if ($shop->shop_img && Storage::exists('public/' . $shop->shop_img)) {
                    Storage::delete('public/' . $shop->shop_img);
                }

                // Save new image path in the database
                $shop->shop_img = $profileImagePath;
            } else {
                return redirect()->route('mystore')->with('error', 'Failed to save profile image.');
            }
        }

        // Handling cover photo
        if ($request->hasFile('cover_photo')) {
            // Store the new cover photo
            $coverPhotoPath = $request->file('cover_photo')->store('merchant/coverphoto', 'public');

            if ($coverPhotoPath) {
                // Delete the old cover photo if it exists
                if ($shop->coverphotopath && Storage::exists('public/' . $shop->coverphotopath)) {
                    Storage::delete('public/' . $shop->coverphotopath);
                }

                // Save new image path in the database
                $shop->coverphotopath = $coverPhotoPath;
            } else {
                return redirect()->route('mystore')->with('error', 'Failed to save cover photo.');
            }
        }

        // Save the shop changes to the database
        $shop->save();

        return redirect()->route('mystore')->with('success', 'Images updated successfully.');
    }
    public function getPartial($nav)
    {
        // Validate the tab name
        $validTabs = ['home', 'allproduct', 'category', 'more'];

        // Check if the provided tab is valid
        if (in_array(strtolower($nav), $validTabs)) {
            $user = Auth::user();
            $shop = $user->shop; 

            if (!$shop) {
                return response('Shop not found', 404);
            }

            $shopId = $shop->shop_id;

            $shopDesign = ShopDesign::where('shop_id', $shopId)->first();

            // If no shop design exists, create a new one
            if (!$shopDesign) {
                $shopDesign = new ShopDesign();
                $shopDesign->shop_id = $shopId; 
                $shopDesign->featuredProduct = ''; 
                $shopDesign->save(); 
            }

            // Get the shop design ID
            $shopDesignId = $shopDesign->shop_design_id;

            $display1 = $shopDesign->display1;
            $display2 = $shopDesign->display2;

            // Fetch the products related to the shop
            $products = Product::where('merchant_id', $user->user_id)->with('images', 'variations')->get();

            // Fetch featured products based on IDs in shop design
            $featuredProductIds = explode(',', $shopDesign->featuredProduct);
            $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get();

            // Handle specific tab views and pass necessary data
            if ($nav == 'home') {
                return view('merchant.partials.home', compact('featuredProducts', 'products', 'display1', 'display2')); 
            }

            if ($nav == 'allproduct') {
                return view('merchant.partials.all-products', compact('products')); 
            }

            return view("merchant.partials.$nav");
        }

        return response('Not Found', 404);
    }

}

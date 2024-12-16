<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopDesign;

class FeaturedProductController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'shop_design_id' => 'required|exists:shop_design,shop_design_id',
            'shop_id' => 'required|exists:shop,shop_id',
            'featuredProduct' => 'required|string',
             // Ensure shop_id is passed and valid
        ]);

        // Check if the shop design record exists
        $shopDesign = ShopDesign::find($request->shop_design_id);

        if (!$shopDesign) {
            $shopDesign = new ShopDesign();
            $shopDesign->shop_id = $request->shop_id; 
        }

        // Update the featured products
        $shopDesign->featuredProduct = $request->featuredProduct;

        $shopDesign->save();

        // success message
        return redirect()->back()->with('success', 'Featured products added successfully.');
    }
    
}

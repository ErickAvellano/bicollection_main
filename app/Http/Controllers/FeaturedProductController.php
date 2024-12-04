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

        // If it doesn't exist, create a new entry
        if (!$shopDesign) {
            $shopDesign = new ShopDesign();
            $shopDesign->shop_id = $request->shop_id; // Set the shop_id
        }

        // Update the featured products as a comma-separated string
        $shopDesign->featuredProduct = $request->featuredProduct;

        // Save the shop design (either creating or updating)
        $shopDesign->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Featured products added successfully.');
    }
    
}

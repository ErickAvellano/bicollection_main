<?php

namespace App\Http\Controllers;

use App\Models\ProductCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductCustomizationController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request
        Log::info('Product Customization Request:', $request->all());

        // Validate the request data
        $validated = $request->validate([
            'material' => 'required|string|max:50',
            'rim_color' => 'required|string|max:50',
            'body_color' => 'required|string|max:50',
            'base_color' => 'required|string|max:50',
            'pattern' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'customer_id' => 'required|integer|exists:customer,customer_id',
            'merchant_id' => 'required|integer|exists:merchant,merchant_id',
        ]);

        // Log the validated data
        Log::info('Validated Product Customization Data:', $validated);

        // Create a new ProductCustomization record
        $customization = ProductCustomization::create($validated);

        // Log the created customization
        Log::info('Product Customization Created:', ['customization' => $customization]);

        // Return response
        return response()->json(['message' => 'Customization saved!', 'data' => $customization], 201);
    }

}

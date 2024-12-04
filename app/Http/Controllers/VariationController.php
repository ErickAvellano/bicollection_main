<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;

class VariationController extends Controller
{
    public function destroy($id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();

        return redirect()->back()->with('success', 'Variation deleted successfully');
    }
}



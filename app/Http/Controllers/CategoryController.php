<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;

use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    public function index()
    {
        // Fetch categories from the database (assuming you have a Category model)
        $categories = Category::all();

        // Pass categories to the view
        return view('category.index', compact('categories'));
    }
    public function showProduct(Request $request, $category_name)
    {
        // Get the authenticated user or set to null
        $user = Auth::user();
        $favorites = [];
    
        if ($user) {
            // Fetch user's favorites if logged in
            $favorites = Favorite::where('customer_id', $user->user_id)
                ->pluck('product_id')
                ->toArray();
        }
    
        // Find category and subcategories
        $category = Category::where('category_name', $category_name)->firstOrFail();
        $subcategories = Category::where('parentcategoryID', $category->category_id)->get();
        $subcategory_ids = $subcategories->pluck('category_id')->toArray();
        $all_category_ids = array_merge([$category->category_id], $subcategory_ids);
    
        // Fetch products for category and subcategories
        $products = Product::with('images')->whereIn('category_id', $all_category_ids)->get();
    
        // Handle AJAX request for "All Products"
        if ($request->ajax()) {
            return view('category.partials.product-list', compact('products', 'favorites'))->render();
        }
    
        // Return full view for normal requests
        return view('category.products', compact('category', 'subcategories', 'products', 'favorites'));
    }


    public function showSubProducts(Request $request, $subcategory_id)
    {
        
        try {
            $user = Auth::user(); // Get the authenticated user
            $favorites = [];

            if ($user) {
                // Fetch the user's favorites if logged in
                $favorites = Favorite::where('customer_id', $user->user_id)
                    ->pluck('product_id')
                    ->toArray();
            }
            // Find the subcategory
            $subcategory = Category::findOrFail($subcategory_id);

            // Fetch products only for this subcategory
            $products = Product::where('subcategory_id', $subcategory_id)->get();

            // Check if the request is AJAX
            if ($request->ajax()) {
                return view('category.partials.product-list', compact('products', 'favorites'))->render();
            }
            $user = Auth::user();
            $favorites = Favorite::where('customer_id', $user->user_id)
            ->pluck('product_id') // Get only the product IDs
            ->toArray();
            // For normal requests, return the full view
            return view('subcategory.products', compact('subcategory', 'products', 'favorites'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function showCategories()
    {
        // Fetch categories with no parent category
        $categories = Category::whereNull('parentcategoryID')->get();

        // Return the view with categories
        return view('components.layout', compact('categories'));
    }
}

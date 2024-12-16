<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryIds = $request->input('category_ids', []); 
        $priceRanges = $request->input('price_ranges', []);
        $sort = $request->input('sort', 'best_match');
        $newArrivals = $request->input('new_arrivals', 0);

        // Start with products matching the search query
        $products = Product::where('product_name', 'like', '%' . $query . '%');

        // Filter by price ranges
        if ($priceRanges) {
            $products = $products->where(function ($query) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    // Split the price range into min and max prices
                    [$minPrice, $maxPrice] = explode('-', $range);
                    $query->orWhereBetween('price', [intval($minPrice), intval($maxPrice)]);
                }
            });
        }

        // Apply category filter if categories are provided
        if ($categoryIds) {
            $products = $products->whereIn('category_id', $categoryIds);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_low_high':
                $products = $products->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $products = $products->orderBy('price', 'desc');
                break;
            default:
                $products = $products->orderBy('created_at', 'desc'); // Default to 'Best Match'
                break;
        }

        // Fetch the final products
        $products = $products->get();

        // Get categories related to the search products
        $productCategories = $products->pluck('category_id')->unique();
        $categories = Category::whereIn('category_id', $productCategories)->get();

        // Map over products to include image URLs
        $products->map(function ($product) {
            $productImage = ProductImg::where('product_id', $product->product_id)->first();
            $product->image_url = $productImage && $productImage->product_img_path1 
                ? Storage::url($productImage->product_img_path1) 
                : 'https://via.placeholder.com/150';
            return $product;
        });

        // New arrivals count (last 30 days)
        $newArrivalsCount = Product::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Count products per category
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->category_id] = Product::where('category_id', $category->category_id)
                ->where('product_name', 'like', '%' . $query . '%')
                ->count();
        }

        // Check if the request is an AJAX call
        if ($request->ajax()) {
            $html = view('partials.product_list', compact('products'))->render();
            return response()->json([
                'html' => $html,
                'newArrivalsCount' => $newArrivalsCount
            ]);
        }

        // Return the search results view with the products, categories, and search query
        return view('searchresult', [
            'products' => $products,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts, 
            'query' => $query,
            'product_count' => $products->count(),
            'sort' => $sort,
            'newArrivals' => $newArrivals,
            'newArrivalsCount' => $newArrivalsCount,
        ]);
    }
}

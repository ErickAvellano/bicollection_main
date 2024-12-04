<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    // Show the Favorites Page
    public function showFavorites()
    {
        $user = Auth::user();
    
        // Retrieve favorite items for the authenticated user
        $favorites = Favorite::where('customer_id', $user->user_id)
            ->with('product.images') // Load product and image details
            ->get();
    
        // Extract product IDs for checking
        $favoriteProductIds = $favorites->pluck('product_id')->toArray();
    
        // Pass data to the view
        return view('profile.favorite', compact('favorites', 'favoriteProductIds'));
    }
    



    // Add a Product to Favorites
    public function add(Request $request, $productId)
    {
        try {
            $user = Auth::user();

            // Check if the product already exists in favorites
            $existingFavorite = Favorite::where('customer_id', $user->user_id)
                ->where('product_id', $productId)
                ->first();

            if ($existingFavorite) {
                return response()->json(['success' => true, 'message' => 'Product is already in your favorites.']);
            }

            // Add product to favorites
            $favorite = Favorite::create([
                'customer_id' => $user->user_id,
                'product_id' => $productId,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Added to favorites!']);

        } catch (\Exception $e) {
            Log::error('Error adding to favorites', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to add to favorites.'], 500);
        }
    }

    // Remove a Product from Favorites
    public function remove($favoriteId)
    {
        $favorite = Favorite::where('favorite_id', $favoriteId)
            ->where('customer_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'message' => 'Removed from favorites.']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
    }

    // Fetch Count of Favorite Items (For Frontend Badges)
    public function getFavoriteItemCount()
    {
        $user = Auth::user();
        $favoriteItemCount = Favorite::where('customer_id', $user->user_id)->count();

        return response()->json(['favoriteItemCount' => $favoriteItemCount]);
    }
    public function toggle(Request $request, $productId)
    {
        $user = Auth::user();

        $favorite = Favorite::where('customer_id', $user->user_id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Unfavorite the product (remove it from favorites)
            $favorite->delete();
            return response()->json(['success' => true, 'message' => 'Removed from favorites.']);
        } else {
            // Add the product to favorites
            Favorite::create([
                'customer_id' => $user->user_id,
                'product_id' => $productId,
                'created_at' => now(),
            ]);
            return response()->json(['success' => true, 'message' => 'Added to favorites.']);
        }
    }
    public function count()
    {
        $user = Auth::user();
        $count = Favorite::where('customer_id', $user->user_id)->count();

        return response()->json(['count' => $count]);
    }


}

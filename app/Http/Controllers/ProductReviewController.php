<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class ProductReviewController extends Controller
{
    public function showRatingPage($rating, $productId)
    {
        return view('orders.rating', compact('rating', 'productId'));
    }

    public function store(Request $request)
    {
        // Validate data
        $validator = Validator::make($request->all(), [
            'product_id' => 'required', 
            'order_id' => 'required|integer',
            'variation_id' => 'nullable|string', 
            'rating' => 'required|integer|min:1|max:5',
            'performance' => 'required|string|max:1000',
            'merchant_service_rating' => 'required|integer|min:1|max:5',
            'platform_rating' => 'required|integer|min:1|max:5',
            'username' => 'required|string|max:255',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Split product_id and variation_id into arrays
        $productIds = explode(',', $request->product_id);
        $variationIds = explode(',', $request->variation_id);

        $createdReviews = [];

        foreach ($productIds as $index => $productId) {
            // Trim the product and variation IDs to ensure no extra spaces
            $productId = trim($productId);
            $variationId = isset($variationIds[$index]) ? trim($variationIds[$index]) : null;

            // Check if a review already exists for this product and order
            $existingReview = ProductReview::where('product_id', $productId)
                ->where('order_id', $request->order_id)
                ->first();

            if ($existingReview) {
                continue; // Skip this product if a review already exists
            }

            // Handle individual image uploads
            $imagePaths = [];
            for ($i = 1; $i <= 5; $i++) {
                $imageField = "image_$i";
                if ($request->hasFile($imageField)) {
                    try {
                        $path = $request->file($imageField)->store('product_reviews', 'public');
                        $imagePaths[$imageField] = $path;
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'error', 'message' => 'Failed to upload image. Please try again.'], 500);
                    }
                } else {
                    $imagePaths[$imageField] = null;
                }
            }

            // Save the review for this product
            try {
                $review = ProductReview::create([
                    'product_id' => $productId,
                    'order_id' => $request->order_id,
                    'customer_id' => Auth::id(),
                    'product_variation_id' => $variationId, 
                    'username' => $request->username,
                    'rating' => $request->rating,
                    'review_text' => $request->performance,
                    'review_date' => now(),
                    'is_approved' => 0,
                    'merchant_service_rating' => $request->merchant_service_rating,
                    'platform_rating' => $request->platform_rating,
                    'image_1' => $imagePaths['image_1'],
                    'image_2' => $imagePaths['image_2'],
                    'image_3' => $imagePaths['image_3'],
                    'image_4' => $imagePaths['image_4'],
                    'image_5' => $imagePaths['image_5'],
                ]);

                $createdReviews[] = $review;
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Failed to submit review. Please try again.'], 500);
            }
        }

        // Update the order status
        try {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->order_status = 'completed';
                $order->updated_at = now();
                $order->save();
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update order status.'], 500);
        }

        return response()->json(['success' => true, 'reviews' => $createdReviews]);
    }



 


}

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
    // Handle rating submission
    public function showRatingPage($rating, $productId)
    {
        // Render the view with the parameters
        return view('orders.rating', compact('rating', 'productId'));
    }
    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Review submission request received', ['request_data' => $request->all()]);

        // Validate the request data, including optional images
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'order_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'performance' => 'required|string|max:1000',
            'merchant_service_rating' => 'required|integer|min:1|max:5',
            'platform_rating' => 'required|integer|min:1|max:5',
            'username' => 'required|string|max:255',
            'image_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_5' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            // Log validation errors
            Log::error('Review validation failed', ['errors' => $validator->errors()]);
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Handle file uploads
        $imagePaths = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile("image_$i")) {
                try {
                    $path = $request->file("image_$i")->store('product_reviews', 'public');
                    $imagePaths["image_$i"] = $path;
                } catch (\Exception $e) {
                    // Log file upload errors
                    Log::error('File upload failed', ['image_index' => $i, 'error' => $e->getMessage()]);
                    return response()->json(['status' => 'error', 'message' => 'Failed to upload image. Please try again.'], 500);
                }
            } else {
                $imagePaths["image_$i"] = null;
            }
        }

        // Attempt to save the review in the database
        try {
            $review = ProductReview::create([
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'customer_id' => Auth::id(),
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

            // Log review creation success
            Log::info('Review created successfully', ['review_id' => $review->id]);
        } catch (\Exception $e) {
            // Log review creation errors
            Log::error('Failed to create review', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to submit review. Please try again.'], 500);
        }

        // Update the order status
        try {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->order_status = 'completed';
                $order->updated_at = now();
                $order->save();

                // Log order status update success
                Log::info('Order status updated successfully', ['order_id' => $order->id, 'status' => $order->order_status]);
            }
        } catch (\Exception $e) {
            // Log order update errors
            Log::error('Failed to update order status', ['order_id' => $request->order_id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to update order status.'], 500);
        }

        // Return success response
        Log::info('Review submitted successfully');
        return response()->json(['status' => 'success', 'message' => 'Review submitted successfully!']);
    }
}

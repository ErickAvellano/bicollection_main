<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\Cart;

class CartController extends Controller
{
    public function showCart()
    {
        $user = Auth::user();

        // Retrieve cart items for the authenticated user
        $cartItems = Cart::where('customer_id', $user->user_id)
            ->where('status', 'active') // Get only active items
            ->with('product.shop') // Load product and shop details to display
            ->get();

        if ($cartItems->isNotEmpty()) {
            // Group items by shop so that products from the same shop are grouped together
            $groupedCartItems = $cartItems->groupBy('product.shop.shop_name');
        } else {
            $groupedCartItems = collect(); // Empty collection if no cart items
        }

        // Calculate subtotal, shipping, and other summary details
        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });
        $shippingCost = 10; // Assuming a flat shipping rate
        $packagingCost = 0; // Assuming no packaging cost for now
        $totalAmount = $subtotal + $shippingCost + $packagingCost;

        // Pass data to the view
        return view('profile.cart', compact('groupedCartItems', 'subtotal', 'shippingCost', 'packagingCost', 'totalAmount'));
    }


    public function add(Request $request, $productId)
    {
        try {
            $user = Auth::user();

            // Log the product and user information for debugging
            Log::info('Add to Cart Attempt', ['user' => $user, 'productId' => $productId]);

            // Retrieve the product by ID
            $product = Product::with('images')->find($productId);

            if (!$product) {
                Log::error('Product not found', ['productId' => $productId]);
                return response()->json(['error' => 'Product not found.'], 404);
            }

            // Check if the product is already in the cart
            $cartItem = Cart::where('customer_id', $user->user_id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // If it exists, update the quantity
                $cartItem->quantity++;
                $cartItem->save();
            } else {
                // Create a new cart entry and assign it to $cartItem
                $cartItem = Cart::create([
                    'customer_id' => $user->user_id,
                    'merchant_id' => $product->merchant_id,
                    'quantity' => 1,
                    'status' => 'active',
                    'product_id' => $productId,
                ]);
            }

            // Calculate cart totals
            $cartTotal = $cartItem->quantity * $product->price;
            $cartItemCount = Cart::where('customer_id', $user->user_id)->count();
            $totalCartAmount = Cart::where('customer_id', $user->user_id)->sum('quantity');

            // Retrieve the first product image
            $productImage = $product->images->first() ? $product->images->first()->product_img_path1 : null;

            // Return the response with updated data
            return response()->json([
                'success' => 'Product added to cart successfully!',
                'product_name' => $product->product_name,
                'product_image' => $productImage,
                'quantity' => $cartItem->quantity,
                'cart_total' => number_format($cartTotal, 2),
                'cart_item_count' => $cartItemCount,
                'total_cart_amount' => number_format($totalCartAmount, 2),
                'cart_id' => $cartItem->cart_id,  // Use the ID of the created or updated cart item
            ]);

        } catch (\Exception $e) {
            // Log the exception for further debugging
            Log::error('Error adding product to cart', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'There was an error adding the product to the cart.'], 500);
        }
    }


    public function remove($cartId)
    {
        // Find the cart item by ID
        $cartItem = Cart::find($cartId);

        // If the item is found, delete it
        if ($cartItem) {
            $cartItem->delete(); // Deletes the item from the cart
            return response()->json(['success' => true]);
        }

        // If the cart item is not found, return an error response
        return response()->json(['error' => 'Item not found.'], 404);
    }
    public function update(Request $request, $cartId)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is a positive integer
        ]);

        // Find the cart item
        $cartItem = Cart::find($cartId);

        if ($cartItem) {
            // Update the quantity
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Return a success response
            return response()->json(['success' => true,
                'quantity' => $cartItem->quantity,
                'updatedPrice' => $cartItem->product->price // Return the updated price from the database
            ]);
        }

        // Return an error response if item not found
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }



    public function getCartTooltip()
    {
        $user = Auth::user();

        // Retrieve cart items for the logged-in user with product and product images
        $cartItems = Cart::where('customer_id', $user->user_id)
            ->with('product.images') // Load product and images
            ->get();

        // Calculate the total amount in the cart
        $totalCartAmount = 0;
        foreach ($cartItems as $cartItem) {
            $totalCartAmount += $cartItem->quantity * $cartItem->product->price;
        }

        // Format the cart data to return as JSON
        $cartData = [];
        foreach ($cartItems as $cartItem) {
            $cartData[] = [
                'cart_id' => $cartItem->cart_id,
                'product_id' => $cartItem->product->product_id,
                'product_name' => $cartItem->product->product_name,
                'quantity' => $cartItem->quantity,
                'price' => number_format($cartItem->product->price, 2),
                'subtotal' => number_format($cartItem->quantity * $cartItem->product->price, 2),
                'image_url' => $cartItem->product->images->first() ? $cartItem->product->images->first()->product_img_path1 : null,
            ];
        }

        return response()->json([
            'cartItems' => $cartData,
            'totalCartAmount' => number_format($totalCartAmount, 2),
        ]);
    }



    public function getCartItemCount()
    {
        $user = Auth::user();
        $cartItemCount = Cart::where('customer_id', $user->user_id)->sum('quantity'); // Get total quantity of items

        return response()->json([
            'cartItemCount' => $cartItemCount,
        ]);
    }

    public function buyNow(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'merchant_id' => 'required|integer',
                'product_id' => 'required|integer',
                'product_variation_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = Cart::create([
                'customer_id' => Auth::id(),
                'merchant_id' => $validatedData['merchant_id'],
                'product_id' => $validatedData['product_id'],
                'product_variation_id' => $validatedData['product_variation_id'],
                'quantity' => $validatedData['quantity'],
                'status' => 'active',
            ]);

            return response()->json(['success' => true, 'cart_id' => $cartItem->cart_id]);

        } catch (\Exception $e) {
            Log::error("Buy Now Error: " . $e->getMessage()); // Log the error message
            return response()->json(['success' => false, 'message' => 'Failed to add to cart'], 500);
        }
    }





}

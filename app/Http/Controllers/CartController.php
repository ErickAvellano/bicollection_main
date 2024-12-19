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
        Log::info('User attempting to add product to cart.', [
            'user_id' => $user->user_id,
            'product_id' => $productId,
        ]);

        // Retrieve the product by ID
        $product = Product::with('variations', 'images')->find($productId);

        if (!$product) {
            Log::warning('Product not found.', ['product_id' => $productId]);
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $productVariationId = $product->variations->first()->product_variation_id ?? null;

        // Check if the product is already in the cart
        $cartItem = Cart::where('customer_id', $user->user_id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // If it exists, update the quantity
            $cartItem->quantity++;
            $cartItem->save();
            Log::info('Product quantity updated in cart.', [
                'cart_id' => $cartItem->cart_id,
                'quantity' => $cartItem->quantity,
            ]);
        } else {
            // Create a new cart entry and assign it to $cartItem
            $cartItem = Cart::create([
                'customer_id' => $user->user_id,
                'product_variation_id' => $productVariationId,
                'merchant_id' => $product->merchant_id,
                'quantity' => 1,
                'status' => 'active',
                'product_id' => $productId,
            ]);
            Log::info('New product added to cart.', [
                'cart_id' => $cartItem->cart_id,
                'quantity' => $cartItem->quantity,
            ]);
        }

        // Calculate cart totals
        $cartTotal = $cartItem->quantity * $product->price;
        $cartItemCount = Cart::where('customer_id', $user->user_id)->count();
        $totalCartAmount = Cart::where('customer_id', $user->user_id)->sum('quantity');

        // Retrieve the first product image
        $productImage = $product->images->first() ? $product->images->first()->product_img_path1 : null;

        Log::info('Cart totals calculated.', [
            'cart_total' => $cartTotal,
            'cart_item_count' => $cartItemCount,
            'total_cart_amount' => $totalCartAmount,
        ]);

        // Return the response with updated data and variations
        return response()->json([
            'success' => 'Product added to cart successfully!',
            'product_name' => $product->product_name,
            'product_image' => $productImage,
            'product_variation' => optional($product->variations->first())->variation_name,
            'product_variations' => $product->variations->map(function ($variation) {
                return [
                    'product_variation_id' => $variation->product_variation_id,
                    'variation_name' => $variation->variation_name,
                ];
            }),
            'quantity' => $cartItem->quantity,
            'cart_total' => number_format($cartTotal, 2),
            'cart_item_count' => $cartItemCount,
            'total_cart_amount' => number_format($totalCartAmount, 2),
            'cart_id' => $cartItem->cart_id,
        ]);

    } catch (\Exception $e) {
        Log::error('Error adding product to cart.', [
            'error_message' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'There was an error adding the product to the cart.'], 500);
    }
}

    public function remove($cartId)
    {
        // Find the cart item by ID
        $cartItem = Cart::find($cartId);

        // If the item is found, delete it
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Item not found.'], 404);
    }
    public function update(Request $request, $cartId)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1', 
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
                'updatedPrice' => $cartItem->product->price 
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }
    public function getCartTooltip()
    {
        $user = Auth::user();

        $cartItems = Cart::where('customer_id', $user->user_id)
            ->with('product.images') 
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
            return response()->json(['success' => false, 'message' => 'Failed to add to cart'], 500);
        }
    }
    public function updateVariation(Request $request, $cartId)
    {
        // Validate request
        $validated = $request->validate([
            'product_variation_id' => 'required|integer|exists:product_variation,product_variation_id',
        ]);

        // Find the cart item by ID
        $cartItem = Cart::where('cart_id', $cartId)
            ->where('customer_id', Auth::id()) 
            ->where('status', 'active') 
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
        }

        // Update the product variation
        $cartItem->product_variation_id = $validated['product_variation_id'];
        $cartItem->save();

        return response()->json(['success' => true, 'message' => 'Product variation updated successfully.']);
    }
    public function viewAddToCart(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated.'], 401);
            }

            $productId = $request->input('product_id');
            $variationId = $request->input('product_variation_id');
            $quantity = $request->input('quantity', 1); 

            // Find the product and check variation
            $product = Product::with('variations')->find($productId);
            if (!$product) {
                return response()->json(['error' => 'Product not found.'], 404);
            }

            if (!$product->variations->contains('product_variation_id', $variationId)) {
                return response()->json(['error' => 'Invalid variation selected.'], 400);
            }

            // Add or update cart item
            $cartItem = Cart::where('customer_id', $user->user_id)
                            ->where('product_id', $productId)
                            ->where('product_variation_id', $variationId)
                            ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity; 
                $cartItem->save();
            } else {
                Cart::create([
                    'customer_id' => $user->user_id,
                    'product_id' => $productId,
                    'product_variation_id' => $variationId,
                    'merchant_id' => $product->merchant_id,
                    'quantity' => $quantity,
                    'status' => 'active'
                ]);
            }

            return response()->noContent(200); 

        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error.'], 500);
        }
    }


    









}

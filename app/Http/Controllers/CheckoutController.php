<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\MerchantMop;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $customerId = Auth::id(); 
        $cartId = $request->query('cart_id'); 
        $cartIds = $request->query('cart_ids');

        // Handle single or multiple cart IDs
        if ($cartIds) {
           
            if (!is_array($cartIds)) {
                $cartIds = explode(',', $cartIds);
            }
        } elseif ($cartId) {
          
            $cartIds = [$cartId];
        } else {
            return redirect()->route('cart.show')->with('error', 'No cart items selected.');
        }

        // Retrieve cart items for the given cart IDs and customer ID
        $cartItems = Cart::with('product.images', 'product.variations')
            ->whereIn('cart_id', $cartIds)
            ->where('customer_id', $customerId)
            ->where('status', 'active')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'No active cart items found.');
        }

        // Get the merchant ID (assuming all items belong to the same merchant for simplicity)
        $merchantId = $cartItems->first()->merchant_id;

        if (is_null($merchantId)) {
            return redirect()->route('cart.show')->with('error', 'Merchant ID not found in cart.');
        }

        // Fetch the merchant's modes of payment
        $merchantMop = MerchantMop::where('merchant_id', $merchantId)->get();
        $gcashMop = $merchantMop->where('account_type', 'GCash')->first();
        $codMop = $merchantMop->where('account_type', 'COD')->where('cod_terms_accepted', 1)->first();

        $merchantSupportsGCash = $gcashMop !== null;
        $merchantSupportsCOD = $codMop !== null;

        // Set GCash and COD Mop IDs
        $gcashMopId = $gcashMop ? $gcashMop->merchant_mop_id : null;
        $codMopId = $codMop ? $codMop->merchant_mop_id : null;

        // Fetch customer details
        $customer = Customer::find($customerId);

        // Fetch the latest address ofcustomer
        $address = $customer->addresses()->latest()->first();

        // Check if there is an existing 'pending' order with a custom shipping address
        $orderShippingAddress = Order::where('customer_id', $customerId)
            ->where('order_status', 'pending')
            ->latest()
            ->value('shipping_address'); // Assuming you store it as a full string

        // Fetch the default address from the user's address records
        $defaultAddress = $customer->addresses()->latest()->first();

        // Set the display address
        $displayAddress = $address
            ? $address->display_address
            : ($defaultAddress ? $defaultAddress->display_address : null);

        // Calculate merchandise subtotal
        $merchandiseSubtotal = $cartItems->sum(function ($cartItem) {
            // Find the variation directly by product_variation_id stored in the cart
            $selectedVariation = $cartItem->product->variations
                ->where('product_variation_id', $cartItem->product_variation_id)
                ->first();
        
            // Use the variation price, or fall back to the product's base price
            $productPrice = $selectedVariation ? $selectedVariation->price : $cartItem->product->price;
        
            // Calculate subtotal for this cart item
            return $cartItem->quantity * $productPrice;
        });

        // Set a fixed shipping subtotal (can be dynamic based on address or other factors)
        $shippingSubtotal = 58; 

        // Calculate total payment
        $totalPayment = $merchandiseSubtotal + $shippingSubtotal;

        // Pass all necessary data to the checkout view
        return view('checkout', compact(
            'cartItems',
            'customer',
            'cartIds',
            'address',
            'merchandiseSubtotal',
            'merchantId',
            'merchantSupportsGCash',
            'merchantSupportsCOD',
            'gcashMopId',
            'codMopId',
            'shippingSubtotal',
            'totalPayment',
            'displayAddress',
            'orderShippingAddress',
            'defaultAddress'
        ));
    }

    public function updateContactNumber(Request $request)
    {
        // Validate the contact number
        $request->validate([
            'contact_number' => 'required|digits_between:10,15'
        ], [
            'contact_number.required' => 'The contact number is required.',
            'contact_number.digits_between' => 'The contact number must be between 10 and 15 digits.'
        ]);
    
        try {
            // Retrieve the customer record associated with the authenticated user
            $customer = Customer::where('user_id', Auth::id())->first(); 
    
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer record not found.'], 404);
            }
    
            // Update the contact number
            $customer->update([
                'contact_number' => $request->contact_number
            ]);
    
            return response()->json(['success' => true, 'message' => 'Contact number updated successfully for the customer!']);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['success' => false, 'message' => 'Failed to update contact number. Please try again later.'], 500);
        }
    }
    
    

    

}

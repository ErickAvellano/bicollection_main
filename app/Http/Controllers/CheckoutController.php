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
        $customerId = Auth::id(); // Get the authenticated user's ID
        $cartId = $request->query('cart_id'); // Get the cart_id from the URL

        // Fetch the cart item for the given cart_id and customer_id
        $cartItem = Cart::where('cart_id', $cartId)
            ->where('customer_id', $customerId)
            ->where('status', 'active')
            ->first();

        if (!$cartItem) {
            return redirect()->route('cart.show')->with('error', 'No active cart item found.');
        }

        // Get the merchant_id from the cart item
        $merchantId = $cartItem->merchant_id;

        if (is_null($merchantId)) {
            return redirect()->route('cart.show')->with('error', 'Merchant ID not found in cart.');
        }

        // Fetch the merchant's mode of payment ID from the merchant_mop table
        $merchantMop = MerchantMop::where('merchant_id', $merchantId)->get();

        $gcashMop = $merchantMop->where('account_type', 'GCash')->first();
        $codMop = $merchantMop->where('account_type', 'COD')->where('cod_terms_accepted', 1)->first();

        $merchantSupportsGCash = $gcashMop !== null;
        $merchantSupportsCOD = $codMop !== null;

        // Set the IDs for GCash and COD
        $gcashMopId = $gcashMop ? $gcashMop->merchant_mop_id : null;
        $codMopId = $codMop ? $codMop->merchant_mop_id : null;

        // Retrieve cart items for the given cart ID and current user
        $cartItems = Cart::with('product.images', 'product.variations')
            ->where('cart_id', $cartId)
            ->where('customer_id', $customerId)
            ->where('status', 'active')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'No active cart items found.');
        }

        // Fetch customer and their latest address
        $customer = Customer::find($customerId);

        $address = $customer->addresses()->latest()->first();

        // Check if there is an existing 'pending' order with a custom shipping address
        $orderShippingAddress = Order::where('customer_id', $customerId)
        ->where('order_status', 'pending')
        ->latest()
        ->value('shipping_address'); // Assuming you store it as a full string

        // Fetch the default address from the user's address records
        $defaultAddress = $customer->addresses()->latest()->first();

        // Set the display address
     $displayAddress = $address ? $address->display_address : $defaultAddress->display_address;

        // Calculate merchandise subtotal
        $merchandiseSubtotal = $cartItems->sum(function ($cartItem) {
            $productPrice = $cartItem->product->variations->first()->price ?? $cartItem->product->price;
            return $cartItem->quantity * $productPrice;
        });

        // Set a fixed shipping subtotal (can be dynamic based on address or other factors)
        $shippingSubtotal = 58; // Example fixed value

        // Calculate total payment
        $totalPayment = $merchandiseSubtotal + $shippingSubtotal;

        // Pass all necessary data to the checkout view
        return view('checkout', compact(
            'cartItems',
            'customer',
            'cartId',
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

    

}

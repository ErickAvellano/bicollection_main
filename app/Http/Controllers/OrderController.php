<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BicollectionSales;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Mail\OrderAcceptedMail;
use App\Mail\OrderDeclinedMail;
use App\Mail\PaymentDeclineMail;
use App\Mail\OrderConfirmation;
use App\Mail\OrderReadyMail;
use App\Models\RefundRequest;
use App\Mail\RefundRequestMail;
use App\Mail\ReferenceMismatchMail;
use App\Mail\RefundCompletedMail;
use App\Mail\OrderReceivedMail;

use Exception;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $currentMerchantId = Auth::id();
        $status = $request->get('status', 'pending');
        $sort = $request->get('sort', '');

        // Define an array to store the counts for each status
        $statusCounts = [
            'pending' => Order::where('merchant_id', $currentMerchantId)->where('order_status', 'pending')->count(),
            'to-ship' => Order::where('merchant_id', $currentMerchantId)->where('order_status', 'to-ship')->count(),
            'to-refund' => Order::where('merchant_id', $currentMerchantId)->where('order_status', 'to-refund')->count(),
            'completed' => Order::where('merchant_id', $currentMerchantId)->where('order_status', 'completed')->count(),
            'cancel/refund' => Order::where('merchant_id', $currentMerchantId)->where('order_status', 'cancel/refund')->count(),
            'cancel' => Order::where('merchant_id', $currentMerchantId)
                ->whereIn('order_status', ['cancelled', 'declined'])
                ->count(),
        ];
        // Build the base query with status and relationships
        $query = Order::with(['customer', 'orderItems.product.images', 'payment'])
                    ->where('merchant_id', $currentMerchantId);

        if ($status === 'canceled') {
            // Include both 'canceled' and 'declined' orders
            $query->whereIn('order_status', ['cancelled', 'declined']);
        } else {
            // Filter by the specified status
            $query->where('order_status', $status);
        }

        if ($sort === 'GCash' || $sort === 'COD') {
            // Filter orders by payment method
            $query->whereHas('payment', function ($q) use ($sort) {
                $q->where('payment_method', $sort);
            })->orderBy('created_at', 'desc');
        } elseif ($sort === 'date') {
            // Sort by creation date only
            $query->orderBy('created_at', 'desc');
        }

        // Get the orders after filtering and sorting
        $orders = $query->get();

        // If AJAX request, return a partial view
        if ($request->ajax()) {
            $view = view('orders.partials.order-list', compact('orders', 'status', 'statusCounts'))->render();
            return response()->json(['html' => $view]);
        }

        // For full page load
        return view('orders.index', compact('orders', 'status', 'statusCounts'));
    }
    public function placeOrder(Request $request)
    {
        $decodedRequest = json_decode($request->getContent(), true);

        // Validate request data
        $validator = Validator::make($decodedRequest, [
            'merchant_id' => 'required|integer',
            'merchant_mop_id' => 'required|integer',
            'cart_id' => 'required|string', // Accept string for multiple cart IDs
            'shipping_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'total_amount' => 'required|numeric',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|integer',
            'order_items.*.product_name' => 'required|string|max:255',
            'order_items.*.variation_id' => 'nullable|integer',
            'order_items.*.variation_name' => 'nullable|string|max:255',
            'order_items.*.product_price' => 'required|numeric',
            'order_items.*.quantity' => 'required|integer',
            'order_items.*.subtotal' => 'required|numeric',
            'payment_method' => 'required|string|in:COD,GCash',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Extract validated data
        $validatedData = $validator->validated();

        try {
            $customerId = Auth::id();
            $cartIds = explode(',', $validatedData['cart_id']); // Split cart IDs into an array
            $cartIdString = implode(',', $cartIds);

            // Start transaction
            DB::beginTransaction();

            // Create a new order
            $order = Order::create([
                'customer_id' => $customerId,
                'merchant_id' => $validatedData['merchant_id'],
                'merchant_mop_id' => $validatedData['merchant_mop_id'],
                'cart_id' => $cartIdString,
                'shipping_address' => $validatedData['shipping_address'],
                'contact_number' => $validatedData['contact_number'],
                'total_amount' => $validatedData['total_amount'],
                'shipping_fee' => 58,
                'order_status' => 'pending',
            ]);

            // Initialize order items array
            $orderItems = [];

            // Process each cart ID
            foreach ($cartIds as $cartId) {
                $cartItems = Cart::where('customer_id', $customerId)
                    ->where('cart_id', $cartId)
                    ->where('status', 'active')
                    ->get();

                // Ensure there are items in the cart
                if ($cartItems->isEmpty()) {
                    throw new Exception("No active items found for Cart ID: {$cartId}");
                }

                // Insert order items
                foreach ($cartItems as $item) {
                    // Fetch product details
                    $product = Product::find($item->product_id);

                    // Fetch variation details if applicable
                    $variationName = null;
                    if (!empty($item->product_variation_id)) {
                        $variation = ProductVariation::find($item->product_variation_id);
                        $variationName = $variation ? $variation->variation_name : null;
                    }

                    // Calculate subtotal
                    $productPrice = $product ? $product->price : 0;
                    $subtotal = $productPrice * $item->quantity;

                    // Create the order item
                    $orderItem = OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $item->product_id,
                        'product_name' => $product ? $product->product_name : null,
                        'product_price' => $productPrice,
                        'variation_id' => $item->product_variation_id ?? null,
                        'variation_name' => $variationName,
                        'quantity' => $item->quantity,
                        'subtotal' => $subtotal,
                    ]);
                    $orderItems[] = $orderItem;

                    // Reduce product quantity
                    if ($product) {
                        $product->quantity_item -= $item->quantity;

                        // Ensure stock doesn't go negative
                        if ($product->quantity_item < 0) {
                            throw new Exception("Insufficient stock for product: {$product->product_name}");
                        }
                        $product->save();
                    }
                }
            }

            // Set payment status based on payment method
            $paymentStatus = $validatedData['payment_method'] === 'GCash' ? 'To-pay' : 'Pending';

            // Create a payment record
            $payment = Payment::create([
                'order_id' => $order->order_id,
                'customer_id' => $customerId,
                'payment_method' => $validatedData['payment_method'],
                'amount' => $validatedData['total_amount'],
                'shipping_fee' => 58,
                'order_status' => 'pending',
                'payment_status' => $paymentStatus,
            ]);

            // Clear all processed cart items
            Cart::where('customer_id', $customerId)->whereIn('cart_id', $cartIds)->where('status', 'active')->delete();

            // Commit the transaction
            DB::commit();

            // Respond based on payment method
            if ($validatedData['payment_method'] === 'GCash') {
                $showPaymentUrl = route('payment.show', ['order_id' => $order->order_id]);
                return response()->json(['success' => true, 'redirect_to' => $showPaymentUrl]);
            } else {
                // Get customer and merchant emails
                $customer = Customer::find($customerId);
                $merchant = Merchant::find($validatedData['merchant_id']);

                // Send email to customer
                Mail::to($customer->email)->send(new OrderConfirmation($order, true, $orderItems));

                // Send email to merchant
                Mail::to($merchant->email)->send(new OrderConfirmation($order, false, $orderItems));

                return response()->json(['success' => true, 'message' => 'Order placed successfully! Please pay upon delivery.']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Show payment page
    public function showPayment(Order $order)
    {
        return view('show-payment', compact('order'));
    }
    // Handle receipt upload
    public function uploadReceipt(Request $request, Order $order)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the receipt image
        $path = $request->file('receipt')->store('receipts', 'public');

        // Update the order with receipt path and set status to 'awaiting confirmation'
        $order->update([
            'receipt_path' => $path,
            'order_status' => 'awaiting confirmation',
        ]);

        return redirect()->route('home')->with('success', 'Receipt uploaded successfully. Please wait for confirmation.');
    }
    public function getOrdersTooltip()
    {
        try {
            $currentMerchantId = Auth::id();

            // Check if the user is authenticated
            if (!$currentMerchantId) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $orders = Order::with(['customer', 'orderItems.productImg', 'payment'])
                ->where('merchant_id', $currentMerchantId)
                ->where('order_status', 'pending')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['orders' => []]);
            }


            $formattedOrders = $orders->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'customer' => $order->customer ? $order->customer->username : 'N/A',
                    'orderItems' => $order->orderItems->map(function ($item) {
                        $productImgPath = $item->productImg ? $item->productImg->product_img_path1 : 'https://via.placeholder.com/60';
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'subtotal' => $item->subtotal,
                            'product_img_path' => $productImgPath,
                        ];
                    }),
                    'total_quantity' => $order->orderItems->sum('quantity'),
                    'total_amount' => number_format($order->total_amount, 2),
                    'payment_method' => $order->payment ? $order->payment->payment_method : 'N/A',
                    'order_status' => $order->order_status,
                ];
            });

            return response()->json(['orders' => $formattedOrders]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch orders'], 500);
        }
    }
    public function getOrderCount()
    {
        try {
            $currentMerchantId = Auth::id();

            // Fetch the count of pending orders for the current merchant
            $orderCount = Order::where('merchant_id', $currentMerchantId)
                                ->where('order_status', 'pending') // Only count pending orders
                                ->count();

            // Return the count as a JSON response
            return response()->json(['orderCount' => $orderCount]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch order count'], 500);
        }
    }
    public function show($orderId)
    {
        // Fetch order details with related order items and payment
        $order = Order::with(['orderItems.product.images', 'orderItems.variation', 'payment'])
                      ->where('order_id', $orderId)
                      ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Prepare data for the view
        $orderData = [
            'order_id' => $order->order_id,
            'total_amount' => $order->total_amount,
            'order_status' => $order->order_status,
            'shipping_address' => $order->shipping_address,
            'contact_number' => $order->contact_number,
            'order_items' => $order->orderItems,
            'payment_method' => $order->payment->payment_method ?? 'N/A',
            'payment_status' => $order->payment->payment_status ?? 'N/A',
            'receipt_img' => $order->payment->receipt_img ?? null,
            'customer_name' =>  $order->customer->name,
        ];

        return view('orders.detail', compact('orderData'));
    }
    public function showRefund($orderId)
    {
        // Fetch order details with related order items and payment
        $order = Order::with(['orderItems.product.images', 'orderItems.variation', 'payment'])
                      ->where('order_id', $orderId)
                      ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Prepare data for the view
        $orderData = [
            'order_id' => $order->order_id,
            'total_amount' => $order->total_amount,
            'order_status' => $order->order_status,
            'shipping_address' => $order->shipping_address,
            'contact_number' => $order->contact_number,
            'order_items' => $order->orderItems,
            'payment_method' => $order->payment->payment_method ?? 'N/A',
            'payment_status' => $order->payment->payment_status ?? 'N/A',
            'receipt_img' => $order->payment->receipt_img ?? null,
            'customer_name' =>  $order->customer->name,
            'customer_username' =>  $order->customer->username,

        ];

        return view('orders.refund', compact('orderData'));
    }

    public function updatePaymentStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:order,order_id',
            'payment_status' => 'required|string'
        ]);

        // Retrieve the order ID and payment status from the request
        $orderId = $request->input('order_id');
        $status = $request->input('payment_status');

        try {
            // Find the order by 'order_id'
            $order = Order::where('order_id', $orderId)->first();

            if ($order) {
                // Update the payment status in the 'payments' table
                $payment = Payment::where('order_id', $orderId)->first();
                if ($payment) {
                    $payment->payment_status = $status;
                    $payment->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment record not found for this order.'
                    ], 404);
                }

                // Handle the email notifications based on the payment status
                try {
                    if ($status === 'Paid') {
                        Mail::to($order->customer->email)->send(new PaymentConfirmationMail($order));
                    } elseif ($status === 'To-pay') {
                        // Send payment decline email
                        Mail::to($order->customer->email)->send(new PaymentDeclineMail($order));
                    }
                } catch (Exception $emailException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to send email notification: ' . $emailException->getMessage()
                    ], 500);
                }

                // Return the appropriate JSON response based on the payment status
                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated successfully.',
                    'redirect' => $status === 'To-pay' ? '/orders' : null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrderStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:order,order_id',
            'order_status' => 'required|string'
        ]);

        // Retrieve the order ID and new status from the request
        $orderId = $request->input('order_id');
        $status = $request->input('order_status');

        try {
            // Find the order by 'order_id'
            $order = Order::with('customer')->where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            // Update the order status
            $order->order_status = $status;
            $order->save();

            // Initialize message dynamically based on status
            $message = 'Order status updated successfully.';

            // Send email based on the updated status
            if ($status === 'To-Ship') {
                Mail::to($order->customer->email)->send(new OrderAcceptedMail($order));
                $message = 'The order has been successfully updated to "To-Ship".';
            } elseif ($status === 'Declined') {
                Mail::to($order->customer->email)->send(new OrderDeclinedMail($order));
                $message = 'The order has been marked as "Declined". Thank you for reviewing it.';
            } elseif ($status === 'Ready') {
                Mail::to($order->customer->email)->send(new OrderReadyMail($order));
                $message = 'Order status updated to "Ready". Notification email sent.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating order status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexUpdateOrderStatus(Request $request, $order_id)
    {
        Log::info('Received request to update order status', [
            'order_id' => $order_id,
            'order_status' => $request->input('order_status')
        ]);

        $request->validate([
            'order_status' => 'required|string'
        ]);

        $status = $request->input('order_status');

        try {
            $order = Order::with('customer', 'orderItems')->where('order_id', $order_id)->first();

            if (!$order) {
                Log::warning('Order not found', ['order_id' => $order_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            $order->order_status = $status;
            $order->save();

            // Initialize message dynamically based on status
            $message = 'Order status updated successfully.';

            // Check and send the correct email based on the updated status
            if ($status === 'To-Ship') {
                Log::info('Sending OrderToShipMail', ['email' => $order->customer->email]);
                 Mail::to($order->customer->email)->send(new OrderAcceptedMail($order));
                $message = 'The order has been successfully updated to "To-Ship"';
            } elseif ($status === 'Declined') {
                Log::info('Sending OrderDeclinedMail', ['email' => $order->customer->email]);
                Mail::to($order->customer->email)->send(new OrderDeclinedMail($order));
                $message = 'The order has been marked as "Declined". Thank you for reviewing';
            } elseif ($status === 'Ready') {
                Log::info('Sending OrderReadyMail', ['email' => $order->customer->email]);
                Mail::to($order->customer->email)->send(new OrderReadyMail($order));
                $message = 'Order status updated to "Ready". Notification email sent.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            Log::error('Error updating order status', [
                'order_id' => $order_id,
                'order_status' => $status,
                'error_message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


//problem here
    public function confirmReceived($orderId)
    {
        try {
            // Fetch the order with its orderItems and related products
            $order = Order::with(['orderItems.product'])->where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->order_status = 'To-rate';
            $order->save();

            $customer = $order->customer;

            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

            // Check if the order has any items
            if (!$order->orderItems || $order->orderItems->isEmpty()) {
                return response()->json(['message' => 'No items found for this order'], 404);
            }

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                if (!$product) {
                    continue; // Skip this item if the product is not found
                }

                BicollectionSales::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'customer_id' => $customer->customer_id,
                    'merchant_id' => $order->merchant_id,
                    'quantity' => $orderItem->quantity,
                    'total_price' => $orderItem->quantity * $product->price,
                    'sale_date' => now(),
                ]);
            }

            Mail::to($customer->email)->send(new OrderReceivedMail($order));

            return response()->json(['message' => 'Order marked as received, sales data saved, and email sent to customer']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order,order_id'
        ]);

        $orderId = $request->input('order_id');
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        // Check if the payment method is GCash and prevent cancellation
        if ($order->payment->payment_method === 'GCash') {
            return response()->json(['success' => false, 'message' => 'Orders with GCash payment cannot be canceled immediately.']);
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update order status to cancelled
            $order->order_status = 'cancelled';
            $order->save();

            // Retrieve all items in the order
            $orderItems = OrderItem::where('order_id', $orderId)->get();

            // Loop through each order item and restore the product quantity
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    // Add back the quantity from the order item
                    $product->quantity_item += $item->quantity;
                    $product->save();
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order has been cancelled.']);
        } catch (Exception $e) {
            // Rollback the transaction in case of any errors
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'An error occurred while canceling the order. Please try again later.'], 500);
        }
    }

    public function cancelOrderCustomer($order_id)
    {
        // Fetch the payment record directly
        $payment = Payment::where('order_id', $order_id)->first();

        if ($payment && $payment->payment_status === 'To-pay') {
            DB::beginTransaction();

            try {
                // Update payment status and order status
                $payment->update([
                    'payment_status' => 'Cancelled'
                ]);

                $order = Order::where('order_id', $payment->order_id)->first();
                $order->update(['order_status' => 'cancelled']);

                $orderItems = OrderItem::where('order_id', $order_id)->get();

                // Restore the product quantities
                foreach ($orderItems as $item) {
                    $product = Product::find($item->product_id);

                    if ($product) {

                        $product->quantity_item += $item->quantity;

                        $product->save();
                    }
                }
                DB::commit();

                // Return a success response in JSON format
                return response()->json(['success' => true, 'message' => 'Order has been cancelled successfully.'], 200);
            } catch (Exception $e) {
                DB::rollBack();

                // Log the error for debugging (optional)
                Log::error('Order cancellation failed', ['error' => $e->getMessage()]);

                // Return a failure response in JSON format with a 500 status
                return response()->json(['success' => false, 'message' => 'Failed to cancel the order.'], 500);
            }
        }

        // If the order cannot be cancelled, return a 400 status
        return response()->json(['success' => false, 'message' => 'Order cannot be cancelled.'], 400);
    }
    public function requestCancelOrder(Request $request)
    {
        $orderId = $request->input('order_id');
        $paymentId = $request->input('payment_id');

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Update order status
            DB::table('order')
                ->where('order_id', $orderId)
                ->update(['order_status' => 'to-refund', 'updated_at' => now()]);

            // Update payment status
            DB::table('payment')
                ->where('payment_id', $paymentId)
                ->update(['payment_status' => 'to-refund', 'updated_at' => now()]);

            // Create a new refund request record
            $refundRequest = RefundRequest::create([
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'refund_status' => 'Pending',
            ]);

            // Retrieve all items in the order
            $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

            // Restore the product quantities
            foreach ($orderItems as $item) {
                $product = DB::table('product')->where('product_id', $item->product_id)->first();

                if ($product) {
                    // Add back the quantity from the order item
                    $newQuantity = $product->quantity_item + $item->quantity;

                    // Update the product quantity in the product table
                    DB::table('product')
                        ->where('product_id', $item->product_id)
                        ->update(['quantity_item' => $newQuantity, 'updated_at' => now()]);
                }
            }

            // Retrieve the order and merchant's email information
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Order not found.']);
            }

            // Fetch the merchant's data separately
            $merchant = DB::table('merchant')
                ->where('merchant_id', $order->merchant_id)
                ->first();

            if (!$merchant) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Merchant not found.']);
            }

            // Send email to the merchant with the order and merchant data
            Mail::to($merchant->email)->send(new RefundRequestMail($order, $merchant));

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cancellation request submitted successfully.']);
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function verify(Request $request)
    {
        $orderId = $request->input('order_id');

        $order = Order::where('order_id', $orderId)->first();
        $payment = Payment::where('order_id', $orderId)->first();

        if ($order && $payment) {
            $order->update(['order_status' => 'Cancel/Refund']);
            $payment->update(['payment_status' => 'To-refund', 'order_status' => 'Cancel/Refund']);

            // Create a new RefundRequest record instead of updating it
            RefundRequest::create([
                'order_id' => $orderId,
                'payment_id' => $payment->payment_id,
                'refund_status' => 'Pending',
                // You can add other relevant fields to the RefundRequest model here
            ]);

            return response()->json(['status' => 'success', 'message' => 'Refund processed successfully!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Error processing refund.']);
    }
    public function notMatch(Request $request)
    {
        $orderId = $request->input('order_id');

        $order = Order::where('order_id', $orderId)->first();
        $payment = Payment::where('order_id', $orderId)->first();

        if ($order && $payment) {
            // Update order and payment statuses
            $order->update(['order_status' => 'Cancel/Declined']);
            $payment->update(['payment_status' => 'Declined']);

            // Create a new RefundRequest record instead of updating it
            RefundRequest::create([
                'order_id' => $orderId,
                'payment_id' => $payment->payment_id,
                'refund_status' => 'Declined',
                // Additional fields can be added as needed
            ]);

            // Send mismatch notification email
            try {
                Mail::to($order->customer->email)->send(new ReferenceMismatchMail($order));
            } catch (Exception $e) {
                // Handle email sending failure if needed
            }

            return response()->json(['status' => 'success', 'message' => 'Order has been cancelled due to reference mismatch.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Error processing cancellation.']);
    }
    public function markRefundCompleted(Request $request)
    {
        $orderId = $request->input('order_id');

        // Fetch the necessary models
        $refundRequest = RefundRequest::where('order_id', $orderId)->first();
        $payment = Payment::where('order_id', $orderId)->first();

        if ($refundRequest && $payment) {
            // Update refund request and payment status
            $refundRequest->update(['refund_status' => 'Completed']);
            $payment->update(['payment_status' => 'Refunded']);

            // Send refund completion email
            try {
                Mail::to($payment->order->customer->email)->send(new RefundCompletedMail($payment->order));
            } catch (Exception $e) {
                // Handle email sending failure if needed
            }

            return response()->json(['status' => 'success', 'message' => 'Refund marked as completed.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Error marking refund as completed.']);
    }
    // OrderController.php
    public function getOrderDetails($orderId)
    {
        $order = Order::with(['orderItems.product.images'])->find($orderId);

        if ($order) {
            $orderDetails = [];

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                $image = $product && $product->images ? $product->images->first() : null;

                // Get variation name if available
                $variationName = null;
                if ($orderItem->variation_id) {
                    $variation = ProductVariation::find($orderItem->variation_id);
                    $variationName = $variation ? $variation->variation_name : 'N/A';
                }

                // Prepare details for this order item
                $orderDetails[] = [
                    'product_id' => $product->product_id ?? null,
                    'product_name' => $product->product_name ?? 'N/A',
                    'variation_id' => $orderItem->variation_id ?? null, 
                    'variation' => $variationName,
                    'quantity' => $orderItem->quantity ?? 0,
                    'price' => $orderItem->product_price ?? 0, // Use product price from orderItem
                    'product_image' => $image ? asset('storage/' . $image->product_img_path1) : 'https://via.placeholder.com/60',
                ];
            }

            return response()->json([
                'status' => 'success',
                'order' => $orderDetails, // Return all order items as an array
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Order not found.',
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MerchantMop;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\OrderItem;
use App\Mail\OrderPaymentConfirmation;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class PaymentController extends Controller
{
    // Show the payment page for a specific order
    public function showPayment($order_id)
    {
        // Find the order by ID
        $order = Order::find($order_id);

        // Get the Gcash details for the merchant
        $merchantMop = MerchantMop::where([
            'merchant_id' => $order->merchant_id,
            'account_type' => 'gcash'
        ])->first();

        // Check if the order exists
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Pass the order data to the view
        return view('show-payment', compact('order', 'merchantMop'));
    }
    protected $paymongo;

    public function __construct(PayMongoService $paymongo)
    {
        $this->paymongo = $paymongo;
    }

    // Method to initiate GCash Payment
    public function createGcashPayment(Request $request)
    {
        $amount = $request->input('amount');  // Amount in PHP
        $gcashSource = $this->paymongo->createGcashSource($amount);

        if ($gcashSource && isset($gcashSource['data']['attributes']['redirect']['checkout_url'])) {
            return redirect($gcashSource['data']['attributes']['redirect']['checkout_url']);
        } else {
            return response()->json(['error' => 'Unable to create GCash payment.'], 500);
        }
    }

    // Success and failure routes
    public function paymentSuccess()
    {
        return view('payment.success');
    }

    public function paymentFailed()
    {
        return view('payment.failed');
    }

    public function confirmGcashPayment(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:order,order_id',
            'gcash_receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            // Return JSON response for validation errors
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Retrieve the order
            $order = Order::findOrFail($request->order_id);

            // Handle file upload
            if ($request->hasFile('gcash_receipt')) {
                $file = $request->file('gcash_receipt');
                $filePath = $file->store('gcash_receipts', 'public'); 

                // Find or create a new payment record
                $payment = Payment::firstOrNew(['order_id' => $order->order_id]);
                $payment->customer_id = $order->customer_id;
                $payment->payment_method = 'GCash';
                $payment->amount = $order->total_amount;
                $payment->receipt_img = $filePath;
                $payment->payment_status = 'To-review'; 
                $payment->save();

                // Update order status
                $order->order_status = 'pending';
                $order->save();

                // Send emails after payment confirmation
                $customer = $order->customer;
                $merchant = $order->merchant;
                $orderItems = $order->orderItems;

                // Send email to customer
                try {
                    Mail::to($customer->email)->send(new OrderPaymentConfirmation($order, true, $orderItems));
                } catch (\Exception $e) {
                    
                }

                // Send email to merchant
                try {
                    Mail::to($merchant->email)->send(new OrderPaymentConfirmation($order, false, $orderItems));
                } catch (\Exception $e) {
                    
                }

                // Return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed and emails sent.'
                ]);
            }

            // If file upload fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload the receipt.'
            ], 500);

        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while confirming the payment.'
            ], 500);
        }
    }




}

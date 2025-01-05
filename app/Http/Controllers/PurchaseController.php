<?php
// app/Http/Controllers/PurchaseController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure the authenticated user is a customer
        $customer = Customer::where('customer_id', $user->user_id)->first();
        if (!$customer) {
            abort(403, 'Unauthorized access. Only customers are allowed.');
        }

        $customerId = $customer->customer_id;
        $username = $customer->username;

        // Define an array to store the counts for each status
        $statusCounts = [
            'pending' => Order::where('customer_id', $customerId)->where('order_status', 'pending')->count(),
            'To-pay' => Order::where('customer_id', $customerId)->whereHas('payment', function ($query) {
                $query->where('payment_status', 'To-pay');
            })->count(),
            'to-ship' => Order::where('customer_id', $customerId)->where('order_status', 'to-ship')->count(),
            'to-received' => Order::where('customer_id', $customerId)
                ->whereIn('order_status', ['to-received', 'ready'])
                ->count(),
            'to-rate' => Order::where('customer_id', $customerId)
                    ->where('order_status', 'to-rate')
                    ->whereDoesntHave('productReviews')
                    ->count(),
            'to-refund' => Order::where('customer_id', $customerId)->where('order_status', 'to-refund')->count(),
            'completed' => Order::where('customer_id', $customerId)->where('order_status', 'completed')->count(),
            'cancel' => Order::where('customer_id', $customerId)
                    ->whereIn('order_status', ['cancelled', 'declined', 'cancel/refund'])
                    ->count(),
        ];

        // Retrieve the orders based on the selected status
        $status = $request->get('status', 'pending');
        $query = Order::with(['orderItems.product','orderItems.product.images', 'payment'])
                    ->where('customer_id', $customerId);


        if ($status === 'pending') {
            $query->where('order_status', 'pending');
        } elseif ($status === 'To-pay') {
            $query->whereHas('payment', function ($q) {
                $q->where('payment_status', 'To-pay');
            });
        } elseif ($status === 'to-ship') {
            $query->where('order_status', 'to-ship');
        } elseif (in_array($status, ['to-received', 'ready'])) {
            $query->whereIn('order_status', ['to-received', 'ready']);
        } elseif ($status === 'completed') {
            $query->where('order_status', 'completed');

        }
        elseif ($status === 'to-rate') {
            $query->where('order_status','to-rate')
                ->whereDoesntHave('productReviews');
        }  elseif ($status === 'to-refund') {
            $query->where('order_status', 'to-refund');
        }elseif ($status === 'cancel') {
            $query->whereIn('order_status', ['cancelled', 'declined','cancel/refund']);
        } else {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Get the orders after filtering
        $purchases = $query->get();



        $ratingOrders = Order::with(['orderItems.product.images'])
            ->where('customer_id', $customerId)
            ->where('order_status', 'to-rate')
            ->whereDoesntHave('productReviews')
            ->get();

        // If it's an AJAX request, return a partial view
        if ($request->ajax()) {
            $view = view('profile.partials.purchase-list', compact('purchases', 'ratingOrders', 'status', 'statusCounts'))->render();
            return response()->json(['html' => $view]);
        }

        // For full page load
        return view('profile.mypurchase', compact('customerId', 'ratingOrders', 'purchases', 'username', 'status', 'statusCounts'));
    }


}

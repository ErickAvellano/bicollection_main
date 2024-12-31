<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application; 
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\Order;

class AdminDashboardController extends Controller
{

    public function confirm($id)
    {
        // Find the application
        $application = Application::findOrFail($id);

        // Update the shop's verification status to Verified
        $shop = Shop::find($application->shop_id);
        if ($shop) {
            $shop->verification_status = 'Verified';
            $shop->save(); // Save the updated status
        }

        return response()->json(['message' => 'Application confirmed successfully.']);
    }

    public function decline($id)
    {
        // Find the application
        $application = Application::findOrFail($id);

        // Update the shop's verification status to Rejected
        $shop = Shop::find($application->shop_id); 
        if ($shop) {
            $shop->verification_status = 'Rejected';
            $shop->save(); 
        }

        return response()->json(['message' => 'Application declined successfully.']);
    }
    public function index()
    {

        $user = Auth::user();

        // Ensure only admin users can access this method
        if ($user->type !== 'admin') {
            return redirect()->route('dashboard');
        }

        $adminId = $user->user_id;
        $transactionCount = Order::count();
        $customerCount = Customer::count();
        $applications = Application::with(['shop.merchant'])->whereHas('shop')->get();
        $transactions = collect();

        return view('admin.admindashboard', [
            'adminId' => $adminId,
            'transactionCount' => $transactionCount,
            'customerCount' => $customerCount,
            'applications' => $applications,
            'viewType' => 'dashboard',
            'transactions' => $transactions, 
        ]);
    }


    public function viewMerchants()
    {
        $transactionCount = Order::count();
        $customerCount = Customer::count();
        $applications = Application::with(['shop.merchant'])->whereHas('shop')->get();
        $transactions = []; 

        return view('admin.admindashboard', [
            'customerCount' => $customerCount,
            'applications' => $applications,
            'viewType' => 'merchants',
            'transactionCount' => $transactionCount,
            'transactions' => $transactions,
        ]);
    }

    public function viewCustomers()
    {
        $transactionCount = Order::count();

        // Fetch transactions
        $transactions = Order::select(
                'order.order_id',
                'order.customer_id',
                'order.merchant_id',
                'order.order_status',
                'payment.payment_status',
                'payment.payment_method',
                'payment.receipt_img'
            )
            ->leftJoin('payment', 'order.order_id', '=', 'payment.order_id')
            ->whereNotNull('payment.payment_status')
            ->get();

        $applications = Application::with(['shop.merchant'])->whereHas('shop')->get();
        $customerCount = Customer::count();
        $customers = Customer::with('addresses')->get();

        return view('admin.admindashboard', [
            'customers' => $customers,
            'customerCount' => $customerCount,
            'viewType' => 'customers',
            'transactionCount' => $transactionCount,
            'applications' => $applications,
            'transactions' => $transactions, 
        ]);
    }

    public function viewTransactions()
    {
        $applications = Application::with(['shop.merchant'])->whereHas('shop')->get();
        $customerCount = Customer::count();
        $transactionCount = Order::count();

        $transactions = Order::select(
                'order.order_id',
                'order.customer_id',
                'order.merchant_id',
                'order.order_status',
                'payment.payment_status',
                'payment.payment_method',
                'payment.receipt_img'
            )
            ->leftJoin('payment', 'order.order_id', '=', 'payment.order_id')
            ->whereNotNull('payment.payment_status')
            ->get();

        return view('admin.admindashboard', [
            'transactions' => $transactions,
            'transactionCount' => $transactionCount,
            'viewType' => 'transactions',
            'applications' => $applications, 
            'customerCount' => $customerCount, 
        ]);
    }

}

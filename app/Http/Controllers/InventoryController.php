<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BicollectionSales;
use App\Models\Order;
use App\Models\Category;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated and is a merchant
        if (!Auth::check() || Auth::user()->type !== 'merchant') {
            return redirect()->route('login')->with('error', 'Unauthorized access. Only merchants can view this page.');
        }

        $user = Auth::user();

        // Fetch data for the inventory view
        $products = Product::with('images')->where('merchant_id', $user->user_id)->get();
        $categories = Category::all();

        // Get sales data and low stock products
        $salesData = BicollectionSales::with('product')->where('merchant_id', $user->user_id)->get();
        $lowStockProducts = Product::where('merchant_id', $user->user_id)->where('quantity_item', '<=', 5)->get();

        // Analytics: Sales per day, month, and year
        $salesPerDay = BicollectionSales::select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->where('merchant_id', $user->user_id)
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date', 'ASC')
            ->get();

        $salesPerMonth = BicollectionSales::select(DB::raw('YEAR(sale_date) as year'), DB::raw('MONTH(sale_date) as month'), DB::raw('SUM(total_price) as total_sales'))
            ->where('merchant_id', $user->user_id)
            ->groupBy(DB::raw('YEAR(sale_date), MONTH(sale_date)'))
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        // Convert salesPerMonth data into a format that JavaScript can use
        $salesPerMonthData = $salesPerMonth->map(function ($item) {
            return [
                'label' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT), // Format like '2023-01'
                'total_sales' => $item->total_sales, // Ensure total_sales exists in the result
            ];
        });

        $salesPerYear = BicollectionSales::select(DB::raw('YEAR(sale_date) as year'), DB::raw('SUM(total_price) as total_sales'))
            ->where('merchant_id', $user->user_id)
            ->groupBy(DB::raw('YEAR(sale_date)'))
            ->orderBy('year', 'ASC')
            ->get();

        $popularProductDetails = BicollectionSales::select(
            'product_id',
            DB::raw('SUM(total_price) as total_sales')
        )
        ->where('merchant_id', $user->user_id)
        ->groupBy('product_id')
        ->with(['product' => function ($query) {
            $query->select('product_id', 'product_name'); // Ensure we get product names
        }])
        ->orderByDesc('total_sales')
        ->get();

        // Format data for Popular Products
        $popularProductData = $popularProductDetails->map(function ($sale) {
            return [
                'product_name' => $sale->product->product_name ?? 'Unknown', // Handle potential null products
                'total_sales' => $sale->total_sales,
            ];
        });

        // Category Trends
        $salesTrendByCategory = BicollectionSales::select(
            DB::raw('YEAR(sale_date) as year'),
            DB::raw('MONTH(sale_date) as month'),
            'category.category_name',
            DB::raw('SUM(bicollection_sales.total_price) as total_sales')
        )
        ->join('product', 'bicollection_sales.product_id', '=', 'product.product_id') // Join with product table
        ->join('category', 'product.category_id', '=', 'category.category_id')       // Join with category table
        ->groupBy(DB::raw('YEAR(sale_date)'), DB::raw('MONTH(sale_date)'), 'category.category_name')
        ->orderBy('year', 'ASC')
        ->orderBy('month', 'ASC')
        ->get();

        // Format data for JavaScript
        $categorySalesTrend = $salesTrendByCategory->groupBy('category_name')->map(function ($sales) {
            return [
                'category' => $sales->first()->category_name,
                'sales' => $sales->map(function ($sale) {
                    return [
                        'label' => $sale->year . '-' . str_pad($sale->month, 2, '0', STR_PAD_LEFT), // Format like "2023-01"
                        'total_sales' => $sale->total_sales,
                    ];
                }), // Convert the sales collection to an array
            ];
        }); 
        // Order Report

        $orders = Order::with(['customer', 'payment'])  // Load related customer and payment data
            ->where('merchant_id', $user->user_id)
            ->where('order_status', '!=', 'to-rate')
            ->get();


        $orderStatuses = Order::select('order_status', DB::raw('COUNT(*) as count'))
            ->where('merchant_id', $user->user_id)
            ->where('order_status', '!=', 'to-rate') // Exclude 'to-rate' status
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status');

        $refundStatuses = RefundRequest::select('refund_status', DB::raw('COUNT(*) as count'))
            ->join('order', 'refund_request.order_id', '=', 'order.order_id')
            ->where('order.merchant_id', $user->user_id)
            ->groupBy('refund_status')
            ->get()
            ->pluck('count', 'refund_status');

        // Return the inventory view with all the data packed in
        return view('merchant.inventory.index', compact(
            'orders',
            'products',
            'categories',
            'salesData',
            'lowStockProducts',
            'salesPerDay',
            'salesPerMonthData',
            'salesPerYear',
            'popularProductData',
            'categorySalesTrend',
            'orderStatuses',
            'refundStatuses'
        ));
    }

    public function generateOrderReportPDF(Request $request)
    {
        $user = Auth::user();

        // Check if the user type is 'merchant', otherwise redirect to dashboard
        if ($user->type !== 'merchant') {
            return redirect()->route('dashboard'); // Or any other route you want to redirect non-merchants to
        }

        $orders = Order::with('customer')->where('merchant_id', $user->user_id)->get();

        // Decode base64 chart images
        $orderStatusChartData = $request->input('orderStatusChart');
        $refundStatusChartData = $request->input('refundStatusChart');

        // Fetch merchant shop name
        $merchantShopName = $user->shop->shop_name ?? 'default';

        // Fetch order statuses grouped by order_status
        $orderStatuses = Order::select('order_status', DB::raw('COUNT(*) as count'))
            ->where('merchant_id', $user->user_id)
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status');

        // Calculate completed revenue
        $completedRevenue = $orders->where('order_status', 'completed')->sum('total_amount');

        $currentDate = now()->format('Y-m-d');
        $previousDate = now()->subMonth()->format('Y-m-d');
        // Create a folder for the merchant in storage/app/merchants/
        $merchantFolder = storage_path('app/public/merchant/' . $merchantShopName);
        if (!file_exists($merchantFolder)) {
            mkdir($merchantFolder, 0755, true);  // Create folder if it doesn't exist
        }

        // Save the charts in the merchant's folder
        $orderStatusChartPath = $merchantFolder . '/order_status_chart_' . $currentDate . '.png';
        $refundStatusChartPath = $merchantFolder . '/refund_status_chart_' . $currentDate . '.png';

        // Remove the 'data:image/png;base64,' prefix and save the charts
        file_put_contents($orderStatusChartPath, base64_decode(str_replace('data:image/png;base64,', '', $orderStatusChartData)));
        file_put_contents($refundStatusChartPath, base64_decode(str_replace('data:image/png;base64,', '', $refundStatusChartData)));

        // Pass the orderStatuses and other data to the PDF view
        $pdf = PDF::loadView('merchant.inventory.partials.order_report_pdf', [
            'orders' => $orders,
            'orderStatuses' => $orderStatuses, // This is the correct variable
            'completedRevenue' => $completedRevenue,
            'orderStatusChartPath' => $orderStatusChartPath,
            'refundStatusChartPath' => $refundStatusChartPath,
            'merchantShopName' => $merchantShopName,

        ]);

        // Return the PDF as a download
        return $pdf->download($merchantShopName . '_order_report_' . $currentDate . '.pdf');
    }
    public function generateSalesReportPDF(Request $request)
    {
        $user = Auth::user();

        // Check if the user type is 'merchant', otherwise redirect to dashboard
        if ($user->type !== 'merchant') {
            return redirect()->route('dashboard'); // Or any other route you want to redirect non-merchants to
        }

        // Get sales data (similar to what you did in your existing code)
        $salesData = BicollectionSales::with('product')
            ->where('merchant_id', $user->user_id)
            ->get();

        // Decode base64 chart images sent from frontend
        $salesPerDayChartData = $request->input('salesPerDayChart');
        $salesPerMonthChartData = $request->input('salesPerMonthChart');
        $salesPerYearChartData = $request->input('salesPerYearChart');
        $popularProductsChartData = $request->input('popularProductsChart');
        $categorySalesTrendChartData = $request->input('categorySalesTrendChart');

        // Fetch merchant shop name
        $merchantShopName = $user->shop->shop_name ?? 'default';

        // Calculate total sales and other statistics
        $totalSales = $salesData->sum('total_price'); // Adjust the field based on your data structure

        $currentDate = now()->format('Y-m-d');
        $previousDate = now()->subMonth()->format('Y-m-d');
        
        // Create a folder for the merchant in storage/app/merchants/
        $merchantFolder = storage_path('app/public/merchant/' . $merchantShopName);
        if (!file_exists($merchantFolder)) {
            mkdir($merchantFolder, 0755, true);  // Create folder if it doesn't exist
        }

        // Save the chart images in the merchant's folder
        $salesPerDayChartPath = $merchantFolder . '/sales_per_day_chart_' . $currentDate . '.png';
        $salesPerMonthChartPath = $merchantFolder . '/sales_per_month_chart_' . $currentDate . '.png';
        $salesPerYearChartPath = $merchantFolder . '/sales_per_year_chart_' . $currentDate . '.png';
        $popularProductsChartPath = $merchantFolder . '/popular_products_chart_' . $currentDate . '.png';
        $categorySalesTrendChartPath = $merchantFolder . '/category_sales_trend_chart_' . $currentDate . '.png';

        // Remove the 'data:image/png;base64,' prefix and save the charts
        file_put_contents($salesPerDayChartPath, base64_decode(str_replace('data:image/png;base64,', '', $salesPerDayChartData)));
        file_put_contents($salesPerMonthChartPath, base64_decode(str_replace('data:image/png;base64,', '', $salesPerMonthChartData)));
        file_put_contents($salesPerYearChartPath, base64_decode(str_replace('data:image/png;base64,', '', $salesPerYearChartData)));
        file_put_contents($popularProductsChartPath, base64_decode(str_replace('data:image/png;base64,', '', $popularProductsChartData)));
        file_put_contents($categorySalesTrendChartPath, base64_decode(str_replace('data:image/png;base64,', '', $categorySalesTrendChartData)));
        // Pass the sales data and other details to the PDF view
        $pdf = PDF::loadView('merchant.inventory.partials.sales_report_pdf', [
            'salesData' => $salesData,
            'totalSales' => $totalSales,
            'salesPerDayChartPath' => $salesPerDayChartPath,
            'salesPerMonthChartPath' => $salesPerMonthChartPath,
            'salesPerYearChartPath' => $salesPerYearChartPath,
            'popularProductsChartPath' => $popularProductsChartPath,
            'categorySalesTrendChartPath' => $categorySalesTrendChartPath,
            'merchantShopName' => $merchantShopName,
        ]);

        // Return the PDF as a download
        return $pdf->download($merchantShopName . '_sales_report_' . $currentDate . '.pdf');
    }



}

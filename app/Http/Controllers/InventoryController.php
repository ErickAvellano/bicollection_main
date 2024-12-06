<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BicollectionSales;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Return the inventory view with all the required data
        return view('merchant.inventory.index', compact('products', 'categories', 'salesData', 'lowStockProducts', 'salesPerDay', 'salesPerMonth', 'salesPerMonthData', 'salesPerYear'));
    }

    public function generatePDF()
    {
        $user = Auth::user();
        $salesData = BicollectionSales::with('product')->where('merchant_id', $user->user_id)->get();
        $pdf = Pdf::loadView('merchant.inventory.partials.sales_report_pdf', compact('salesData'));

        return $pdf->download('sales_report.pdf');
    }
}

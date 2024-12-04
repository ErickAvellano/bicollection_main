<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BicollectionSales;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Ensure the authenticated user is a merchant
        $user = Auth::user();
        if ($user->type !== 'merchant') {
            return redirect()->route('home')->with('error', 'Unauthorized access. Only merchants can view this page.');
        }

        // Fetch data for the inventory view
        $products = Product::with('images')
            ->where('merchant_id', $user->user_id)
            ->get();


        $categories = Category::all();
        $salesData = BicollectionSales::with('product')->get();
        $lowStockProducts = Product::where('merchant_id', $user->user_id) // Filter low-stock products by merchant
            ->where('quantity_item', '<=', 5)
            ->get();

        // Return the inventory view
        return view('merchant.inventory.index', compact('products', 'categories', 'salesData', 'lowStockProducts'));
    }
}

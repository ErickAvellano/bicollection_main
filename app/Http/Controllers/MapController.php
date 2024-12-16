<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Merchant;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Exception;
class MapController extends Controller
{
    public function showMap()
    {
        // Fetch all regions
        $regions = Region::all();

        // Fetch all categories and products for the regions
        $categories = Category::all();
        $products = Product::all();
        $shops = Shop::all();

        return view('map.map', compact('regions', 'categories', 'products', 'shops'));
    }
    
    public function mapLanding($regionAlias)
    {
        // Mapping of aliases to full region names
        $regionMapping = [
            'camnorte' => 'Camarines Norte',
            'camsur' => 'Camarines Sur',
            'albay' => 'Albay',
            'sorsogon' => 'Sorsogon',
            'catanduanes' => 'Catanduanes',
            'masbate' => 'Masbate',
        ];

        // Get the full region name using the alias
        $regionName = $regionMapping[$regionAlias] ?? null;

        if (!$regionName) {
            abort(404); 
        }

        // Fetch the region information
        $region = Region::where('name', $regionName)->firstOrFail();

        // Define dimensions for the six provinces
        $dimensions = [
            'camnorte' => ['width' => '600px', 'height' => 'auto'],
            'camsur' => ['width' => '640px', 'height' => 'auto'],
            'albay' => ['width' => '670px', 'height' => 'auto'],
            'sorsogon' => ['width' => '610px', 'height' => 'auto'],
            'catanduanes' => ['width' => '550px', 'height' => 'auto'],
            'masbate' => ['width' => '550px', 'height' => 'auto'],
        ];

        $width = $dimensions[$regionAlias]['width'] ?? '800px';  
        $height = $dimensions[$regionAlias]['height'] ?? '600px';

        // Fetch shops located in the selected province
        $shops = Shop::where('province', $regionName)->get();

        if ($shops->isEmpty()) {
            Log::info("No shops found for region: {$regionName}");
        }

        // Fetch merchants from those shops
        $merchantIds = $shops->pluck('merchant_id');

        // Fetch products from those merchants
        $products = Product::whereIn('merchant_id', $merchantIds)->get();

        // Get the product list from the region (assuming it’s comma-separated)
        $productList = explode(',', $region->products_list);

        // Fetch categories based on the product list
        $categories = Category::whereIn('category_id', $productList)->get();

        if ($categories->isEmpty()) {
            Log::info("No categories found for region: {$regionName}");
        }

        return view('map.maplanding', compact('region', 'width', 'height', 'shops', 'products', 'categories'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImg;
use App\Models\Region;
use App\Models\Shop;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use App\Models\ProductReview;
use App\Models\ProductVisitLog;



class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::whereNull('parentcategoryID')->get();

        // Fetch all subcategories
        $subcategories = Category::whereNotNull('parentcategoryID')->get();

        // Return
        return view('merchant.product.create', compact('categories', 'subcategories'));
    }
    public function inventorycreate()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        $user = Auth::user();
        if ($user->type !== 'merchant') {
            return redirect()->route('home')->with('error', 'Unauthorized access. Only merchants can view this page.');
        }
    
        $categories = Category::whereNull('parentcategoryID')->get();

        // Fetch all subcategories
        $subcategories = Category::whereNotNull('parentcategoryID')->get();

       // Return
        return view('merchant.inventory.create', compact('categories', 'subcategories'));
    }



    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('merchant.product.create')->with('error', 'You must be logged in to add a product.');
        }

        try {
            // Validation logic
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'quantity_item' => 'required|integer',
                'category_id' => 'required|exists:category,category_id',
                'subcategory_id' => 'required|exists:category,category_id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'variations.*.attribute' => 'nullable|string|max:255',
                'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'variations.*.quantity_item' => 'nullable|integer',
                'variations.*.price' => 'nullable|numeric',
            ]);

            // Create the product
            $product = Product::create([
                'merchant_id' => Auth::user()->user_id,
                'product_name' => $validatedData['product_name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'quantity_item' => $validatedData['quantity_item'],
                'category_id' => $validatedData['category_id'],
                'subcategory_id' => $validatedData['subcategory_id'],
                'product_status' => 1,
            ]);

            // Handle main product images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Save product images
            $productImages = ProductImg::create([
                'product_id' => $product->product_id,
                'product_img_path1' => $imagePaths[0] ?? null,
                'product_img_path2' => $imagePaths[1] ?? null,
                'product_img_path3' => $imagePaths[2] ?? null,
                'product_img_path4' => $imagePaths[3] ?? null,
                'product_img_path5' => $imagePaths[4] ?? null,
            ]);

            // Handle variations
            if ($request->has('variations') && count($request->variations) > 0) {
                foreach ($request->variations as $variation) {
                    if (!isset($variation['attribute']) || empty($variation['attribute'])) {
                        continue; // Skip variations without an attribute
                    }

                    $variationImagePath = null;

                    // Check and upload the variation image
                    if (isset($variation['image']) && $variation['image'] instanceof UploadedFile) {
                        $variationImagePath = $variation['image']->store('products', 'public');
                    }

                    // Save the variation
                    ProductVariation::create([
                        'product_id' => $product->product_id,
                        'variation_name' => $variation['attribute'],
                        'variation_image' => $variationImagePath,
                        'quantity_item' => $variation['quantity_item'] ?? $validatedData['quantity_item'],
                        'price' => $variation['price'] ?? $validatedData['price'],
                        'product_status' => 1,
                    ]);

                    // Save variation image to the next available slot in product images
                    if ($variationImagePath) {
                        for ($i = 1; $i <= 5; $i++) {
                            $imageField = 'product_img_path' . $i;
                            if (empty($productImages->$imageField)) {
                                $productImages->$imageField = $variationImagePath;
                                $productImages->save();
                                break; //stop
                            }
                        }
                    }
                }
            } else {
                // If no variations were added, create a default variation
                $defaultVariationName = '<default>';
                ProductVariation::create([
                    'product_id' => $product->product_id,
                    'variation_name' => $defaultVariationName,
                    'variation_image' => null,
                    'quantity_item' => $validatedData['quantity_item'],
                    'price' => $validatedData['price'],
                    'product_status' => 1,
                ]);
            }

            return redirect()->route('mystore')->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            return redirect()->route('merchant.product.create')->with('error', 'An error occurred while adding the product.');
        }
    }

    public function inventorystore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('merchant.product.create')->with('error', 'You must be logged in to add a product.');
        }

        // Validation logic
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity_item' => 'required|integer',
            'category_id' => 'required|exists:category,category_id',
            'subcategory_id' => 'required|exists:category,category_id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'variations.*.attribute' => 'nullable|string|max:255',  
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'variations.*.quantity_item' => 'nullable|integer',
            'variations.*.price' => 'nullable|numeric',
        ]);

        // Create the product
        $product = Product::create([
            'merchant_id' => Auth::user()->user_id,
            'product_name' => $validatedData['product_name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity_item' => $validatedData['quantity_item'],
            'category_id' => $validatedData['category_id'],
            'subcategory_id' => $validatedData['subcategory_id'],
            'product_status' => 1,
        ]);

        // Handle main product images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        // Save product images
        $productImages = ProductImg::create([
            'product_id' => $product->product_id,
            'product_img_path1' => $imagePaths[0] ?? null,
            'product_img_path2' => $imagePaths[1] ?? null,
            'product_img_path3' => $imagePaths[2] ?? null,
            'product_img_path4' => $imagePaths[3] ?? null,
            'product_img_path5' => $imagePaths[4] ?? null,
        ]);

        // Handle variations
        if ($request->has('variations') && count($request->variations) > 0) {
            foreach ($request->variations as $variation) {
                $variationImagePath = null;

                // Check and upload the variation image
                if (isset($variation['image']) && $variation['image'] instanceof UploadedFile) {
                    $variationImagePath = $variation['image']->store('products', 'public');
                }

                // Save the variation
                ProductVariation::create([
                    'product_id' => $product->product_id,
                    'variation_name' => $variation['attribute'],
                    'variation_image' => $variationImagePath,
                    'quantity_item' => $variation['quantity_item'] ?? $validatedData['quantity_item'],
                    'price' => $variation['price'] ?? $validatedData['price'],
                    'product_status' => 1,
                ]);

                // Save variation image to the next available slot in product images
                if ($variationImagePath) {
                    for ($i = 1; $i <= 5; $i++) {
                        $imageField = 'product_img_path' . $i;
                        if (empty($productImages->$imageField)) {
                            $productImages->$imageField = $variationImagePath;
                            $productImages->save();
                            break; // Stop 
                        }
                    }
                }
            }
        } else {
            // If no variations were added, create a default variation
            $defaultVariationName = '<default>';
            ProductVariation::create([
                'product_id' => $product->product_id,
                'variation_name' => $defaultVariationName,
                'variation_image' => null,
                'quantity_item' => $validatedData['quantity_item'],
                'price' => $validatedData['price'],
                'product_status' => 1,
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Product added successfully!');
    }


    // Edit product by ID
    public function edit($id)
    {
        // Fetch the product by ID
        $product = Product::findOrFail($id);
        $variations = $product->variations;
        // Directly query image paths from the database for this product
        $images = DB::table('product_img')->where('product_id', $product->product_id)->first();

        $categories = Category::whereNull('parentcategoryID')->get();
        $subcategories = Category::whereNotNull('parentcategoryID')->get();

        return view('merchant.product.edit', compact('product', 'categories', 'subcategories', 'images', 'variations'));
    }
    public function inventoryedit($id)
    {
        $product = Product::findOrFail($id);
        $variations = $product->variations;
 
        $images = DB::table('product_img')->where('product_id', $product->product_id)->first();

        $categories = Category::whereNull('parentcategoryID')->get();
        $subcategories = Category::whereNotNull('parentcategoryID')->get();

        return view('merchant.inventory.edit', compact('product', 'categories', 'subcategories', 'images', 'variations'));
    }

    // Show products by category
    public function showByCategory($category_name)
    {
        $category = Category::where('category_name', $category_name)->firstOrFail();
        $products = Product::where('category_id', $category->category_id)->get();

        return view('mystore', compact('products', 'category'));
    }

    public function update(Request $request, $id)
    {
        // Validate data
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity_item' => 'required|integer',
            'category_id' => 'required|exists:category,category_id',
            'subcategory_id' => 'required|exists:category,category_id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variations.*.attribute' => 'string|max:255',
            'variations.*.price' => 'numeric',
            'variations.*.quantity_item' => 'integer',
            'variations.*.image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the product by ID and update it
        $product = Product::findOrFail($id);
        $product->update([
            'product_name' => $validatedData['product_name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity_item' => $validatedData['quantity_item'],
            'category_id' => $validatedData['category_id'],
            'subcategory_id' => $validatedData['subcategory_id'],
        ]);

        // Handle product images (main images)
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');
                $imagePaths['product_img_path' . ($key + 1)] = $path; 
            }
        }

        $productImages = ProductImg::firstOrCreate(
            ['product_id' => $product->product_id],
            [
                'product_img_path1' => null,
                'product_img_path2' => null,
                'product_img_path3' => null,
                'product_img_path4' => null,
                'product_img_path5' => null,
            ]
        );

        foreach ($imagePaths as $key => $path) {
            if ($path) {
                if (empty($productImages->$key)) {  
                    $productImages->$key = $path;
                }
            }
        }
        $productImages->save();

        if ($request->variations) {
            foreach ($request->variations as $index => $variation) {
                $variationImagePath = null;

                if (isset($variation['image'])) {
                    $variationImagePath = $variation['image']->store('products', 'public');
                }

                ProductVariation::updateOrCreate(
                    [
                        'product_id' => $product->product_id,
                        'variation_name' => $variation['attribute'],
                    ],
                    [
                        'variation_image' => $variationImagePath,
                        'quantity_item' => $variation['quantity_item'] ?? $validatedData['quantity_item'],
                        'price' => $variation['price'] ?? $validatedData['price'],
                        'product_status' => 1,
                    ]
                );

                // Assign variation image to product_img_path if the product image slot is empty
                if ($variationImagePath) {
                    // Find the first available empty product_img_path slot
                    for ($i = 1; $i <= 5; $i++) {
                        $imageSlotField = 'product_img_path' . $i;
                        if (empty($productImages->$imageSlotField)) {
                            $productImages->$imageSlotField = $variationImagePath;
                            break; // Stop 
                        }
                    }
                }
            }
            $productImages->save();
        }

        return redirect()->route('mystore')->with('success', 'Product updated successfully!');
    }
    public function inventoryupdate(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity_item' => 'required|integer',
            'category_id' => 'required|exists:category,category_id',
            'subcategory_id' => 'required|exists:category,category_id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variations.*.attribute' => 'string|max:255',
            'variations.*.price' => 'numeric',
            'variations.*.quantity_item' => 'integer',
            'variations.*.image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the product by ID and update it
        $product = Product::findOrFail($id);
        $product->update([
            'product_name' => $validatedData['product_name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity_item' => $validatedData['quantity_item'],
            'category_id' => $validatedData['category_id'],
            'subcategory_id' => $validatedData['subcategory_id'],
        ]);

        // Handle product images (main images)
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');
                $imagePaths['product_img_path' . ($key + 1)] = $path; 
            }
        }

        // Ensure that the product images record exists
        $productImages = ProductImg::firstOrCreate(
            ['product_id' => $product->product_id],
            [
                'product_img_path1' => null,
                'product_img_path2' => null,
                'product_img_path3' => null,
                'product_img_path4' => null,
                'product_img_path5' => null,
            ]
        );

        foreach ($imagePaths as $key => $path) {
            if ($path) {
                if (empty($productImages->$key)) {  
                    $productImages->$key = $path;
                }
            }
        }

        // Save the updated product images
        $productImages->save();

        // Handle product variations 
        if ($request->variations) {
            foreach ($request->variations as $index => $variation) {
                $variationImagePath = null;

                // Handle image upload for variations
                if (isset($variation['image'])) {
                    $variationImagePath = $variation['image']->store('products', 'public');
                }

                ProductVariation::updateOrCreate(
                    [
                        'product_id' => $product->product_id,
                        'variation_name' => $variation['attribute'],
                    ],
                    [
                        'variation_image' => $variationImagePath,
                        'quantity_item' => $variation['quantity_item'] ?? $validatedData['quantity_item'],
                        'price' => $variation['price'] ?? $validatedData['price'],
                        'product_status' => 1,
                    ]
                );
                if ($variationImagePath) {
                    
                    for ($i = 1; $i <= 5; $i++) {
                        $imageSlotField = 'product_img_path' . $i;
                        if (empty($productImages->$imageSlotField)) {
                            $productImages->$imageSlotField = $variationImagePath;
                            break; // Stop 
                        }
                    }
                }
            }

            $productImages->save();
        }

        return redirect()->route('inventory.index')->with('success', 'Product updated successfully!');
    }

        public function viewProduct($id)
        {
            // Fetch the product details based on the product ID
            $product = Product::with(['category', 'subcategory', 'images', 'variations', 'merchant.shop'])->findOrFail($id);

            ProductVisitLog::updateOrInsert(
                ['product_id' => $product->product_id], 
                ['view_count' => DB::raw('view_count + 1')] 
            );

            $shop = $product->merchant->shop;

            // Fetch approved reviews for the product
            $reviews = ProductReview::with('customerImage') 
                ->where('product_id', $id)
                ->where('is_approved', 0)
                ->orderBy('review_date', 'desc')
                ->get();

            $reviewCount = $reviews->count();

            // Calculate the average rating if there are reviews
            if ($reviews->isNotEmpty()) {
                $totalRating = $reviews->sum('rating');
                $reviewCount = $reviews->count();
                $averageRating = round($totalRating / $reviewCount, 1);
            } else {
                $averageRating = null; // No reviews available
            }

            // Pass the product, shop, reviews, and average rating to the view
            return view('merchant.product.view', compact('product', 'shop', 'reviews', 'averageRating', 'reviewCount'));
        }

        //Filter the category
        public function filterProducts($categoryId, $regionName)
        {
            // If "all" is selected, return all products from the province
            if ($categoryId === 'all') {
                $shops = Shop::where('province', $regionName)->get();
                $merchantIds = $shops->pluck('merchant_id');
                $products = Product::whereIn('merchant_id', $merchantIds)->with('images')->get();
            } else {
                // Filter products by category and province
                $shops = Shop::where('province', $regionName)->get();
                $merchantIds = $shops->pluck('merchant_id');
                $products = Product::where('category_id', $categoryId)
                    ->whereIn('merchant_id', $merchantIds)
                    ->with('images')
                    ->get();
            }

            // Format products data as JSON
            return response()->json($products->map(function($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->product_name,
                    'price' => number_format($product->price, 2),
                    'category_id' => $product->category_id,
                    'image_url' => $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/100',
                ];
            }));
        }
    public function inventoryproductdestroy($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Optionally delete associated images, if needed
        if ($product->images()->exists()) {
            foreach ($product->images as $image) {
                // Delete the image from storage
                Storage::delete($image->product_img_path1);
            }
        }

        // Delete the product
        $product->delete();

        // Redirect back with a success message
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }



}

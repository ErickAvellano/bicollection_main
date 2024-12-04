@extends('Components.layout')

@section('styles')
<style>
    body, html {
        overflow: auto;
        height: 100%;
        margin: 0; /* Ensure no default margin */
    }

    .map-landing-container {
        margin: 0 auto;
        padding: 20px;
        background-color: #fafafa;
    }

    .map-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .map-image {
        width: 100%;
        max-width: 700px; /* Fixed the typo here */
        height: auto;
    }

    .categories {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    .category-item {
        padding: 10px 20px;
        background-color: #ddd;
        border-radius: 5px;
        text-align: center;
    }

    .product-card img {
        height: 80px;
        object-fit: contain;
    }

    .merchant-logos img {
        width: 100px;
        object-fit: cover;
    }

    .mt-4 {
        margin-top: 0rem !important;
    }

    .merchant-logos {
        display: flex;
        justify-content: center;
        flex-wrap: wrap; /* This will allow items to wrap onto a new line if needed */
        gap: 15px; /* Adds space between items */
    }

    .merchant-logos .text-center {
        width: 110px; /* Set a consistent width for each merchant logo block */
        position: relative;
    }
    /* Menu Container */
    .menu-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
        background-color: #fafafa;
    }

    /* Menu List */
    .menu-container ul {
        list-style-type: none;
        display: flex;
        margin: 0;
        padding: 0;
    }

    /* Menu Items */
    .menu-container ul li {
        margin-right: 30px;
    }

    /* Links Styling */
    .menu-container ul li a {
        text-decoration: none;
        color: #333;
        font-size: 16px;
        font-weight: 500;
        position: relative;
        padding-bottom: 5px;
    }

    /* Active Link with Red Underline */
    .menu-container ul li a.active {
        color: #28a745;
        border-bottom: 2px solid #28a745;
    }

    /* Hover Effect */
    .menu-container ul li a:hover {
        color: #28a745;
    }

    /* Dropdown Menu */
    .dropdown-content {
        display: none;
        position: absolute;
        top: 30px;
        background-color: #f9f9f9;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px 0;
    }

    /* Dropdown Links */
    .dropdown-content a {
        color: #000;
        padding: 10px 15px;
        text-decoration: none;
        display: block;
    }

    /* Show Dropdown on Hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Dropdown Hover Effect */
    .dropdown-content a:hover {
        background-color: #e63946;
        color: #fff;
    }
    .allproduct-products-container {
        padding: 10px 0; /* Reduce top/bottom padding of container */
    }
    .row-equal {
        display: flex; /* Use flexbox for the row */
    }

    .equal-height {
        display: flex;
        flex-direction: column; /* Align items vertically */
        flex: 1; /* Allow the column to grow and fill available space */
    }

    .merchant-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px; /* Keep the same border radius as the parent card */
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .merchant-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }


</style>
@endsection

@section('content')
<div class="container map-landing-container">
    <!-- Map Section -->
    <div class="row">
        <div class="col-md-7 text-center map-container">
            <div style="width: {{ $width }}; height: {{ $height }};">
                <img class="map-image" src="{{ asset("images/assets/map/landmark/{$region->name}.png") }}" alt="{{ ucfirst($region) }} Map" style="object-fit: cover; filter: drop-shadow(0px 1px 50px rgba(137, 216, 236, 0.9)); width: 90%;">
            </div>
        </div>

        <!-- About Section -->
        <div class="col-md-5 mb-4">
            <div class="card" style="border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <div class="card-body" style="padding: 30px;">
                    <h5 class="card-title" style="font-size: 1.75rem; font-weight: bold; color: #333;">
                        About {{ $region->name }}
                    </h5>
                    <p class="card-text" style="font-size: 1rem; line-height: 1.75; color: #666;">
                        {!! $region->description !!}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section (About, List Products, Merchant) -->
    <div class="row row-equal mb-4">
        <div class="col-md-6 equal-height">
            <div class="card" style="border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); height:100%;">
                <div class="card-body" style="padding: 30px;">
                    <h5 class="card-title" style="font-size: 1.4rem; font-weight: bold; color: #333;">List Product</h5>
                    <ul class="card-text">
                        @foreach($categories as $category)
                            <li>{{ $category->category_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 equal-height">
            <div class="card" style="border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <div class="card-body" style="padding: 30px; text-align:center;">
                    <h5 class="card-title" style="font-size: 1.4rem; font-weight: bold; color: #333; text-align:start;">
                        Merchants in {{ ucfirst($region->name) }}
                    </h5>
                    <div class="merchant-logos d-flex justify-content-start flex-wrap">
                        @foreach($shops as $shop)
                            <div class="merchant-card" style="margin: 20px 15px 0px 15px;">
                                <div class="text-center merchant-block" style="position: relative;">
                                    <!-- Awning Image positioned in front -->
                                    <img src="{{ asset('images/assets/awd.png') }}"
                                         alt="Awning"
                                         style="width: 60px; position: absolute; top: -5px; left: 50%; transform: translateX(-50%); z-index: 10;">

                                    <!-- Shop Image with Rounded Circle positioned behind the awning -->
                                    <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : asset('images/default_profile.png') }}"
                                         alt="{{ $shop->shop_name }}"
                                         class="rounded-circle mb-2"
                                         style="display: block; margin: auto; width: 55px; height: 55px; object-fit: cover; position: relative; z-index: 5;">

                                    <!-- Shop Name -->
                                    <p style="font-size: 11px;">{{ $shop->shop_name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    <!-- Product Section -->
    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <div class="menu-container">
                <ul>
                    <li><a href="#" class="product-link active" data-category="all" >All Products</a></li> <!-- Link to show all products -->
                    @foreach($categories as $category)
                        <li><a href="#" class="product-link" data-category="{{ $category->category_id }}">{{ $category->category_name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- All Products Section -->
        <div class="col-md-12 mt-4 allproduct-products-container">
            @if($products->isEmpty())
                <p>No products found.</p>
            @else
                <div class="row row-cols-1 row-cols-md-6 g-5">
                    @foreach($products as $product)
                        <div class="col product-card">
                            <div class="card h-100" style="width: 180px;">
                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}"
                                    class="card-img-top"
                                    alt="{{ $product->product_name }}"
                                    style="width: auto; height: 150px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $product->product_name }}</h6>
                                    <p class="card-text" style="font-size: 13px;"><strong>Price: ${{ $product->price }}</strong></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="#" class="btn btn-primary btn-sm mt-auto">Add to cart</a>

                                        <!-- Heart Icon -->
                                        <button class="btn btn-outline-danger btn-sm wishlist-button" data-product-id="{{ $product->id }}">
                                            <i class="far fa-heart"></i> <!-- Font Awesome Heart Icon -->
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Category Products Section -->
        <div class="col-md-12 mt-4 category-products-container" style="display:none;">
            @if($products->isEmpty())
                <p>Currently no product listed.</p>
            @else
                <div class="row row-cols-1 row-cols-md-6 g-5">
                    @foreach($products as $product)
                        <div class="col product-card category-product" data-category="{{ $product->category_id }}">
                            <div class="card h-100" style="width: 180px;">
                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}"
                                    class="card-img-top"
                                    alt="{{ $product->product_name }}"
                                    style="width: auto; height: 150px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $product->product_name }}</h6>
                                    <p class="card-text" style="font-size: 13px;"><strong>Price: ${{ $product->price }}</strong></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="#" class="btn btn-primary btn-sm mt-auto">Add to cart</a>

                                        <!-- Heart Icon -->
                                        <a href="#" class="wishlist-icon" data-product-id="{{ $product->id }}">
                                            <i class="far fa-heart" style="color: red; font-size: 1.5em;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.querySelectorAll('.product-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default anchor behavior

            const category = this.dataset.category; // Get the category from the data attribute

            if (category === 'all') {
                document.querySelector('.allproduct-products-container').style.display = 'block'; // Show all products
                document.querySelector('.category-products-container').style.display = 'none'; // Hide category products
            } else {
                // Hide all products and show only the selected category products
                document.querySelector('.allproduct-products-container').style.display = 'none';
                const categoryProducts = document.querySelectorAll('.category-product');

                categoryProducts.forEach(product => {
                    if (product.dataset.category === category) {
                        product.parentElement.parentElement.parentElement.style.display = 'block'; // Show matched category product
                    } else {
                        product.parentElement.parentElement.parentElement.style.display = 'none'; // Hide unmatched category product
                    }
                });

                document.querySelector('.category-products-container').style.display = 'block'; // Show category products
            }
        });
    });
</script>
@endsection

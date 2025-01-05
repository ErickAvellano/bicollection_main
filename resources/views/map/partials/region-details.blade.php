<!-- map/partials/region-details.blade.php -->
<style>
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
    .product-card {
        border: 2px solid transparent; /* Default border */
        transition: transform 0.3s, border-color 0.3s; /* Smooth transition for transform and border color */
    }

    .product-card-hover:hover {
        border-color: #28a745; /* Change border color on hover */
        transform: scale(1.05); /* Slightly enlarge card on hover */
    }
    .icon-hover {
        transition: color 0.3s ease; /* Smooth transition */
    }

    .icon-hover:hover {
        color: #28a745; /* Change color on hover */
    }
    /* .img-hover-zoom--point-zoom img { */
        /* transition: transform 1s, filter .5s ease-out; */
        /* transition: transform 0.3s ease, transform-origin 0.3s ease; */
    /* } */

    /* The Transformation
    .img-hover-zoom--point-zoom:hover img {
        transform: scale(2);
    } */
    .card-title{
        font-size: 1rem; 
        font-weight: 
        bold; 
        color: #333;
    }
    .card-text{
        font-size: 0.95rem; 
        line-height: 1.6; 
        color: #666;
    }

    .zoom-area {
        width: 100%;
        margin: 0 auto;
        position: relative;
        cursor: none; /* Hide cursor inside zoom area */
        z-index: 999; 
        
    }
    .large {
        width: 300px;
        height: 300px;
        position: absolute;
        border-radius: 50%;
        box-shadow: 0 0 0 7px rgba(255, 255, 255, 0.85), 0 0 7px 7px rgba(0, 0, 0, 0.25), inset 0 0 40px 2px rgba(0, 0, 0, 0.25);
        display: none;
        z-index: 999; /* Ensure it's above the image */
    }
    .small {
        display: block;
    }
    .product-title {
        font-size: 1rem;
        font-weight: bold;
        margin: 0;
        min-height: 2.5rem; /* Adjusted to allow more content */
        max-height: 2.5rem; /* Adjusted to allow more content */
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    

    @media only screen and (min-width: 1441px) and (max-width: 1920px) {
        .card-text {
            font-size:1.3rem;
        }
        .card-title{
            font-size:1.4rem;
        }
    }

</style>


<div class="container map-landing-container" style="background-color: transparent; padding: 10px;">
    <div class="row mt-5">
        <div class="col-md-12 mb-4 text-center">
            <div class="zoom-area" style="width: {{ $width }}; height: {{ $height }}; margin: 0 auto; display: flex; justify-content: center; align-items: center; position: relative;" >
                <!-- Magnify Glass -->
                <div class="large"></div>
                <!-- Map Image -->
                <img id="regionMapImage"
                    class="map-image small"
                    src="{{ asset('images/assets/map/' . strtolower($region->name) . 'landmark' . '.png') }}"
                    alt="{{ ucfirst($region->name) }} Map"
                    loading="lazy"
                    style="object-fit: cover; filter: drop-shadow(0px 1px 50px rgba(31, 123, 145, 0.9)); max-width: 100%; height: auto; transition: transform 0.3s ease, transform-origin 0.3s ease;">
                <h2 style="position: absolute; top: {{ $top }}; left: {{ $left }}; padding: 5px; border-radius: 5px; color: #333; font-weight: bold;">
                    {{ ucfirst($region->name) }}
                </h2>
            </div>
            <input type="hidden" id="region_name" name="region_name" value="{{ $region->name }}">
        </div>
        
        <div class="col-md-12 mb-4">
            <div class="card" style="border-radius: 10px; border:none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); text-align: justify;">
                <div class="card-body" style="padding: 30px;">
                    <h6 class="card-title" style="font-size: 1rem; font-weight: bold; color: #333;">
                        About {{ $region->name }}
                    </h6>
                    <p class="card-text">
                        {!! $region->description !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4 equal-height">
        <div class="card" style="border-radius: 10px; border:none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); height:100%;">
            <div class="card-body" style="padding: 30px;">
                <h6 class="card-title">Product List</h6>
                <ul class="card-text">  
                    @foreach($categories as $category)
                        <li style="padding: 5px 0; ">{{ $category->category_name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12 equal-height">
        <div class="card" style="border-radius: 10px; border:none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="padding: 30px;">
                <h6 class="card-title" >
                    Merchants in {{ ucfirst($region->name) }}
                </h6>
                @if($products->isEmpty())
                <div class="text-center" style="min-height: 115px">
                    <p class="card-text">Oops! Looks like we don't have any merchants from this province yet. If you're a local merchant, why not join us?</p>
                    <a class="btn btn-custom mt-2" href="{{ route('merchant.startselling') }}" style="display: inline-flex; align-items: center;">
                        <i class="fa-regular fa-handshake icon-hover" style=" margin-right: 5px;"></i>
                        Sell On BiCollection
                    </a>

                </div>
                 @else
                    <div class="merchant-logos d-flex justify-content-start flex-wrap" style="min-height: 115px">
                        @foreach($shops as $shop)
                        <a href="{{ route('merchant.viewstore', ['shopId' => $shop->shop_id]) }}" style="text-decoration: none; color:#212529;">
                            <div class="merchant-card" style="margin: 20px 15px 0px 15px;">
                                <div class="text-center merchant-block" style="position: relative;">
                                    <img src="{{ asset('images/assets/awd.png') }}" alt="Awning" style="width: 60px; position: absolute; top: -5px; left: 50%; transform: translateX(-50%); z-index: 10;">
                                    <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : asset('images/default_profile.png') }}" alt="{{ $shop->shop_name }}" class="rounded-circle mb-2" style="display: block; margin: auto; width: 55px; height: 55px; object-fit: cover; position: relative; z-index: 5;">
                                    <p style="font-size: 11px;">{{ $shop->shop_name }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Section -->
    <div class="row mt-3 mb-3">
        <div class="col-md-12 mb-4">
            <div class="card" style="border-radius: 10px; border: none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); height: 100%;">
                <div class="card-body">
                    <div class="menu-container">
                        <ul>
                            <li><a href="#" class="product-link-map active" data-category="all">Products</a></li>
                            @foreach($categories as $category)
                                <li><a href="#" class="product-link-map" data-category="{{ $category->category_id }}">{{ $category->category_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Products Section -->
                <div id="product-list" style="min-height: 240px">
                    @if($products->isEmpty())
                        <div class="text-center">
                            <p class ="card-text">No current product listed</p>
                        </div>
                    @else
                        <div class="d-flex flex-wrap justify-content-start" style="padding:0 20px;">
                            @foreach($products as $product)
                                <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none product-item" data-category="{{ $product->category_id }}" style="color: inherit;">
                                    <div class="product-item p-1">
                                        <div class="card product-card product-card-hover" style="width: 9rem; border: 2px solid transparent; transition: transform 0.3s, border-color 0.3s; position: relative;">
                                            <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/100' }}"
                                                class="card-img-top"
                                                alt="{{ $product->product_name }}"
                                                style="width: 100%; height: 110px; object-fit: cover;">
                                            <div class="card-body text-center" style="padding: 10px 0;">
                                                <h6 class="card-title product-title" style="font-size: 0.85rem; font-weight: bold;">{{ $product->product_name }}</h6>
                                                <p class="card-text"style="font-size: 12px; color: #555;"><strong>₱{{ number_format($product->price, 2) }}</strong>
                                                </p>
                                                <p class="card-text" style="font-size: 11px; color: #555;">No reviews</p>

                                                <div class="d-flex justify-content-between align-items-center mt-2 p-1">
                                                    <a href="#" class="btn btn-custom btn-sm add-to-cart" data-product-id="{{ $product->product_id }}" style="font-size: 12px;">
                                                       Add to Cart
                                                    </a>
                                                    <a href="#" class="btn btn-outline-danger btn-sm" style="width: 2rem; font-size: 12px;">
                                                        <i class="fas fa-heart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


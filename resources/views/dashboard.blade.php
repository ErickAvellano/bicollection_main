@extends('Components.layout')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    body, html {
        overflow: auto;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-modal-inner {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .custom-modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        min-width: 500px;
        text-align: center;
        position: relative;
    }

    .custom-close-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        cursor: pointer;
    }

    .custom-register-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 20px;
        display: inline-block;
    }

    .custom-register-btn:hover {
        background-color: #45a049;
    }

    .custom-ads-section {
        display: flex;
        justify-content: space-around;
    }

    .custom-ad img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .ads-section {
        display: flex;
        justify-content: space-around;
    }

    .register-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 20px;
        display: inline-block;
    }

    .register-btn:hover {
        background-color: #45a049;
    }
    .full-width-container {
        padding: 0;
        margin: 0;
        width: 100%;
    }
    .full-width-row {
        margin-left: 0;
        margin-right: 0;
        padding-left: 0;
        padding-right: 0;
    }
    .debug-border {
        outline: 2px solid red;
    }
    .product-card {
        width: 10rem;
        height: auto;
        margin-bottom: 15px;
    }


    .product-card img {
        width: 100%;
        height: 50px;
        object-fit: cover;
        border-radius: 5px 5px 0 0;
    }


    .product-card .card-body {
        padding: 8px;
    }

    .product-card .card-title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .product-card .card-text {
        font-size: 13px;
    }

    /* Reducing margin and padding in container */
    .allproduct-products-container, .popular-products-container {
        padding: 0 0;
    }

    .allproduct-products-row {
        margin-left: 0;
        margin-right: 0;
        gap: 5px;
    }
    .product-carousel {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 5px;

    }
    .product-carousel-product {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 5px;

    }
    //added new
    .product-carousel-recently {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 5px;

    }

    /* Product link styles */
    .product-link {
        text-decoration: none;
        color: inherit;
    }


    .product-items {
        padding: 10px;
        min-width: 150px;
        transition: transform 0.3s;
    }

    /* Card styling */
    .card {
        width: 11rem;
        border: none;
        position: relative;
        transition: transform 0.3s;
    }

    /* Card image styling */
    .card-img-top {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    /* Card body styling */
    .card-body {
        text-align: center;
    }

    /* Product title */
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

   .featured-title{
        font-size: 1rem;
        font-weight: bold;
        margin: 0;
   }

    /* Reviews text */
    .card-reviews {
        font-size: 12px;
        color: #777;
        margin: 0;
    }

    /* Action buttons styling */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    /* Wishlist button */
    .wishlist-button {
        width: 2rem;
        text-align: center;
    }

    /* View Product text */
    .view-product-text {
        display: none; /* Hide initially */
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.8);
        padding: 5px;
        border-radius: 5px;
    }

    /* Hover effect */
    .product-item:hover .view-product-text {
        display: block;
    }

    .btn {
    --bs-btn-padding-x: 0.7rem;
    --bs-btn-padding-y: 0.2rem;
    --bs-btn-font-size: 0.9rem;
    }

    /* Popular product header */
    .popular-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .popular-product-header h3 {
        margin: 0;
    }

    .popular-product-header .line {
        flex-grow: 1;
        height: 2px;
        border-top: 2px solid #333;
        margin: 0 10px;
    }

    .popular-product-header .button-group {
        display: flex;
        gap: 5px;
    }

    /* Banner Section */
    .banner {
        width: 100%;
        height: 350px;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        box-shadow: inset 0 -4px 6px rgba(0, 0, 0, 0.3);
        padding: 0;
    }

    /* CSS for smooth transition and carousel dots */
    #dynamicBanner {
        transition: background-image 1s ease-in-out;
        position: relative;
        z-index: -1;
        height: 350px;
        background-size: cover;
        background-position: center;
    }

    /* #carouselIndicators { */
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        bottom: 10px;
        width: 100%;
    /* } */

    #carouselIndicators {
        position: absolute;
        bottom: 15px; /* Adjusts how far up the dots appear from the bottom of the banner */
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 0;
        z-index: 10; /* Make sure it's above the banner image */
    }

    .carousel-dot {
        width: 10px;
        height: 10px;
        background-color: gray;
        margin: 0 5px;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .carousel-dot.active {
        background-color: #228b22;
    }
    #productCarousel {
        display: flex;
        width: 100%;
        overflow-x: scroll;
        scroll-behavior: smooth;
    }

    .product-card-hover {
        transition: border-color 0.3s ease-in-out;
        border: 1px solid transparent;
        transition: border 0.3s;
    }

    .product-card-hover:hover {
        border-color: #28a745;
        border: 3px solid 228b22;
    }

    .modal-backdrop.fade.show {
        display: none;
    }
    .modal-open{
        overflow: hidden !important;
        padding: 0 !important;
    }


    .modal.fade.show {
        display: flex !important; /* Ensure the modal is displayed properly */
        justify-content: center;  /* Center the modal horizontally */
        align-items: center;      /* Center the modal vertically */
        overflow: hidden;

    }

    .modal-content {
        border-radius: 10px;      /* Round the corners of the modal */
        padding: 20px;            /* Add padding inside the modal */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Shadow for better appearance */
    }

    .modal-header {
        border-bottom: none;      /* Remove the border from the header */
    }

    .modal-title {
        font-size: 1.25rem;       /* Adjust the title size */
        color: #333;              /* Color for the title text */
    }

    .modal-body {
        font-size: 1rem;          /* Adjust the font size in the modal body */
        color: #555;              /* Slightly lighter color for the body text */
    }

    .modal-body p {
        margin: 0;                /* Remove any margin from paragraphs */
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
        padding: 10px 20px;       /* Add some padding */
    }

    .modal-footer .btn {
        padding: 10px 15px;       /* Add some padding to the buttons */
    }

    .modal-footer .btn-primary {
        background-color: #28a745; /* Green color for Proceed to Checkout */
        border-color: #28a745;
    }

    .modal-footer .btn-primary:hover {
        background-color: #218838; /* Slightly darker on hover */
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d; /* Gray for Continue Shopping */
        border-color: #6c757d;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #5a6268; /* Darker on hover */
    }
    .popular-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding:0;
    }

    .popular-product-header h3 {
        margin: 0;
    }

    .popular-product-header .line {
        flex-grow: 1;
        height: 2px;
        border: none;             /* Remove default border */
        border-top: 2px solid #333; /* Set color and thickness */
        margin: 0 10px;
    }
    .popular-product-header .button-group {
        display: flex;
        gap: 5px; /* Adjusts space between buttons */
    }

    .recently-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding:0;
    }

    .recently-product-header h3 {
        margin: 0;
    }

    .recently-product-header .line {
        flex-grow: 1;
        height: 2px;
        border: none;             /* Remove default border */
        border-top: 2px solid #333; /* Set color and thickness */
        margin: 0 10px;
    }
    .merchant-header .line {
        flex-grow: 1;
        height: 2px;
        border: none;             /* Remove default border */
        border-top: 2px solid #333; /* Set color and thickness */
        margin: 0 10px;
    }

    .merchant-header{
        display: flex;
        align-items: center;
        margin-bottom:20px;

    }


    .recently-product-header .button-group {
        display: flex;
        gap: 5px; /* Adjusts space between buttons */
    }
        /* Recently Added Products Header */
    .recently-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    #productCarouselRecentlyAdded {
        display: flex;
        overflow-x: hidden;
        scroll-behavior: smooth;
        gap: 5px;
    }

    /* Product Item Styling */
    .product-item {
        padding: 0.5rem;
        transition: transform 0.3s;
        cursor: pointer;
        position: relative;
    }

    .product-item:hover .view-product-text {
        display: block;
    }

    /* Card and Content Styling */
    .card {
        width: 11rem;
        border: none;
        position: relative;
        transition: transform 0.3s;
    }

    .card-img-top {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .card-body {
        text-align: center;
    }


    /* Add to Cart and Wishlist Buttons */
    .add-to-cart {
        font-size: 0.875rem;
        margin-right: 4px;
    }

    .wishlist-button {
        width: 2rem;
        text-align: center;
    }

    /* View Product Text on Hover */
    .view-product-text {
        display: none;
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.8);
        padding: 5px;
        border-radius: 5px;
    }
    .merchant-card{
        height: 312px;
        width:100%;
        padding:10px;
        background-color:#228b22;
        border-radius:0;
    }
    .merchant-card-body{
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .rounded-circle{
        width: 100px;
        height: 100px;
        background-size: cover;
        background-position: center;
    }

    .featured-product{
        display: flex;
        align-items: center;
        width: 100%;
        margin-bottom:20px;
    }

    .featured-product-title {
        margin: 0px 0px 0px 10px;
        padding-bottom:0;
    }

    .featured-product-card{
        height: 312px;
        width:auto;
        border: none;
        background-color:#fafafa;
        border-radius:0;
    }

    @media only screen and (min-width: 360px) and (max-width: 425px) {
        /* Styles for devices in this range */
        body {
            font-size: 12px;
        }
        #dynamicBanner {
            position: relative;
            z-index: -1;
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .banner {
            width: 100%;
            padding: 0;
        }

        #carouselIndicators {
            position: absolute;
            bottom: 5px; /* Adjusts how far up the dots appear from the bottom of the banner */
            width: 100%;
            padding: 10px 0;
            z-index: 10; /* Make sure it's above the banner image */
            margin: 0;
        }
        .carousel-dot{
            width: 5px;
            height: 5px;
        }

        .btn{
            --bs-btn-font-size: 0.9rem;
        }
        .navbar-brand{
            font-size: 1.2rem;
        }
        .product-carousel{
            display: flex; /* Allows horizontal scrolling of items */
            transition: transform 0.3s ease;
            -webkit-overflow-scrolling: touch;
            gap:0;
        }
        .product-carousel-product{
            gap:0;
        }
        .product-carousel-recently{
            gap:0;
        }

        .product-item{
            transform: scale(0.9);
        }
        .popular-product-header h3, .recently-product-header h3 {
            padding:0;
        }
        /* .button-group{
            margin-right:10px;
        } */
        .popularContainer{
            padding:0;
        }
        .popular-product-header, .merchant-header, .recently-product-header{
            padding: 0 10px;
        }
        .line{
            display:none;
        }

        .rounded-circle{
            width: 70px;
            height: 70px;
        }
        .merchant-card {
            height:225px;
            width:100%;
            padding: 5px;
            background-color: transparent;
        }

        .merchant-header{
            margin-bottom: 0px;
        }
        .shop-name {
            font-size: 1rem; /* Smaller font size for phones */
        }
        .shop-rating, .shop-address, .shop-contact {
            font-size: 0.8rem;
        }
        .shop-rating i,
        .shop-address i,
        .shop-contact i {
            font-size: 0.8rem; /* Adjust icon size for smaller screens */
        }

        .featured-title {
            font-size: 0.8rem; /* Smaller font size for title */
            margin-left: 0.5rem;
        }
        .card-reviews{
            margin-left: 0.5rem;
        }
        .card-reviews i {
            font-size: 0.75rem; /* Adjust star size */
          
        }
        .price {
            font-size: 0.75rem; /* Adjust font size */
            margin-left: 0.5rem;
        }
        .featured-product-card{
            height:auto;
            max-height: 312px;
        }
        .shop-location-style{
            margin-left: 0.5rem;
        }
        .popular-product-header h3, 
        .popular-product-header h3, 
        .merchant-header h3,
        .featured-product-title, 
        .recently-product-header h3, 
        .popular-product-header h3{
            font-size:20px;

        }
}


</style>
@endsection

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<section id="dynamicBanner" class="banner container-fluid">
        <div id="carouselIndicators" class="carousel-indicators"></div>
</section>
<!--Popular Product-->
<div class="container">
    <div class="mt-3 row">
        <div class="popular-product-header">
            <h3>POPULAR PRODUCT</h3>
            <hr class="line">
            <div class="button-group">
                <button id="prevBtnPopular" class="mb-2 btn btn-outline-dark btn-sm">
                    <i class="fa-solid fa-chevron-left" style="font-size: 14px;"></i>
                </button>
                <button id="nextBtnPopular" class="mb-2 btn btn-outline-dark btn-sm">
                    <i class="fa-solid fa-chevron-right" style="font-size: 14px;"></i>
                </button>
            </div>
        </div>

        <div class="mt-3 col-md-12 popularContainer">
            <div id="productCarouselPopular" class="product-carousel">
                @foreach($products as $product)
                    <a href="{{ route('product.view', $product->product_id) }}" class="product-link">
                        <div class="product-item product-card-hover">
                            <div class="card">
                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                                     class="card-img-top"
                                     loading="lazy"
                                     alt="{{ $product->product_name }}">
                                <div class="card-body">
                                    <h6 class="card-title product-title">{{ $product->product_name }}</h6>
                                    <p class="mb-1 card-price">₱{{ number_format($product->price, 2) }}</p>
                                    @if($product->averageRating > 0)
                                        <p class="card-reviews">
                                            {{-- Display stars based on the rating --}}
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->averageRating))
                                                    <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                                @elseif ($i - $product->averageRating < 1)
                                                    <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i>
                                                @else
                                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                @endif
                                            @endfor
                                            <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                        </p>
                                    @endif
                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        <a class="btn btn-custom add-to-cart" data-product-id="{{ $product->product_id }}">Add to Cart</a>
                                        <a href="#"
                                            class="btn {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                            data-product-id="{{ $product->product_id }}"
                                            style="width: 2rem; padding: 0.2rem 0;"
                                            title="{{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                                <i class="fas fa-heart {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- End Popular Product-->
<!-- Check if there is a merchant and shop to display -->
@if ($shop && $shop->verification_status === 'Verified')
    <!-- Merchant Card -->
    <div class="container">
        <div class="row">
            <div class="mt-3 col-md-12">
                <div class="row">
                    <div class="col-12 col-md-7">

                    </div>
                    <div class=" col-12 col-md-5">

                    </div>
                </div>
            </div>
        </div>
            <!-- Shop and Products Section -->
        <div class="mt-3 mb-2 col-md-12" style="padding:0;">
            <div class="row" id="productCarousel_partner">
                <!-- Shop Details -->
                <div class="col-12 col-md-7" style="padding:0;">
                    <div class="merchant-header">
                        <h3 style="margin: 0;">MERCHANT</h3>
                        <hr class="line">
                    </div>
                    <div class="card merchant-card">
                        <div class="card-body merchant-card-body">
                            <!-- Nested Card with Dynamic Background -->
                            <div class="card"
                                style="width:100%; height:100%; border-radius:10px;
                                @if ($shop->coverphotopath)
                                    background-image: url('{{ asset('storage/' . $shop->coverphotopath) }}');
                                @else
                                    background-image: url('{{ asset('images/default-cover.jpg') }}');
                                @endif
                                background-size: cover; background-position: center;">
                                <div class="card-body d-flex align-items-center justify-content-center" style="width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); border-radius:10px;">
                                    <!-- Shop Image -->
                                    <div class="col-3 col-md-4 d-flex align-items-center justify-content-center">
                                        @if ($shop->shop_img)
                                            <div class="rounded-circle" style="background-image: url('{{ asset('storage/' . $shop->shop_img) }}');"></div>
                                        @else
                                            <div class="rounded-circle bg-secondary"></div>
                                        @endif
                                    </div>

                                    <!-- Shop Name and Location -->
                                    <div class="col-md-8 shop-location-style">
                                        <h5 class="shop-name text-white text-start">{{ $shop->shop_name }}</h5>
                                        <p class="shop-rating mb-0 text-white text-start">
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        </p>
                                        <p class="shop-address mb-0 text-white text-start">
                                            <i class="fa-solid fa-location-dot text-danger"></i>
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->shop_street . ', ' . $shop->barangay . ', ' . $shop->city . ', ' . $shop->province) }}"
                                                target="_blank"
                                                style="text-decoration: none; color: inherit;">
                                                {{ $shop->shop_street }}, {{ $shop->barangay }}, {{ $shop->city }}, {{ $shop->province }}
                                            </a>
                                        </p>
                                        <p class="shop-contact mb-0 text-white text-start">
                                            <i class="text-black fa-solid fa-phone"></i>
                                            @if($shop->merchant->contact_number)
                                                @php
                                                    $contactNumber = $shop->merchant->contact_number;
                                                    if (strpos($contactNumber, '+63') === 0) {
                                                        $formattedNumber = $contactNumber;
                                                    } elseif (strpos($contactNumber, '63') === 0) {
                                                        $formattedNumber = '+' . $contactNumber;
                                                    } elseif (strpos($contactNumber, '0') === 0) {
                                                        $formattedNumber = '+63' . substr($contactNumber, 1);
                                                    } else {
                                                        $formattedNumber = $contactNumber;
                                                    }
                                                @endphp
                                                <a href="tel:{{ $formattedNumber }}" style="text-decoration: none; color: inherit;">
                                                    {{ $formattedNumber }}
                                                </a>
                                            @else
                                                No contact number set
                                            @endif
                                        </p>
                                    </div>

                                    <!-- View Store Button -->
                                    <div class="view-button position-absolute" style="bottom: 20px; right:20px;">
                                        <a href="{{ route('merchant.viewstore', ['shopId' => $shop->shop_id]) }}" class="btn btn-custom d-none d-md-inline">
                                            View Store
                                        </a>

                                        <!-- Icon Button (Shown on smaller screens) -->
                                        <a href="{{ route('merchant.viewstore', ['shopId' => $shop->shop_id]) }}}" class="btn btn-custom d-inline d-md-none" style="font-size: 12px;">
                                            View Store
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Featured Products -->
                <div class="col-12 col-md-5" style="padding:0;">
                    <div class="featured-product">
                        <h3 class="featured-product-title">FEATURED PRODUCT</h3>
                        <hr class="line">
                    </div>
                    <div class="card featured-product-card">
                        <div class="card-body" style="padding-top:0; width:auto;">
                            @foreach($featuredProducts as $product)
                                <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none " style="text-decoration: none; color: inherit;">
                                    <div class="product-card-hover">
                                        <div class="mb-1 card" style="height: 72px; width:auto;">
                                            <div class="card-body" style="padding: 10px;">
                                                <div class="row">
                                                    <!-- Product Image -->
                                                    <div class="col-2 col-md-2">
                                                        @if($product->images->first())
                                                            <div class="bg-secondary" style="width: 54px; height: 54px; background-image: url('{{ asset('storage/' . $product->images->first()->product_img_path1) }}'); background-size: cover;"></div>
                                                        @else
                                                            <div class="bg-secondary" style="width: 54px; height: 54px;"></div>
                                                        @endif
                                                    </div>
                                                    <!-- Product Details -->
                                                    <div class="col-8 col-md-8 text-start">
                                                        <h6 class="card-title featured-title">{{ $product->product_name }}</h6>
                                                        <p class="card-reviews">
                                                            @if($product->averageRating > 0)
                                                                {{-- Display stars based on the rating --}}
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= floor($product->averageRating))
                                                                        <i class="fa-solid fa-star" style="color: #FFD700;"></i> {{-- Full star in gold --}}
                                                                    @elseif ($i - $product->averageRating < 1)
                                                                        <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i> {{-- Half star in light gold --}}
                                                                    @else
                                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i> {{-- Empty star in gray --}}
                                                                    @endif
                                                                @endfor
                                                                <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                                            @else
                                                                {{-- Display empty stars if no reviews --}}
                                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                            @endif
                                                        </p>
                                                        <p class="mb-0 price" style="font-size: 13px">₱{{ $product->price }}</p>
                                                    </div>
                                                </div>
                                                <!-- Add to Cart & Wishlist Buttons -->
                                                <div class="view-button position-absolute d-flex" style="bottom: 10px; right: 20px; gap: 3px;">
                                                    <!-- Text Button (Shown on larger screens) -->
                                                    <a class="btn btn-custom add-to-cart d-none d-md-inline" data-product-id="{{ $product->product_id }}" style="font-size: 12px;">
                                                        Add to Cart
                                                    </a>

                                                    <!-- Icon Button (Shown on smaller screens) -->
                                                    <a class="btn btn-custom add-to-cart d-inline d-md-none" data-product-id="{{ $product->product_id }}" style="font-size: 12px;">
                                                        <i class="fa-solid fa-cart-plus"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="btn {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                                        data-product-id="{{ $product->product_id }}"
                                                        style="width: 2rem; font-size:0.8rem; font-size:0.9rem; padding: 0.2rem 0;"
                                                        title="{{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                                        <i class="fas fa-heart {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if(!empty($recentlyAddedProducts) && $recentlyAddedProducts->isNotEmpty())
    <!-- Recently Added Product -->
    <div class="container recently-added-container">
        <div class="mt-4 row">
            <div class="recently-product-header">
                <h3>RECENTLY ADDED</h3>
                <hr class="line">
                <div class="button-group">
                    <button id="prevBtnRecentlyAdded" class="mb-2 btn btn-outline-dark btn-sm">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button id="nextBtnRecentlyAdded" class="mb-2 btn btn-outline-dark btn-sm">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="mt-3 col-md-12">
                <div id="productCarouselRecentlyAdded" class="product-carousel-recently">
                    @foreach($recentlyAddedProducts as $product)
                        <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none product-link">
                            <div class="p-2 product-item product-card-hover">
                                <div class="card">
                                    <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                                        class="card-img-top"
                                        loading="lazy"
                                        alt="{{ $product->product_name }}">
                                    <div class="text-center card-body">
                                        <h6 class="card-title product-title">{{ $product->product_name }}</h6>
                                        <p class="mb-1 card-price">₱{{ number_format($product->price, 2) }}</p>
                                        <p class="card-reviews">
                                            @if($product->averageRating > 0)
                                                {{-- Display stars based on the rating --}}
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($product->averageRating))
                                                        <i class="fa-solid fa-star" style="color: #FFD700;"></i> {{-- Full star in gold --}}
                                                    @elseif ($i - $product->averageRating < 1)
                                                        <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i> {{-- Half star in light gold --}}
                                                    @else
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i> {{-- Empty star in gray --}}
                                                    @endif
                                                @endfor
                                                <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                            @else
                                                {{-- Display empty stars if no reviews --}}
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            @endif
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <a href="#" class="btn btn-custom add-to-cart" data-product-id="{{ $product->product_id }}">Add to Cart</a>
                                            <a href="#"
                                                class="btn {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                                data-product-id="{{ $product->product_id }}"
                                                style="width: 2rem; padding: 0.2rem 0;"
                                                title="{{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                                    <i class="fas fa-heart {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<!-- End Recently Added Product-->
<!-- All Product Carousel -->
<div class="container">
    <div class="mt-3 row">
        <div class="popular-product-header">
            <h3>ALL PRODUCTS</h3>
            <hr class="line">
            <div class="button-group">
                <button id="prevBtnProductAll" class="mb-2 btn btn-outline-dark btn-sm">
                    <i class="fa-solid fa-chevron-left" style="font-size: 14px;"></i>
                </button>
                <button id="nextBtnProductAll" class="mb-2 btn btn-outline-dark btn-sm">
                    <i class="fa-solid fa-chevron-right" style="font-size: 14px;"></i>
                </button>
            </div>
        </div>
        <div class="mt-3 col-md-12">
            <div id="productCarouselAll" class="product-carousel-product">
                <!-- Loop through products -->
                @foreach($allProducts as $product)
                    <a href="{{ route('product.view', $product->product_id) }}" class="product-link">
                        <div class="product-item product-card-hover">
                            <div class="card">
                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                                     class="card-img-top"
                                     loading="lazy"
                                     alt="{{ $product->product_name }}">
                                <div class="card-body">
                                    <h6 class="card-title product-title">{{ $product->product_name }}</h6>
                                    <p class="mb-1 card-price">₱{{ number_format($product->price, 2) }}</p>
                                    <p class="card-reviews">
                                        {{-- Display stars based on the rating if available --}}
                                        @if($product->averageRating > 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->averageRating))
                                                    <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                                @elseif ($i - $product->averageRating < 1)
                                                    <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i>
                                                @else
                                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                @endif
                                            @endfor
                                            <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                        @else
                                            {{-- Display empty stars if no reviews --}}
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        @endif
                                    </p>
                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        <a href="#" class="btn btn-custom add-to-cart" data-product-id="{{ $product->product_id }}">Add to Cart</a>

                                        <a href="#"
                                            class="btn {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                            data-product-id="{{ $product->product_id }}"
                                            style="width: 2rem; padding: 0.2rem 0;"
                                            title="{{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                                <i class="fas fa-heart {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- Merchant Modal -->
@if(Auth::check() && Auth::user()->isMerchant())
    @php
        $shop = Auth::user()->shop; // Get the shop related to the authenticated user
    @endphp
    @if($shop)
        @if($shop->registration_step == 'Completed')
            <!-- Show completed merchant modal -->
            <div id="custom-merchant-modal" class="custom-modal">
                <div class="custom-modal-inner">
                    <div class="custom-modal-content" style="width:450px;">
                        <span class="custom-close-btn"><i class="fa-solid fa-xmark"></i></span>
                        <h2>Welcome Back, Merchant!</h2>
                        <p>Thank you for being a part of our platform. Don’t forget to update your shop and enjoy the exclusive deals available for our partners!</p>
                        <a href="#" class="custom-register-btn">Go to Merchant Dashboard</a>
                        <div class="custom-ads-section">
                            <div class="custom-ad">
                                <img src="/images/ad1.jpg" loading="lazy" alt="Ad 1">
                                <p>Special discounts for bulk purchases!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Show application incomplete modal -->
            <div id="custom-application-modal" class="custom-modal">
                <div class="custom-modal-inner">
                    <div class="custom-modal-content" style="width:450px;">
                        <h2>Complete Your Merchant Application</h2>
                        <p>Great! You are ready for the next step in your application. Please complete the remaining steps to activate your shop.</p>
                        <a href="{{ route('merchant.secondstep') }}" class="custom-register-btn">Complete Your Application</a>
                        <div class="custom-ads-section">
                            <div class="custom-ad">
                                <img src="/images/ad1.jpg" loading="lazy" alt="Ad 1">
                                <p>Special discounts for bulk purchases!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <p>No shop found for the merchant.</p>
    @endif
@endif
@include('Components.add-to-cart')
@include('Components.supporticon')
@include('Components.footer')

@include('Components.favorite-success-modal') 
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the modal elements by ID
        var merchantModal = document.getElementById('custom-merchant-modal');
        var applicationModal = document.getElementById('custom-application-modal');
        var customerModal = document.getElementById('custom-customer-modal');

        console.log(merchantModal); // Check if the modal element is found

        // Show the merchant modal
        if (merchantModal) {
            merchantModal.style.display = 'flex'; // Set display to flex
        }

        // Show the application modal
        if (applicationModal) {
            applicationModal.style.display = 'flex'; // Set display to flex
        }

        // Show the customer modal
        if (customerModal) {
            customerModal.style.display = 'flex'; // Set display to flex
        }

        // Add event listeners to the close buttons
        document.querySelectorAll('.custom-close-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var modal = btn.closest('.custom-modal');
                if (modal) {
                    modal.style.display = 'none'; // Hide the modal
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function initializeCarousel(carouselId, prevBtnId, nextBtnId, scrollAmount) {
            const carousel = document.getElementById(carouselId);
            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);

            // Add event listeners for scrolling
            prevBtn.addEventListener('click', function () {
                carousel.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function () {
                carousel.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        }

        // Initialize each carousel independently
        initializeCarousel('productCarouselPopular', 'prevBtnPopular', 'nextBtnPopular', 600);
        initializeCarousel('productCarouselAll', 'prevBtnProductAll', 'nextBtnProductAll', 600);
        initializeCarousel('productCarouselRecentlyAdded', 'prevBtnRecentlyAdded', 'nextBtnRecentlyAdded', 600);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to change the background image
        function changeBannerImage(imageUrl) {
            const bannerSection = document.getElementById('dynamicBanner');
            if (bannerSection) {
                bannerSection.style.backgroundImage = `url('${imageUrl}')`;
            }
        }

        // List of dynamic images to cycle through
        const bannerImages = [
            '{{ asset('images/assets/ads/2.png') }}',
            '{{ asset('images/assets/ads/3.jpg') }}',
            '{{ asset('images/assets/ads/5.jpg') }}'
        ];

        // Initially set the first image before cycling
        function initializeBanner(images) {
            changeBannerImage(images[0]);  // Set the initial background
            updateIndicator(0);  // Highlight the first dot as active
        }

        // Function to update the indicator
        function updateIndicator(index) {
            const dots = document.querySelectorAll('.carousel-dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);  // Add or remove the active class
            });
        }

        // Function to cycle through the dynamic images with delay
        function cycleImages(images, delay) {
            let currentIndex = 0;

            // Change image and update indicator
            setInterval(() => {
                currentIndex = (currentIndex + 1) % images.length;
                changeBannerImage(images[currentIndex]);
                updateIndicator(currentIndex);
            }, delay);
        }

        // Add event listeners to the dots for manual control
        function setupIndicators() {
            const indicatorContainer = document.getElementById('carouselIndicators');
            bannerImages.forEach((image, index) => {
                const dot = document.createElement('div');
                dot.classList.add('carousel-dot');
                if (index === 0) dot.classList.add('active');  // First dot is active by default
                dot.addEventListener('click', function() {
                    changeBannerImage(bannerImages[index]);
                    updateIndicator(index);
                });
                indicatorContainer.appendChild(dot);
            });
        }

        // Initialize indicators and start cycling images
        setupIndicators();
        initializeBanner(bannerImages);  // Set the initial image before cycling
        cycleImages(bannerImages, 7000);  // Cycle images every 7 seconds
    });
</script>

@endsection

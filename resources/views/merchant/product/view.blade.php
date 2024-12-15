@extends('Components.layout')

@section('styles')
    <style>
        body, html {
            overflow: auto;
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .card-placeholder {
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
        }

        .main-card {
            width: 100%;
            padding: 10px;
            border-radius: 0;
            background-color: #f8f9fa;
        }

        .main-image {
            width: 100%;
            height: 210px;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 7px;
            border: 1px solid #ddd;
        }

        .small-image {
            width: 80px;
            height: 80px;
            margin-right: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
        }

        .icon {
            font-size: 40px;
            color: black;
        }

        .d-flex {
            display: flex;
        }

        .product-info {
            background-color: #e0e0e0;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .breadcrumb a {
            text-decoration: none;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb .breadcrumb-item.active {
            font-size: 14px;
            font-weight: bold;
            color: #228b22;
        }

        /* Carousel Main Image Styling */
        .carousel-inner .main-image {
            height: 380px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
        }

        /* Carousel Controls */
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
        }

        /* Product Info Card Styling */
        .product-info-card {
            background-color: #fafafa;
            padding: 20px;
            border-radius: 10px;
            height: 500px;
            border: 1px solid #ccc;
            position: relative;
        }

        /* Pricing Section */
        .product-price {
            padding: 10px;
            background-color: rgb(226, 220, 220);
            border-radius: 5px;
            color: #d9534f;
            font-weight: bold;
        }

        /* Variation Button Styling */
        .variation-button {
            margin-right: 5px;
        }

        /* Quantity Input Group */
        .quantity-input-group {
            width: 150px;
            text-align: center;
        }

        /* Add to Cart and Buy Now Button Group */
        .button-group {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        /* Shop Info Styling */
        .shop-info {
            display: flex;
            align-items: center;
            height: 100px;
            background-color: #fafafa;
            padding: 0.5rem;
            margin-top: 0.75rem;
        }
        .shop-info .card {
            border: 1px solid #ddd;          /* Light border for the card */
            border-radius: 8px;               /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        .shop-info .shop-img {
            width: 60px;                      /* Fixed size for the circular image */
            height: 60px;
            background-size: cover;
            background-position: center;
            border-radius: 50%;               /* Makes it a perfect circle */
        }

        .shop-info .placeholder-image {
            background-color: #28a745;        /* Green color for the placeholder */
            width: 60px;
            height: 60px;
            display: inline-block;
            border-radius: 50%;               /* Circle for placeholder */
        }

        .shop-info .card-title {
            font-weight: bold;
            font-size: 1.2rem;                /* Slightly larger font for the store name */
            margin: 0;
        }

        .shop-info .btn-outline-success {
            padding: 5px 15px;
            font-size: 0.9rem;                /* Adjust button size for consistency */
            border-color: #28a745;            /* Green border to match theme */
            color: #28a745;                   /* Green text */
        }

        .shop-info .btn-outline-success:hover {
            background-color: #28a745;        /* Green background on hover */
            color: white;                     /* White text on hover */
            border-color: #28a745;
        }

        /* Related Products and Description Sections */
        .related-products, .product-description, .product-reviews {
            background-color: #fafafa;
        }
        #imageCarousel:hover .thumbnail-strip {
            opacity: 0.5 !important;
        }

        .product-carousel-container {
        }

        /* Carousel Images */
        .carousel-image {
            height: 500px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
        }

        /* Thumbnail Strip */
        .thumbnail-strip {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #333;
            opacity: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
            transition: opacity 0.1s ease;
        }

        /* Thumbnail Item */
        .thumbnail-item {
            width: 60px;
            height: 60px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
            border: 2px solid #1F6421;
            box-sizing: border-box;
        }

        /* No Images Message */
        .no-images-message {
            font-size: 1.2em;
            color: #888;
        }
        .view-container{
            padding: 0 20px 20px 20px;
            height:500px;
        }

        .sticky-footer{
            display:none;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem; /* Equivalent to mt-4 */
        }
        .product-info-card{
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .line{
            padding-bottom: 15px; border-bottom: 1px solid #ddd;"
        }
        .button-group {
            display: flex; /* Equivalent to d-flex */
            justify-content: space-between; /* Equivalent to justify-content-between */
            align-items: center; /* Equivalent to align-items-center */
            margin-top: 1.5rem; /* Equivalent to mt-4 (1.5rem is the standard spacing for mt-4 in Bootstrap) */
        }
        .mobile-description{
            display:none;
        }
        .variation-button {
            padding: 5px 15px;
            border-radius: 10px;
        }

        .variation-button.btn-outline-secondary:checked + label,
        .variation-button.btn-outline-secondary:hover {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        /* Product Reviews Section Styling */
        .review-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
        }

        .user-img {
            width: 35px;
            height: 35px;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
        }

        .star-rating i {
            font-size: 0.8rem;
        }

        .review-images .review-image {
            width: 35px;
            height: 35px;
            background-size: cover;
            background-position: center;
            border-radius: 4px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .review-card .small {
            font-size: 0.875rem; /* Smaller font size for compact view */
        }

        .review-card p {
            margin-bottom: 0.5rem;
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
        .expand-button {
            color: #28a745;
        }

        .expand-button:hover {
            color: #1f7a31; /* Darker shade for hover effect (optional) */
        }
        .quantity-input-group input {
            width: 50px;
            text-align: center;
            font-size: 1rem;
        }

        .quantity-input-group button {
            padding: 0.4rem 0.75rem;
            font-size: 1rem;
        }

        @media only screen and (min-width: 360px) and (max-width: 425px) {
            .top-crumb{
                display:none;
            }
            .product-carousel-container{
                padding:0;
            }
            .view-container{
                padding: 0;
                height:auto;
            }
            .container{
                padding:0;
                margin: 0;
            }
            .carousel-image {
                height: 300px;
            }
            .product-info-card{
                margin-top:20px;
            }
            .hidden-icon{
                position: relative;
            }
            .return-hidden-icon {
                display:block;
                position: absolute;
                left: 15px; /* Aligns the icon to the far left */
                top: 50%; /* Centers vertically within the container */
                transform: translateY(-50%); /* Adjust for vertical centering */
            }
            .sticky-footer {
                display:block;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #fff; /* Background color of the footer */
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1); /* Optional shadow */
                z-index: 1000;
                padding: 10px 0;
                border-top: 2px solid #228b22;
            }

            .footer-content {
                align-items: center;
                max-width: 1200px; /* Adjust based on layout needs */
                margin: 0 auto;
                padding: 0 20px;
            }
            /* Button Styles */

            .add-to-cart-btn, .buy-now-btn{
                display: none!important;
            }
            .shipping{
                display:none;
            }
            .product-info-card{
                margin:0;
            }
            .line{
                padding:0;
                padding-bottom: none;
                border-bottom: none;
            }
            .star-rating, .rating-count{
                font-size:0.6rem;
            }
            .button-group{
                display: none;
            }
            .product-info-card{
                height:260px;

            }
            .fa-bars, .text-color1, .text-color2 {
                display:none;
            }
            .related-product{
                display:none;
            }.shop-info {
                margin-top: 0;
            }
            .shop-info .shop-img {
                width: 40px;
                height: 40px;
            }
            .product-info-card h3 {
                font-size: 1.2rem;
            }

            .product-info-card .star-rating {
                font-size: 0.9rem;
            }

            .product-info-card h4 {
                font-size: 1.1rem;
            }

            .product-info-card h5 {
                font-size: 0.9rem;
            }

            .product-info-card .rating-count {
                font-size: 0.8rem;
            }
            .variation-button {
                font-size: 0.7rem;
                padding: 8px 12px;
                border-radius: 15px; /* Adjusted radius for a smaller, rounder appearance */
            }
            .product-quantity h5 {
                font-size: 1rem; /* Reduce font size of the title */
            }

            .quantity-input-group {
                width: 100%; /* Allow the input group to take full width on small screens */
            }

            .quantity-input-group input {
                width: 30px; /* Reduce the input width for mobile */
                padding: 0.25rem; /* Less padding for a compact input field */
                font-size: 0.9rem; /* Slightly smaller font size */
            }

            .quantity-input-group button {
                padding: 0.25rem 0.6rem; /* Reduce padding for smaller buttons */
                font-size: 0.9rem; /* Slightly smaller font size */
            }

        }
    </style>
@endsection

@section('content')
<div class="container">
    <nav class="top-crumb" aria-label="breadcrumb" style="margin-left:20px">
        <ol class="breadcrumb mb-2 mt-3">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.product', ['category_name' => $product->category->category_name]) }}">{{ $product->category->category_name }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $product->subcategory->category_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
        </ol>
    </nav>
    <div class="col-md-12 view-container">
        <div class="row">
            <div class="col-md-6 text-center product-carousel-container">
                <div id="imageCarousel" class="carousel slide main-carousel" data-bs-ride="carousel">

                    @if (!empty($product->images) && $product->images->isNotEmpty())
                        <div class="carousel-inner">
                            @php $isFirstImage = true; @endphp

                            @foreach ($product->images as $index => $image)
                                <!-- Image 1 -->
                                @if (!empty($image->product_img_path1))
                                    <div class="carousel-item @if ($isFirstImage) active @endif product-carousel-item">
                                        <div class="carousel-image"
                                             style="background-image: url('{{ asset('storage/' . $image->product_img_path1) }}');">
                                        </div>
                                    </div>
                                    @php $isFirstImage = false; @endphp
                                @endif

                                <!-- Image 2 -->
                                @if (!empty($image->product_img_path2))
                                    <div class="carousel-item @if ($isFirstImage) active @endif product-carousel-item">
                                        <div class="carousel-image"
                                             style="background-image: url('{{ asset('storage/' . $image->product_img_path2) }}');">
                                        </div>
                                    </div>
                                    @php $isFirstImage = false; @endphp
                                @endif

                                <!-- Image 3 -->
                                @if (!empty($image->product_img_path3))
                                    <div class="carousel-item @if ($isFirstImage) active @endif product-carousel-item">
                                        <div class="carousel-image"
                                             style="background-image: url('{{ asset('storage/' . $image->product_img_path3) }}');">
                                        </div>
                                    </div>
                                    @php $isFirstImage = false; @endphp
                                @endif

                                <!-- Image 4 -->
                                @if (!empty($image->product_img_path4))
                                    <div class="carousel-item @if ($isFirstImage) active @endif product-carousel-item">
                                        <div class="carousel-image"
                                             style="background-image: url('{{ asset('storage/' . $image->product_img_path4) }}');">
                                        </div>
                                    </div>
                                    @php $isFirstImage = false; @endphp
                                @endif

                                <!-- Image 5 -->
                                @if (!empty($image->product_img_path5))
                                    <div class="carousel-item @if ($isFirstImage) active @endif product-carousel-item">
                                        <div class="carousel-image"
                                             style="background-image: url('{{ asset('storage/' . $image->product_img_path5) }}');">
                                        </div>
                                    </div>
                                    @php $isFirstImage = false; @endphp
                                @endif
                            @endforeach
                        </div>

                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                        <!-- Thumbnail Strip -->
                        <div class="thumbnail-strip">
                            @php $thumbIndex = 0; @endphp

                            @foreach ($product->images as $index => $image)
                                <!-- Thumbnail for Image 1 -->
                                @if (!empty($image->product_img_path1))
                                    <div class="thumbnail-item"
                                         style="background-image: url('{{ asset('storage/' . $image->product_img_path1) }}');"
                                         data-bs-target="#imageCarousel" data-bs-slide-to="{{ $thumbIndex }}">
                                    </div>
                                    @php $thumbIndex++; @endphp
                                @endif

                                <!-- Thumbnail for Image 2 -->
                                @if (!empty($image->product_img_path2))
                                    <div class="thumbnail-item"
                                         style="background-image: url('{{ asset('storage/' . $image->product_img_path2) }}');"
                                         data-bs-target="#imageCarousel" data-bs-slide-to="{{ $thumbIndex }}">
                                    </div>
                                    @php $thumbIndex++; @endphp
                                @endif

                                <!-- Thumbnail for Image 3 -->
                                @if (!empty($image->product_img_path3))
                                    <div class="thumbnail-item"
                                         style="background-image: url('{{ asset('storage/' . $image->product_img_path3) }}');"
                                         data-bs-target="#imageCarousel" data-bs-slide-to="{{ $thumbIndex }}">
                                    </div>
                                    @php $thumbIndex++; @endphp
                                @endif

                                <!-- Thumbnail for Image 4 -->
                                @if (!empty($image->product_img_path4))
                                    <div class="thumbnail-item"
                                         style="background-image: url('{{ asset('storage/' . $image->product_img_path4) }}');"
                                         data-bs-target="#imageCarousel" data-bs-slide-to="{{ $thumbIndex }}">
                                    </div>
                                    @php $thumbIndex++; @endphp
                                @endif

                                <!-- Thumbnail for Image 5 -->
                                @if (!empty($image->product_img_path5))
                                    <div class="thumbnail-item"
                                         style="background-image: url('{{ asset('storage/' . $image->product_img_path5) }}');"
                                         data-bs-target="#imageCarousel" data-bs-slide-to="{{ $thumbIndex }}">
                                    </div>
                                    @php $thumbIndex++; @endphp
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="no-images-message">No images available for this product.</p>
                    @endif
                </div>
            </div>
            <!-- Product Info -->
            <div class="col-md-6 product-info-card p-4">
                <!-- Product Name -->
                <div class="line">
                    <h3 class="font-weight-bold mb-0">{{ $product->product_name }}</h3>
                </div>
                <!-- Product Ratings and Sold Count -->
                <div class="product-overall-rating d-flex align-items-center mt-2" style="gap: 5px;">
                    <!-- Star Rating Display for Average Rating -->
                    <span class="star-rating" style="color: #FFC107; font-size: 1rem;">
                        @if ($averageRating)
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <!-- Full Star -->
                                    <i class="fa-solid fa-star"></i>
                                @elseif ($i - $averageRating < 1)
                                    <!-- Half Star for .5 -->
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <!-- Empty Star -->
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        @else
                            <span class="no-rating">No rating available</span>
                        @endif
                    </span>

                    <!-- Numeric Average Rating Count -->
                    @if ($averageRating)
                        <span class="rating-count" style="font-weight: bold;">({{ $averageRating }} / 5)</span>
                    @endif
                </div>

                <!-- Product Price -->
                <div class="product-price my-3">
                    <h4 style="color: #e60023; font-weight: bold;">₱{{ number_format($product->price, 2) }}</h4>
                </div>

                <!-- Product Variations -->
                <div class="product-options d-flex align-items-start justify-content-between">
                    <!-- Product Variations -->
                    @if($product->variations->count() > 1)
                        <div class="product-variations">
                            <h5 class="font-weight-bold">Select Variation</h5>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($product->variations as $variation)
                                    <input type="radio"
                                        class="btn-check"
                                        name="product_variation"
                                        id="variation{{ $variation->product_variation_id }}"
                                        value="{{ $variation->product_variation_id }}"
                                        autocomplete="off">
                                    <label class="btn btn-outline-secondary variation-button"
                                        for="variation{{ $variation->product_variation_id }}">
                                        {{ $variation->variation_name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @elseif($product->variations->count() === 1)
                        {{-- Render the single variation input but hide it from the user --}}
                        @php
                            $singleVariation = $product->variations->first();
                        @endphp
                        <input type="radio"
                            class="btn-check"
                            name="product_variation"
                            id="variation{{ $singleVariation->product_variation_id }}"
                            value="{{ $singleVariation->product_variation_id }}"
                            checked hidden>
                    @endif

                    <!-- Quantity Selector -->
                    <div class="product-quantity">
                        <h5 class="font-weight-bold">Quantity</h5>
                        <div class="d-flex align-items-center mt-2">
                            <div class="input-group quantity-input-group" style="width: fit-content;">
                                <button class="btn btn-outline-secondary" type="button" id="decrementBtn">-</button>
                                <input type="text" id="quantityInput" value="1" style="width: 50px; text-align: center;" class="form-control">
                                <button class="btn btn-outline-secondary" type="button" id="incrementBtn">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="my-4 shipping">
                    <h5 class="font-weight-bold d-flex align-items-center">
                        Shipping
                        <i class="fa fa-truck-fast ml-2" style="margin-left: 10px; color: #1F6421;"></i>
                    </h5>
                    <p class="text-muted">Standard Shipping: 3-5 business days</p>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <a href="#" 
                        class="btn btn-outline-custom add-to-cart-btn" 
                        data-product-id="{{ $product->product_id }}">
                        <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
                    </a>
                    <button class="btn btn-custom" onclick="buyNow()">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-description p-0">
        <div class="row w-100">
            <!-- Column for Product Description -->
            <div class="col-12">
                <header class="header-section">
                    <h5>Product Description</h5>
                </header>
                <article class="body-section">
                    <p class="product-description">{{ $product->description }}</p>
                </article>
            </div>
        </div>
    </div>

    <!-- Shop Info Section -->
    <section class="col-md-12 shop-info ">
        <div class="card rounded p-3 w-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if($shop && $shop->shop_img)
                        <div class="shop-img rounded-circle me-3"
                            style="background-image: url('{{ asset('storage/' . $shop->shop_img) }}');">
                        </div>
                    @else
                        <div class="shop-img rounded-circle me-3 placeholder-image"></div>
                    @endif
                    <h5 class="card-title mb-0 fw-bold">{{ $shop->shop_name ?? 'Store Name' }}</h5>
                </div>
                <a href="#" class="btn btn-outline-custom">View Store</a>
            </div>
        </div>
    </section>


    <!-- Related Description Section -->
    <section class="related-description mt-3 px-2">
        <div class="row gx-3">

            <!-- Related Products Section -->
            <div class="col-md-4 related-product">
                <article class="card h-100 related-products p-1">
                    <header class="card-header">
                        <h5>Related Products</h5>
                    </header>
                    <div class="card-body">
                        <!-- Related products content -->
                    </div>
                </article>
            </div>

            <!-- Main Product Description and Additional Related Products -->
            <div class="col-md-8 mb-5">

                <!-- Product Description Section -->
                <article class="card mb-3">
                    <header class="card-header">
                        <h5>Product Description</h5>
                    </header>
                    <div class="card-body">
                        <p class="product-description">{{ $product->description }}</p>
                    </div>
                </article>

                <!-- Product Review Section -->
                <article class="card">
                    <header class="card-header">
                        <h5>Product Reviews ({{ $reviewCount }})</h5>
                    </header>
                    <div class="card-body">
                        @if ($reviews->isEmpty())
                            <p class="text-muted">No reviews available for this product.</p>
                        @else
                            <!-- Display the first 2 reviews -->
                            @foreach ($reviews->take(2) as $review)
                                <div class="review-card card p-2 mb-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center">
                                            <div class="user-img rounded-circle me-2"
                                                style="background-image: url('{{ $review->customerImage && $review->customerImage->img_path ? asset('storage/' . $review->customerImage->img_path) : asset('images/assets/default_profile.png') }}');
                                                        width: 35px; height: 35px; background-size: cover;">
                                            </div>
                                            <div>
                                                <span class="fw-bold">{{ $review->username }}</span>
                                                <div class="star-rating text-warning small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fa-solid fa-star"></i>
                                                        @else
                                                            <i class="fa-regular fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="text-muted small">{{ $review->rating }}/5</span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-muted small">{{ \Carbon\Carbon::parse($review->review_date)->format('m/d/Y') }}</span>
                                    </div>
                                    <p class="mb-1 small">{{ $review->review_text }}</p>
                                    <div class="review-images mt-1">
                                        @if ($review->image_1 || $review->image_2 || $review->image_3 || $review->image_4 || $review->image_5)
                                            <p class="fw-bold mb-1 small">With Media:</p>
                                            <div class="d-flex gap-1">
                                                @foreach (range(1, 5) as $i)
                                                    @php $imagePath = 'image_' . $i; @endphp
                                                    @if (!empty($review->$imagePath))
                                                        <div class="review-image"
                                                             style="background-image: url('{{ asset('storage/' . $review->$imagePath) }}');"
                                                             data-bs-toggle="modal"
                                                             data-bs-target="#imageModal"
                                                             onclick="showImageInModal('{{ asset('storage/' . $review->$imagePath) }}')">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Hidden section for additional reviews -->
                            <div id="hidden-reviews" style="display: none;">
                                @foreach ($reviews->slice(2) as $review)
                                    <div class="review-card card p-2 mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="d-flex align-items-center">
                                                <div class="user-img rounded-circle me-2"
                                                    style="background-image: url('{{ $review->customerImage && $review->customerImage->img_path ? asset('storage/' . $review->customerImage->img_path) : asset('images/assets/default_profile.png') }}');
                                                            width: 35px; height: 35px; background-size: cover;">
                                                </div>
                                                <div>
                                                    <span class="fw-bold">{{ $review->username }}</span>
                                                    <div class="star-rating text-warning small">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <i class="fa-solid fa-star"></i>
                                                            @else
                                                                <i class="fa-regular fa-star"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="text-muted small">{{ $review->rating }}/5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-muted small">{{ \Carbon\Carbon::parse($review->review_date)->format('m/d/Y') }}</span>
                                        </div>
                                        <p class="mb-1 small">{{ $review->review_text }}</p>
                                        <div class="review-images mt-1">
                                            @if ($review->image_1 || $review->image_2 || $review->image_3 || $review->image_4 || $review->image_5)
                                                <p class="fw-bold mb-1 small">With Media:</p>
                                                <div class="d-flex gap-1">
                                                    @foreach (range(1, 5) as $i)
                                                        @php $imagePath = 'image_' . $i; @endphp
                                                        @if (!empty($review->$imagePath))
                                                            <div class="review-image"
                                                                 style="background-image: url('{{ asset('storage/' . $review->$imagePath) }}');"
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#imageModal"
                                                                 onclick="showImageInModal('{{ asset('storage/' . $review->$imagePath) }}')">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Expand button -->
                            <div class="text-center">
                                <button id="expand-button" class="btn btn-link expand-button" onclick="showAllReviews()">Show All Reviews</button>
                            </div>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Review Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>


<footer class="sticky-footer text-end">
    <div class="footer-content ">
        <a class="btn btn-outline-custom add-to-cart-btn" data-product-id="{{ $product->product_id }}">
            <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
        </a>
        <button class="btn btn-custom" onclick="buyNow()">Buy Now</button>
    </div>
</footer>

@include('Components.status-modal')

@endsection



@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.getElementById('quantityInput');

        // Increment Button
        document.getElementById('incrementBtn').addEventListener('click', function () {
            // Parse the current value and increment
            let currentValue = parseInt(quantityInput.value);
            if (!isNaN(currentValue)) {
                quantityInput.value = currentValue + 1; // Increment quantity
            }
        });

        // Decrement Button
        document.getElementById('decrementBtn').addEventListener('click', function () {
            // Parse the current value and decrement
            let currentValue = parseInt(quantityInput.value);
            if (!isNaN(currentValue) && currentValue > 1) {
                quantityInput.value = currentValue - 1; // Decrement quantity
            }
        });
    });
    function showImageInModal(imageUrl) {
        // Set the modal image src attribute to the clicked image URL
        document.getElementById('modalImage').src = imageUrl;
    }
    function showAllReviews() {
        var hiddenReviews = document.getElementById('hidden-reviews');
        var expandButton = document.getElementById('expand-button');

        if (hiddenReviews.style.display === 'none') {
            hiddenReviews.style.display = 'block';
            expandButton.textContent = 'Show Less';
        } else {
            hiddenReviews.style.display = 'none';
            expandButton.textContent = 'Show All Reviews';
        }
    }
</script>
<script>
    function buyNow() {
        const selectedVariation = document.querySelector('input[name="product_variation"]:checked');
        if (!selectedVariation) {
            alert("Please select a product variation.");
            return;
        }

        const data = {
            product_id: '{{ $product->product_id }}',
            merchant_id: '{{ $product->merchant_id }}',
            product_variation_id: selectedVariation.value,
            quantity: document.getElementById('quantityInput').value,
            _token: '{{ csrf_token() }}'
        };

        fetch("{{ route('cart.buyNow') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server error');
            }
            return response.json();
        })
        .then(response => {
            if (response.success) {
                window.location.href = `/checkout?cart_id=${response.cart_id}`;
            } else {
                alert('Failed to proceed to checkout.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
<script>
    $(document).ready(function () {
        let selectedVariationId = null; // Initialize globally to hold the variation ID
        // Add to Cart Button Logic
        $('.add-to-cart-btn').click(function (e) {
            e.preventDefault();

            let productId = $(this).data('product-id');
            let quantity = parseInt($('#quantityInput').val()); // Get quantity input value

            // Check for variations
            if ($('input[name="product_variation"]').length > 0) {
                // Auto-select the first variation if there's only one
                if ($('input[name="product_variation"]').length === 1) {
                    let firstVariation = $('input[name="product_variation"]').first();
                    firstVariation.prop('checked', true);
                    selectedVariationId = firstVariation.val(); // Assign the auto-selected value
                }

                // Get the selected variation
                selectedVariationId = $('input[name="product_variation"]:checked').val();

                if (!selectedVariationId) {
                    alert('Please select a variation before adding to cart.');
                    return;
                }
            }

            // AJAX request to add product to cart
            $.ajax({
                url: '/cart/view/add',
                type: 'POST',
                data: {
                    product_id: productId,
                    product_variation_id: selectedVariationId,
                    quantity: quantity, // Include quantity
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    $('#statusModalIcon').removeClass('fa-xmark').addClass('fa-solid fa-circle-check check-icon');
                    $('#statusModalMessage').text('Product added to cart successfully!');
                    $('#statusModal').modal('show');
                    setTimeout(() => {
                        $('#statusModal').modal('hide');
                    }, 1000);
                    updateCartCount();
                },
                error: function (xhr) {
                    console.error('Failed to add product to cart.', xhr.responseJSON?.error || 'Unknown error');
                }
            });
        });

        // Function to update cart count dynamically
        function updateCartCount() {
            $.ajax({
                url: '{{ route("cart.count") }}',
                type: 'GET',
                success: function (response) {
                    $('#cart-count').text(response.cartItemCount);
                },
                error: function () {
                    console.error('Failed to update cart count.');
                }
            });
        }
    });
</script>


@endsection

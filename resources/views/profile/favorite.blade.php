@extends('Components.layout')

@section('styles')
<style>
    .breadcrumb a {
        text-decoration: none;
        font-size: 15px;
        color: #666;
    }

    .breadcrumb .breadcrumb-item.active {
        font-size: 15px;
        font-weight: bold;
        color: #228b22;
    }

    .fa-stack:hover .fa-certificate {
        color: #228b22;
    }

    .fa-certificate {
        color: rgba(34, 139, 34, 0.5);
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .fa-check {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.5em;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    /* Section Header Styling */

/* Carousel Container */
#productCarouselRecentlyAdded,
#productCarouselFavorites {
    display: flex;
    overflow-x: hidden;
    scroll-behavior: smooth;
    gap: 10px;
}

/* Product Item Styling */
.product-item {
    padding: 0.5rem;
    transition: transform 0.3s;
    cursor: pointer;
    position: relative;
}

.product-item:hover {
    transition: transform 0.3s;
}

.product-item:hover .view-product-text {
    display: block;
}

/* Card and Content Styling */
.card {
    width: 170px;
    border: none;
    position: relative;
    transition: transform 0.3s;
}

.card-img-top {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.card-body {
    text-align: center;
}

/* Product Title */
.card-title {
    font-size: 1rem;
    font-weight: bold;
    margin: 0;
}

/* Product Price */
.card-price {
    font-size: 14px;
    color: #555;
    font-weight: bold;
}

/* Reviews Section */
.card-reviews {
    font-size: 12px;
    color: #777;
    margin: 0;
}

/* Hover Effect */
.product-card-hover {
    border: 1px solid transparent;
    transition: border 0.3s;
    width: 180px;
}

.product-card-hover:hover {
    border-color: #28a745;
}

/* Add to Cart Button */
.btn-custom.add-to-cart {
    font-size: 0.875rem;
    margin-right: 4px;
}

/* Wishlist Button */
.favorite-button,
.remove-item-favorite {
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

/* Buttons for Carousel Navigation */
.button-group .btn {
    border-radius: 50%;
    padding: 5px;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 20px;
}

/* Dots for Carousel Indicators */
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
.add-to-cart{
    font-size: 0.875rem;
    margin-right: 4px;
}
    .favorite-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .favorite-product-header h3 {
        margin: 0;
    }

    .favorite-product-header .line {
        flex-grow: 1;
        height: 2px;
        border: none;             /* Remove default border */
        border-top: 2px solid #333; /* Set color and thickness */
        margin: 0 10px;
    }
    .favorite-product-header .button-group {
        display: flex;
        gap: 0px; /* Adjusts space between buttons */
    }
    /* Recently Added Products Header */
    .favorite-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }


</style>
@endsection

@section('content')
<div class="container mt-3">
    <nav aria-label="breadcrumb" style="margin-left:20px">
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">BiCollection</a></li>
            <li class="breadcrumb-item active" aria-current="page">Favorites</li>
        </ol>
    </nav>
    <div class="col-md-12 mt-3">
        @if($favorites->isNotEmpty())
            <div class="container favorite-added-container">
                <div class="row mt-4">
                    <div class="favorite-product-header">
                        <h3>My Favorites</h3>
                    </div>
                    <hr>
                    <div class="row" style="gap:14px;">
                        @foreach($favorites as $favoriteItem)
                            @php
                                $product = $favoriteItem->product;
                            @endphp
                            <div style="width:170px; padding:5px;"> 
                                <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none product-link">
                                    <div class="product-item p-1 product-card-hover">
                                        <div class="card">
                                            <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                                                class="card-img-top"
                                                alt="{{ $product->product_name }}">
                                            <div class="card-body text-ce nter">
                                                <h6 class="card-title">{{ $product->product_name }}</h6>
                                                <p class="card-price">₱{{ number_format($product->price, 2) }}</p>
                                                <p class="card-reviews">
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
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                                    @endif
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-2 m-0">
                                                    <a href="#" class="btn btn-custom add-to-cart w-100" data-product-id="{{ $product->product_id }}">Add to Cart</a>
                                                    <a href="#"
                                                        class="btn {{ in_array($product->product_id, $favoriteProductIds) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                                        data-product-id="{{ $product->product_id }}"
                                                        style="width:3rem; padding: 0.2rem 0;"
                                                        title="{{ in_array($product->product_id, $favoriteProductIds) ? 'Unfavorite' : 'Favorite' }}"
                                                        onclick="setTimeout(() => location.reload(), 2000);">
                                                        <i class="fas fa-heart {{ in_array($product->product_id, $favoriteProductIds) ? 'text-white' : '' }}"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        @else
            <div class="text-center mt-5">
                <h5>You haven’t added any products to your favorites yet.</h5>
                <p>Explore our products and add your favorites to this list!</p>
                <a href="{{ route('dashboard') }}" class="btn btn-custom mt-3">Browse Products</a>
            </div>
        @endif
    </div>



</div>
@include('Components.add-to-cart')
@include('Components.favorite-success-modal')
@endsection

@section('scripts')


@endsection

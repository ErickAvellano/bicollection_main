@extends('Components.layout')

@section('styles')
<style>
    /* Your existing styles... */
    body, html {
        overflow: auto;
        height: 100%;
        margin: 0; /* Ensure no default margin */
    }

    .close-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        cursor: pointer;
    }

    .profile-card {
        background-color: #6e6e6e;
        color: white;
        border-radius: 10px;
        padding: 20px;
        min-height: 200px; /* Optional: set a maximum width */
    }

    /* Profile image container */
    .profile-img-container {
        position: relative;
        display: inline-block;
        text-align: center;
    }

    /* Profile image styling */
    .profile-img-container img {
        width: 80px;
        height: 80px;
        border-radius: 50%; /* Circular profile image */
        background-color: #e0e0e0;
    }

    /* Badge verified */
    .badge-verified {
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #28a745; /* Green badge color */
        padding: 3px 8px;
        border-radius: 15px;
        color: white;
        font-size: 12px;
        text-align: center;
    }
    .badge-pending {
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #c9bb01; /* Green badge color */
        padding: 3px 8px;
        border-radius: 15px;
        color: white;
        font-size: 12px;
        text-align: center;
    }

    .badge-rejected {
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #e61616; /* Green badge color */
        padding: 3px 8px;
        border-radius: 15px;

        color: white;
        font-size: 12px;
        text-align: center;
    }
/*
    Adjusting card dimensions and layout */
    /* .product-card {
        width: 13rem; /* Adjust width */
        /* height: auto; */
        /* margin-bottom: 15px; Reduce margin between product cards */
    /* }  */

    /* Ensuring uniform image display */
    /* .product-card img {
        width: 100%;
        height: 180px; /* Uniform image height */
        /* object-fit: cover;
        border-radius: 5px 5px 0 0; */
    /* }  */

    /* Adjusting card body padding
    .product-card .card-body {
        padding: 8px; /* Reduce padding for compact look */
    /* } */
/*
    .product-card .card-title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    } */
/*
    .product-card .card-text {
        font-size: 13px;
    } */

    /* Reducing margin and padding in container */
    .featured-products-container,
    .allproduct-products-container {
        padding: 10px 0; /* Reduce top/bottom padding of container */
    }

    /* Adjusting row spacing */
    .featured-products-row,
    .allproduct-products-row, {
        margin-left: 0;
        margin-right: 0;
        gap: 5px; /* Add gap between product cards */
    }

    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }
    .btn-custom:hover,
    .btn-custom:focus {
        background-color: #fafafa;; /* Custom hover background color */
        border-color: #228b22;; /* Custom hover border color */
        color: black;
    }

/* Ensure product items take up equal width and respect the gap */

    /* On hover, change the border color
    .product-item {
        padding: 0.5rem;
        transition: transform 0.3s;
        cursor: pointer;
        position: relative;
        width: 12rem;
    } */

    .product-item:hover .view-product-text {
        display: block;
    }

    /* Card and Content Styling */

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
    .product-card-hover {
        transition: border-color 0.3s ease-in-out;
        border: 1px solid transparent;
        transition: border 0.3s;
    }

    .product-card-hover:hover {
        border-color: #28a745;
        border: 3px solid 228b22;
    }
    .product-links {
        text-decoration: none;
    }
    .product-item {
        width: 14.2857%; /* Adjust width for 7 items per row */
        box-sizing: border-box; /* Ensures padding doesn't affect the width */
    }

    .homeallproduct-products-row {
        display: flex;
        flex-wrap: wrap; /* Wrap to the next line after 7 items */
        gap: 0px; /* Optional: Add space between items */
    }
    .card-reviews {
        font-size: 12px;
        color: #777;
        margin: 0;
    }
    .card-title {
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

    /* Price styling */
    .card-price {
        font-size: 14px;
        color: #555;
        font-weight: bold;
    }

    /* Default state for the tabs */
    .nav-tabs .nav-link {
        color: black !important; /* Default color is black */
        background-color: transparent !important; /* Keep background transparent */
    }

    /* Active state for the clicked tab */
    .nav-tabs .nav-link.active {
        color: #228b22 !important; /* Active tab color */
        font-weight: bold; /* Optional: Make active tab bold */
        background-color: transparent !important; /* Optional: Keep background transparent */
    }

    /* Hover effect (optional) */
    .nav-tabs .nav-link:hover {
        color: #228b22 !important; /* Change color on hover */
    }


</style>
@endsection

@section('content')
<div class="container mt-2" style="background-color: white; width:100%; padding: 20px;">
    <div class="row">
        <!-- Profile Column -->
        <div class="col-md-5">
            <div class="card profile-card"
                style="background-image: url('{{ $shop->coverphotopath ? Storage::url($shop->coverphotopath) : asset('images/default-bg.jpg') }}');
                        background-size: cover;" id="profileCard">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-img-container">
                            <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : ('images/default_profile.png') }}"
                                alt="User Avatar"
                                class="rounded-circle mb-3"
                                width="80"
                                height="80">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title mt-3">{{ $shop->shop_name }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Column -->
        <div class="col-md-6 mt-4" style="margin-left:20px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="store-info">
                        <p><span>Products:</span> {{ count($products) }}</p>
                        <p><span>Visitors:</span>  {{ $visitorCount }} <i class="fa-solid fa-user"></i></p>
                        <p><span>Rating:</span> {{ $shop->rating }} ({{ $shop->ratings_count }} Rating)</p>
                        <p><span>Joined:</span> {{ $shop->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="store-info">
                        <p><span>Total Sales:</span> {{ $shop->total_sales }}</p>
                        <p><span>Pending Orders:</span> {{ $shop->pending_orders }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Home and Products -->
    <ul class="nav nav-tabs mt-5 mb-3" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="#" data-nav="home" data-shop-id="{{ $shop->shop_id }}" role="tab">Home</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-nav="allproduct" data-shop-id="{{ $shop->shop_id }}" role="tab">All Products</a>
        </li>
    </ul>
    <!-- Content Container for Dynamic Tab Content -->
    <div id="tabContentContainer">
        <!-- Initially load content here (e.g., featured products or all products) -->
    </div>
</div>

@include('Components.add-to-cart')
@include('Components.favorite-success-modal')

@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.nav-link');
            const contentContainer = document.getElementById('tabContentContainer');

            // Handle tab click
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();

                    const navValue = tab.getAttribute('data-nav').toLowerCase(); // 'home' or 'allproduct'
                    const shopId = tab.getAttribute('data-shop-id'); // Shop ID

                    // Update the URL without refreshing the page
                    history.pushState({}, '', `?nav=${navValue}`);

                    // Fetch content for the clicked tab
                    fetchContent(navValue, shopId);
                });
            });

            // Fetch the content based on the clicked tab
            function fetchContent(navValue, shopId) {
                fetch(`/merchant/partial/${navValue}/${shopId}`)
                    .then(response => response.text())
                    .then(data => {
                        contentContainer.innerHTML = data;
                    })
                    .catch(error => console.error('Error fetching content:', error));
            }

            // Initially load the content based on the current URL query parameter (e.g., ?nav=home)
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('nav') || 'home'; // Default to 'home'
            fetchContent(activeTab, "{{ $shop->shop_id }}");
        });
    </script>
    <script>
        $(document).ready(function () {
            // Add click event listener to each tab link
            $('.nav-link').on('click', function () {
                // Remove 'active' class from all links
                $('.nav-link').removeClass('active');
                
                // Add 'active' class to the clicked tab
                $(this).addClass('active');
            });
        });

    </script>
@endsection

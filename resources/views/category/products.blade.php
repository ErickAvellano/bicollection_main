@extends('Components.layout')

@section('styles')
<style>
    /* Card Hover Effects */
    .category-item {
        position: relative;
        width: 100%; /* Set width to fill the column */
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        text-align: center;
        cursor: pointer;
    }

    /* Hover Effect for Scale */
    .category-item:hover {
        transform: scale(1.05);
    }

    /* Category Image Styling */
    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.7); /* Darkens the image slightly */
    }

    /* Category Title Styling */
    .category-title {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        color: #ffffff;
        font-size: 1.2em;
        font-weight: bold;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.8);
        transition: color 0.3s ease;
    }

    /* Title Color Change on Hover */
    .category-item:hover .category-title {
        color: #ffd700; /* Gold color on hover */
    }

    /* Styling for Subcategory Pills */
    .category-pills .nav-link {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        margin-right: 10px;
        padding: 10px 15px;
        color: #333;
        border-radius: 20px;
        transition: all 0.3s ease-in-out;
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
        text-transform: capitalize;
    }

    .category-pills .nav-link:hover {
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .category-pills .nav-link.active {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        border: 1px solid #0056b3;
    }

    /* Adjusting Card Image */
    .card-img {
        border-radius: 8px;
    }

    /* Vertical alignment for right section */
    .align-vertical-center {
        display: flex;
        align-items: center;
        height: 100%;
    }

    .nav-tabs {
        border-bottom: 2px solid #ddd;
        background-color: #f8f9fa; /* Light background */
    }

    .nav-tabs .nav-link {
        color: #333;
        border: none;
        padding: 10px 15px;
        font-size: 1rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .nav-tabs .nav-link:hover {
        color: #28a745; /* Green on hover */
        border-bottom: 2px solid #28a745; /* Green underline */
    }

    .nav-tabs .nav-link.active {
        color: #28a745; /* Green for active tab */
        font-weight: bold;
        border-bottom: 2px solid #28a745; /* Green underline for active tab */
    }
    .product-item {
        padding: 0.5rem;
        transition: transform 0.3s;
        cursor: pointer;
        position: relative;
    }

    .product-item:hover .view-product-text {
        display: block;
    }

        /* Product Link Styling */
    .product-link {
        text-decoration: none;
        color: inherit;
    }

    .product-link:hover h6 {
        text-decoration: underline;
    }




</style>
@endsection

@section('content')
<div class="container">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">BiCollection</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->category_name }}</li>
        </ol>
    </nav>
    @php
        $filename = str_replace(' ', '', $category->category_name) . '.jpg';
    @endphp

    <!-- Category Information -->
    <div class="container mt-3">
        <div class="row">
            <!-- Left Section: Image -->
            <div class="col-md-4">
                <div class="card">
                    <!-- Use card-img-top class to properly bind the image to the card -->
                    <img src="{{ asset('images/assets/category/' . $filename ) }}"
                         alt="{{ $category->category_name }}"
                         class="card-img"
                         loading="lazy"
                         style="height: 300px; object-fit: cover;">

                </div>
            </div>


            <!-- Right Section: Description -->
            <div class="col-md-8 d-flex flex-column justify-content-center">
                <div style="text-align: justify">
                    <h3><strong>{{ $category->category_name }}</strong></h3>
                    <p>
                        {{ $category->category_description }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategory Menu -->
    <div class="container mt-4">
        <!-- Subcategory Navigation Menu -->
        <div class="row mb-3">
            <div class="col-12">
                <ul class="nav nav-tabs" id="category-tabs">
                    <!-- "All Products" Tab -->
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                           class="nav-link active"
                           data-category="all"
                           data-url="{{ route('category.product', ['category_name' => $category->category_name]) }}">
                            All Products
                        </a>
                    </li>

                    <!-- Dynamically Generated Subcategories -->
                    @foreach ($subcategories as $subcategory)
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                               class="nav-link"
                               data-category="{{ $subcategory->category_id }}"
                               data-url="{{ route('subcategory.products', ['subcategory' => $subcategory->category_id]) }}">
                                {{ $subcategory->category_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Products Content -->
        <div id="product-container" classs="mb-5">
            @if (view()->exists('category.partials.product-list'))
                @include('category.partials.product-list', ['products' => $products])
            @else
                <p>View does not exist. Check the path.</p>
            @endif
        </div>

    </div>
</div>

@include('Components.add-to-cart')
@include('Components.favorite-success-modal')
@include('Components.footer')
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // When any tab is clicked
            $('#category-tabs .nav-link').on('click', function() {
                // Get the URL for the clicked tab
                var url = $(this).data('url');
                var category = $(this).data('category');

                // Highlight the clicked tab and remove active class from others
                $('#category-tabs .nav-link').removeClass('active');
                $(this).addClass('active');

                // Make an AJAX request to fetch products for the clicked category
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        category_id: category
                    },
                    success: function(response) {
                        // Replace the content in #product-container with the new product list
                        $('#product-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data: ", error);
                    }
                });
            });
        });
    </script>
@endsection

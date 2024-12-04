@extends('Components.layout')

@section('styles')
    <style>

        .category-list, .price-list {
            list-style: none;
            padding: 0;
        }

        .category-list li, .price-list li {
            margin-bottom: 8px;
        }

        .category-list li a, .price-list li a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .category-list li a:hover, .price-list li a:hover {
            color: #007bff;
        }

        .price-slider {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .price-label {
            margin-left: 10px;
            font-weight: bold;
        }

        /* Sorting and Filtering Styles */
        .sort-options select, .filter-options input {
            margin-right: 10px;
        }

        .view-options .btn {
            margin-left: 5px;
        }

        /* Product Grid Styles */
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 10px;
            text-align: center;
        }

        .product-card .card-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 0;
        }

        .breadcrumb a {
            text-decoration: none; /* Remove underline */
            font-size: 14px; /* Adjust the font size as needed */
            color: #666; /* Adjust the text color as needed */
        }

        .breadcrumb a:hover {
            color: #228b22; /* Dark green color on hover */
        }

        .breadcrumb .breadcrumb-item.active {
            font-size: 14px; /* Adjust the font size for active item */
            font-weight: bold; /* Make the active item bold */
            color: #228b22 !important; /* Dark green color for the active item */
        }

        /* New hover effect for all breadcrumb items */
        .breadcrumb .breadcrumb-item:hover {
            color: #228b22; /* Change color to dark green on hover */
        }
        .result-count {
            font-size: 12px;        /* Change font size */
            font-weight: bold;      /* Make it bold */
            color: #228b22;         /* Change font color */
            font-family: Arial, sans-serif; /* Change font family */
        }
        .hidden-category {
            display: none;
        }

        #viewMoreBtn {
            cursor: pointer;
            text-decoration:none;
            color:#228b22;
        }
        .category-list li,
        .price-list li {
            padding-left: 10px; /* Add indentation */
            list-style-type: none; /* Optional: remove default bullet points */
            position: relative;
        }
        .category-list li a,
        .price-list li a {
            color: inherit; /* Inherit default color */
            text-decoration: none; /* Remove underline */
            display: inline-block; /* Make hover padding work */
            transition: color 0.3s; /* Smooth color transition */
        }

        .category-list li a:hover,
        .category-list li a:focus,
        .category-list li a:active,
        .price-list li a:hover,
        .price-list li a:focus,
        .price-list li a:active {
            color: #228b22; /* Change color on hover, focus, and active states */
        }
        /* Custom styled checkbox */
        .form-check-input.custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 15px;
            height: 15px;
            background-color: #ddd;
            border: 2px solid #ccc;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .form-check-input.custom-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.5);
        }

        .form-check-input.custom-checkbox:checked {
            background-color: #228b22;
            border-color: #228b22;
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
        .close-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            cursor: pointer;
        }
        .form-check-input {
        }

        .form-check-input:checked {
            background-color: #228b22;  
            border-color: #228b22;  
            color: #228b22;   
        }

        .form-check-input:focus {
            border-color: #228b22;    
            box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.5); 
        }

        .form-check-input:disabled {
            background-color: #e9ecef;
            border-color: #ced4da;
            cursor: not-allowed;
        }

    </style>
@endsection

@section('content')
<div class="container mt-3">
    <div class="container mt-3">
        <!-- Breadcrumb -->
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
            <li class="breadcrumb-item active">
                "{{ $query }}" (<span class="result-count">{{ $product_count }} results found</span>)
            </li>
        </ol>
        <!-- Rest of the content -->
    </div>
    <div class="row mt-3">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <!-- Category Filter -->
            <h5 class="sidebar-title">CATEGORIES</h5>
            <form id="filterForm">
                <div class="category-list" id="categoryList">
                    @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" name="category[]" value="{{ $category->category_id }}" id="category{{ $category->category_id }}">
                            <label class="form-check-label" for="category{{ $category->category_id }}">
                                {{ $category->category_name }} ({{ $categoryCounts[$category->category_id] ?? 0 }})
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <!-- Price Filter -->
                <h5 class="sidebar-title mt-4">PRICE</h5>
                <div class="price-list">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price[]" value="0-50" id="priceUnder50">
                        <label class="form-check-label" for="priceUnder50">Under ₱50</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price[]" value="50-100" id="price50to100">
                        <label class="form-check-label" for="price50to100">₱50 to ₱100</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input " type="checkbox" name="price[]" value="200-350" id="price200to350">
                        <label class="form-check-label" for="price200to350">₱200 to ₱350</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price[]" value="400-800" id="price400to800">
                        <label class="form-check-label" for="price400to800">₱400 to ₱800</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price[]" value="1000-999999" id="priceAbove1000">
                        <label class="form-check-label" for="priceAbove1000">₱1000 & Above</label>
                    </div>
                </div>
            </form>
        </div>
        

        <!-- Product Grid -->
        <div class="col-md-9">
            <!-- Sorting and Filters -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="sort-options">
                    <label for="sortBy" class="form-label">Sort by:</label>
                    <select id="sortBy" class="form-select">
                        <option value="best_match">Best Match</option>
                        <option value="price_low_high">Price: Low to High</option>
                        <option value="price_high_low">Price: High to Low</option>
                    </select>
                </div>
                <div class="filter-options">
                    <input type="checkbox" id="freeShipping" class="form-check-input custom-checkbox"> <label for="freeShipping">Free Shipping (0)</label>
                    <input type="checkbox" id="newArrivals"  class="form-check-input custom-checkbox" {{ $newArrivals ? 'checked' : '' }}> 
                    <label for="newArrivals">New Arrivals (<span id="newArrivalsCount">{{ $newArrivalsCount }}</span>)</label>
                </div>
            </div>
                <!-- Searched Products -->
            <div class="container">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h3>"{{ $query }}" Result (<span>{{ $product_count }}</span>)</h3>
                        <hr>
                    </div>
                </div>

                <div class="row d-flex justify-content-center" id="productList">
                    <div class="d-flex flex-wrap justify-content-start">
                        @if($products->isNotEmpty())
                            @foreach($products as $product)
                                <div style="width: auto; margin: 0; padding: 0;">
                                    <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none" style="color: inherit;">
                                        <div class="product-item p-2 product-card-hover">
                                            <div class="card" style="width: 11rem; border: none; transition: transform 0.3s; position: relative;">
                                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                                                    class="card-img-top"
                                                    alt="{{ $product->product_name }}"
                                                    style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem;">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title" style="font-size: 1rem; font-weight: bold;">{{ $product->product_name }}</h6>
                                                    <p class="card-text" style="font-size: 14px; color: #555;">
                                                        <strong>₱{{ number_format($product->price, 2) }}</strong>
                                                    </p>
                                                    <p class="card-text" style="font-size: 12px; color: #777;">No reviews</p>
                
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        @if(Auth::check())
                                                            <!-- User is logged in -->
                                                            <a href="#" class="btn btn-custom btn-sm add-to-cart" data-product-id="{{ $product->product_id }}">
                                                                <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
                                                            </a>
                                                            <a href="#" class="btn btn-outline-danger btn-sm" style="width: 2rem;">
                                                                <i class="fas fa-heart"></i>
                                                            </a>
                                                        @else
                                                            <!-- User is a guest -->
                                                            <a href="#" class="btn btn-custom btn-sm suggest-login">
                                                                <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
                                                            </a>
                                                            <a href="#" class="btn btn-outline-danger btn-sm" style="width: 2rem;">
                                                                <i class="fas fa-heart"></i>
                                                            </a>
                                                        @endif   
                                                    </div>
                                                </div>
                                                <!-- View Product Text -->
                                                <div class="view-product-text" style="display: none; position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);">
                                                    <span style="background: rgba(255, 255, 255, 0.8); padding: 5px; border-radius: 5px;">View Product</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                    </div>
                        <!-- No products matched the search -->
                        <div class="mt-4" style="text-align: center !important;">
                            <p>No products match your search. Try searching for something else.</p>
                        </div>
                    @endif
                    <div class="row d-flex justify-content-center" id="productList" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Components.add-to-cart')
@endsection

@section('scripts')
<script>
    // Toggle the "View More" / "See Less" functionality
    document.getElementById('viewMoreBtn').addEventListener('click', function() {
        const hiddenItems = document.querySelectorAll('.hidden-category');
        const isExpanded = this.textContent === 'See Less';

        if (isExpanded) {
            // Collapse the list: hide items and change button text
            hiddenItems.forEach(item => item.style.display = 'none');
            this.textContent = 'View More';
        } else {
            // Expand the list: show items and change button text
            hiddenItems.forEach(item => item.style.display = 'list-item');
            this.textContent = 'See Less';
        }
    });
</script>

<script>
    // Sort products based on selected option
    $('#sortBy').on('change', function() {
        const selectedSort = $(this).val();

        $.ajax({
            url: '{{ route("search.results") }}',
            type: 'GET',
            data: {
                query: '{{ $query }}', // Keep the search query
                sort: selectedSort,
                category_ids: getSelectedCategories(), // Pass selected categories
                price_ranges: getSelectedPrices(), // Pass selected price ranges
                new_arrivals: $('#newArrivals').is(':checked') ? 1 : 0 // Pass new arrivals filter
            },
            success: function(response) {
                // Replace the product list with the sorted results
                $('#productList').html(response.html);
            },
            error: function() {
                alert('Failed to sort products. Please try again.');
            }
        });
    });
</script>

<script>
    // Filter by new arrivals checkbox
    $('#newArrivals').on('change', function() {
        const isChecked = $(this).is(':checked');

        $.ajax({
            url: '{{ route("search.results") }}',
            type: 'GET',
            data: {
                query: '{{ $query }}', // Keep the search query
                sort: '{{ $sort }}', // Maintain current sorting
                new_arrivals: isChecked ? 1 : 0, // Send 1 if checked, 0 if unchecked
                category_ids: getSelectedCategories(), // Pass selected categories
                price_ranges: getSelectedPrices() // Pass selected price ranges
            },
            beforeSend: function() {
                // Hide the product list before the AJAX call
                $('#productList').hide();
            },
            success: function(response) {
                // Replace the product list with the filtered results
                $('#productList').html(response.html);
                // Show the product list after filtering
                $('#productList').fadeIn(); // Smoothly show the product list
            },
            error: function() {
                alert('Failed to filter products. Please try again.');
            }
        });
    });
</script>

<script>
    // Filter by price ranges
    $(document).on('click', '.price-filter', function(e) {
        e.preventDefault(); // Prevent the default anchor click behavior

        const minPrice = $(this).data('min');
        const maxPrice = $(this).data('max');

        $.ajax({
            url: '{{ route("search.results") }}',
            type: 'GET',
            data: {
                query: '{{ $query }}', // Keep the search query
                sort: '{{ $sort }}', // Maintain the current sorting
                new_arrivals: $('#newArrivals').is(':checked') ? 1 : 0, // Include "New Arrivals" filter
                min_price: minPrice,
                max_price: maxPrice,
                category_ids: getSelectedCategories(), // Pass selected categories
            },
            beforeSend: function() {
                // Hide the product list before the AJAX call
                $('#productList').hide();
            },
            success: function(response) {
                // Replace the product list with the filtered results
                $('#productList').html(response.html);
                // Show the product list after filtering
                $('#productList').fadeIn(); // Smoothly show the product list
            },
            error: function() {
                alert('Failed to filter products by price. Please try again.');
            }
        });
    });
</script>

<script>
    // Suggest login for users who attempt to add products to the cart
    $(document).on('click', '.suggest-login', function(e) {
        e.preventDefault(); // Prevent default anchor behavior
        alert('Please log in to add products to your cart.');
        // Optionally, you can redirect to the login page
        window.location.href = '{{ route("login") }}';
    });
</script>

<script>
    // Handle category and price filters dynamically
    $(document).ready(function() {
        // When categories or prices change, update the product list
        $('#filterForm input').on('change', function() {
            const requestData = {
                category_ids: getSelectedCategories(),
                price_ranges: getSelectedPrices(),
                query: '{{ $query }}', // Keep the search query
                sort: '{{ $sort }}', // Maintain the current sorting
                new_arrivals: $('#newArrivals').is(':checked') ? 1 : 0 // Include "New Arrivals" filter
            };

            $.ajax({
                url: "{{ route('search.results') }}", // Your route for search
                method: "GET",
                data: requestData,
                beforeSend: function() {
                    // Hide the product list while waiting for the response
                    $('#productList').hide();
                },
                success: function(response) {
                    // Update the product list with filtered results
                    $('#productList').html(response.html);
                    // Show the product list after the response
                    $('#productList').fadeIn(); // Smooth transition
                },
                error: function() {
                    alert('Failed to filter products. Please try again.');
                }
            });
        });
    });

    // Helper functions to gather selected categories and price ranges
    function getSelectedCategories() {
        const selectedCategories = [];
        $('input[name="category[]"]:checked').each(function() {
            selectedCategories.push($(this).val());
        });
        return selectedCategories;
    }

    function getSelectedPrices() {
        const selectedPrices = [];
        $('input[name="price[]"]:checked').each(function() {
            selectedPrices.push($(this).val());
        });
        return selectedPrices;
    }
</script>
@endsection


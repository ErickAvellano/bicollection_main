@extends('Components.layout')

@section('styles')
<style>
    body, html {
        overflow: auto;
        height: 100%;
        margin: 0; /* Ensure no default margin */
    }

    /* Styles for modals */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center; /* Horizontally centers modal */
        align-items: center; /* Vertically centers modal */
    }

    .modal-inner {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        max-width: 500px;
        text-align: center;
        position: relative;
    }

    .close-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        cursor: pointer;
    }

    .register-btn {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .ads-section {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    .ad {
        text-align: center;
    }

    .ad img {
        max-width: 100px;
    }

    .nav-pills{
        display:none;
    }
    .profile-card {
        background-color: #6e6e6e;
        color: white;
        border-radius: 10px;
        padding: 20px;
        max-height: 200px; /* Optional: set a maximum width */
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

    /* Action buttons styling */
    .profile-actions .btn {
        border-radius: 5px;
        padding: 8px 15px;
        margin: 5px;
        border: 2px solid #228b22;
        background-color: #228b22;
        color: #fff;
    }

    .profile-actions .btn:hover,
    .profile-actions .btn:focus {
        border-color: #228b22; /* Custom hover border color */
        background-color: transparent;
        color: #2e2e2e;
    }
    /* Info section styles */
    .store-info {
        font-size: 14px;
    }

    .store-info p {
        margin-bottom: 10px;
    }

    .store-info span {
        font-weight: bold;
    }
    .btn-edit{
        font-size: 10px;
    }
    .btn-save{
        border: 2px solid #28a745;
    }
    /* Menu Container */
    .menu-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 10px 0;
        background-color: #fff;
    }

    /* Menu List */
    .menu-container ul {
        list-style-type: none;
        display: flex;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    /* Menu Items */
    .menu-container ul li {
        margin-right: 30px;
    }

    /* Links Styling */
    .menu-container ul li a {
        text-decoration: none;
        color: #000;
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

    /* Dropdown Container */
    .dropdown {
        position: relative;
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
    /* Floating Chat Box */
    .chat-box {
        position: fixed;
        bottom: 0;
        right: 0;
        margin: 20px;
        z-index: 1000;
    }

    .chat-btn {
        background-color: #28a745;
        color: white;
        padding: 15px 25px;
        border-radius: 10px 10px 0 0;
        border: none;
        cursor: pointer;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 120px;
        text-align: center;
    }

    .chat-btn:hover {
        background-color: #218838;
    }

    /* Chat Modal (Optional) */
    .chat-modal {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 1  00%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content:  center;
        align-items: center;
    }

    .chat-modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        text-align: center;
        position: relative;
    }

    .close-chat {
        position: absolute;
        right: 15px;
        top: 10px;
        cursor: pointer;
        font-size: 20px;
        color: #333;
    }
    /* .product-card {
        width: 13rem;
        height: auto;
        margin-bottom: 15px;
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 5px 5px 0 0;
    }

    .product-card .card-body {
        padding: 8px;
    }

    .product-card .card-title {
        font-size: 14p x;
        font-weight: bold;
        margin-bottom:  5px;
    } */

    .product-card .card-text {
        font-size: 13px;
    }
     .featured-products-container,
    .allproduct-products-container {
        padding: 10px 0;
    }

    /* Adjusting row spacin g */
    .featured-products-row,
    .allproduct-products-row, {
        margin-left: 0;
        margin-right: 0;
        gap: 5px;
    }
    .btn-link-custom{
        color:#218838;
        border: none;
        outline: none;
        box-shadow: none;
    }
    .btn .btn-link-custom:focus,
    .btn  .btn-link-custom:active {
        outline: none;
        box-shadow: none;
    }
    .floating-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: row;
        align-items: flex- end;
        gap: 15px;
     }


    .btn-add-product {
        background-color: #218838;
        color: white;
        padding: 10px 20px;
        border- radius: 30px;
        border: none;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        display: flex;
        align-items: center;
    }


    .btn-settings {
        background-color: #2   18838;
        color: white;
        padding: 12px;
         border-radius: 50%;
        border: none;
        font-size: 24px;
        box-sha dow: 0 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }


    .btn-add-product:hover, .btn-setti   ngs:hover {
        background-color: #1a692b;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        wid th: 100%;
        min-width: 700px;
        text-align: center;
        position: relative;
        margin-top:150px;
    }

    .close-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 20px;
        color: #333;
        padding: 5px;
        outline: none;
        font-size:30px;
    }
    /* Modal-specific product card adjustments */
    .modal .product-card {
        width: 12rem;
        margin-bottom: 15px;
    }

    .modal .product-card img {
        height: 150px;
        object-fit: cover;
    }
    .btn-custom {
        background-color: #228b22;
        bord er-color: #228b22;
        color: #fff;
    }
     .btn-custom:hover,
     .btn-custom:focus {
        background-color: #fafafa;;
        border-color: #228b22;;
        color: black;
    }
    .btn-select{
        border-color: #228b22;
        color: #333;
    }

    #card1, #card2 {
        position: relative;
        background-size: cover;
        background-position: center;
    }

    #editButtons1, #editButtons2 {
        disp lay: none;
        position: abs olute;
        bottom:  20px;
        right: 20px;
        z-index: 10;
        gap: 10px;
        flex-direction: r ow;
    }
    .modal-backdrop{
         display: none;
    }
    /* Custom Modal Overlay */
    .custom-modal {
         display: none;
        position: fixed;
        z-index: 1050;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Centering Custom Modal Dialog */
    .custom-modal-dialog {
        display: flex;
         align-items: center;
         justify-content: center;
        min-height: 100vh;
        margin: 0;
    }

    /* Custom Modal Content */
    .custom-modal-content {
        background-color: #fff;
        border-radius: 5px;
        padding: 15px;
        width: 100%;
        max-width: 500px;
        position: relative;
    }

    /* Custom Modal Header */
    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #dee2e6;
    }

    /* Custom Modal Title */
    .custom-modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 500;
    }

    /* Custom Close Button */
    .custom-close {
        border: none;
        background: none;
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
        color: #000;
        opacity: 0.5;
    }

    .custom-close:hover {
        opacity: 0.8;
    }

    /* Custom Modal Body */
    .custom-modal-body {
        padding: 15px;
        font-size: 1rem;
    }

    /* Custom Modal Footer */
    .custom-modal-footer {
        padding: 10px 15px;
        border-top: 1px solid #dee2e6;
    }
    .search-container, .search-icon{
        display: none;
    }
    .nav-tabs .nav-link {
        color: black !important;
        background-color: transparent !important;
    }

    /* Active state for the clicked tab */
    .nav-tabs .nav-link.active {
        color: #228b22 !important;
        font-weight: bold;
        background-color: transparent !important;
    }

    /* Hover effect (optional) */
    .nav-tabs .nav-link:hover {
        color: #228b22 !important;
    }

    .card-img-top {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
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
    .product-l inks {
        text-decoration: none;
    }
    .pr oduct-item {
        width: 14.2857%; /* Adjust width for 7 items per row */
        box-sizing: border-box; /* E nsures padding doesn't affect the width */
    }

    .homeallproduct-products-row {
        display: flex;
        flex-wrap: wrap; /* Wrap to the next line after 7 items */
        gap: 0px; /* Optional: Add space between items */
    }
    .card-reviews {
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

</style>
@endsection


@section('content')
<div class="container mt-2" style="background-color: white; width:100%; padding: 20px 20px 0 20px;">
    <div class="row">
        <!-- Profile Column -->
        <div class="col-md-5">
            <!-- Profile Card -->
            <div class="card profile-card"
                style="background-image: url('{{ $shop->coverphotopath ? Storage::url($shop->coverphotopath) : asset('images/default-bg.jpg') }}');
                        background-size: cover;" id="profileCard" >
                <!-- Profile image and badge -->
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-img-container">
                            <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : ('images/assets/default_profile.png') }}"
                                alt="User Avatar"
                                id="profileImage"
                                class="rounded-circle mb-3"
                                width="80"
                                height="80">
                                @if($shop->verification_status === 'Verified')
                                    <span class="badge-verified w-100">Verified</span>
                                @elseif($shop->verification_status === 'Pending')
                                    <span class="badge-pending w-100">Pending</span>
                                @elseif($shop->verification_status === 'Rejected')
                                    <span class="badge-rejected w-100">Rejected</span>
                                @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title mt-3" id="cardTitle">{{ $shop->shop_name }}</h5>
                    </div>
                    <div class="col-md-4 text-center">
                        <!-- Profile Action Button to Change Profile Image -->
                        <div class="profile-actions d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-edit me-2" id="showProfileInputBtn" style="display: none;">Change Profile</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="profile-actions d-flex justify-cont ent-end mt-4">
                            <button class="btn btn-edit me-2" id="showEditBtn"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                            <button class="btn btn-edit" id="editBackgroundBtn" style="display: none;">Edit Background</button>
                            <form id="profileImageForm" method="POST" enctype="multipart/form-data" action="{{ route('shop.updateImages') }}">
                                @csrf
                                <input type="file" id="profileImageInput" name="shop_img" accept="image/*" style="display: none;">
                                <input type="file" id="backgroundInput" name="cover_photo" accept="image/*" style="display: none;">
                                <button type="submit" class="btn btn-save me-2" id="save-button" style="display: none; font-size:10px;">Save</button>
                            </form>
                        </div>
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
         <!-- Additional Content Below the Row -->
         <div class="row mt-5">
            <div class="col-md-12">
                <div class="menu-container">
                    <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active {{ request()->get('nav') == 'Home' || !request()->has('nav') ? 'active' : '' }}"
                               href="#" data-nav="Home" role="tab">
                                Home
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->get('nav') == 'Allproduct' ? 'active' : '' }}"
                               href="#" data-nav="Allproduct" role="tab">
                                All Products
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->get('nav') == 'Category' ? 'active' : '' }}"
                               href="#" data-nav="Category" role="tab">
                                Category
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->get('nav') == 'More' ? 'active' : '' }}"
                               href="#" data-nav="More" role="tab">
                                More
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div id="tabContentContainer"></div>


<!-- Modal Structure -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Select Products to Feature</h5>
                <button type="button" class="close close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('featured-product.store') }}" method="POST" id="featuredProductForm">
                    @csrf
                    <input type="hidden" name="shop_design_id" value="{{ $shopDesignId }}">
                    <input type="hidden" name="shop_id" value="{{ $shopId }}">
                    <input type="hidden" name="featuredProduct" id="featuredProductInput" value="">
                    <div class="container">
                        @if($products->isEmpty())
                            <p>No Products Found</p>
                            <a href="{{ route('merchant.product.create') }}" class="btn btn-custom">Add Product</a>
                        @else
                            <div class="row">
                                @foreach($products as $product)
                                    <div class="col-md-4">
                                        <div class="card product-card" style="width: 12rem;">
                                            <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}" class="card-img-top" alt="{{ $product->product_name }}" style="width: 100%; height: 150px; object-fit: cover;">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">{{ $product->product_name }}</h6>
                                                <p class="card-price mb-1">₱{{ number_format($product->price, 2) }}</p>
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
                                                <button type="button"
                                                        class="btn btn-select btn-sm w-100 select-product-btn"
                                                        data-product-id="{{ $product->product_id }}">
                                                    @if(in_array($product->product_id, $featuredProductIds))
                                                        Selected
                                                    @else
                                                        Select
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-custom" id="saveFeaturedProductsBtn">Save Featured Products</button>
            </div>
        </div>
    </div>
</div>


<!-- Floating Buttons -->
<div class="floating-buttons">
    <!-- Add Product Button -->
    <a href="{{ route('merchant.product.create') }}" class="btn btn-add-product">
        <i class="fa fa-plus"></i>&nbsp; Add Product
    </a>

    <!-- Settings Button -->
    {{-- <a href="#" class="btn btn-settings">
        <i class="fa fa-cog"></i>
    </a> --}}
</div>

<!-- Modal to prompt for contact and MOP -->
<div class="custom-modal" id="contactMopModal" tabindex="-1" role="dialog" aria-labelledby="contactMopModalLabel" aria-hidden="true">
    <div class="custom-modal-dialog" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title" id="contactMopModalLabel">Complete Your Profile</h5>
                <button type="button" class="custom-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="custom-modal-body">
                <p class="text-center mb-4">
                    It looks like you haven't set your contact number or mode of payment yet.
                    Please complete your profile to make transactions easier for your customers.
                </p>
            </div>
            <div class="custom-modal-footer d-flex justify-content-end gap-1">
                <!-- Redirect button to myprofile page -->
                <a href="{{ route('merchant.myProfile') }}" class="btn btn-custom">Complete Profile</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Later</button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
    <!--  Script for Button Visibility Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showEditBtn = document.getElementById('showEditBtn');
            const showProfileInputBtn = document.getElementById('showProfileInputBtn');
            const editBackgroundBtn = document.getElementById('editBackgroundBtn');
            const saveButton = document.getElementById('save-button');

            // Function to toggle visibility of buttons
            function toggleButtons() {
                const isHidden = showProfileInputBtn.style.display === 'none';
                showProfileInputBtn.style.display = isHidden ? 'inline-block' : 'none';
                editBackgroundBtn.style.display = isHidden ? 'inline-block' : 'none';
                saveButton.style.display = isHidden ? 'inline-block' : 'none';
                showEditBtn.style.display = isHidden ? 'none' : 'inline-block';
            }

            // Event listener for "Edit" button
            showEditBtn?.addEventListener('click', toggleButtons);
        });
    </script>

    <!--  Script for Profile Image Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showProfileInputBtn = document.getElementById('showProfileInputBtn');
            const profileImageInput = document.getElementById('profileImageInput');
            const profileImage = document.getElementById('profileImage');
            const saveButton = document.getElementById('save-button');

            // Show file input when "Change Profile" button is clicked
            showProfileInputBtn?.addEventListener('click', () => profileImageInput.click());

            // Update profile image preview
            profileImageInput?.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        profileImage.src = e.target.result;
                        saveButton.style.display = 'inline-block'; // Show save button
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
        <!--  Script for Background Image Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBackgroundBtn = document.getElementById('editBackgroundBtn');
            const backgroundInput = document.getElementById('backgroundInput');
            const profileCard = document.getElementById('profileCard');
            const cardTitle = document.querySelector('.card-title');

            // Trigger file input when "Edit Background" button is clicked
            editBackgroundBtn?.addEventListener('click', () => backgroundInput.click());

            // Update background image and adjust text color
            backgroundInput?.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imageUrl = e.target.result;
                        profileCard.style.backgroundImage = `url(${imageUrl})`;
                        profileCard.style.backgroundSize = 'cover';
                        adjustTextColorAndButtonStyles(imageUrl, cardTitle); // Adjust text color
                    };
                    reader.readAsDataURL(file);
                }
            });
            // Function to adjust text color based on background image
            function adjustTextColorAndButtonStyles(bgImageUrl, element) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const img = new Image();
                img.src = bgImageUrl;
                img.crossOrigin = 'Anonymous';

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const pixels = imageData.data;
                    let r = 0, g = 0, b = 0;

                    for (let i = 0; i < pixels.length; i += 4) {
                        r += pixels[i];
                        g += pixels[i + 1];
                        b += pixels[i + 2];
                    }

                    const pixelCount = pixels.length / 4;
                    r = Math.floor(r / pixelCount);
                    g = Math.floor(g / pixelCount);
                    b = Math.floor(b / pixelCount);

                    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                    element.style.color = luminance > 0.5 ? '#000' : '#fff';
                };
            }
        });
    </script>
    <!--  Script for Modal Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabContentContainer = document.getElementById('tabContentContainer'); // Parent container for dynamic content
            const productModal = new bootstrap.Modal(document.getElementById('productModal')); // Bootstrap Modal API

            // Delegate click events to the parent container
            tabContentContainer?.addEventListener('click', function (event) {
                const target = event.target.closest('#openModalButton'); // Check if #openModalButton was clicked
                if (target) {
                    productModal.show(); // Show the modal
                }
            });

            // Optional: Close modal programmatically
            const closeBtn = document.querySelector('.close');
            closeBtn?.addEventListener('click', function () {
                productModal.hide(); // Close the modal
            });
        });
        </script>

    <!--  Script for Featured Product Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedProductIds = @json($featuredProductIds);
            const featuredProductInput = document.getElementById('featuredProductInput');
            const saveButtonAds = document.getElementById('saveFeaturedProductsBtn');

            // Pre-check already selected products
            selectedProductIds.forEach((productId) => {
                const button = document.querySelector(`.select-product-btn[data-product-id="${productId}"]`);
                if (button) {
                    updateButtonState(button, true);
                }
            });

            // Update hidden input field
            featuredProductInput.value = selectedProductIds.join(',');

            // Toggle product selection
            document.querySelectorAll('.select-product-btn').forEach((button) => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const isSelected = !selectedProductIds.includes(productId);

                    if (isSelected) {
                        selectedProductIds.push(productId);
                    } else {
                        selectedProductIds = selectedProductIds.filter(id => id !== productId);
                    }

                    updateButtonState(this, isSelected);
                    featuredProductInput.value = selectedProductIds.join(',');
                });
            });

            // Save selected products
            saveButtonAds?.addEventListener('click', () => {
                featuredProductInput.value = selectedProductIds.join(',');
                document.getElementById('featuredProductForm').submit();
            });

            // Update button state
            function updateButtonState(button, isSelected) {
                if (isSelected) {
                    button.style.backgroundColor = 'green';
                    button.style.color = 'white';
                    button.textContent = 'Selected';
                } else {
                    button.style.backgroundColor = '';
                    button.style.color = '';
                    button.textContent = 'Select';
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabContentContainer = document.getElementById('tabContentContainer');

            function initializeCardEvents() {
                [1, 2].forEach(function (cardNumber) {
                    const card = document.getElementById(`card${cardNumber}`);
                    const triggerEdit = document.getElementById(`triggerEdit${cardNumber}`);
                    const addImage = document.getElementById(`addImage${cardNumber}`);
                    const fileInput = document.getElementById(`imageUpload${cardNumber}`);
                    const form = document.getElementById(`form${cardNumber}`);
                    const editButtons = document.getElementById(`editButtons${cardNumber}`);
                    const cancelButton = document.getElementById(`cancelImage${cardNumber}`);
                    const changeButton = document.getElementById(`changeImage${cardNumber}`);

                    function toggleFormVisibility(show) {
                        form.style.display = show ? 'block' : 'none';
                        editButtons.style.display = show ? 'flex' : 'none';
                        triggerEdit.style.display = show ? 'none' : 'block';
                        addImage.style.display = show ? 'none' : 'inline-block';
                    }

                    triggerEdit?.addEventListener('click', function () {
                        toggleFormVisibility(true);
                    });

                    addImage?.addEventListener('click', function () {
                        fileInput.click();
                    });

                    changeButton?.addEventListener('click', function () {
                        fileInput.click();
                    });

                    fileInput?.addEventListener('change', function (event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                card.style.backgroundImage = `url('${e.target.result}')`;
                                card.style.backgroundSize = 'cover';
                                toggleFormVisibility(true);
                                addImage.style.display = 'none'; // Hide the "plus" icon
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    cancelButton?.addEventListener('click', function () {
                        fileInput.value = '';
                        card.style.backgroundImage = ''; // Remove the background image
                        toggleFormVisibility(false);
                        addImage.style.display = 'inline-block'; // Show the "plus" icon
                    });

                    form?.addEventListener('submit', function (event) {
                        if (!fileInput.files.length) {
                            event.preventDefault();
                            alert('Please select an image before saving.');
                        }
                    });
                });
            }

            const observer = new MutationObserver(function (mutationsList) {
                for (const mutation of mutationsList) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length) {
                        initializeCardEvents();
                    }
                }
            });

            observer.observe(tabContentContainer, { childList: true });

            initializeCardEvents();
        });



    </script>



    <script>
        $(document).ready(function() {
            // Show the modal if contact number or MOP is not set
            @if(!$merchant->contact_number || !$merchantMop)
                $('#contactMopModal').fadeIn();
            @endif
            //Close the modal
            $('.custom-close, .btn-secondary').on('click', function() {
                $('#contactMopModal').fadeOut();
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabContentContainer = document.getElementById('tabContentContainer'); // Dynamic content container
            const productModal = new bootstrap.Modal(document.getElementById('productModal')); // Bootstrap modal

            // Handle navigation and content loading
            const tabs = document.querySelectorAll('#productTabs .nav-link');
            tabs.forEach((tab) => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    const navValue = this.getAttribute('data-nav').toLowerCase();

                    // Fetch content for the selected tab
                    fetch(`/merchant/partial/${navValue}`)
                        .then((response) => response.text())
                        .then((data) => {
                             tabContentContainer.innerHTML = data; // Insert content dynamically
                        })
                        .catch((error) => console.error('Error loading content:', error));
                });
            });

            // Load default tab content (home) on page load
            fetch(`/merchant/partial/home`)
                .then((response) => response.text())
                .then((data) => {
                    tabContentContainer.innerHTML = data; // Load content
                })
                .catch((error) => console.error('Error loading default content:', error));

            // Handle modal opening via event delegation
            tabContentContainer?.addEventListener('click', function (event) {
                const target = event.target.closest('#openModalButton'); // Check if modal button is clicked
                if (target) {
                    console.log('Modal button clicked'); // Debugging
                    productModal.show(); // Show modal
                }
            });

            // Optional: Handle modal closing programmatically
            const closeBtn = document.querySelector('.close');
            closeBtn?.addEventListener('click', function () {
                productModal.hide(); // Close the modal
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Add click event listener to each tab
            $('.nav-link').on('click', function() {
                // Remove 'active' class from all links
                $('.nav-link').removeClass('active');

                // Add 'active' class to the clicked tab
                $(this).addClass('active');
            });
        });

    </script>
@endsection

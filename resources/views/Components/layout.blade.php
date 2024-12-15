<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a24a773990.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Futura+PT:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="icon" href="{{ asset('images/assets/bicollectionlogoico.ico') }}" type="image/x-icon">
    <title>BiCollection</title>
    @yield('styles')
</head>
<body>
    <div class="top-info"></div>
    <nav class="navbar navbar-expand-lg" id="mainNavbar" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
        <div class="container-fluid">
            <button class="navbar-toggler custom-toggler" type="button" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a class="navbar-brand d-flex align-items-center" href="/">
                <span class="text-color1">BiCol</span><span class="text-color2">lection</span>
            </a>
            <div class="navbar-right d-flex align-items-center ms-auto">
                <form class="d-flex me-3 desktop-search-form" action="{{ route('search.results') }}" method="GET" role="search" id="searchForm">
                    <div class="search-container position-relative">
                        <input
                            class="form-control search-control rounded-pill w-300"
                            type="search"
                            name="query"
                            placeholder="Search products..."
                            aria-label="Search"
                            id="searchInput"
                            autocomplete="off">
                        <button class="btn search-icon" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <!-- Dropdown for search suggestions -->
                        <div id="searchSuggestions" class="mt-1 list-group position-absolute w-100" style="display: none; z-index: 1000;">
                            <!-- Suggestions will be dynamically added here -->
                        </div>
                    </div>
                </form>
                <!-- Hamburger Navigation -->

                <ul class="navbar-nav desktop-nav">
                    @if(Auth::check())
                        <!-- Top Navigation Items -->
                        @if(Auth::user()->isAdmin())
                            <!-- Admin-specific navigation items -->
                            <li class="nav-item cart">
                                <a href=""
                                    class="nav-link position-relative payment"
                                    id="paymentIcon"
                                    title="Verify Payment"
                                    data-bs-toggle="popover"
                                    data-bs-html="true"
                                    data-bs-trigger="click">
                                    <i class="fa-solid fa-money-bill"></i>
                                    <span class="payment-badge">0</span> <!-- Badge for payments -->
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a href=""
                                    class="nav-link position-relative application"
                                    id="applicationIcon"
                                    title="Current Application"
                                    data-bs-toggle="popover"
                                    data-bs-html="true"
                                    data-bs-trigger="click">
                                    <i class="fa-regular fa-handshake"></i>
                                    <span class="application-badge">0</span> <!-- Badge for applications -->
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a href="{{ route('orders.index') }}"
                                    class="nav-link position-relative order"
                                    id="orderIcon"
                                    title="Current Order List"
                                    data-bs-toggle="popover"
                                    data-bs-html="true"
                                    data-bs-trigger="click">
                                    <i class="fa-regular fa-rectangle-list"></i>
                                    <span class="badge">0</span> <!-- Badge for orders -->
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="#" title="Admin Dashboard">
                                    <i class="fa-solid fa-cogs"></i> <!-- Admin icon -->
                                </a>
                            </li>
                        @elseif(Auth::user()->isMerchant())
                            <!-- Merchant-specific navigation items -->
                            <li class="nav-item store me-2">
                                <a class="nav-link d-flex align-items-center" href="{{ route('mystore') }}" title="My Store">
                                    <i class="fa-solid fa-store"></i>
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a href="{{ route('orders.index') }}"
                                    class="nav-link position-relative order"
                                    id="orderIcon"
                                    title="Orders"
                                    data-bs-toggle="popover"
                                    data-bs-html="true"
                                    data-bs-trigger="click">
                                    <i class="fa-regular fa-rectangle-list"></i>
                                    <span id="order-count" class="badge">0</span> <!-- Badge for orders -->
                                </a>
                            </li>
                        @else
                            <!-- Customer-specific navigation items -->
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('map') }}" title="Map">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </a>
                            </li>
                            <li class="nav-item truck me-2">
                                <a class="nav-link d-flex align-items-center" href="{{ route('order.track.form') }}" title="Track Order">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a class="nav-link d-flex align-items-center position-relative order" href="{{ route('favorites.index') }}" title="Favorite">
                                    <i class="fa-solid fa-heart"></i>
                                    <span id="favorites-count" class="badge">0</span> <!-- Badge for favorites -->
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a href="{{ route('cart.show') }}" class="nav-link position-relative order" id="cartIcon" title="Cart" data-bs-toggle="popover" data-bs-html="true" data-bs-content='@include("Components.cart-tooltip")'>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="cart-count" class="badge">0</span> <!-- Badge for cart -->
                                </a>
                            </li>
                        @endif
                    @endif

                    <!-- Dropdown for Profile -->
                    <li class="nav-item dropdown">
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                                <!-- Admin-specific: Only show Logout button -->
                                <li class="nav-item">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="nav-link d-flex align-items-center" style="font-size:15px; border: none; background: none; cursor: pointer;">
                                            <i class="fa-solid fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            @else
                                <!-- Dropdown for non-admin users -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        @php
                                            $user = Auth::user();
                                            // Handle admin, merchant, or customer image
                                            if ($user->isMerchant() && $user->shop && $user->shop->shop_img) {
                                                $profileImage = asset('storage/' . $user->shop->shop_img); // Merchant shop image
                                            } elseif ($user->customer && $user->customer->customerImage) {
                                                $profileImage = asset('storage/' . $user->customer->customerImage->img_path); // Customer image
                                            } else {
                                                $profileImage = asset('images/assets/default_profile.png'); // Default image
                                            }
                                        @endphp
                                        <img src="{{ $profileImage }}" alt="User Avatar" class="rounded-circle" width="30" height="30">
                                        <span class="login-text ms-2" style="font-size:0.9rem">Hi, {{ $user->username }}!</span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        @if(Auth::user()->isMerchant())
                                            <!-- Merchant-specific dropdown items -->
                                            <li><a class="dropdown-item myaccount" href="{{ route('merchant.myProfile') }}">My Account</a></li>
                                            <li><a class="dropdown-item" href="{{ route('inventory.index') }}">Inventory</a></li>
                                            <li><a class="dropdown-item mystore" href="{{ route('mystore') }}">My Store</a></li>
                                            <li><a class="dropdown-item orders" href="{{ route('orders.index') }}">Orders</a></li>
                                        @else
                                            <!-- Customer-specific dropdown items -->
                                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Account</a></li>
                                            <li><a class="dropdown-item" href="{{ route('mypurchase') }}">My Purchase</a></li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <!-- Direct form submission without JavaScript -->
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="dropdown-item d-flex justify-content-between align-items-center">
                                                    Logout <i class="fa-solid fa-sign-out-alt ms-2"></i>
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @else
                            <!-- For guests -->
                            <!-- Customer-specific navigation items -->
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('map') }}" title="Map">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </a>
                            </li>
                            <li class="nav-item truck me-2">
                                <a class="nav-link d-flex align-items-center" href="{{ route('order.track.form') }}" title="Track Order">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a class="nav-link d-flex align-items-center position-relative order" href="{{ route('favorites.index') }}" title="Favorite">
                                    <i class="fa-solid fa-heart"></i>
                                    <span id="favorites-count" class="badge">0</span> <!-- Badge for favorites -->
                                </a>
                            </li>
                            <li class="nav-item cart">
                                <a href="{{ route('cart.show') }}" class="nav-link position-relative order" id="cartIcon" title="Cart" data-bs-toggle="popover" data-bs-html="true" data-bs-content='@include("Components.cart-tooltip")'>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="cart-count" class="badge">0</span> <!-- Badge for cart -->
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                                    <i class="fas fa-user"></i>
                                    <span class="login-text">
                                        Login /<br>
                                        Sign In
                                    </span>
                                </a>
                            </li>
                        @endif
                    </li>
                </ul>


                <!-- Mobile Navigation -->
                <ul class="navbar-nav mobile-nav">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-start" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-arrow-left return-hidden-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" id="mobile-search-icon">
                            <i class="fas fa-search mobile"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('map') }}">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </a>
                    </li>
                    <li class="nav-item cart">
                        <a href="{{ route('cart.show') }}" class="nav-link position-relative order" id="cartIcon" title="Cart" data-bs-toggle="popover" data-bs-html="true" data-bs-content='@include("Components.cart-tooltip")'>
                            <i class="fa fa-shopping-cart"></i>
                            <span id="cart-count" class="badge">0</span> <!-- Placeholder for cart count -->
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                                // Handle merchant or customer image
                                $profileImage = $user->isMerchant() && $user->shop && $user->shop->shop_img
                                    ? asset('storage/' . $user->shop->shop_img)   // Merchant shop image
                                    : ($user->customer && $user->customer->customerImage
                                        ? asset('storage/' . $user->customer->customerImage->img_path)  // Customer image
                                        : asset('images/assets/default_profile.png'));  // Default image
                            @endphp
                            {{-- <a class="nav-link d-flex align-items-center" href="{{ route('myprofile') }}">
                                <img src="{{ $profileImage }}" alt="User Avatar" class="rounded-circle" width="30" height="30">
                            </a> --}}
                        @else
                            <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                                <i class="fas fa-user"></i>
                                <span class="login-text">
                                    Login /<br>
                                    Sign In
                                </span>
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Secondary Menu for larger screens -->
    <div class="container-fluid second-nav" id="navPillsContainer" style="padding: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); background-color: #fafafa">
        <div class="container">
            <div class="secondary-menu">
                <ul class="nav nav-pills">
                    <li class="nav-item dropdown">
                        <a class="nav-link custom-dropdown-toggle" href="#" id="customCategoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bars-staggered pills-icon"></i> Category
                        </a>
                        <ul class="p-0 dropdown-menu dropdown-menu-end custom-dropdown-width transition-dropdown" aria-labelledby="customCategoryDropdown">
                            <li class="p-0 dropdown-item">
                                <div class="container">
                                    <h5 class="mt-2 mb-2" style="color: black;">Browse Product by Category</h5>
                                    <div class="row category" >
                                        <!-- Dynamically Load Categories -->
                                        @foreach($categories as $category)
                                            @php
                                                $filename = str_replace(' ', '', $category->category_name) . '.jpg';
                                            @endphp
                                            <div class="col-2 category-col">
                                                <a href="{{ route('category.product', ['category_name' => $category->category_name]) }}">
                                                    <div class="overflow-hidden text-center border rounded category-box position-relative">
                                                        <img src="{{ asset('images/assets/category/' . $filename) }}" alt="{{ $category->category_name }}" class="img-fluid w-100 h-100">
                                                        <div class="category-name-overlay position-absolute w-100 d-flex align-items-center justify-content-center">
                                                            <p class="m-0 text-white font-weight-bold">{{ $category->category_name }}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Other menu items -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa-solid fa-store pills-icon"></i> Stores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('merchant.customize') }}"><i class="fa-regular fa-pen-to-square pills-icon"></i> Customize a Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('merchant.startselling') }}"> <i class="fa-regular fa-handshake pills-icon"></i> Sell On BiCollection</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <div id="mobile-search-form" class="collapse">
        <form class="d-flex" role="search">
            <div class="search-container position-relative">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                <button class="btn search-icon" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <!-- Main Sidebar -->
    <div id="sidebar" class="sidebar" style="width: 0;">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <h5 class="sidebar-title">MENU</h5>
        <ul class="mt-4 mb-5">
            <li class="nav-item home">
                <a class="nav-link d-flex align-items-center me-2" href="#">
                    <i class="fa-solid fa-house"></i>Home
                </a>
            </li>
            <li class="nav-item product">
                <a class="nav-link d-flex align-items-center me-2" href="{{ route('product.index') }}">
                    <i class="fa-solid fa-table-list"></i> Products
                </a>
            </li>
            <li class="nav-item category">
                <a class="nav-link d-flex align-items-center me-2" href="#">
                    <i class="fa-solid fa-bars-staggered"></i> Category
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="#">
                    <i class="fa-solid fa-store pills-icon"></i> Stores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa-regular fa-pen-to-square pills-icon">
                    </i> Customize a Product</a>
            </li>
            <li class="nav-item liked">
                <a class="nav-link d-flex align-items-center" href="#">
                    <i class="fa-solid fa-heart"></i> Liked
                </a>
            </li>
            <li class="nav-item truck">
                <a class="nav-link d-flex align-items-center me-2" href="{{ route('order.track.form') }}">
                    <i class="fa-solid fa-truck-fast"></i> Track Order
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('merchant.startselling') }}">
                    <i class="fa-regular fa-handshake pills-icon"></i> Sell On BiCollection</a>
            </li>
        </ul>
        <div class="footer">
            @if (Auth::check())
                <a href="#">
                    <i class="fa-solid fa-cog"></i>
                    <span class="login-text ms-2">{{ Auth::user()->username }}</span> <!-- Using Auth::user() to get the authenticated user's details -->
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout <i class="fa-solid fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="#">
                    <i class="fa-solid fa-cog"></i>
                    <span class="login-text ms-2">Guest</span> <!-- Display something for non-logged-in users -->
                </a>
                <a href="{{ route('login') }}">
                    Login <i class="fas fa-sign-in-alt"></i>
                </a>
            @endif
        </div>
    </div>

@yield('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Navbar --}}
    <script>
        // Get the navbar element
        const navbar = document.getElementById("mainNavbar");
        const navPills = document.getElementById("navPillsContainer");

        // Track the previous scroll position
        let lastScrollTop = 0;
        const stickyScrollThreshold = 50; // Set a threshold for when to add sticky classes

        // Add scroll event listener
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Check if scrolled more than the threshold
            if (currentScroll > stickyScrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    // User is scrolling down
                    navbar.classList.add('sticky');
                    navPills.classList.add('fixed-nav');
                } else {
                    // User is scrolling up
                    navbar.classList.remove('sticky');
                    navPills.classList.remove('fixed-nav');
                }
            } else {
                // Reset sticky classes if not past the threshold
                navbar.classList.remove('sticky');
                navPills.classList.remove('fixed-nav');
            }

            // Update last scroll position
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
        });
    </script>
    {{-- Sidebar --}}
    <script>
        function openNav() {
            document.getElementById("sidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("sidebar").style.width = "0";
        }

        document.getElementById('sidebarToggle').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.style.width === '250px') {
                closeNav();
            } else {
                openNav();
            }
        });

        document.getElementById('mobile-search-icon').addEventListener('click', function() {
            var searchForm = document.getElementById('mobile-search-form');
            if (searchForm.style.display === 'none' || searchForm.style.display === '') {
                searchForm.style.display = 'block';
            } else {
                searchForm.style.display = 'none';
            }
        });
        document.getElementById('mobile-search-icon').addEventListener('click', function() {
            var searchForm = document.getElementById('mobile-search-form');
            if (searchForm.style.display === 'none' || searchForm.style.display === '') {
                searchForm.style.display = 'block';
            } else {
                searchForm.style.display = 'none';
            }
        });

    </script>
    {{-- Cart Popover --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartIcon = document.getElementById('cartIcon');

            // Initialize Bootstrap popover for cart
            const cartPopover = new bootstrap.Popover(cartIcon, {
                trigger: 'manual',
                placement: 'bottom',
                html: true,
                content: function () {
                    // Placeholder content to be replaced with AJAX response
                    return `
                        <div class="mt-3 mb-3 text-center" id="popover-cart-content">
                            <div class="spinner-border text-custom spinner-medium" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`;
                }
            });

            // Function to fetch and update cart data in popover
            function updateCartPopover() {
                $.ajax({
                    url: '/cart-tooltip',  // Ensure this route is correct
                    type: 'GET',
                    success: function (response) {
                        let content = '';

                        if (response.cartItems.length === 0) {
                            content = '<p class="text-center">Your cart is empty.</p>';
                        } else {
                            content += `<div class="cart-items-container" style="max-height: 220px; overflow-y: auto; padding-left:10px;">`;

                            // Loop through cart items and generate content
                            response.cartItems.forEach(function (cartItem) {
                                content += `
                                    <div class="cart-item" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                        <!-- Product Image -->
                                        <img src="/storage/${cartItem.image_url}" alt="Product Image" style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px;">

                                        <!-- Product Details -->
                                        <div style="flex-grow: 1;">
                                            <p class="text-start">
                                                <a href="/merchant/product/${cartItem.product_id}" class="product-link">
                                                    ${cartItem.product_name}
                                                </a>
                                            </p>
                                            <p class="text-start">Qty: ${cartItem.quantity}</p>
                                            <p class="text-start">Subtotal: ₱${cartItem.subtotal}</p>
                                        </div>

                                        <!-- Remove Button (X) -->
                                        <button class="remove-item" data-cart-id="${cartItem.cart_id}" style="background: none; border: none; color: red; font-size: 1.2rem; cursor: pointer;">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <hr>`;
                            });

                            content += `</div>`; // End of the scrollable container
                            content += `
                                <p style="margin-bottom:0; padding:0px 20px;" class="text-end"><strong>Total: ₱${response.totalCartAmount}</strong></p>
                                <div style="text-align: center;">
                                    <a href="/cart" class="mb-2 btn btn-outline-custom btn-sm">Go to Cart</a>
                                </div>`;
                        }

                        // Inject the dynamic content into the popover
                        const popoverContent = document.getElementById('popover-cart-content');
                        if (popoverContent) {
                            popoverContent.innerHTML = content;

                            // Attach event listener to remove-item buttons
                            document.querySelectorAll('.remove-item').forEach(button => {
                                button.addEventListener('click', function () {
                                    const cartId = this.getAttribute('data-cart-id');
                                    removeCartItem(cartId);  // Call the function to remove the item
                                });
                            });
                        }
                    },
                    error: function () {
                        const popoverContent = document.getElementById('popover-cart-content');
                        if (popoverContent) {
                            popoverContent.innerHTML = '<p class="text-center">Your cart is empty.</p>';
                        }
                    }
                });
            }

            // Show cart popover on hover and fetch cart data
            cartIcon.addEventListener('mouseenter', function () {
                updateCartPopover(); // Fetch and update the cart data
                cartPopover.show();
            });

            // Function to remove cart item via AJAX
            function removeCartItem(cart_id) {
                $.ajax({
                    url: `/cart/remove/${cart_id}`,  // Ensure the URL matches your delete route
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token in headers
                    },
                    success: function (response) {
                        if (response.success) {
                            // Update the cart tooltip content to reflect changes
                            updateCartPopover();

                            // Update the global cart count
                            updateCartCount();
                        } else {
                            // alert('Error removing item from cart.');
                        }
                    },
                    // error: function (xhr, status, error) {
                    //     console.log('XHR:', xhr);
                    //     console.log('Status:', status);
                    //     console.log('Error:', error);
                    //     alert('Error removing item from cart.');
                    // }
                });
            }

            // Function to update cart count in the icon
            function updateCartCount() {
                $.ajax({
                    url: '{{ route("cart.count") }}',  // Ensure this URL matches your route for fetching cart count
                    type: 'GET',
                    success: function (response) {
                        $('#cart-count').text(response.cartItemCount);
                    },
                    // error: function () {
                    //     console.error('Failed to load cart item count');
                    // }
                });
            }

            // Hide cart popover on mouseleave
            cartIcon.addEventListener('mouseleave', function () {
                setTimeout(() => {
                    if (!cartIcon.matches(':hover') && !document.querySelector('.popover:hover')) {
                        cartPopover.hide();
                    }
                }, 100);
            });

            // Close cart popover when clicking outside
            document.addEventListener('click', function (event) {
                if (!cartIcon.contains(event.target) && !document.querySelector('.popover:hover')) {
                    cartPopover.hide();
                }
            });
        });
    </script>
    {{-- Cart Badge --}}
    <script>
        // Function to update cart count in the icon
        function updateCartCount() {
            $.ajax({
                url: '{{ route("cart.count") }}', // Ensure this URL matches your route for fetching cart count
                type: 'GET',
                success: function(response) {
                    $('#cart-count').text(response.cartItemCount); // Update the cart count span with the value from the server
                },
                // error: function() {
                //     console.error('Failed to load cart item count');
                // }
            });
        }

        // On page load, update the cart count
        $(document).ready(function () {
            updateCartCount(); // Update the cart count on page load
        });

    </script>
    {{-- Add to Cart --}}
    <script>
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();

            const productId = $(this).data('product-id'); // Get the product ID

            $.ajax({
                url: `/cart/add/${productId}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function (response) {
                    if (response.success) {
                        // Reset modal content
                        $('#productName').text('');
                        $('#productDetails select').remove(); // Remove any previous dropdowns
                        $('#quantity').text('');
                        $('#cartTotal').text('');
                        $('#productImage').attr('src', '');
                        $('#productDetails').attr('data-cart-id', '');

                        // Set product details
                        $('#productName').text(response.product_name);
                        $('#quantity').text(`Quantity: ${response.quantity}`);
                        $('#cartTotal').text(`Total: ₱${response.cart_total}`);
                        $('#cartItemCount').text(response.cart_item_count);
                        $('#totalCartAmount').text(`Cart Total: ₱${response.total_cart_amount}`);

                        // Update the product image if available
                        if (response.product_image) {
                            $('#productImage').attr('src', `/storage/${response.product_image}`);
                        }

                        // Set the cart_id as a data attribute
                        $('#productDetails').attr('data-cart-id', response.cart_id);

                        // Dynamically add a dropdown for variations under #productDetails
                        const dropdownHTML = `
                            <select id="variation-select-${response.cart_id}" class="form-select variation-select" style="width: auto; min-width: 100px; max-width: 300px; font-size: 0.7rem;">
                            </select>`;

                        // Replace the existing dropdown dynamically
                        $('#productDetails')
                            .find('select#variationSelect') // Locate the existing dropdown
                            .remove(); // Remove the old dropdown to avoid duplicates

                        // Add the new dropdown HTML after the label
                        $('#productDetails')
                            .find('label[for="variation-Select"]') // Target the label for the dropdown
                            .after(dropdownHTML);

                        // Populate the new dropdown dynamically with variations
                        const variationSelect = $(`#variation-select-${response.cart_id}`);
                        variationSelect.empty(); // Clear any existing options

                        if (response.product_variations && response.product_variations.length > 0) {
                            response.product_variations.forEach(variation => {
                                const isSelected =
                                    response.product_variation_id === variation.product_variation_id ? 'selected' : '';
                                variationSelect.append(
                                    `<option value="${variation.product_variation_id}" ${isSelected}>
                                        ${variation.variation_name}
                                    </option>`
                                );
                            });
                        } else {
                            variationSelect.append('<option value="">No variations available</option>');
                        }

                        // Update the "Proceed to Checkout" link with the cart ID
                        $('#checkoutLink').attr('href', `/checkout?cart_id=${response.cart_id}`);

                        // Show the modal
                        $('#successModal').modal('show');

                        updateCartCount();
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON ? xhr.responseJSON.error : 'An unexpected error occurred.');
                    alert(xhr.responseJSON ? xhr.responseJSON.error : 'An unexpected error occurred.');
                }
            });
        });
    </script>
    {{-- Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');

            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                const baseUrl = "{{ url('/') }}"

                if (query.length > 0) {
                    fetch(`/search/suggestions?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            searchSuggestions.innerHTML = '';

                            //"Best result" label at the top
                            const bestResultLabel = document.createElement('div');
                            bestResultLabel.className = 'list-group-item bg-light text-dark font-weight-bold';
                            bestResultLabel.style.fontSize = '14px';
                            bestResultLabel.style.color = '#228b22';
                            bestResultLabel.textContent = `Result (${data.length})`;

                            //Divider below the label
                            const divider = document.createElement('hr');
                            divider.style.margin = '0';
                            divider.style.border = '0.5px solid #ccc';

                            searchSuggestions.appendChild(bestResultLabel);
                            searchSuggestions.appendChild(divider);

                            if (data.length > 0) {
                                data.forEach(product => {
                                    const suggestionItem = document.createElement('a');
                                    suggestionItem.href = `${baseUrl}/merchant/product/${product.product_id}`;
                                    suggestionItem.className = 'list-group-item list-group-item-action d-flex align-items-center';

                                    // Check if image URL exists and create an image element
                                    if (product.image_url) {
                                        const productImage = document.createElement('img');
                                        productImage.src = product.image_url; // Use the provided image URL from the backend
                                        productImage.alt = product.product_name;
                                        productImage.style.width = '40px';
                                        productImage.style.height = '40px';
                                        productImage.style.objectFit = 'cover';
                                        productImage.style.marginRight = '10px';
                                        productImage.className = 'rounded';

                                        // Append the image to the suggestion item
                                        suggestionItem.appendChild(productImage);
                                    }

                                    // Create a span for the product name
                                    const productName = document.createElement('span');
                                    productName.textContent = product.product_name;
                                    productName.className = 'flex-grow-1';

                                    // Append the product name to the suggestion item
                                    suggestionItem.appendChild(productName);

                                    // Append the suggestion item to the search suggestions container
                                    searchSuggestions.appendChild(suggestionItem);
                                });
                                searchSuggestions.style.display = 'block';
                            } else {
                                // Show "No products found" message when there are no results
                                const noResultsItem = document.createElement('p');
                                noResultsItem.className = 'list-group-item text-center text-muted';
                                noResultsItem.style.fontSize = '14px';
                                noResultsItem.textContent = 'No products found.';

                                // Append the "no results" message to the search suggestions container
                                searchSuggestions.appendChild(noResultsItem);
                                searchSuggestions.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching search suggestions:', error);
                            searchSuggestions.style.display = 'none';
                        });
                } else {
                    searchSuggestions.style.display = 'none';
                }
            });

            // Hide suggestions when clicking outside the search box
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.style.display = 'none';
                }
            });
        });

    </script>
    {{-- Order Popover --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderIcon = document.getElementById('orderIcon');
            let isHoveringOrderPopover = false;
            let isHoveringIcon = false;

            // Initialize Bootstrap popover for orders
            const orderPopover = new bootstrap.Popover(orderIcon, {
                trigger: 'manual',
                placement: 'bottom',
                html: true,
                customClass: 'orderpover',

                content: function () {
                    // Placeholder content to be replaced with AJAX response
                    return `
                        <div class="mt-3 mb-3 text-center" id="popover-orders-content">
                            <div class="spinner-border text-custom spinner-medium" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`;
                }
            });

            // Function to fetch and update orders data in the popover
            function updateOrdersPopover() {
                fetch('/orders-tooltip')
                    .then(response => response.json())
                    .then(data => {
                        let content = '';

                        if (!data.orders || data.orders.length === 0) {
                            content = '<p class="text-center">No pending orders found.</p>';
                        } else {
                            content += `<div id="orders-tooltip-container" class="container p-3"
                                        style="max-height: 350px; overflow-y: auto; overflow-x: hidden; font-family: Arial, sans-serif;">`;

                            data.orders.forEach(function (order) {
                                if (order.order_status === 'pending') { // Only show pending orders
                                    content += `
                                        <div class="mb-3 card">
                                            <div class="card-body">
                                                <div class="row align-items-start gx-3">
                                                    <div class="col-auto">
                                                        <img id="orderProductImage"
                                                            src="${order.orderItems[0].product_img_path ? '/storage/' + order.orderItems[0].product_img_path : 'https://via.placeholder.com/60'}"
                                                            alt="Product Image"
                                                            class="border img-fluid"
                                                            loading="lazy"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    </div>
                                                    <div class="col">
                                                        <p id="orderID" class="mt-2 mb-0 text-start"><strong>Order ID:</strong> ${order.order_id}</p>
                                                        <p id="customerUsername" class="mb-0 text-start"><strong>Order by:</strong> ${order.customer}</p>
                                                    </div>
                                                    <div class="col-auto text-end">
                                                        <p id="orderQuantity" class="mt-2 mb-0 text-start"><strong>Qty:</strong> ${order.total_quantity}</p>
                                                        <p id="orderTotal" class="mb-0"><strong>Total:</strong> ₱${order.total_amount}</p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 row align-items-center">
                                                    <div class="col">
                                                        <p id="orderProductName" class="mb-0 text-start"><strong>Product Name:</strong> ${order.orderItems[0].product_name}</p>
                                                        <div class="d-flex align-items-center">
                                                            <p id="paymentMethod" class="mb-0 me-2"><strong>Mode of Payment:</strong> ${order.payment_method}</p>
                                                            <a id="viewOrderLink" href="/orders/${order.order_id}"
                                                            class="btn btn-outline-success btn-sm ms-auto">View Order</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                                }
                            });

                            content += `</div>`; // Close orders container
                        }

                        // Inject the content into the popover
                        const popoverElement = document.querySelector('.popover');
                        if (popoverElement) {
                            const popoverContent = popoverElement.querySelector('#popover-orders-content');
                            if (popoverContent) {
                                popoverContent.innerHTML = content;

                                // Add hover listeners to the popover
                                popoverElement.addEventListener('mouseenter', function () {
                                    isHoveringOrderPopover = true;
                                });

                                popoverElement.addEventListener('mouseleave', function () {
                                    isHoveringOrderPopover = false;
                                    hideOrderPopoverWithDelay();
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching orders:', error);
                        const popoverContent = document.getElementById('popover-orders-content');
                        if (popoverContent) {
                            popoverContent.innerHTML = '<p>Error loading orders data.</p>';
                        }
                    });
            }

            // Show order popover and fetch data when hovering over the icon
            orderIcon.addEventListener('mouseenter', function () {
                isHoveringIcon = true;
                updateOrdersPopover(); // Fetch and update the orders data
                orderPopover.show();
            });

            // Handle mouse leave on the icon
            orderIcon.addEventListener('mouseleave', function () {
                isHoveringIcon = false;
                hideOrderPopoverWithDelay();
            });

            // Function to hide the order popover with delay
            function hideOrderPopoverWithDelay() {
                setTimeout(() => {
                    if (!isHoveringOrderPopover && !isHoveringIcon) {
                        orderPopover.hide();
                    }
                }, 200); // Delay to allow mouse transition
            }

            // Close the order popover when clicking outside of it
            document.addEventListener('click', function (event) {
                if (!orderIcon.contains(event.target) && !document.querySelector('.popover:hover')) {
                    orderPopover.hide();
                }
            });
        });

    </script>
    {{-- Orders badge --}}
    <script>
        // Function to update the order count in the icon
        function updateOrderCount() {
            const orderCountBadge = document.getElementById('order-count'); // Check if the badge element exists
            const orderIcon = document.getElementById('orderIcon'); // Check if the icon element exists

            // Proceed only if both elements exist
            if (orderCountBadge && orderIcon) {
                $.ajax({
                    url: '/order-count',  // Ensure this URL matches your route for fetching order count
                    type: 'GET',
                    success: function(response) {
                        // Check if response and orderCount exist
                        if (response && response.orderCount !== undefined) {
                            const orderCount = parseInt(response.orderCount);

                            // Update the order count span if there are orders
                            if (orderCount > 0) {
                                $('#order-count').text(orderCount).show(); // Show the badge with the order count
                            } else {
                                $('#order-count').hide(); // Hide the badge if no orders
                            }
                        }
                    },
                    error: function() {
                        console.error('Failed to load order count');
                    }
                });
            } else {
                console.warn('Order icon or count badge is not present in the DOM.');
            }
        }

        // On page load, update the order count
        $(document).ready(function () {
            updateOrderCount(); // Update the order count on page load

            // Refresh order count periodically (e.g., every 30 seconds)
            setInterval(updateOrderCount, 30000);
        });

        // Ensure badge stays visible when hovering over the icon or popover
        document.addEventListener('DOMContentLoaded', function () {
            const orderIcon = document.getElementById('orderIcon');
            const orderCountBadge = document.getElementById('order-count');

            if (orderIcon && orderCountBadge) {
                orderIcon.addEventListener('mouseenter', function () {
                    if (parseInt(orderCountBadge.textContent) > 0) {
                        orderCountBadge.style.display = 'inline-block'; // Ensure badge is visible
                    }
                });

                orderIcon.addEventListener('mouseleave', function () {
                    if (parseInt(orderCountBadge.textContent) > 0) {
                        orderCountBadge.style.display = 'inline-block'; // Keep badge visible if there are orders
                    } else {
                        orderCountBadge.style.display = 'none'; // Hide badge if no orders
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.querySelector('.dropdown'); // Get the dropdown element
            const dropdownToggle = document.querySelector('.dropdown-toggle'); // Dropdown toggle (link or button)
            const dropdownMenu = document.querySelector('.dropdown-menu'); // Dropdown menu

            // Show dropdown on hover
            dropdown.addEventListener('mouseenter', function () {
                dropdownToggle.classList.add('show'); // Add Bootstrap's "show" class
                dropdownMenu.classList.add('show'); // Add Bootstrap's "show" class to display the dropdown
                dropdownMenu.style.display = 'block'; // Optional for extra control
            });

            // Hide dropdown when mouse leaves
            dropdown.addEventListener('mouseleave', function () {
                dropdownToggle.classList.remove('show'); // Remove the "show" class
                dropdownMenu.classList.remove('show'); // Remove the "show" class
                dropdownMenu.style.display = 'none'; // Optional for extra control
            });
             dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
        });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.querySelector('.dropdown'); // Get the dropdown element
            const customdropdownToggle = document.querySelector('.custom-dropdown-toggle'); // Dropdown toggle (link or button)
            const dropdownMenu = document.querySelector('.dropdown-menu'); // Dropdown menu

            // Show dropdown on hover
            dropdown.addEventListener('mouseenter', function () {
                customdropdownToggle.classList.add('show'); // Add Bootstrap's "show" class
                dropdownMenu.classList.add('show'); // Add Bootstrap's "show" class to display the dropdown
                dropdownMenu.style.display = 'block'; // Optional for extra control
            });

            // Hide dropdown when mouse leaves
            dropdown.addEventListener('mouseleave', function () {
                customdropdownToggle.classList.remove('show'); // Remove the "show" class
                dropdownMenu.classList.remove('show'); // Remove the "show" class
                dropdownMenu.style.display = 'none'; // Optional for extra control
            });
                dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
        });
        });

    </script>
    <script>
        $(document).on('click', '.favorite-button', function (e) {
            e.preventDefault();

            const productId = $(this).data('product-id');
            const button = $(this);

            $.ajax({
                url: `/favorites/toggle/${productId}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function (response) {
                    if (response.success) {
                        // Toggle button styles and title
                        if (button.hasClass('btn-outline-danger')) {
                            // Change to unfavorite state
                            button.removeClass('btn-outline-danger').addClass('btn-danger');
                            button.find('i').addClass('text-white');
                            button.attr('title', 'Unfavorite'); // Update title

                            // Show success modal
                            $('#favoriteModalMessage').text('Product has been added to favorites!');
                        } else {
                            // Change to favorite state
                            button.removeClass('btn-danger').addClass('btn-outline-danger');
                            button.find('i').removeClass('text-white');
                            button.attr('title', 'Favorite'); // Update title

                            // Show success modal
                            $('#favoriteModalMessage').text('Product has been removed from favorites!');
                        }

                        // Display the modal
                                  $('#favoriteSuccessModal').modal('show');
                        setTimeout(function () {
                            $('#favoriteSuccessModal').modal('hide');
                        }, 1000);
                    } else {

                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText); // Log full error response
                }
            });
        });
    </script>
    <script>
        function updateFavoritesCount() {
            $.ajax({
                url: '{{ route('favorites.count') }}',
                type: 'GET',
                success: function (response) {
                    $('#favorites-count').text(response.count); // Update the badge count
                },
                error: function (xhr) {
                    console.error('Failed to fetch favorites count:', xhr.responseText);
                }
            });
        }

        // Call the function on page load
        $(document).ready(function () {
            updateFavoritesCount();
        });
        // Optionally, call it after favorite toggle success
        $(document).on('click', '.favorite-button', function () {
            setTimeout(updateFavoritesCount, 500); // Delay to allow backend to update
        });

    </script>
 
    @yield('scripts')
</body>
</html>

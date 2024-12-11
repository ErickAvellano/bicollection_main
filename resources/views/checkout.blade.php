@extends('Components.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('styles')
<style>
    /* General Styles */
    body {
        background-color: #fafafa;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .section {
        margin-bottom: 24px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .section-title i {
        margin-right: 10px;
        color: #228b22;
    }

    .address-info, .product-item, .voucher-section, .payment-section, .summary-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .address-info p, .product-details p, .summary-item p {
        margin: 5px 0;
    }

    .change-link, .request-link, .select-link {
        color: #228b22;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .change-link:hover, .request-link:hover, .select-link:hover {
        color: #1e7e1e;
    }

    .product-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin-right: 15px;
        border-radius: 4px;
    }

    .product-details {
        flex-grow: 1;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #444;
    }

    .product-variation {
        font-size: 0.9rem;
        color: #888;
    }

    .product-price p {
        font-size: 1rem;
        text-align: right;
    }

    .voucher-section {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
    }

    .summary-section {
        border-top: 2px solid #ddd;
        padding-top: 15px;
        margin-top: 15px;
    }

    .summary-item, .summary-total {
        width: 100%;
        display: flex;
        justify-content: space-between;
        font-size: 1rem;
        padding: 5px 0;
    }

    .summary-total {
        font-weight: 700;
        font-size: 1.2rem;
        margin-top: 10px;
    }

    .total-amount {
        color: #e04a00;
    }

    .place-order-section {
        text-align: center;
        margin-top: 25px;
    }

    .place-order-btn {
        background-color: #228b22;
        color: #fff;
        padding: 5px 25px;
        border: none;
        border-radius: 20px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .place-order-btn:hover {
        background-color: #1e7e1e;
        transform: scale(1.05);
    }

    .breadcrumb a {
        text-decoration: none;
        font-size: 1rem;
        color: #666;
        transition: color 0.3s;
    }

    .breadcrumb a:hover {
        color: #228b22;
    }

    .breadcrumb .breadcrumb-item.active {
        font-weight: bold;
        color: #228b22;
    }
    .nav-pills{
        display: none;
    }
    .custom-icon{
        font-weight: bold;
        color: #228b22;
    }
    /* Custom Radio Button Style */
    .custom-radio .form-check-input {
        appearance: none; /* Remove default styling */
        -webkit-appearance: none; /* Remove default styling for WebKit browsers */
        -moz-appearance: none; /* Remove default styling for Mozilla browsers */
        width: 20px;
        height: 20px;
        border: 2px solid #228b22; /* Border color */
        border-radius: 50%;
        display: inline-block;
        position: relative;
        margin-right: 8px;
        cursor: pointer;
        outline: none;
        transition: background 0.3s, border-color 0.3s;
    }

    .custom-radio .form-check-input:checked {
        background-color: #228b22; /* Checked background color */
        border-color: #228b22; /* Border color when checked */
    }

    .custom-radio .form-check-input:checked::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 10px;
        height: 10px;
        background-color: white;
        border-radius: 50%;
    }

    .custom-radio .form-check-label {
        cursor: pointer;
        font-size: 0.95rem;
    }
    .custom-address-form {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .custom-form-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .custom-form-group {
        margin-bottom: 15px;
    }

    .custom-form-label {
        display: block;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .custom-form-input,
    .custom-form-select {
        width: 100%;
        padding: 10px;
        font-size: 0.95rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: none;
    }

    .custom-form-input:focus,
    .custom-form-select:focus {
        border-color: #228b22;
        outline: none;
    }

    .custom-form-select {
        appearance: none;
        background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="%23333" d="M7 7l5 5 5-5z"/></svg>') no-repeat right 10px center/12px;
        background-color: #fff;
    }
    .alert-warning {
        font-size: 0.9rem; /* Slightly smaller font size */
        background-color: #fff3cd; /* Soft yellow background */
        border-color: #ffecb5; /* Matching border */
    }
    .fa-triangle-exclamation {
        color: #856404; /* Dark yellow icon color */
    }

    .payment-option {
        position: relative;
    }

    .disabled-option {
        color: #6c757d;
        cursor: not-allowed;
        text-decoration: line-through;
        pointer-events: none; /* Make the button unclickable */
    }

    .fa-circle-info {
        color: #ffc107;
        margin-left: 5px;
        cursor: pointer;
    }

    .payment-option button {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .default-badge {
        display: inline-block;
        margin-top:-10px;
        margin-left: 5px;
        padding: 1px 6px;
        border: 1px solid #228b22;  /* Use the desired red color here */
        color: #228b22;  /* Use the same red color for text */
        font-size: 10px;
        border-radius: 4px;
        font-weight: bold;
    }
    button[disabled] {
        cursor: not-allowed;
        opacity: 0.5;
    }
    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }
    .btn-custom:hover {
        background-color: #1e7e1e; /* Custom hover background color */
        border-color: #1e7e1e; /* Custom hover border color */
    }
    .btn-custom:focus,
    .btn-custom:active {
        background-color: #228b22 !important; /* Ensure focus and active states match */
        border-color: #228b22 !important; /* Ensure border color matches */
        box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25); /* Adjust the box shadow */
    }

</style>
@endsection

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item active">Checkout</li>
    </ol>

    <div class="section">
        <h2 class="section-title mb-3"><i class="fas fa-map-marker-alt"></i> Shipping Details</h2>
        <div style="margin-left:1.7rem;">
            <div class="shipping-details d-flex justify-content-between align-items-center mb-2">
                <!-- Name Section -->
                <div class="name-section">
                    <p class="mb-1">
                        <strong>Name:</strong> <span style="font-weight: 600;">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                    </p>
                </div>
            </div>
            <input type="hidden" id="cartId" name="cart_id" value="{{ $cartId }}">
            <!-- Contact Number Section -->
            <div class="contact-number-section d-flex">
                <p class="mb-1" style="margin-right: 10px;">
                    <strong>Contact Number:</strong>  @if (!empty($customer->contact_number))
                    {{ $customer->contact_number }}
                @else
                    <span class="text-danger">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        Please add your contact number
                    </span>
                @endif
                </p>
                <a href="#" class="change-link text-success ms-2" data-bs-toggle="modal" data-bs-target="#changeContactModal" style="font-weight: bold; font-size: 14px;">
                    Change
                </a>
            </div>
            <!-- Shipping Address Section -->
            <div class="shipping-address-section d-flex">
                <p class="mb-1" style="margin-right: 10px;">
                    <strong>Shipping Address:</strong>
                    <i class="fa-solid fa-truck-fast custom-icon"></i>
                
                    @if ($defaultAddress)
                        {{ $defaultAddress->house_street ?? '' }}, {{ $defaultAddress->barangay ?? '' }},
                        {{ $defaultAddress->city ?? '' }}, {{ $defaultAddress->province ?? '' }},
                        {{ $defaultAddress->postalcode ?? '' }}, {{ $defaultAddress->region ?? '' }}
                        <span class="default-badge">Default</span>
                    @else
                        <span class="text-danger">Add or press 'Change' to change your shipping address</span>
                    @endif
                </p>
                <a href="#" class="change-link text-success ms-2" data-bs-toggle="modal" data-bs-target="#selectAddressModal" style="font-weight: bold; font-size: 14px;">
                    Change
                </a>

            </div>
        </div>
    </div>
    <form id="addressForm">
        <input type="hidden" id="selectedAddressOption" name="address_option" value="default">
        <input type="hidden" id="hiddenCompleteAddress" name="complete_address">
    </form>

    <!-- Products Ordered Section -->
    <div class="section">
        <h2 class="section-title">Products Ordered</h2>
        @foreach ($cartItems as $cartItem)
            <div class="product-item" style="display: flex; margin-bottom: 20px; margin-left:1.7rem;">
                <!-- Product Image -->

                <img src="{{ asset('storage/' . $cartItem->product->images->first()->product_img_path1 ?? 'https://via.placeholder.com/80') }}"
                    alt="{{ $cartItem->product->product_name }}"
                    class="product-image"
                    style="width: 80px; height: 80px; object-fit: cover; margin-right: 20px;">

                <!-- Product Details -->
                <div class="product-details">
                    <p class="product-name" data-product-id="{{ $cartItem->product->product_id }}">{{ $cartItem->product->product_name }}</p>
                    <p class="product-variation" data-variation-id="{{ $cartItem->product->variations->first()->product_variation_id ?? '' }}">
                        Variation:
                        {{ $cartItem->product->variations->first()->variation_name ?? 'Default' }}
                    </p>
                </div>

                <!-- Product Price -->
                <div class="product-price" style="margin-left: auto; text-align: right;">
                    <p>₱{{ number_format($cartItem->product->variations->first()->price ?? $cartItem->product->price, 2) }}</p>
                    <p>Qty: {{ $cartItem->quantity }}</p>
                    <p>₱{{ number_format($cartItem->quantity * ($cartItem->product->variations->first()->price ?? $cartItem->product->price), 2) }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="section voucher-section">
        <!-- Shop Voucher Section -->
        <h2 class="section-title ">Platform Voucher</h2>
        <a href="#" class="select-link" data-bs-toggle="modal" data-bs-target="#selectVoucherModal">Select Voucher</a>
    </div>
    <div class="section payment-section">
        <!-- Payment Option Section -->
        <h2 class="section-title">Payment Options</h2>
        <div class="selected-payment-method d-flex align-items-center" id="selectedPaymentMethodDisplay">
            <span id="selectedPaymentMethodText" style="margin-right:4rem;"></span>
            <a href="#" class="select-link ms-2" data-bs-toggle="modal" data-bs-target="#selectPaymentModal">CHANGE</a>
        </div>
        <input type="hidden" id="paymentMethod" name="payment_method" required>
        <input type="hidden" id="paymentMethodId" name="payment_method_id">
    </div>
    <div class="section summary-section">
        <!-- Merchandise Subtotal -->
        <div class="summary-item">
            <p>Merchandise Subtotal</p>
            <p>₱{{ number_format($merchandiseSubtotal, 2) }}</p>
        </div>

        <!-- Shipping Subtotal -->
        <div class="summary-item">
            <p>Shipping Subtotal</p>
            <p>₱{{ number_format($shippingSubtotal, 2) }}</p>
        </div>

        <!-- Total Payment -->
        <div class="summary-total">
            <p>Total Payment:</p>
            <p class="total-amount">₱{{ number_format($totalPayment, 2) }}</p>
        </div>
    </div>

    <!-- Place Order Button -->
    <div class="place-order-section d-flex justify-content-end">
        <button class="place-order-btn mb-5" id="placeOrderBtn">Place Order</button>
    </div>
</div>

<!-- Order Summary Modal -->
<div class="modal fade" id="orderSummaryModal" tabindex="-1" aria-labelledby="orderSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderSummaryModalLabel">Order Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be filled here -->
                <div id="orderSummaryContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="proceedToPaymentBtn">Proceed to Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for New Address and Payment -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Add Address & Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="newAddressForm" method="POST" action="">
                    <!-- Address Fields -->
                    <div class="form-group mb-2">
                        <label for="house_street">Street Address:</label>
                        <input type="text" class="form-control" id="house_street" name="house_street" placeholder="House No. / Street" required>
                    </div>
                    <!-- Add other address fields similar to above -->
                    <div class="form-group mb-2">
                        <label for="contact_number">Phone Number:</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Phone Number" required>
                    </div>

                    <!-- Payment Fields -->
                    <div class="form-group mb-2">
                        <label for="newPaymentMethod">Payment Method:</label>
                        <select class="form-select" id="newPaymentMethod" name="paymentMethod" required>
                            <option value="" selected disabled>-- Select Payment Method --</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>

                    <!-- GCash Number Input -->
                    <div class="form-group mt-2" id="newGcashNumberField" style="display: none;">
                        <label for="newGcashNumber">GCash Number:</label>
                        <input type="text" class="form-control" id="newGcashNumber" name="gcashNumber" placeholder="Enter GCash Number">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Selecting Address -->
<div class="modal fade" id="selectAddressModal" tabindex="-1" aria-labelledby="selectAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectAddressModalLabel">Select Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="selectAddressForm">
                    @csrf
                    <!-- Option for Registered Address -->
                    <div class="form-check mb-3 custom-radio">
                        <input 
                            class="form-check-input" 
                            type="radio" 
                            name="addressOption" 
                            id="defaultAddress" 
                            value="default" 
                            {{ !$defaultAddress ? 'disabled' : 'checked' }}>

                        <label class="form-check-label" for="defaultAddress">
                            Use Default Address:
                            @if ($defaultAddress)
                                <p><i class="fa-solid fa-location-dot custom-icon"></i>
                                    {{ $defaultAddress->house_street ?? '' }}, {{ $defaultAddress->barangay ?? '' }},
                                    {{ $defaultAddress->city ?? '' }}, {{ $defaultAddress->province ?? '' }},
                                    {{ $defaultAddress->postalcode ?? '' }}, {{ $defaultAddress->region ?? '' }}
                                </p>
                            @else
                                <p class="text-danger">No default address is set</p>
                            @endif
                        </label>
                    </div>

                    <!-- Option for Custom Address -->
                    <div class="form-check mb-3 custom-radio">
                        <input class="form-check-input" type="radio" name="addressOption" id="customAddress" value="custom">
                        <label class="form-check-label" for="customAddress">
                            Use Custom Address
                        </label>
                    </div>
                </form>

                <div class="custom-address-form" id="addressesContent" style="display: none;">
                    <h5 class="custom-form-title mb-3">New Address</h5>
                    <form id="addressForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                        <!-- Street Address Field -->
                        <div class="custom-form-group mb-3">
                            <label for="house_street" class="custom-form-label">Street Address:</label>
                            <input type="text" class="custom-form-input" id="house_street_input" name="house_street_input"
                                value="" placeholder="House No. / Street" required>
                        </div>

                        <!-- Region Field -->
                        <div class="custom-form-group mb-3">
                            <label for="region" class="custom-form-label">Region:</label>
                            <select class="custom-form-select" id="region" name="region" required>
                                <option selected disabled>-- Select Region --</option>
                            </select>
                            <input type="hidden" id="selectedRegion" name="selectedRegion" value="{{ $address->region ?? '' }}">
                        </div>

                        <!-- Province Field -->
                        <div class="custom-form-group mb-3">
                            <label for="province" class="custom-form-label">Province:</label>
                            <select class="custom-form-select" id="province" name="province" required>
                                <option selected disabled>-- Select Province --</option>
                            </select>
                            <input type="hidden" id="selectedProvince" name="selectedProvince" value="{{ $address->province ?? '' }}">
                        </div>

                        <!-- City/Municipality Field -->
                        <div class="custom-form-group mb-3">
                            <label for="city" class="custom-form-label">City/Municipality:</label>
                            <select class="custom-form-select" id="city" name="city" required>
                                <option selected disabled>-- Select City/Municipality --</option>
                            </select>
                            <input type="hidden" id="selectedCity" name="selectedCity" value="{{ $address->city ?? '' }}">
                        </div>

                        <!-- Barangay Field -->
                        <div class="custom-form-group mb-3">
                            <label for="barangay" class="custom-form-label">Barangay:</label>
                            <select class="custom-form-select" id="barangay" name="barangay" required>
                                <option selected disabled>-- Select Barangay --</option>
                            </select>
                            <input type="hidden" id="selectedBarangay" name="selectedBarangay" value="{{ $address->barangay ?? '' }}">
                        </div>

                        <!-- Postal Code Field -->
                        <div class="custom-form-group mb-3">
                            <label for="postalcode" class="custom-form-label">Postal Code:</label>
                            <input type="text" class="custom-form-input" id="postalcode" name="postalcode"
                                value="" placeholder="Enter postal code" required>
                        </div>
                    </form>
                    <!-- Message or Error Display -->
                    <div id="message" class="mt-3"></div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom" id="confirmAddressBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Change Contact Number Modal -->
<div class="modal fade" id="changeContactModal" tabindex="-1" aria-labelledby="changeContactModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="changeContactModalLabel">Change Contact Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="changeContactForm">
                    @csrf
                    <div class="mb-3">
                        <label for="newContactNumber" class="form-label">New Contact Number:</label>
                        <input type="text" class="form-control" id="newContactNumber" name="new_contact_number" placeholder="Enter new contact number" required>
                    </div>
                    <small class="form-text text-muted mt-2">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Updating this will also update your contact number.
                    </small>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" id="saveContactNumberBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Selecting Voucher -->
<div class="modal fade" id="selectVoucherModal" tabindex="-1" aria-labelledby="selectVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="selectVoucherModalLabel">Select Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Placeholder for voucher list -->
                <div class="voucher-list">
                    <!-- Example Vouchers -->
                    <div class="voucher-item mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <span>Voucher 1: 10% Off</span>
                            <button class="btn btn-success btn-sm select-voucher-btn" data-voucher="Voucher 1">Select</button>
                        </div>
                    </div>
                    <div class="voucher-item mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <span>Voucher 2: Free Shipping</span>
                            <button class="btn btn-success btn-sm select-voucher-btn" data-voucher="Voucher 2">Select</button>
                        </div>
                    </div>
                    <!-- Add more voucher items as needed -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Payment Modal -->
<div class="modal fade" id="selectPaymentModal" tabindex="-1" role="dialog" aria-labelledby="selectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="selectPaymentModalLabel">Select Payment Option</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <!-- COD Option -->
                <div class="payment-option mb-2" id="codOption">
                    <button class="btn btn-outline-success w-100"
                            id="selectCodButton"
                            @if(!$merchantSupportsCOD) disabled @endif>
                        Cash on Delivery (COD)
                    </button>
                </div>

                <!-- GCash Option -->
                <div class="payment-option mt-2" id="gcashOption">
                    <button class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center"
                            id="selectGcashButton"
                            @if(!$merchantSupportsGCash) disabled @endif>
                        <img src="{{ asset('images/assets/gcash_logo.png') }}" alt="GCash Logo" class="me-2" style="width: 24px; height: 24px;">
                        GCash
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Order Placed Successfully!</h5>
        </div>
        <div class="modal-body">
            <p>Please pay upon delivery. Thank you for your order! We will notify the merchant to prepare the product, and you will receive an email once the order is accepted and completed.</p>
        </div>
        <div class="modal-footer">
          <button type="button" id="redirectToCart" class="btn btn-custom">Go to Cart</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="loadingSpinner" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@include('Components.status-modal')

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultAddressRadio = document.getElementById('defaultAddress');
        const customAddressRadio = document.getElementById('customAddress');
        const addressesContent = document.getElementById('addressesContent');
        const confirmAddressBtn = document.getElementById('confirmAddressBtn');

        // Hidden form inputs
        const selectedAddressOptionInput = document.getElementById('selectedAddressOption');
        const hiddenCompleteAddressInput = document.getElementById('hiddenCompleteAddress'); // Single hidden input for complete custom address

        // Handle default address selection (registered address)
        if (defaultAddressRadio) {
            defaultAddressRadio.addEventListener('change', function() {
                if (defaultAddressRadio.checked) {
                    addressesContent.style.display = 'none';
                    selectedAddressOptionInput.value = 'default';
                    hiddenCompleteAddressInput.value = `
                        {{ $defaultAddress->house_street ?? '' }},
                        {{ $defaultAddress->barangay ?? '' }},
                        {{ $defaultAddress->city ?? '' }},
                        {{ $defaultAddress->province ?? '' }},
                        {{ $defaultAddress->postalcode ?? '' }},
                        {{ $defaultAddress->region ?? '' }}
                    `.trim();
                    updateAddressUI({ addressOption: 'default' });
                    console.log('Selected: Default Registered Address');
                }
            });
        }

        // Handle custom address selection
        if (customAddressRadio) {
            customAddressRadio.addEventListener('change', function() {
                if (customAddressRadio.checked) {
                    addressesContent.style.display = 'block';
                    selectedAddressOptionInput.value = 'custom';
                    console.log('Selected: Custom Address');
                }
            });
        }

        // Handle confirm button click
        if (confirmAddressBtn) {
            confirmAddressBtn.addEventListener('click', function() {
                if (defaultAddressRadio && defaultAddressRadio.checked) {
                    // Set hidden form values for default address
                    selectedAddressOptionInput.value = 'default';
                    hiddenCompleteAddressInput.value = `
                        {{ $defaultAddress->house_street ?? '' }},
                        {{ $defaultAddress->barangay ?? '' }},
                        {{ $defaultAddress->city ?? '' }},
                        {{ $defaultAddress->province ?? '' }},
                        {{ $defaultAddress->postalcode ?? '' }},
                        {{ $defaultAddress->region ?? '' }}
                    `.trim(); // Clear custom address input
                    updateAddressUI({ addressOption: 'default' });
                } else if (customAddressRadio && customAddressRadio.checked) {
                    // Get custom address input values
                    const houseStreet = document.getElementById('house_street_input')?.value.trim();
                    const barangayElement = document.getElementById('barangay');
                    const cityElement = document.getElementById('city');
                    const regionElement = document.getElementById('region');
                    const provinceElement = document.getElementById('province');
                    const postalcode = document.getElementById('postalcode')?.value.trim();

                    // Get selected text for each dropdown
                    const barangayText = barangayElement?.options[barangayElement.selectedIndex]?.text;
                    const cityText = cityElement?.options[cityElement.selectedIndex]?.text;
                    const regionText = regionElement?.options[regionElement.selectedIndex]?.text;
                    const provinceText = provinceElement?.options[provinceElement.selectedIndex]?.text;

                    // Validate fields
                    if (!houseStreet || !barangayText || !cityText || !regionText || !provinceText || !postalcode) {
                        alert('Please fill in all the required fields for the custom address.');
                        return;
                    }

                    // Construct the complete address string
                    const completeAddress = `${houseStreet}, ${barangayText}, ${cityText}, ${provinceText}, ${postalcode}, ${regionText}`;

                    // Set hidden form values for custom address
                    selectedAddressOptionInput.value = 'custom';
                    hiddenCompleteAddressInput.value = completeAddress; // Set the complete custom address in the hidden input

                    // Call update UI for display
                    updateAddressUI({
                        addressOption: 'custom',
                        completeAddress: completeAddress
                    });
                }

                // Close the modal
                $('#selectAddressModal').modal('hide');
            });
        }

        // Function to update the address display in the UI
        function updateAddressUI(addressData) {
            const addressDisplay = document.querySelector('.shipping-address-section');

            if (addressDisplay) {
                if (addressData.addressOption === 'default') {
                    // Display the default registered address
                    addressDisplay.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
                            <div class="d-flex align-items-center">
                                <span style="font-weight: bold;">Shipping Address:</span>
                                <i class="fa-solid fa-truck-fast mx-2 custom-icon" style="color: #4caf50;"></i>
                                <span>{{ $defaultAddress->house_street ?? '' }}, {{ $defaultAddress->barangay ?? '' }},
                                    {{ $defaultAddress->city ?? '' }}, {{ $defaultAddress->province ?? '' }},
                                    {{ $defaultAddress->postalcode ?? '' }}, {{ $defaultAddress->region ?? '' }}</span>
                                <span class="default-badge">Default</span>
                            </div>
                            <a href="#" class="change-link text-success ms-3" data-bs-toggle="modal" data-bs-target="#selectAddressModal" style="font-weight: bold; font-size: 14px;">
                                Change
                            </a>
                        </div>
                    `;
                } else {
                    // Display the custom address
                    addressDisplay.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
                            <div class="d-flex align-items-center">
                                <span style="font-weight: bold;">Shipping Address:</span>
                                <i class="fa-solid fa-truck-fast mx-2 custom-icon" style="color: #4caf50;"></i>
                                <span>${addressData.completeAddress}</span>
                                <span class="default-badge">Custom</span>
                            </div>
                            <a href="#" class="change-link text-success ms-3" data-bs-toggle="modal" data-bs-target="#selectAddressModal" style="font-weight: bold; font-size: 14px;">
                                Change
                            </a>
                        </div>
                    `;
                }
            }
        }
    });


</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formFields = document.querySelectorAll('.custom-form-input, .custom-form-select');
        const addressesContent = document.getElementById('addressesContent');

        // Function to populate regions dropdown
        populateRegions();

        // Event listeners for dropdown changes
        document.getElementById('region').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].textContent;
            document.getElementById('selectedRegion').value = selectedText;
            updateProvinces();
        });

        document.getElementById('province').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].textContent;
            document.getElementById('selectedProvince').value = selectedText;
            updateCities();
        });

        document.getElementById('city').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].textContent;
            document.getElementById('selectedCity').value = selectedText;
            updateBarangays();
        });

        document.getElementById('barangay').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].textContent;
            document.getElementById('selectedBarangay').value = selectedText;
        });

        // Populate regions dropdown
        function populateRegions() {
            fetch('https://psgc.gitlab.io/api/regions.json')
                .then(response => response.json())
                .then(data => {
                    const regionSelect = document.getElementById('region');
                    regionSelect.innerHTML = '<option selected disabled>-- Select Region --</option>';

                    data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.code;
                        option.textContent = region.name;
                        regionSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching regions:', error));
        }

        // Update provinces dropdown based on selected region
        function updateProvinces() {
            const regionCode = document.getElementById('region').value;
            fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces.json`)
                .then(response => response.json())
                .then(data => {
                    const provinceSelect = document.getElementById('province');
                    provinceSelect.innerHTML = '<option selected disabled>-- Select Province --</option>';

                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.code;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching provinces:', error));
        }

        function updateCities() {
        const provinceCode = document.getElementById('province').value;
        const selectedCity = document.getElementById('selectedCity').value;

        const fetchCities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities.json`);
        const fetchMunicipalities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/municipalities.json`);

        Promise.all([fetchCities, fetchMunicipalities])
            .then(responses => Promise.all(responses.map(response => response.json())))
            .then(dataArrays => {
                const [cities, municipalities] = dataArrays;
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option selected disabled>-- Select City/Municipality --</option>';

                // Combine cities and municipalities into one array
                const combinedData = [
                    ...cities.map(city => ({ ...city, isCity: true })),
                    ...municipalities.map(municipality => ({ ...municipality, isCity: false }))
                ];

                // Create options for each city/municipality
                combinedData.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.code;
                    option.textContent = location.name;
                    option.dataset.isCity = location.isCity; // Set the isCity attribute
                    citySelect.appendChild(option);
                });

                // Pre-select city/municipality if data exists
                if (selectedCity) {
                    const prefilledOption = Array.from(citySelect.options).find(option => option.textContent === selectedCity);
                    if (prefilledOption) {
                        citySelect.value = prefilledOption.value;
                        updateBarangays(); // Trigger barangay update
                    }
                }
            })
            .catch(error => console.error('Error fetching cities/municipalities:', error));
    }

    function updateBarangays() {
        const citySelect = document.getElementById('city');
        const cityCode = citySelect.value;
        const selectedBarangay = document.getElementById('selectedBarangay').value;
        const isCity = citySelect.selectedOptions[0].dataset.isCity === 'true'; // Correctly identify if it's a city

        const endpoint = isCity
            ? `https://psgc.gitlab.io/api/cities/${cityCode}/barangays.json`
            : `https://psgc.gitlab.io/api/municipalities/${cityCode}/barangays.json`;

        fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const barangaySelect = document.getElementById('barangay');
                barangaySelect.innerHTML = '<option selected disabled>-- Select Barangay --</option>';

                if (data.length === 0) {
                    const option = document.createElement('option');
                    option.textContent = 'No barangays available';
                    option.disabled = true;
                    barangaySelect.appendChild(option);
                } else {
                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.code;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                }

                // Pre-select barangay if data exists
                if (selectedBarangay) {
                    const prefilledOption = Array.from(barangaySelect.options).find(option => option.textContent === selectedBarangay);
                    if (prefilledOption) {
                        barangaySelect.value = prefilledOption.value;
                    }
                }

                barangaySelect.addEventListener('change', function() {
                    const selectedText = this.options[this.selectedIndex].textContent;
                    document.getElementById('selectedBarangay').value = selectedText;
                });
            })
            .catch(error => {
                console.error('Error fetching barangays:', error);
            });
    }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all voucher select buttons
        const voucherButtons = document.querySelectorAll('.select-voucher-btn');

        // Add click event listeners to each voucher button
        voucherButtons.forEach(button => {
            button.addEventListener('click', function() {
                const selectedVoucher = this.getAttribute('data-voucher');

                // Display an alert or update the UI with the selected voucher
                alert(`Voucher "${selectedVoucher}" selected!`);

                // You can add more logic here to update the UI or send the voucher to the backend

                // Close the modal
                $('#selectVoucherModal').modal('hide');
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveContactNumberBtn = document.getElementById('saveContactNumberBtn');
        const newContactNumberInput = document.getElementById('newContactNumber');
        let contactNumber = null; // Variable to keep track of the contact number

        // Handle the click event for saving the contact number
        saveContactNumberBtn.addEventListener('click', function () {
            const newContactNumber = newContactNumberInput.value.trim();

            // Validate the new contact number
            if (!newContactNumber || !/^\d+$/.test(newContactNumber)) {
                alert('Please enter a valid contact number.');
                return;
            }

            // Show saving state
            saveContactNumberBtn.textContent = "Saving...";
            saveContactNumberBtn.disabled = true;

            // Send the new contact number to the server
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/update-contact-number', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    contact_number: newContactNumber
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error(`Request failed with status ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update the UI with the new contact number
                        updateContactUI(newContactNumber);

                        // Update the contactNumber variable with the new value
                        contactNumber = newContactNumber;

                        // Close the modal
                        $('#changeContactModal').modal('hide');

                        // Show success modal
                        $('#statusModalIcon').removeClass('fa-xmark').addClass('fa-solid fa-circle-check check-icon'); // Success icon
                        $('#statusModalMessage').text('Contact number updated successfully!');
                        $('#statusModal').modal('show');

                        setTimeout(() => {
                            $('#statusModal').modal('hide');
                        }, 1000);
                    } else {
                        // Show error modal
                        $('#statusModalIcon').removeClass('fa-circle-check').addClass('fa-solid fa-circle-xmark'); // Error icon
                        $('#statusModalMessage').text('Failed to update the contact number. Please try again.');
                        $('#statusModal').modal('show');

                        setTimeout(() => {
                            $('#statusModal').modal('hide');
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error("Error updating contact number:", error);
                    alert("An error occurred while updating the contact number.");
                })
                .finally(() => {
                    // Reset the button state
                    saveContactNumberBtn.textContent = "Save";
                    saveContactNumberBtn.disabled = false;
                });
        });

        // Function to update the contact number display in the UI
        function updateContactUI(newContactNumber) {
            const contactDisplay = document.querySelector('.contact-number-section');

            // Update the inner HTML of the contact number container
            contactDisplay.innerHTML = `
                <div class="d-flex align-items-center">
                    <span style="font-weight: bold;">Contact Number:</span>
                    <span class="mx-2" style="margin-right: 10px;">${newContactNumber}</span>
                </div>
                <a href="#" class="change-link text-success ms-3" data-bs-toggle="modal" data-bs-target="#changeContactModal" style="font-weight: bold; font-size: 14px;">
                    Change
                </a>
            `;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const proceedToPaymentBtn = document.getElementById('proceedToPaymentBtn');
        const paymentMethodInput = document.getElementById('paymentMethod');
        const paymentMethodIdInput = document.getElementById('paymentMethodId');
        const cartIdInput = document.getElementById('cartId');
        const loadingSpinner = document.getElementById('loadingSpinner'); // Spinner element

        let orderItems = []; // Define orderItems globally

        // Function to show the spinner
        function showSpinner() {
            if (loadingSpinner) {
                loadingSpinner.style.display = 'flex';
            }
        }

        // Function to hide the spinner
        function hideSpinner() {
            if (loadingSpinner) {
                loadingSpinner.style.display = 'none';
            }
        }

        // Initially hide the spinner on page load
        hideSpinner();

        function validateShippingAddress() {
            const shippingAddress = getFinalShippingAddress();
            if (!shippingAddress) {
                alert("Please provide a valid shipping address before placing the order.");
                return false;
            }
            return true;
        }

        // Handle "Place Order" button click
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function() {
                const shippingAddress = getFinalShippingAddress();

                if (!validateShippingAddress()) {
                    return; // Stop further execution if the address is invalid
                }
                // Get contact number
                let contactNumber = document.querySelector('.contact-number-section p');
                if (contactNumber) {
                    contactNumber = contactNumber.innerText.split(': ')[1];
                }

                const selectedPaymentMethod = paymentMethodInput ? paymentMethodInput.value : null;

                let paymentMethodDisplay;
                if (selectedPaymentMethod === 'GCash') {
                    paymentMethodDisplay = `<img src="{{ asset('images/assets/gcash_logo.png') }}" alt="GCash Logo" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 8px;"> GCash`;
                } else if (selectedPaymentMethod === 'COD') {
                    paymentMethodDisplay = `Cash on Delivery (COD) <i class="fa-solid fa-truck-fast" style="font-size: 17px; vertical-align: middle; margin-right: 8px;"></i>`;
                } else {
                    alert('Please select a payment method.');
                    return;
                }

                // Clear and populate the orderItems array
                orderItems = [];
                document.querySelectorAll('.product-item').forEach(item => {
                    let productId = item.querySelector('.product-name')?.getAttribute('data-product-id');
                    let productName = item.querySelector('.product-name')?.innerText;
                    let variationId = item.querySelector('.product-variation')?.getAttribute('data-variation-id');
                    let productVariation = item.querySelector('.product-variation')?.innerText.split(': ')[1];
                    let productPrice = parseFloat(item.querySelector('.product-price p:first-child')?.innerText.replace('₱', '').replace(',', ''));
                    let quantity = parseInt(item.querySelector('.product-price p:nth-child(2)')?.innerText.split(': ')[1]);
                    let productImg = item.querySelector('.product-image')?.getAttribute('src');

                    if (productId && productName && productPrice && quantity) {
                        orderItems.push({
                            product_id: productId,
                            product_name: productName,
                            variation_id: variationId,
                            variation_name: productVariation,
                            product_price: productPrice,
                            quantity: quantity,
                            subtotal: productPrice * quantity,
                            image: productImg
                        });
                    }
                });

                const cartId = cartIdInput ? cartIdInput.value : null;
                if (!cartId) {
                    alert('Cart ID is missing.');
                    return;
                }

                let totalPaymentElement = document.querySelector('.summary-total p.total-amount');
                let totalPayment = totalPaymentElement ? parseFloat(totalPaymentElement.innerText.replace('₱', '').replace(',', '')) : 0;

                let orderData = {
                    merchant_id: "{{ $merchantId }}",
                    merchant_mop_id: paymentMethodIdInput ? paymentMethodIdInput.value : null,
                    cart_id: cartId,
                    shipping_address: shippingAddress,
                    contact_number: contactNumber,
                    total_amount: totalPayment,
                    order_items: orderItems
                };

                document.getElementById('orderSummaryContent').innerHTML = generateOrderSummaryHTML(orderData, paymentMethodDisplay);

                let orderSummaryModal = new bootstrap.Modal(document.getElementById('orderSummaryModal'));
                orderSummaryModal.show();
            });
        }

        // Handle "Proceed to Payment" button click
                if (proceedToPaymentBtn) {
            proceedToPaymentBtn.addEventListener('click', function() {
                const shippingAddress = getFinalShippingAddress();
                const cartId = cartIdInput ? cartIdInput.value : null;

                const orderData = {
                    merchant_id: "{{ $merchantId }}",
                    merchant_mop_id: paymentMethodIdInput ? paymentMethodIdInput.value : null,
                    cart_id: cartId,
                    shipping_address: shippingAddress,
                    contact_number: document.querySelector('.contact-number-section p')?.innerText.split(': ')[1],
                    total_amount: parseFloat(document.querySelector('.summary-total p.total-amount')?.innerText.replace('₱', '').replace(',', '')),
                    order_items: orderItems,
                    payment_method: paymentMethodInput ? paymentMethodInput.value : null,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                // Show spinner
                showSpinner();

                fetch("{{ route('place-order') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => {
                    if (!response.ok) throw new Error(`Request failed with status ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    // Hide spinner
                    hideSpinner();

                    if (data.success) {

                        if (orderData.payment_method === 'GCash' && data.redirect_to) {
                            // Redirect to the GCash payment page
                            window.location.href = data.redirect_to;
                        } else {
                            const orderSummaryModalElement = document.getElementById('orderSummaryModal');
                            const orderSummaryModal = bootstrap.Modal.getInstance(orderSummaryModalElement) || new bootstrap.Modal(orderSummaryModalElement);

                            // Check if the modal is open before hiding it
                            if (orderSummaryModalElement.classList.contains('show')) {
                                orderSummaryModal.hide();
                            }
                            // Show success modal for COD
                            const successModalElement = document.getElementById('successModal');
                            const successModal = new bootstrap.Modal(successModalElement);
                            successModal.show();

                            document.getElementById('redirectToCart').addEventListener('click', function() {
                                window.location.href = "/cart";
                            });
                        }
                    } else {
                        alert(data.message || 'Failed to place the order. Please try again.');
                    }
                })
                .catch(error => {
                    hideSpinner();
                    console.error('Error placing order:', error);
                    alert('An error occurred while placing the order.');
                });
            });
        }

        function generateOrderSummaryHTML(orderData, paymentMethodDisplay) {
            let summaryHtml = `<div class="order-summary"><h5>Shipping Details</h5>
                <p style="margin: 0rem 1rem;"><strong>Shipping Address:</strong> ${orderData.shipping_address}</p>
                <p style="margin: 0rem 1rem;"><strong>Contact Number:</strong> ${orderData.contact_number}</p>
                <h5 class="mt-3">Products Ordered:</h5><hr><div class="ordered-products">`;

            orderData.order_items.forEach(item => {
                summaryHtml += `<div class="ordered-product-item" style="display: flex; align-items: center; margin-bottom: 10px; margin: 0rem 1rem;">
                    <img src="${item.image}" alt="${item.product_name}" style="width: 60px; height: 60px; object-fit: cover;">
                    <div style="flex-grow: 1; margin-left: 0.5rem;">
                        <p style="margin: 0; font-weight: bold;">${item.product_name}</p>
                        <p style="margin: 0; font-size: 12px; color: #555;">Variation: ${item.variation_name}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0;">₱${item.product_price.toFixed(2)}</p>
                        <p style="margin: 0;">Qty: ${item.quantity}</p>
                    </div>
                </div>`;
            });

            summaryHtml += `<hr><div style="margin: 0rem 1rem;"><p><strong>Selected Payment Option:</strong>
                <span id="selectedPaymentOption" style="align-items: center;">${paymentMethodDisplay}</span></p>
                <p><strong>Selected Voucher:</strong>
                <span id="selectedVoucher" style="align-items: center;">None</span></p></div>
                <div class="total-amount" style="text-align: right; margin-top: 10px;">
                <p><strong>Total Amount:</strong> ₱${orderData.total_amount.toFixed(2)}</p></div></div>`;

            return summaryHtml;
        }
        // Function to get the final shipping address
        function getFinalShippingAddress() {
            const hiddenCompleteAddress = document.getElementById('hiddenCompleteAddress')?.value;
            if (hiddenCompleteAddress) {
                return hiddenCompleteAddress.replace(/\s+/g, ' ').trim();
            }

            const shippingAddressElement = document.querySelector('.shipping-address-section p');
            if (shippingAddressElement) {
                // Clone the element to avoid modifying the original
                const clonedElement = shippingAddressElement.cloneNode(true);

                // Remove the "Default" span or any other spans from the cloned element
                clonedElement.querySelectorAll('span').forEach(span => span.remove());

                // Get the cleaned-up address text, removing "Shipping Address:" if it exists
                const addressText = clonedElement.textContent
                    .replace(/^\s*Shipping Address:\s*/i, "") // Remove "Shipping Address:" at the beginning if present
                    .replace(/\s+/g, ' ') // Normalize spaces
                    .trim();

                return addressText;
            }

            return "";
        }
    });
</script>

<script>
    var merchantSupportsCod = @json($merchantSupportsCOD);
    var merchantSupportsGcash = @json($merchantSupportsGCash);
    var gcashMopId = @json($gcashMopId);
    var codMopId = @json($codMopId);

    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodInput = document.getElementById('paymentMethod');
        const paymentMethodIdInput = document.getElementById('paymentMethodId'); // Add this input in the HTML
        const selectedPaymentMethodText = document.getElementById('selectedPaymentMethodText');
        const selectCodButton = document.getElementById('selectCodButton');
        const selectGcashButton = document.getElementById('selectGcashButton');

        // Check if the elements exist
        if (!paymentMethodInput || !selectedPaymentMethodText || !selectCodButton || !selectGcashButton) {
            console.error('Payment elements are not found in the DOM.');
            return;
        }

        // Auto-select available payment method
        function autoSelectPaymentMethod() {
            // Enable or disable buttons based on availability
            selectCodButton.disabled = !merchantSupportsCod;
            selectGcashButton.disabled = !merchantSupportsGcash;

            // Auto-select based on availability
            if (merchantSupportsCod) {
                paymentMethodInput.value = 'COD';
                paymentMethodIdInput.value = codMopId; // Set the COD merchant_mop_id
                selectedPaymentMethodText.textContent = 'Cash on Delivery';
            } else if (merchantSupportsGcash) {
                paymentMethodInput.value = 'GCash';
                paymentMethodIdInput.value = gcashMopId; // Set the GCash merchant_mop_id
                selectedPaymentMethodText.innerHTML = `
                    <img src="{{ asset('images/assets/gcash_logo.png') }}" alt="GCash Logo" style="width: 30px; height: 30px;" class="me-2">
                    GCash
                `;
            } else {
                selectedPaymentMethodText.textContent = 'No available payment method';
            }
        }

        // Handle GCash selection
        selectGcashButton.addEventListener('click', function () {
            if (!selectGcashButton.disabled && paymentMethodInput.value !== 'GCash') {
                paymentMethodInput.value = 'GCash';
                paymentMethodIdInput.value = gcashMopId; // Set the GCash merchant_mop_id
                selectedPaymentMethodText.innerHTML = `
                    <img src="{{ asset('images/assets/gcash_logo.png') }}" alt="GCash Logo" style="width: 30px; height: 30px;" class="me-2">
                    GCash
                `;
                $('#selectPaymentModal').modal('hide'); // Close the modal
            }
        });

        // Handle COD selection
        selectCodButton.addEventListener('click', function () {
            if (!selectCodButton.disabled && paymentMethodInput.value !== 'COD') {
                paymentMethodInput.value = 'COD';
                paymentMethodIdInput.value = codMopId; // Set the COD merchant_mop_id
                selectedPaymentMethodText.textContent = 'Cash on Delivery';
                $('#selectPaymentModal').modal('hide'); // Close the modal
            }
        });

        // Initialize with auto-selection
        autoSelectPaymentMethod();
    });



</script>



@endsection

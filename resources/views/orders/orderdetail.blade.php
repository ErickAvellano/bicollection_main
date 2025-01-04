@extends('Components.layout')

@section('styles')
<style>
    .nav-pills, .search-control, .search-icon {
        display: none;
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
    .product-img {
        width:100%;
        height: 200px;
        display: block;
        border-radius: 5px;
    }
    .custom-p {
        margin-left: 10px;
    }
    .card-body{
        padding:20px;
    }
    .btn-link{
        text-decoration:none;
        font-weight: bold;
        color:#333;
    }
    .btn-link:hover{
        font-weight: bold;
        color: #228b22;
    }

</style>
@endsection

@section('content')
<div class="container mt-3 mb-5">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mypurchase') }}">My Purchase</a></li>
        <li class="breadcrumb-item active">Order Details</li>
    </ol>

    <!-- Order Details Container -->
    <div class="order-details-container">
        <!-- Order Details Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">Order Details</h4>
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-3">
                        <img src="{{ $orderData['order_items'][0]->product->images[0]->product_img_path1 ? asset('storage/' . $orderData['order_items'][0]->product->images[0]->product_img_path1) : 'https://via.placeholder.com/200x200.png?text=Product+Image' }}" alt="Product Image" class="product-img">
                    </div>
                    <!-- Product Details -->
                    <div class="col-md-8 mb-2">
                        <div class="row">
                            <div class="col-md-8 mt-2">
                                <p><strong>Order ID:</strong> {{ $orderData['order_id'] }}</p>
                                <p class="mb-0"><strong>Product ID:</strong> {{ $orderData['order_items'][0]->product_id }}</p>
                                <p class="mb-0"><strong>Product Name:</strong> {{ $orderData['order_items'][0]->product_name }}</p>
                                    @if (!isset($orderData['order_items'][0]->variation->variation_name) || $orderData['order_items'][0]->variation->variation_name !== '<default>')
                                        <p class="mb-0"><strong>Variation:</strong> {{ $orderData['order_items'][0]->variation->variation_name }}</p>
                                    @endif
                                <p class="mb-0"><strong>Quantity:</strong> {{ $orderData['order_items'][0]->quantity }}</p>
                                <p class="mb-0"><strong>Price:</strong> {{ $orderData['order_items'][0]->subtotal}}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="mb-0" id="orderStatus"><strong>Order Status:</strong> {{ ucfirst($orderData['order_status']) }}</p>
                                <p class="mb-0"><strong>Mode of Payment:</strong> <span id="payment-method">{{ $orderData['payment_method'] }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check if there are additional products -->
                @if (count($orderData['order_items']) > 1)

                    <!-- Hidden container for additional products -->
                    <div id="additional-products" style="display: none; margin-top: 20px;">
                        @foreach ($orderData['order_items'] as $index => $orderItem)
                            @if ($index > 0) <!-- Skip the first product -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <img src="{{ $orderItem->product->images[0]->product_img_path1 ? asset('storage/' . $orderItem->product->images[0]->product_img_path1) : 'https://via.placeholder.com/200x200.png?text=Product+Image' }}" alt="Product Image" class="product-img">
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-0"><strong>Product ID:</strong> {{ $orderItem->product_id }}</p>
                                    <p class="mb-0"><strong>Product Name:</strong> {{ $orderItem->product_name }}</p>
                                        @if (!isset($orderData['order_items'][0]->variation->variation_name) || $orderData['order_items'][0]->variation->variation_name !== '<default>')
                                            <p class="mb-0"><strong>Variation:</strong> {{ $orderData['order_items'][0]->variation->variation_name }}</p>
                                        @endif
                                    <p class="mb-0"><strong>Quantity:</strong> {{ $orderItem->quantity }}</p>
                                    <p class="mb-0"><strong>Price:</strong> {{ $orderData['order_items'][0]->subtotal}}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <!-- Button to toggle additional products -->
                    <div class="text-center mt-3">
                        <button class="btn btn-link p-0" id="view-more-products-btn">View More Products</button>
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <strong>Total:</strong> ₱{{ number_format($orderData['total_amount'], 2) }}
            </div>
        </div>


        <!-- Shipping Details Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">Shipping Details</h4>
                <p class="custom-p"><strong>Customer Name:</strong> {{ $orderData['customer_name'] }}</p>
                <p class="custom-p"><strong>Contact Number:</strong> {{ $orderData['contact_number'] }}</p>
                <p class="custom-p"><strong>Shipping Address:</strong> {{ $orderData['shipping_address'] }}</p>
            </div>
        </div>

        <!-- GCash Payment Card (conditionally displayed) -->
        @if ($orderData['payment_method'] == 'GCash')
        <div class="card mb-3" id="gcash-payment-card">
            <div class="card-body">
                <h4 class="card-title">GCash Payment</h4>
                <p class="custom-p" id="paymentStatus"><strong>Payment Status:</strong> {{ $orderData['payment_status'] }}</p>
                <div class="text-center">
                    <button class="btn btn-outline-custom btn-sm" id="reviewPaymentBtn"
                            data-receipt-img="{{ asset('storage/' . $orderData['receipt_img']) }}">
                        Review Payment
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>




@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewMoreProductsBtn = document.getElementById('view-more-products-btn');
        const additionalProductsContainer = document.getElementById('additional-products');

        if (viewMoreProductsBtn) {
            viewMoreProductsBtn.addEventListener('click', function () {
                if (additionalProductsContainer.style.display === 'none') {
                    additionalProductsContainer.style.display = 'block';
                    viewMoreProductsBtn.innerText = 'Hide More Products';
                } else {
                    additionalProductsContainer.style.display = 'none';
                    viewMoreProductsBtn.innerText = 'View More Products';
                }
            });
        }
    });
</script>

@endsection

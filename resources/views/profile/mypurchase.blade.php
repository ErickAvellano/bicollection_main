@extends('Components.layout')

@section('styles')
<style>
    /* Existing Styles */
    .nav-pills, .search-control, .search-icon {
        display: none;
    }

    .nav-link {
        color: #333;
        font-weight: bold;
    }
    .nav-link.active {
        color: #228b22 !important;
        font-weight: bold;
    }

    .order-card {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
    }
    .order-card h5 {
        font-size: 1.2rem;
        color: #228b22;
    }
    .order-card p {
        margin-bottom: 5px;
    }
    .btn-view, .btn-accept, .btn-decline {
        color: white;
    }
    .btn-view {
        background-color: #17a2b8;
    }
    .btn-accept {
        background-color: #28a745;
    }
    .btn-decline {
        background-color: #dc3545;
    }
    .custom-icon {
        color: #228b22;
    }
    .text-custom {
        color: #333;
    }
    .sortBy {
        font-weight: bold;
    }
    #starRating .star {
        cursor: pointer;
    }
    #starRating .star.filled {
        color: gold;
    }
    .form-check-label {
        margin-bottom: 0;
        font-weight: 400;
        color: #212529;
    }

    /* Form Check Input Styles */
    .form-check-input {
        width: 1em;
        height: 1em;
        margin-top: 0.25em;
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid rgba(0, 0, 0, 0.25);
        border-radius: 0.25rem;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    .form-check-input:checked {
        background-color: #228b22;
        border-color: #228b22;
    }
    .form-check-input:focus {
        border-color: #228b22;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(34, 139, 34, 0.25);
    }
    .form-check-input:disabled {
        background-color: #e9ecef;
        border-color: #ced4da;
    }

    /* Image Upload Box Styles */
    .image-upload-box {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #28a745;
        border-radius: 5px;
        width: 70px;
        height: 70px;
        position: relative;
        cursor: pointer;
    }

    .plus-icon {
        color: #28a745;
        font-weight: bold;
        position: absolute;
        z-index: 1;
    }

    .file-input {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        cursor: pointer;
    }


    .remove-button {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
    }
    .reviewtext{
        height:100px;
    }

    /* Responsive Adjustments for Image Upload Box */
    @media (max-width: 375px) {
        .image-upload-box {
            width: 50px;
            height: 50px;
        }
        .plus-icon {
            font-size: 1rem;
        }
    }

    /* Media query for smaller mobile screens */
    @media (max-width: 575px) {
        #purchaseTabs {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        #purchaseTabs .nav-item {
            display: inline-block;
        }
        #purchaseTabs .nav-link {
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 425px) {
        #purchaseTabs {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        #purchaseTabs .nav-item {
            display: inline-block;
        }
        #purchaseTabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

@endsection

@section('content')
<div class="container mt-3 mb-5">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item active">My Purchase</li>
    </ol>

    <!-- Purchase Status Tabs -->
    <ul class="nav nav-tabs mb-3" id="purchaseTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="#" data-status="pending" role="tab">
                Pending Purchase <span class="text-custom">({{ $statusCounts['pending'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="To-pay" role="tab">
                To-Pay <span class="text-custom">({{ $statusCounts['To-pay'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-ship" role="tab">
                To-Ship <span class="text-custom">({{ $statusCounts['to-ship'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-received" role="tab">
                To-Receive <span class="text-custom">({{ $statusCounts['to-received'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-rate" role="tab">
                To-Rate <span class="text-custom">({{ $statusCounts['to-rate'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-refund" role="tab">
                To-Refund <span class="text-custom">({{ $statusCounts['to-refund'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="completed" role="tab">
                Completed <span class="text-custom">({{ $statusCounts['completed'] }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="cancel" role="tab">
                Cancelled <span class="text-custom">({{ $statusCounts['cancel'] }})</span>
            </a>
        </li>
    </ul>


    <!-- Sort By Dropdown -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 id="pageHeading">My Purchase (Pending Orders)</h4>
        <div>
            <label for="sortBy" class="sortBy">Sort by:</label>
            <select id="sortBy" class="form-control form-control-sm">
                <option value="">Select Sort Option</option>
                <option value="GCash">MOP: GCash</option>
                <option value="COD">MOP: COD</option>
                <option value="date">Date</option>
            </select>
        </div>
    </div>

    <!-- Purchases List -->
    <div id="purchaseContent" class="mt-3">
        @if ($purchases->isEmpty())
            <p>No {{ ucfirst($status) }} purchases found.</p>
        @else
            <div class="list-group">
                @foreach ($purchases as $purchase)
                    <div class="list-group-item order-card d-flex flex-column p-4 mb-4 border rounded shadow-sm">
                        <!-- Product Image and Details -->
                        <div class="d-flex align-items-start">
                            <!-- Product Image -->
                            <div class="me-4">
                                @php
                                    $orderItem = $purchase->orderItems->first();
                                    $product = $orderItem ? $orderItem->product : null;
                                    $image = $product ? $product->images->first() : null;
                                    $imagePath = $image ? $image->product_img_path1 : 'https://via.placeholder.com/60';
                                @endphp
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="Product Image" class="img-fluid border" loading="lazy"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>

                            <!-- Purchase Details -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p><strong>Order ID:</strong> {{ $purchase->order_id }}</p>
                                        <p><strong>Product Name:</strong> {{ $orderItem->product->product_name ?? 'N/A' }}</p>
                                        <p><strong>Mode of Payment:</strong> {{ $purchase->payment->payment_method ?? 'N/A' }}</p>
                                        <p><strong>Shipping Address:</strong> {{ $purchase->shipping_address ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p><strong>Status:</strong>
                                            <span class="badge
                                                @if($purchase->order_status === 'cancelled') bg-danger
                                                @elseif($purchase->order_status === 'declined') bg-warning
                                                @elseif($purchase->order_status === 'to-ship') bg-info
                                                @elseif($purchase->order_status === 'to-received') bg-primary
                                                @elseif($purchase->order_status === 'completed') bg-success
                                                @elseif($purchase->order_status === 'pending') bg-secondary
                                                @else bg-dark
                                                @endif">
                                                {{ ucfirst($purchase->order_status) }}
                                            </span>
                                        </p>
                                        <p><strong>Qty:</strong> {{ $purchase->orderItems->sum('quantity') }}</p>
                                        <p><strong>Total:</strong> ₱{{ number_format($purchase->total_amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-3">
                            @if ($purchase->payment && $purchase->payment->payment_status === 'To-pay')
                                <a href="{{ route('payment.show', ['order_id' => $purchase->order_id]) }}" class="btn btn-primary me-2">Pay Now</a>
                                <button type="button" class="btn btn-danger me-2" onclick="openCancelConfirmationModal({{ $purchase->order_id }})">
                                    Cancel Order
                                </button>
                            @elseif ($purchase->order_status === 'to-ship')
                                <a href="{{ route('order.track', ['order' => $purchase->order_id]) }}" class="btn btn-secondary me-2">Track Order</a>
                                @elseif ($purchase->order_status === 'to-received' || $purchase->order_status === 'ready')
                                <a href="#" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#confirmReceivedModal" data-order-id="{{ $purchase->order_id }}" data-product-id="{{ $orderItem->product->product_id }}">
                                    Item Received
                                </a>
                            @elseif ($purchase->order_status === 'to-rate')
                                <a href="#" id="rateProductButton" class="btn btn-outline-custom me-2" data-order-id="{{ $purchase->order_id }}" onclick="openReviewModal(event)">Rate Product</a>
                            @elseif ($purchase->order_status === 'cancel')
                                <span class="btn btn-danger me-2 disabled">Order Cancelled</span>
                            @elseif ($purchase->order_status === 'pending')
                                <a href="#" class="btn btn-view me-2">View</a>
                                <a class="btn btn-danger me-2" data-order-id="{{ $purchase->order_id }}" data-payment-id="{{ $purchase->payment->payment_id }}" data-payment-method="{{ $purchase->payment->payment_method ?? '' }}" onclick="openCancelModal(event)">Request Cancel</a>
                            @else
                                <span class="btn btn-secondary me-2 disabled">Status Unknown</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<!-- Modal for confirming item received -->
<div class="modal fade" id="confirmReceivedModal" tabindex="-1" aria-labelledby="confirmReceivedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold " id="confirmReceivedModalLabel">Confirm Item Received</h5>
            </div>
            <div class="modal-body ">
                <p>Did you already receive the product? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirmReceivedButton">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal for Thank You Message -->
{{-- <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-items-center">
                <h5 class="modal-title d-flex align-items-center" id="thankYouModalLabel">
                    <i class="fa-regular fa-comment-dots text-success me-2"></i> <!-- Icon with color and spacing -->
                    Thank You for Your Purchase!
                </h5>
                <!-- Close button with accessible styling -->
                <button type="button" class="btn-close d-flex justify-content-center" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></i></o></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">We appreciate your purchase! Please take a moment to share your feedback to help us improve.</p>
                <p class="mb-4">Click the button below to access our feedback form:</p>
                <a href="#" class="btn btn-primary d-inline-flex align-items-center" target="_blank">
                    <i class="fa-solid fa-pen-to-square me-2"></i> <!-- Icon with spacing -->
                    Give Feedback
                </a>
            </div>
        </div>
    </div>
</div> --}}
<div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center align-items-center">
                <h5 class="modal-title d-flex align-items-center" id="thankYouModalLabel">
                </h5>
            </div>
            <div class="modal-body text-center">
                <h1 class="mb-3"><i class="fa-regular fa-circle-check text-success"></i></h1>
                <p class="mb-4">Thank you for your purchase</p>
                <p>Rate the Product</p>

                <input type="hidden" id="orderIdInputs" name="order_id" value="">
                <input type="hidden" id="productIdInputs" name="product_id" value="">


                <input type="hidden" id="ReviewStar" name="review_star" value="">
                <!-- Star Rating -->
                <div class="text-warning my-3" id="starRating">
                    <i class="fa-regular fa-star fa-2x star" data-value="1"></i>
                    <i class="fa-regular fa-star fa-2x star" data-value="2"></i>
                    <i class="fa-regular fa-star fa-2x star" data-value="3"></i>
                    <i class="fa-regular fa-star fa-2x star" data-value="4"></i>
                    <i class="fa-regular fa-star fa-2x star" data-value="5"></i>
                </div>


            </div>
        </div>
    </div>
</div>
{{-- <div class="modal fade" id="ratePromptModal" tabindex="-1" aria-labelledby="ratePromptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-start w-100 fw-bold" id="ratePromptModalLabel">Rate Product</h5>
                <button type="button" class="btn-close d-flex justify-content-center" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body text-center">
                <p>Please rate the product you previously bought.</p>
                <p>Your feedback helps us improve our platform.</p>

                <!-- Star rating (static example) -->
                <div class="text-warning my-3" id="starRating">
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="ratePromptModal" tabindex="-1" aria-labelledby="ratePromptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-start w-100 fw-bold" id="ratePromptModalLabel">Rate Product</h5>
                <button type="button" class="btn-close d-flex justify-content-center" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body text-center" id="starRating">
                <p>Please rate the product you previously bought.</p>
                <p>Your feedback helps us improve our platform.</p>
                <!-- Star rating (static example) -->
                <div class="text-warning my-3">
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                    <i class="fa-solid fa-star fa-2x"></i>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Review Modal (Shows After Selecting Star Rating) -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="reviewModalLabel">Rate Product</h5>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContainer">
                    <!-- Order details will be populated here dynamically -->
                </div>
                <form id="reviewForm" action="{{ route('submit.review') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" name="product_id" id="productIdInput" value="">
                        <input type="hidden" name="order_id" id="orderIdInput" value="">
                    <!-- Ratings -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="productQualityRating" class="form-label me-3 mb-0" style="min-width: 100px;">Product Quality:</label>
                        <div id="productQualityRating" class="text-warning d-flex align-items-center">
                            <i class="fa-regular fa-star fa-lg star" data-value="1"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="2"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="3"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="4"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="5"></i>
                            <span id="productQualityDescription" class="ms-2 text-warning" style="display: none;">Amazing</span>
                        </div>
                        <input type="text" name="rating" id="reviewRatingInput" value="" required>
                    </div>
                    <div class="card mb-3" id="expandedFields" style="display: none;">
                        <div class="card-body">
                            <!-- Product Review Textarea -->
                            <div class="form-group mb-3">
                                <label for="performance" class="form-label">Product Review</label>
                                <textarea class="form-control reviewtext" id="performance" name="performance" rows="2" placeholder="Write a review for the product" required></textarea>
                            </div>

                            <!-- Add Images Section -->
                            <div class="form-group">
                                <label class="form-label">Add Images</label>
                                <div class="d-flex gap-2">
                                    <div class="image-upload-box">
                                        <span class="plus-icon">+</span>
                                        <input type="file" class="file-input" name="image_1" accept="image/*">
                                    </div>
                                    <div class="image-upload-box">
                                        <span class="plus-icon">+</span>
                                        <input type="file" class="file-input" name="image_2" accept="image/*">
                                    </div>
                                    <div class="image-upload-box">
                                        <span class="plus-icon">+</span>
                                        <input type="file" class="file-input" name="image_3" accept="image/*">
                                    </div>
                                    <div class="image-upload-box">
                                        <span class="plus-icon">+</span>
                                        <input type="file" class="file-input" name="image_4" accept="image/*">
                                    </div>
                                    <div class="image-upload-box">
                                        <span class="plus-icon">+</span>
                                        <input type="file" class="file-input" name="image_5" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Merchant Service Rating -->
                    <div class="form-group mb-3 d-flex align-items-center">
                        <label for="merchantServiceRating" class="form-label me-3 mb-0" style="min-width: 100px;">Merchant Service:</label>
                        <div id="merchantServiceRating" class="text-warning d-flex align-items-center">
                            <i class="fa-regular fa-star fa-lg star" data-value="1"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="2"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="3"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="4"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="5"></i>
                            <span id="merchantServiceDescription" class="ms-2 text-warning" style="display: none;">Amazing</span>
                        </div>
                        <input type="text" name="merchant_service_rating" id="merchantServiceRatingInput" value="" required>
                    </div>

                    <!-- Platform Rating -->
                    <div class="form-group mb-3 d-flex align-items-center">
                        <label for="platformRating" class="form-label me-3 mb-0" style="min-width: 100px;">Platform Rating:</label>
                        <div id="platformRating" class="text-warning d-flex align-items-center">
                            <i class="fa-regular fa-star fa-lg star" data-value="1"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="2"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="3"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="4"></i>
                            <i class="fa-regular fa-star fa-lg star" data-value="5"></i>
                            <span id="platformDescription" class="ms-2 text-warning" style="display: none;">Amazing</span>
                        </div>
                        <input type="text" name="platform_rating" id="platformRatingInput" value="" required>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <label for="username" class="form-label" style="min-width: 100px;">
                            Username: <span id="usernameDisplay">{{ $username[0] . str_repeat('*', strlen($username) - 2) . substr($username, -1) }}</span>
                            <span id="toggleUsernameIcon" onclick="toggleUsername()" style="cursor: pointer;">
                                <i class="fas fa-eye-slash"></i> <!-- Initial icon for "masked" state -->
                            </span>
                        </label>

                        <!-- Hidden input to store the full username (always unmasked here) -->
                        <input type="hidden" id="fullUsername" name="username" value="{{ $username }}">
                    </div>
                    <small class="text-muted">
                        <i class="fa-solid fa-circle-info text-success"></i> Note: Toggling the visibility will reflect in the product review submission.
                    </small>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <div class=" d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-custom" id="submitButton" disabled>Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Confirm Cancellation</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this order? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelButton" data-order-id="" >Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div>

<!-- GCash Warning Modal -->
<div class="modal fade" id="gcashWarningModal" tabindex="-1" aria-labelledby="gcashWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gcashWarningModalLabel">
                    <i class="fa-solid fa-exclamation-triangle text-warning me-2"></i> Warning
                </h5>
            </div>
            <div class="modal-body">
                Orders with GCash payment cannot be canceled immediately. Press the Request button to initiate a cancellation request and start the refund process.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="requestCancelOrder" data-order-id="" data-payment-id="">Request Cancellation</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h1><i class="status-icon fa-regular"></i></h1>
                <p class="status-message"></p>
            </div>
        </div>
    </div>
</div>
{{-- <!-- Custom Confirmation Modal -->
<div class="modal fade" id="cancelConfirmationModal" tabindex="-1" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConfirmationModalLabel">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div> --}}

<div id="loadingSpinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050;">
    <div class="spinner-border" style="color: #228b22;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@include('Components.status-modal')

@endsection

@section('scripts')
    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                const statusIcon = document.querySelector('.status-icon');
                const statusMessage = document.querySelector('.status-message');

                // Set icon and message based on session status
                @if (session('status') === 'success')
                    statusIcon.classList.add('fa-circle-check', 'check-icon');
                    statusMessage.textContent = "{{ session('message') }}";
                @else
                    statusIcon.classList.add('fa-circle-exclamation', 'exclamation-icon');
                    statusMessage.textContent = "{{ session('message') }}";
                @endif

                // Show the modal
                confirmationModal.show();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#purchaseTabs a.nav-link');
            const purchaseContent = document.getElementById('purchaseContent');
            const sortBy = document.getElementById('sortBy');

            function loadPurchases(status, sort = '') {
                const url = new URL("{{ route('mypurchase') }}"); // AJAX route URL
                url.searchParams.append('status', status);
                if (sort) url.searchParams.append('sort', sort);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Indicate AJAX request
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.html) {
                        purchaseContent.innerHTML = data.html;
                    } else {
                        purchaseContent.innerHTML = '<p>Error loading purchases.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    purchaseContent.innerHTML = '<p>Failed to load purchases. Please try again.</p>';
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const status = this.getAttribute('data-status');
                    sortBy.value = '';
                    loadPurchases(status);
                });
            });

            sortBy.addEventListener('change', function() {
                const activeTab = document.querySelector('#purchaseTabs .nav-link.active');
                const status = activeTab ? activeTab.getAttribute('data-status') : 'pending';
                const selectedSort = sortBy.value;
                loadPurchases(status, selectedSort);
            });

            // Load initial tab content
            const initialStatus = document.querySelector('#purchaseTabs .nav-link.active').getAttribute('data-status');
            loadPurchases(initialStatus);
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let orderId = null;
            let productId = null;

            // Handle tab navigation and content loading
            const tabs = document.querySelectorAll('#purchaseTabs .nav-link');

            tabs.forEach(tab => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default link behavior

                    // Get the status from the clicked tab
                    const status = this.getAttribute('data-status');

                    // Update the URL
                    const newUrl = `/mypurchase?status=${status}`;
                    window.history.pushState(null, '', newUrl);

                    // Fetch the content for the selected status
                    fetchContentForStatus(status);

                    // Update the active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            function refreshToReceivedContent() {
                const contentContainer = document.getElementById('purchaseContent');
                const loadingSpinner = document.getElementById('loadingSpinner'); // Reference to the spinner element

                // Show the spinner
                loadingSpinner.style.display = 'block';

                fetch('/mypurchase?status=to-received', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to refresh to-received content.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hide the spinner
                        loadingSpinner.style.display = 'none';

                        // Update the content container with the new HTML
                        contentContainer.innerHTML = data.html;
                        decrementToReceiveCounts();

                    })
                    .catch(error => {
                        console.error('Error refreshing content:', error);

                        // Hide the spinner
                        loadingSpinner.style.display = 'none';

                        contentContainer.innerHTML = '<p>Error refreshing content. Please try again later.</p>';
                    });
            }
            function decrementToReceiveCounts() {
                // Decrement "To-Receive" count
                const toReceiveCountElement = document.querySelector('[data-status="to-received"] .text-custom');
                if (toReceiveCountElement) {
                    const currentCount = parseInt(toReceiveCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                    if (!isNaN(currentCount) && currentCount > 0) {
                        toReceiveCountElement.textContent = `(${currentCount - 1})`; // Decrement the count
                    }
                }

                // Increment "To-Rate" count
                const toRateCountElement = document.querySelector('[data-status="to-rate"] .text-custom');
                if (toRateCountElement) {
                    const currentCount = parseInt(toRateCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                    if (!isNaN(currentCount)) {
                        toRateCountElement.textContent = `(${currentCount + 1})`; // Increment the count
                    }
                }
            }
            // Handle back/forward navigation
            window.addEventListener('popstate', function () {
                const params = new URLSearchParams(window.location.search);
                const status = params.get('status') || 'pending';

                // Update the content and active tab
                fetchContentForStatus(status);

                tabs.forEach(t => t.classList.remove('active'));
                const activeTab = document.querySelector(`#purchaseTabs .nav-link[data-status="${status}"]`);
                if (activeTab) {
                    activeTab.classList.add('active');
                }
            });

            // When the "Item Received" button is clicked, store the order ID and product ID
            $('#confirmReceivedModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget); // Button that triggered the modal
                orderId = button.data('order-id'); // Extract info from data-order-id attribute
                productId = button.data('product-id'); // Correct assignment for product ID
            });

            // When the "Confirm" button in the modal is clicked
            document.getElementById('confirmReceivedButton').addEventListener('click', function () {
                if (orderId) {
                    // Close the confirmation modal immediately
                    $('#confirmReceivedModal').modal('hide');

                    // Show the loading spinner
                    document.getElementById('loadingSpinner').style.display = 'block';

                    fetch(`/order/confirm-received/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                        },
                        body: JSON.stringify({ status: 'received' })
                    })
                        .then(response => {
                            if (response.ok) {
                                // Refresh the "to-received" content dynamically
                                refreshToReceivedContent();
                                decrementToReceiveCounts();
                                // Show the thank-you modal after a short delay
                                setTimeout(function () {
                                    $('#thankYouModal').modal('show');
                                    setTimeout(function () {
                                        $('#thankYouModal').modal('hide');
                                    }, 3000);
                                }, 500);
                            } else {
                                console.error('Request failed. Unable to confirm received.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        })
                        .finally(() => {
                            // Hide the loading spinner
                            document.getElementById('loadingSpinner').style.display = 'none';
                        });
                }
            });

            // When the "Thank You" modal is shown
            $('#thankYouModal').on('shown.bs.modal', function () {
                if (orderId && productId) {
                    // Set the value of the input fields to display the orderId and productId
                    $('#orderIdInputs').val(orderId);
                    $('#productIdInputs').val(productId);
                }
            });

            // Function to refresh the "to-received" content dynamically
            function refreshToReceivedContent() {
                const contentContainer = document.getElementById('purchaseContent');
                contentContainer.innerHTML = '<p>Refreshing...</p>'; // Show a loading indicator

                fetch('/mypurchase?status=to-received', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to refresh to-received content.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Update the content container with the new HTML
                        contentContainer.innerHTML = data.html;
                    })
                    .catch(error => {
                        console.error('Error refreshing content:', error);
                        contentContainer.innerHTML = '<p>Error refreshing content. Please try again later.</p>';
                    });
            }
        });
    </script>






    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find the "To-Rate" tab link
            const toRateTab = document.querySelector('a[data-status="to-rate"]');

            if (toRateTab) {
                toRateTab.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default link behavior

                    // Show the rate prompt modal
                    $('#ratePromptModal').modal('show');
                });
            }
        });
    </script>
    <script>
        document.getElementById('payNowButton').addEventListener('click', function() {
            const form = document.getElementById('paymentForm');
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',  // Ensures a POST request
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Display success message
                } else {
                    alert(data.message || 'Failed to update payment status.');
                }
            })
            .catch(error => console.error('Error:', error));
        });

    </script>
    <script>
        function openCancelModal(event) {
            event.preventDefault();

            const orderId = event.currentTarget.getAttribute('data-order-id');
            const paymentId = event.currentTarget.getAttribute('data-payment-id');
            const paymentMethod = event.currentTarget.getAttribute('data-payment-method');

            if (paymentMethod === 'GCash') {
                // Show the GCash warning modal
                document.getElementById('requestCancelOrder').setAttribute('data-order-id', orderId);
                document.getElementById('requestCancelOrder').setAttribute('data-payment-id', paymentId);
                const gcashWarningModal = new bootstrap.Modal(document.getElementById('gcashWarningModal'));
                gcashWarningModal.show();
            } else {
                // For other payment methods, show the cancel order modal
                // Set the order ID on the confirm button
                document.getElementById('confirmCancelButton').setAttribute('data-order-id', orderId);

                // Show the cancel order modal
                const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
                cancelOrderModal.show();
            }
        }
        // Function to confirm and send the cancellation request
        document.getElementById('confirmCancelButton').addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');

            const cancelOrderModal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal')) ||
                             new bootstrap.Modal(document.getElementById('cancelOrderModal'));

            cancelOrderModal.hide();
            // Show spinner
            const loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.style.display = 'block';

            fetch('/cancel-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusModal('success', data.message || 'Order cancelled successfully.');
                    refreshContentAfterCancellation();
                } else {
                    showStatusModal('error', data.message || 'Failed to cancel order.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error modal for unexpected errors
                showStatusModal('error', 'An unexpected error occurred.');
            });
        });

        function refreshContentAfterCancellation() {
            const contentContainer = document.getElementById('purchaseContent'); // Adjust to your content container ID

            // Show the spinner
            const loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.style.display = 'block';

            fetch('/mypurchase?status=pending', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to refresh content after cancellation.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    // Update the content container with the new HTML
                    contentContainer.innerHTML = data.html;

                    // Optionally, decrement the "To-Receive" count if needed
                    decrementPendingCount();
                })
                .catch(error => {
                    console.error('Error refreshing content:', error);

                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    contentContainer.innerHTML = '<p>Error refreshing content. Please try again later.</p>';
                });
        }


        function decrementPendingCount() {
            // Decrement "To-Rate" count
            const toRateCountElement = document.querySelector('[data-status="pending"] .text-custom');
            if (toRateCountElement) {
                const currentToRateCount = parseInt(toRateCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                if (!isNaN(currentToRateCount) && currentToRateCount > 0) {
                    toRateCountElement.textContent = `(${currentToRateCount - 1})`; // Decrement the count
                }
            }

            // Increment "Completed" count
            const completedCountElement = document.querySelector('[data-status="cancel"] .text-custom');
            if (completedCountElement) {
                const currentCompletedCount = parseInt(completedCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                if (!isNaN(currentCompletedCount)) {
                    completedCountElement.textContent = `(${currentCompletedCount + 1})`; // Increment the count
                }
            }
        }
        // Function to show the status modal
        function showStatusModal(type, message) {
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const statusModalBody = document.getElementById('statusModalBody');
            const statusModalIcon = document.getElementById('statusModalIcon');
            const statusModalMessage = document.getElementById('statusModalMessage');

            // Configure modal content based on the type
            if (type === 'success') {
                statusModalIcon.className = 'fa-regular fa-circle-check text-success';
            } else if (type === 'error') {
                statusModalIcon.className = 'fa-regular fa-circle-xmark text-danger';
            }

            // Set the message
            statusModalMessage.textContent = message;

            // Show the modal
            statusModal.show();
        }
        function fetchContentForStatus(status) {
            const contentContainer = document.getElementById('purchaseContent');
            const loadingSpinner = document.getElementById('loadingSpinner'); // Reference to the spinner element

            // Show the spinner
            loadingSpinner.style.display = 'block';

            fetch(`/mypurchase?status=${status}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch content for status: ' + status);

                    }
                    return response.json();
                })
                .then(data => {
                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    // Update the content container with the new HTML
                    contentContainer.innerHTML = data.html;
                    decrementPendingCount();
                })
                .catch(error => {

                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    contentContainer.innerHTML = '<p>Error loading content. Please try again later.</p>';
                });
        }

    </script>
    <script>
        const originalUsername = "{{ $username }}";
        let isMasked = true; // Track if the username is currently masked

        function toggleUsername() {
            const usernameDisplay = document.getElementById('usernameDisplay');
            const fullUsernameInput = document.getElementById('fullUsername');
            const toggleIcon = document.getElementById('toggleUsernameIcon');

            if (isMasked) {
                // Unmask username: show full username in display span and input field
                usernameDisplay.textContent = originalUsername;
                fullUsernameInput.value = originalUsername;
                toggleIcon.innerHTML = '<i class="fas fa-eye"></i>'; // Change icon to eye
                isMasked = false; // Update the state to unmasked
            } else {
                // Mask username: show masked version in display span and input field
                const maskedUsername = originalUsername[0] + '*'.repeat(originalUsername.length - 2) + originalUsername.slice(-1);
                usernameDisplay.textContent = maskedUsername;
                fullUsernameInput.value = maskedUsername; // Update input field with masked version
                toggleIcon.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Change icon to eye-slash
                isMasked = true; // Update the state to masked
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            function setupStarRating(starContainerId, inputId, descriptionId) {
                // Get all stars in the specified container
                const stars = document.querySelectorAll(`#${starContainerId} .star`);
                const ratingInput = document.getElementById(inputId);
                const descriptionElement = document.getElementById(descriptionId);
                const expandedFields = document.getElementById('expandedFields'); // Reference the expanded fields container

                // Descriptions for ratings (customize as needed)
                const descriptions = ["Poor", "Fair", "Good", "Very Good", "Amazing"];

                // Add event listeners for each star
                stars.forEach((star, index) => {
                    // Highlight stars on hover
                    star.addEventListener('mouseover', () => fillStars(stars, index));

                    // Reset stars when the mouse leaves
                    star.addEventListener('mouseleave', () => resetStars(stars, ratingInput.value));

                    // Set the rating when a star is clicked
                    star.addEventListener('click', () => {
                        const selectedRating = index + 1;

                        // Update the input value and display
                        ratingInput.value = selectedRating;
                        ratingInput.setAttribute('value', selectedRating);

                        setRating(stars, selectedRating); // Update star visuals
                        updateDescription(selectedRating, descriptionElement); // Update description
                        activateExpandedFields(selectedRating); // Activate expanded fields
                        console.log(`Rating updated to: ${selectedRating}`); // Debugging log
                    });
                });

                // Listen for manual changes in the rating input
                ratingInput.addEventListener('input', () => {
                    const selectedRating = parseInt(ratingInput.value, 10) || 0;

                    if (selectedRating >= 1 && selectedRating <= 5) {
                        setRating(stars, selectedRating); // Update star visuals
                        updateDescription(selectedRating, descriptionElement); // Update description
                        activateExpandedFields(selectedRating); // Activate expanded fields
                        console.log(`Rating manually changed to: ${selectedRating}`); // Debugging log
                    } else {
                        resetStars(stars, 0); // Reset stars if invalid rating
                        descriptionElement.style.display = "none"; // Hide description
                        expandedFields.style.display = "none"; // Hide expanded fields
                        console.log(`Invalid rating: ${selectedRating}`); // Debugging log
                    }
                });

                // Function to fill stars up to the specified index
                function fillStars(stars, index) {
                    stars.forEach((star, i) => {
                        if (i <= index) {
                            star.classList.add('fa-solid');
                            star.classList.remove('fa-regular');
                        } else {
                            star.classList.add('fa-regular');
                            star.classList.remove('fa-solid');
                        }
                    });
                }

                // Function to reset stars based on the current rating
                function resetStars(stars, rating) {
                    const currentRating = parseInt(rating, 10) || 0;
                    setRating(stars, currentRating);
                }

                // Function to set stars based on a given rating
                function setRating(stars, rating) {
                    stars.forEach((star, i) => {
                        if (i < rating) {
                            star.classList.add('fa-solid');
                            star.classList.remove('fa-regular');
                        } else {
                            star.classList.add('fa-regular');
                            star.classList.remove('fa-solid');
                        }
                    });
                }

                // Function to update the description based on the rating
                function updateDescription(rating, descriptionElement) {
                    if (!descriptionElement) {
                        console.error("Description element not found.");
                        return;
                    }
                    if (rating >= 1 && rating <= 5) {
                        descriptionElement.innerText = descriptions[rating - 1];
                        descriptionElement.style.display = "inline";
                    } else {
                        descriptionElement.style.display = "none";
                    }
                }

                // Function to activate expanded fields based on the rating
                function activateExpandedFields(rating) {
                    if (!expandedFields) {
                        console.error("Expanded fields container not found.");
                        return;
                    }
                    if (rating >= 1 && rating <= 5) {
                        expandedFields.style.display = "block";
                    } else {
                        expandedFields.style.display = "none";
                    }
                }
            }
            // Initialize star ratings for Product Quality, Merchant Service, and Platform Rating
            setupStarRating('productQualityRating', 'reviewRatingInput', 'productQualityDescription');
            setupStarRating('merchantServiceRating', 'merchantServiceRatingInput', 'merchantServiceDescription');
            setupStarRating('platformRating', 'platformRatingInput', 'platformDescription');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const thankYouStars = document.querySelectorAll('#starRating .star');
            const reviewStarInput = document.getElementById('ReviewStar'); // Hidden input to store the star rating
            const reviewTextarea = document.getElementById('performance');

            // Attach event listener to Thank You Modal stars
            thankYouStars.forEach((star, index) => {
                star.addEventListener('mouseover', () => fillStars(thankYouStars, index));
                star.addEventListener('mouseleave', () => resetStars(thankYouStars, reviewStarInput.value));
                star.addEventListener('click', (event) => {
                    const selectedRating = index + 1;
                    reviewStarInput.value = selectedRating; // Save selected rating in hidden input

                    // Update the stars visually
                     setRating(thankYouStars, selectedRating);

                    // Fetch orderId and productId from hidden inputs
                    const orderId = document.getElementById('orderIdInputs').value;
                    const productId = document.getElementById('productIdInputs').value;

                    // Simulate button click to activate openReviewModal function
                    const fakeButton = document.createElement('button');
                    fakeButton.setAttribute('data-order-id', orderId);
                    fakeButton.setAttribute('data-product-id', productId);

                    openReviewModals({
                        preventDefault: () => {}, // Mock preventDefault for a synthetic event
                        currentTarget: fakeButton
                    });
                });
            });

            // Function to fill stars on hover
            function fillStars(stars, index) {
                stars.forEach((star, i) => {
                    if (i <= index) {
                        star.classList.add('fa-solid');
                        star.classList.remove('fa-regular');
                    } else {
                        star.classList.add('fa-regular');
                        star.classList.remove('fa-solid');
                    }
                });
            }

            // Function to reset stars to the current rating
            function resetStars(stars, rating) {
                const currentRating = parseInt(rating, 10) || 0;
                setRating(stars, currentRating);
            }

            // Function to set stars based on a given rating
            function setRating(stars, rating) {
                stars.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.add('fa-solid');
                        star.classList.remove('fa-regular');
                    } else {
                        star.classList.add('fa-regular');
                        star.classList.remove('fa-solid');
                    }
                });
            }

            // Simulated openReviewModal function
            function openReviewModals(event) {
                event.preventDefault();

                const thankYouModalElement = document.getElementById('thankYouModal');
                const thankYouModal = bootstrap.Modal.getInstance(thankYouModalElement) || new bootstrap.Modal(thankYouModalElement);

                // Hide the modal using Bootstrap's API
                thankYouModal.hide();

                // Optional: Remove the backdrop manually if still present
                const modalBackdrop = document.querySelector('.modal-backdrop');
                if (modalBackdrop) {
                    modalBackdrop.remove();
                }

                // Fetch order and product IDs from the clicked element
                const orderId = event.currentTarget.getAttribute('data-order-id');
                const productId = event.currentTarget.getAttribute('data-product-id');

                // Update hidden fields in the review modal
                const orderIdInput = document.getElementById('orderIdInput');
                const productIdInput = document.getElementById('productIdInput');
                const reviewModalRatingInput = document.getElementById('reviewRatingInput'); // Input in the review modal
                const reviewModalStars = document.querySelectorAll('#productQualityRating .star'); // Stars in the review modal

                // Ensure hidden inputs are updated with order and product IDs
                if (orderIdInput) {
                    orderIdInput.value = orderId;
                }
                if (productIdInput) {
                    productIdInput.value = productId;
                }
                // Retrieve the selected rating from the Thank You modal (if any)
                const selectedRating = parseInt(document.getElementById('ReviewStar').value, 10) || 0;

                // Update the star rating and textarea in the review modal
                if (reviewModalRatingInput) {
                    reviewModalRatingInput.value = selectedRating; // Update hidden input for rating
                }
                if (reviewModalStars) {
                    setRating(reviewModalStars, selectedRating); // Reflect star selection visually
                    expandedFields.style.display = "block";
                    productQualityDescription.style.display = "block";

                }

                // Show the review modal
                const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
                reviewModal.show();

                fetch(`/get-order-details/${orderId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                toggleSpinner(false);
                if (data.status === 'success') {
                    const orderDetails = data.order;

                    // Dynamically update modal content
                    orderDetailsContainer.innerHTML = `
                        <div class="card mb-3">
                            <div class="list-group-item order-card d-flex p-3 m-0 align-items-start">
                                <div class="me-3">
                                    <img src="${orderDetails.product_image}" alt="Product Image" class="img-fluid border" loading="lazy"
                                        style="width: 70px; height: 70px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p><strong>Product Name:</strong> ${orderDetails.product_name}</p>
                                            <p class="text-muted small"><strong>Variation:</strong> ${orderDetails.variation || 'N/A'}</p>
                                            <p class="text-muted small"><strong>Quantity:</strong> ${orderDetails.quantity}</p>
                                            <p class="text-muted small"><strong>Price:</strong> $${orderDetails.price}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    `;
                } else {
                    // Handle case where order details are not found
                    orderDetailsContainer.innerHTML = `<p class="text-danger">Unable to fetch order details. Please try again later.</p>`;
                }
            })
            .catch(error => {
                toggleSpinner(false);
                orderDetailsContainer.innerHTML = `<p class="text-danger">An error occurred. Please try again later.</p>`;
            });
            }

            function setupStarRating(starContainerId, inputId, descriptionId) {
            const stars = document.querySelectorAll(`#${starContainerId} .star`);
            const ratingInput = document.getElementById(inputId);
            const descriptionElement = document.getElementById(descriptionId);
            const expandedFields = document.getElementById('expandedFields');

            // Descriptions for ratings
            const descriptions = ["Poor", "Fair", "Good", "Very Good", "Amazing"];

            // Set initial rating from the input value
            const initialRating = parseInt(ratingInput.value, 10) || 0;
            if (initialRating > 0) {
                setRating(stars, initialRating);
                updateDescription(initialRating, descriptionElement);
                expandedFields.style.display = "block";
            }

            // Add event listeners for stars
            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => fillStars(stars, index));
                star.addEventListener('mouseleave', () => resetStars(stars, ratingInput.value));
                star.addEventListener('click', () => {
                    const selectedRating = index + 1;
                    ratingInput.value = selectedRating; // Update the hidden input
                    setRating(stars, selectedRating); // Update star visuals
                    updateDescription(selectedRating, descriptionElement); // Update description
                    expandedFields.style.display = "block"; // Show expanded fields
                });
            });
        }

        // Function to update the product quality description
        function updateDescription(rating, descriptionElement) {
            const descriptions = ["Poor", "Fair", "Good", "Very Good", "Amazing"];
            const description = descriptions[rating - 1] || "Select a rating";
            descriptionElement.textContent = description;
            descriptionElement.style.display = "inline-block"; // Make the description visible
        }

        });
    </script>
    <script>
        function openReviewModal(event) {
            event.preventDefault();

            toggleSpinner(true);

            // Get the order ID and product ID from the button's data attributes
            const orderId = event.currentTarget.getAttribute('data-order-id');
            const productId = event.currentTarget.getAttribute('data-product-id');

            // Set the hidden input fields in the modal
            document.getElementById('orderIdInput').value = orderId;
            document.getElementById('productIdInput').value = productId;

            // Reference the order details container
            const orderDetailsContainer = document.getElementById('orderDetailsContainer');

            // Fetch order details from the server
            fetch(`/get-order-details/${orderId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                toggleSpinner(false);

                if (data.status === 'success') {
                    const orderDetails = data.order;

                    // Update modal content dynamically
                    orderDetailsContainer.innerHTML = `
                        <div class="card mb-3">
                            <div class="list-group-item order-card d-flex p-3 m-0 align-items-start">
                                <div class="me-3">
                                    <img src="${orderDetails.product_image}" alt="Product Image" class="img-fluid border" loading="lazy"
                                        style="width: 70px; height: 70px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p><strong>Product Name:</strong> ${orderDetails.product_name}</p>
                                            <p class="text-muted small"><strong>Variation:</strong> ${orderDetails.variation || 'N/A'}</p>
                                            <p class="text-muted small"><strong>Quantity:</strong> ${orderDetails.quantity}</p>
                                            <p class="text-muted small"><strong>Price:</strong> $${orderDetails.price}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Handle case where order details are not found
                    orderDetailsContainer.innerHTML = `<p class="text-danger">Unable to fetch order details. Please try again later.</p>`;
                }

                // Show the modal
                const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
                reviewModal.show();
            })
            .catch(error => {
                toggleSpinner(false);
                console.error('Error fetching order details:', error);
                orderDetailsContainer.innerHTML = `<p class="text-danger">An error occurred. Please try again later.</p>`;
            });
        }

        function toggleSpinner(show) {
            const loadingSpinner = document.getElementById('loadingSpinner');
            if (loadingSpinner) {
                loadingSpinner.style.display = show ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const reviewForm = document.getElementById('reviewForm');
            const submitButton = document.getElementById('submitButton');
            const orderIdInput = document.getElementById('order-id-input');

            // Enable submit button if all required fields are filled
            reviewForm.addEventListener('input', function () {
                const allFilled = [...reviewForm.querySelectorAll('input[required]')];
                submitButton.disabled = !allFilled;
            });

            // Set `order_id` dynamically when opening the "Item Received" modal
            document.querySelectorAll('[data-bs-toggle="modal"][data-order-id]').forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    if (orderIdInput) {
                        orderIdInput.value = orderId;
                    }
                });
            });

            // Attach submitReview to the form's submit event
            reviewForm.addEventListener('submit', function (event) {
                event.preventDefault();
                submitReview();
            });

            // Submit review via AJAX
            function submitReview() {
                toggleSpinner(true);

                const formData = new FormData(reviewForm);

                fetch("{{ route('submit.review') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showSuccessModal(data.message);
                            resetForm();
                            submitButton.disabled = true;
                            toggleSpinner(false);
                            // Hide the review modal
                            const reviewModal = document.getElementById('reviewModal');
                            const modalInstance = bootstrap.Modal.getInstance(reviewModal) || new bootstrap.Modal(reviewModal);
                            modalInstance.hide();
                            // Refresh the "To-Rate" content and decrement the count
                            refreshToRateContent();
                        } else if (data.status === 'error') {
                            showErrorModal(data.errors);
                        }
                    })
                    .catch(error => {
                        toggleSpinner(false);
                        console.error('Error submitting review:', error);
                        showErrorModal('An unexpected error occurred.');
                    });
            }

            // Function to refresh the "To-Rate" content dynamically
            function refreshToRateContent() {
                const contentContainer = document.getElementById('purchaseContent');
                const loadingSpinner = document.getElementById('loadingSpinner'); // Reference to the spinner element

                // Show the spinner
                loadingSpinner.style.display = 'block';

                fetch('/mypurchase?status=to-rate', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to refresh to-rate content.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hide the spinner
                        loadingSpinner.style.display = 'none';
                        decrementToRateCount();
                        // Update the content container with the new HTML
                        contentContainer.innerHTML = data.html;
                    })
                    .catch(error => {
                        console.error('Error refreshing content:', error);

                        // Hide the spinner
                        loadingSpinner.style.display = 'none';

                        contentContainer.innerHTML = '<p>Error refreshing content. Please try again later.</p>';
                    });
            }

            // Function to decrement the "To-Rate" count
            function decrementToRateCount() {
                const toRateCountElement = document.querySelector('[data-status="to-rate"] .text-custom');
                if (toRateCountElement) {
                    const currentCount = parseInt(toRateCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                    if (!isNaN(currentCount) && currentCount > 0) {
                        toRateCountElement.textContent = `(${currentCount - 1})`; // Decrement the count
                    }
                }
                const toRateCountElement = document.querySelector('[data-status="completed"] .text-custom');
                if (toRateCountElement) {
                    const currentCount = parseInt(toRateCountElement.textContent.replace(/[()]/g, ''), 10); // Extract the current count
                    if (!isNaN(currentCount)) {
                        toRateCountElement.textContent = `(${currentCount + 1})`; // Increment the count
                    }
                }
            }
            // Reset form and clear image previews
            function resetForm() {
                reviewForm.reset();
                document.querySelectorAll('.image-upload-box').forEach(box => {
                    box.innerHTML = '<span class="text-success fw-bold">+</span>';
                });
            }

            // Success modal
            function showSuccessModal(message) {
                const successModalHtml = `
                    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h1><i class="fa-regular fa-circle-check text-success"></i></h1>
                                    <p>${message || 'Review submitted successfully!'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', successModalHtml);
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('successModal').remove();
                });
            }

            // Error modal
            function showErrorModal(errors) {
                const errorMessages = Array.isArray(errors)
                    ? errors.map(error => `<li>${error}</li>`).join('')
                    : `<li>${errors}</li>`;

                const errorModalHtml = `
                    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h1><i class="fa-regular fa-circle-xmark text-danger"></i></h1>
                                    <p>Error!</p>
                                    <ul class="text-start">${errorMessages}</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', errorModalHtml);
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
                document.getElementById('errorModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('errorModal').remove();
                });
            }

            // Image upload box preview
            document.querySelectorAll('.file-input').forEach((fileInput) => {
                fileInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    const box = fileInput.closest('.image-upload-box');
                    const plusIcon = box.querySelector('.plus-icon');

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            plusIcon.style.display = 'none';

                            let previewImage = box.querySelector('img');
                            if (!previewImage) {
                                previewImage = document.createElement('img');
                                previewImage.style.width = '100%';
                                previewImage.style.height = '100%';
                                previewImage.style.objectFit = 'cover';
                                previewImage.style.borderRadius = '5px';
                                box.appendChild(previewImage);
                            }
                            previewImage.src = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    }
                });
            });
        });
    </script>
    {{-- ;s --}}
    {{-- <script>
        let currentOrderId = null; // Store the order ID for the current cancellation

        // Function to open the modal and set the order ID
        function openCancelConfirmationModal(orderId) {
            currentOrderId = orderId; // Set the current order ID to be used in cancellation
            const cancelConfirmationModal = new bootstrap.Modal(document.getElementById('cancelConfirmationModal'));
            cancelConfirmationModal.show(); // Show the confirmation modal
        }

        // Event listener for the "Yes, Cancel Order" button in the modal
        document.addEventListener('DOMContentLoaded', function () {
            const confirmCancelOrderButton = document.getElementById('confirmCancelOrder');

            if (confirmCancelOrderButton) {
                confirmCancelOrderButton.addEventListener('click', function () {

                    const cancelConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('cancelConfirmationModal'));
                    cancelConfirmationModal.hide();


                    if (currentOrderId) {
                        // Send AJAX request to cancel the order
                        fetch(`/order/cancel/${currentOrderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ order_id: currentOrderId })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                            const statusIcon = document.querySelector('.status-icon');
                            const statusMessage = document.querySelector('.status-message');

                            if (data.success) {
                                // Update modal for success
                                statusIcon.className = 'status-icon fa-regular fa-circle-check text-success';
                                statusMessage.textContent = 'Order has been cancelled successfully.';
                                confirmationModal.show(); // Show the modal after updating content
                                setTimeout(() => {
                                    window.location.href = '/mypurchase'; // Redirect after a short delay
                                }, 2000); // Optional: delay for the user to read the message
                            } else {
                                // Update modal for failure
                                statusIcon.className = 'status-icon fa-regular fa-circle-exclamation text-danger';
                                statusMessage.textContent = 'Failed to cancel the order. Please try again.';
                                confirmationModal.show(); // Show the modal
                            }
                        })
                        .catch(error => {
                            console.error('Error occurred while canceling the order:', error);

                            // Display error in the modal as a fallback
                            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                            const statusIcon = document.querySelector('.status-icon');
                            const statusMessage = document.querySelector('.status-message');

                            statusIcon.className = 'status-icon fa-regular fa-circle-exclamation text-danger';
                            statusMessage.textContent = 'An error occurred while canceling the order. Please try again.';
                            confirmationModal.show(); // Show the modal
                        });
                    } else {
                        console.warn("No order ID specified for cancellation.");
                    }
                });
            } else {
                console.warn("The element with id 'confirmCancelOrder' was not found.");
            }
        });

    </script> --}}

    <script>
        document.getElementById('requestCancelOrder').addEventListener('click', function () {
            // Get the data-order-id attribute from the button dynamically
            const currentOrderId = this.getAttribute('data-order-id');
            const currentPaymentId = this.getAttribute('data-payment-id');

            // Close the GCash Warning Modal
            const gcashWarningModal = bootstrap.Modal.getInstance(document.getElementById('gcashWarningModal'));
            if (gcashWarningModal) {
                gcashWarningModal.hide();
            }

            // Show the spinner
            const loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.style.display = 'block';

            // Send AJAX request to cancel the order
            fetch(`/request-cancel-order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ order_id: currentOrderId, payment_id:  currentPaymentId}), // Ensure this matches server requirements
            })
                .then((response) => {
                    console.log('Response status:', response.status); // Debug: Log the response status
                    if (!response.ok) {
                        return response.json().then((error) => {
                            console.error('Server error response:', error); // Debug: Log server error
                            throw new Error(error.message || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log('Success response:', data); // Debug: Log the success response
                    loadingSpinner.style.display = 'none'; // Hide the spinner

                    if (data.success) {
                        showStatusModal('success', data.message || 'Cancel request submitted successfully!');
                        refreshContentAfterCancellation();
                    } else {
                        showStatusModal('error', data.message || 'Failed to submit cancel request.');
                    }
                })
                .catch((error) => {
                    console.error('Error occurred while canceling the order:', error); // Debug: Log errors
                    loadingSpinner.style.display = 'none'; // Hide the spinner
                    showStatusModal('error', error.message || 'An unexpected error occurred while canceling the order.');
                });
        });

        // Function to refresh the content dynamically after cancellation
        function refreshContentAfterCancellation() {
            const contentContainer = document.getElementById('purchaseContent');

            // Show the spinner
            const loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.style.display = 'block';

            fetch('/mypurchase?status=pending', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Failed to refresh content after cancellation.');
                    }
                    return response.json();
                })
                .then((data) => {
                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    // Update the content dynamically
                    contentContainer.innerHTML = data.html;

                    // Decrement the pending count and increment the cancelled count
                    decrementPendingCount1();
                })
                .catch((error) => {
                    console.error('Error refreshing content:', error); // Debug: Log errors
                    loadingSpinner.style.display = 'none'; // Hide the spinner
                    contentContainer.innerHTML = '<p>Error refreshing content. Please try again later.</p>';
                });
        }

        // Function to decrement "Pending" count dynamically
        function decrementPendingCount1() {
            const pendingCountElement = document.querySelector('[data-status="pending"] .text-custom');
            if (pendingCountElement) {
                const currentPendingCount = parseInt(pendingCountElement.textContent.replace(/[()]/g, ''), 10);
                if (!isNaN(currentPendingCount) && currentPendingCount > 0) {
                    pendingCountElement.textContent = `(${currentPendingCount - 1})`;
                }
            }

            const cancelledCountElement = document.querySelector('[data-status="cancel"] .text-custom');
            if (cancelledCountElement) {
                const currentCancelledCount = parseInt(cancelledCountElement.textContent.replace(/[()]/g, ''), 10);
                if (!isNaN(currentCancelledCount)) {
                    cancelledCountElement.textContent = `(${currentCancelledCount + 1})`;
                }
            }
        }

        // Function to show the status modal
        function showStatusModal(type, message) {
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const statusModalIcon = document.getElementById('statusModalIcon');
            const statusModalMessage = document.getElementById('statusModalMessage');

            // Configure modal content based on the type
            if (type === 'success') {
                statusModalIcon.className = 'fa-regular fa-circle-check text-success';
            } else if (type === 'error') {
                statusModalIcon.className = 'fa-regular fa-circle-xmark text-danger';
            }

            // Set the message
            statusModalMessage.textContent = message;

            // Show the modal
            statusModal.show();
        }

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('#purchaseTabs .nav-link');
            const pageHeading = document.getElementById('pageHeading'); // The heading element to update

            // Handle tab clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default link behavior

                    // Get the status from the clicked tab
                    const status = this.getAttribute('data-status');
                    const statusCapitalized = status === 'cancel'
                        ? 'Cancelled'
                        : status.charAt(0).toUpperCase() + status.slice(1);

                    // Update the URL
                    const newUrl = `/mypurchase?status=${status}`;
                    window.history.pushState(null, '', newUrl);

                    // Fetch the content for the selected status
                    fetchContentForStatus(status);

                    // Update the active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update the heading
                    pageHeading.textContent = `My Purchase (${statusCapitalized})`;
                });
            });

            // Function to fetch content dynamically
            function fetchContentForStatus(status) {
            const contentContainer = document.getElementById('purchaseContent');

            // Show the spinner
            loadingSpinner.style.display = 'block';

            fetch(`/mypurchase?status=${status}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes this as an AJAX request
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    // Update the content with the fetched HTML
                    contentContainer.innerHTML = data.html;
                })
                .catch(error => {
                    // Hide the spinner
                    loadingSpinner.style.display = 'none';

                    contentContainer.innerHTML = '<p>Error loading content. Please try again later.</p>';
                });
        }

            // Handle back/forward navigation
            window.addEventListener('popstate', function () {
                const params = new URLSearchParams(window.location.search);
                const status = params.get('status') || 'pending';
                const activeTab = document.querySelector(`#purchaseTabs .nav-link[data-status="${status}"]`);
                const statusText = activeTab ? activeTab.textContent.trim() : 'Pending Orders';

                // Update the content and active tab
                fetchContentForStatus(status);

                tabs.forEach(t => t.classList.remove('active'));
                if (activeTab) {
                    activeTab.classList.add('active');
                }

                // Update the heading
                pageHeading.textContent = `My Purchases (${statusText})`;
            });
        });
    </script>




















@endsection

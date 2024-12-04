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
        height: 280px;
        display: block;
        border-radius: 5px;
    }
    .custom-p {
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="container mt-3 mb-5">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mystore') }}">My Store</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Order</a></li>
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
                    <div class="col-md-4">
                        <img src="{{ $orderData['order_items'][0]->product->images[0]->product_img_path1 ? asset('storage/' . $orderData['order_items'][0]->product->images[0]->product_img_path1) : 'https://via.placeholder.com/200x200.png?text=Product+Image' }}" alt="Product Image" class="product-img">
                    </div>
                    <!-- Product Details -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <p><strong>Order ID:</strong> {{ $orderData['order_id'] }}</p>
                                <p><strong>Product ID:</strong> {{ $orderData['order_items'][0]->product_id }}</p>
                                <p><strong>Product Name:</strong> {{ $orderData['order_items'][0]->product_name }}</p>
                                <p><strong>Variation:</strong> {{ $orderData['order_items'][0]->variation->variation_name ?? 'N/A' }}</p>
                                <p><strong>Quantity:</strong> {{ $orderData['order_items'][0]->quantity }}</p>
                                <p><strong>Total:</strong> ₱{{ $orderData['total_amount'] }}</p>
                                <p><strong>Mode of Payment:</strong> <span id="payment-method">{{ $orderData['payment_method'] }}</span></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p id="orderStatus"><strong>Order Status:</strong> {{ ucfirst($orderData['order_status']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
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

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-custom me-2" id="confirmButton">Accept Order</button>
            <button class="btn btn-outline-danger" id="declineButton">Decline</button>
        </div>
    </div>
</div>

<!-- Payment Receipt Modal -->
<div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentReceiptModalLabel">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body text-center">
                <img id="paymentReceiptImage" src="{{ asset('storage/' . $orderData['receipt_img']) }}" alt="Payment Receipt" class="img-fluid" style="max-height: 400px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom" id="confirmButtonPaymentReceipt">Confirmed</button>
                <button type="button" class="btn btn-danger" id="declineButtonPaymentReceipt">Decline</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Payment</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you have checked the reference and it matches the receipt? We will also notify the customer via email that the payment is accepted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" id="confirmPaymentBtn">Yes, Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline Confirmation Modal -->
<div class="modal fade" id="declineConfirmationModal" tabindex="-1" aria-labelledby="declineConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineConfirmationModalLabel"><i class="fa-solid fa-triangle-exclamation" style="color: red;"></i> Confirm Decline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to decline this payment? The order will also be marked as declined.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeclineBtn">Yes, Decline</button>
            </div>
        </div>
    </div>
</div>
<!-- Activity Confirmation -->
<div class="modal fade" id="ConfirmationModal" tabindex="-1" aria-labelledby="ConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ConfirmationModalLabel">Confirm Action</h5>
            </div>
            <div class="modal-body" id="ConfirmationModalBody">
                <!-- Dynamic confirmation message will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" id="proceedActionBtn">Yes, Proceed</button>
            </div>
        </div>
    </div>
</div>
<div id="loadingSpinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050;">
    <div class="spinner-border" style="color: #228b22;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center" id="statusModalBody">
                <h1><i class="fa-regular" id="statusModalIcon"></i></h1>
                <p id="statusModalMessage"></p>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reviewPaymentBtn = document.querySelector('#reviewPaymentBtn');
        const paymentReceiptModalElement = document.getElementById('paymentReceiptModal');
        const paymentReceiptModal = new bootstrap.Modal(paymentReceiptModalElement);
        const paymentReceiptImage = document.getElementById('paymentReceiptImage');
        const confirmButtonPaymentReceipt = document.getElementById('confirmButtonPaymentReceipt');
        const declineButtonPaymentReceipt = document.getElementById('declineButtonPaymentReceipt');
        const confirmButton = document.getElementById('confirmButton');
        const declineButton = document.getElementById('declineButton');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const statusModalElement = document.getElementById('statusModal');
        const statusModal = new bootstrap.Modal(statusModalElement);
        const confirmationModalElement = document.getElementById('ConfirmationModal');
        const confirmationModal = new bootstrap.Modal(confirmationModalElement);
        const confirmationModalBody = document.getElementById('ConfirmationModalBody');
        const proceedActionBtn = document.getElementById('proceedActionBtn');

        const orderId = {{ $orderData['order_id'] }}; // Ensure the Blade template renders this correctly
        const csrfToken = '{{ csrf_token() }}'; // Laravel CSRF token

        const paymentMethod = document.getElementById('payment-method').textContent.trim();
        const paymentStatus = document.getElementById('paymentStatus')?.textContent.includes('Paid');
        const currentOrderStatus = document.getElementById('orderStatus')?.textContent.includes('To-Ship');

        function showLoadingSpinner() {
            if (loadingSpinner) loadingSpinner.style.display = 'block';
        }

        function hideLoadingSpinner() {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
        }

        function showStatusModal(isSuccess, message) {
            const modalIcon = document.getElementById('statusModalIcon');
            const modalMessage = document.getElementById('statusModalMessage');

            modalIcon.className = isSuccess
                ? 'fa-regular fa-circle-check check-icon'
                : 'fa-regular fa-circle-exclamation exclamation-icon';
            modalMessage.innerText = message || (isSuccess ? 'Update successful!' : 'An error occurred!');

            statusModal.show();
        }

        function showConfirmationModal(message, showProceedBtn, status = '') {
            confirmationModalBody.textContent = message;
            proceedActionBtn.dataset.status = status;

            proceedActionBtn.style.display = showProceedBtn ? 'inline-block' : 'none';
            confirmationModal.show();
        }

        function updateStatus(endpoint, payload, successCallback) {
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((errorData) => {
                            throw new Error(errorData.message || 'Failed to update.');
                        });
                    }
                    return response.json();
                })
                .then(successCallback)
                .catch((error) => showStatusModal(false, error.message))
                .finally(() => hideLoadingSpinner());
        }

        // Payment Receipt Modal Handling
        reviewPaymentBtn?.addEventListener('click', () => {
            const receiptImgUrl = reviewPaymentBtn.getAttribute('data-receipt-img');
            if (paymentReceiptImage) {
                paymentReceiptImage.src = receiptImgUrl || '';
                paymentReceiptModal.show();
            }
        });

        confirmButtonPaymentReceipt?.addEventListener('click', () => {
            paymentReceiptModal.hide();
            showLoadingSpinner();
            updateStatus('/update-payment-status', { order_id: orderId, payment_status: 'Paid' }, (data) => {
                showStatusModal(true, data.message || 'Payment marked as "Paid".');
                setTimeout(() => location.reload(), 2000);
            });
        });

        declineButtonPaymentReceipt?.addEventListener('click', () => {
            paymentReceiptModal.hide();
            showConfirmationModal('Are you sure you want to decline the payment?', true, 'To-pay');
        });

        proceedActionBtn?.addEventListener('click', () => {
            const status = proceedActionBtn.dataset.status;

            showLoadingSpinner();
            confirmationModal.hide();

            if (status === 'To-pay') {
                updateStatus('/update-order-status', { order_id: orderId, order_status: status }, (data) => {
                    showStatusModal(true, data.message);
                    setTimeout(() => window.location.href = '/orders', 3000);
                });
            } else {
                updateStatus('/update-order-status', { order_id: orderId, order_status: status }, (data) => {
                    showStatusModal(true, data.message);
                    setTimeout(() => window.location.href = '/orders', 3000);
                });
            }
        });

        // Accept Order Handling
        confirmButton?.addEventListener('click', () => {
            if (paymentMethod === 'COD' || (paymentMethod === 'GCash' && paymentStatus)) {
                showConfirmationModal(
                    'Are you sure you want to accept this order? It will be marked as "To-Ship".',
                    true,
                    'To-Ship'
                );
            } else if (paymentMethod === 'GCash' && !paymentStatus) {
                showConfirmationModal('GCash orders must be marked as "Paid" to accept.', false);
            }
        });

        // Decline Order Handling
        declineButton?.addEventListener('click', () => {
            if (currentOrderStatus || paymentStatus) {
                showConfirmationModal(
                    'This order cannot be declined because the payment is marked as Paid.',
                    false
                );
            } else {
                showConfirmationModal(
                    'Are you sure you want to decline this order? This action cannot be undone.',
                    true,
                    'Declined'
                );
            }
        });
    });
</script>


{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const reviewPaymentBtn = document.querySelector('#reviewPaymentBtn');
        const paymentReceiptModalElement = document.getElementById('paymentReceiptModal');
        const paymentReceiptModal = new bootstrap.Modal(paymentReceiptModalElement);
        const paymentReceiptImage = document.getElementById('paymentReceiptImage');
        const confirmButtonPaymentReceipt = document.getElementById('confirmButtonPaymentReceipt');
        const declineButtonPaymentReceipt = document.getElementById('declineButtonPaymentReceipt');

        // Modal and spinner references
        const statusModalElement = document.getElementById('statusModal');
        const statusModal = new bootstrap.Modal(statusModalElement);
        const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
        const declineConfirmationModalElement = document.getElementById('declineConfirmationModal');
        const declineConfirmationModal = new bootstrap.Modal(declineConfirmationModalElement);
        const confirmDeclineBtn = document.getElementById('confirmDeclineBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');

        function showLoadingSpinner() {
            if (loadingSpinner) {
                loadingSpinner.style.display = 'block';
            }
        }

        function hideLoadingSpinner() {
            if (loadingSpinner) {
                loadingSpinner.style.display = 'none';
            }
        }

        function showStatusModal(isSuccess, message = 'Update successful!') {
            const modalIcon = document.getElementById('statusModalIcon');
            const modalMessage = document.getElementById('statusModalMessage');

            if (isSuccess) {
                modalIcon.className = 'fa-regular fa-circle-check check-icon';
                modalMessage.innerText = message;
            } else {
                modalIcon.className = 'fa-regular fa-circle-exclamation exclamation-icon';
                modalMessage.innerText = message || 'An error occurred!';
            }

            statusModal.show();
        }

        // Function to handle payment status update
        function updatePaymentStatus(status) {
            const orderId = {{ $orderData['order_id'] }}; // Ensure this is correctly embedded in your Blade template

            fetch('/update-payment-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                },
                body: JSON.stringify({ order_id: orderId, payment_status: status }) // Send the order ID and new status
            })
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    hideLoadingSpinner(); // Hide the spinner after the request is processed

                    if (data.success) {
                        // Update the displayed payment status
                        document.getElementById('paymentStatus').innerHTML = `<strong>Payment Status:</strong> ${status}`;
                        showStatusModal(true, data.message || 'Payment status updated successfully.');

                        if (data.redirect) {
                            // Delay redirection to show modal
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000); // 2-second delay
                        } else {
                            // Reload the page to reflect updated status
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    } else {
                        // Show error modal
                        showStatusModal(false, data.message || 'Failed to update payment status.');
                    }
                })
                .catch(error => {
                    showStatusModal(false, 'An error occurred while updating payment status.');
                    hideLoadingSpinner(); // Hide the spinner even on error
                });
        }

        // Handle the "Review Payment" button click
        if (reviewPaymentBtn) {
            reviewPaymentBtn.addEventListener('click', function () {
                const receiptImgUrl = reviewPaymentBtn.getAttribute('data-receipt-img');
                if (paymentReceiptImage) {
                    paymentReceiptImage.src = receiptImgUrl || '';
                    paymentReceiptModal.show();
                }
            });
        }

        // Confirm button in payment receipt modal (when "Paid" status is confirmed)
        if (confirmButtonPaymentReceipt) {
            confirmButtonPaymentReceipt.addEventListener('click', function () {
                paymentReceiptModal.hide();
                showLoadingSpinner();
                updatePaymentStatus('Paid');
            });
        }

        // Decline button in payment receipt modal (show decline confirmation modal)
        if (declineButtonPaymentReceipt) {
            declineButtonPaymentReceipt.addEventListener('click', function () {
                declineConfirmationModal.show();
                paymentReceiptModal.hide();
            });
        }

        // Confirm decline button (update to "To-pay" status)
        if (confirmDeclineBtn) {
            confirmDeclineBtn.addEventListener('click', function () {
                showLoadingSpinner();
                updatePaymentStatus('To-pay');
                declineConfirmationModal.hide();
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements for Accept and Decline buttons
        const confirmButton = document.getElementById('confirmButton');
        const declineButton = document.getElementById('declineButton');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Get payment method and status from the page
        const paymentMethod = document.getElementById('payment-method').textContent.trim();
        const paymentStatus = document.getElementById('paymentStatus') ? document.getElementById('paymentStatus').textContent.includes('Paid') : false;
        const currentOrderStatus = document.getElementById('orderStatus') ? document.getElementById('orderStatus').textContent.includes('To-Ship') : false;

        // Get elements for the confirmation modal

        const statusModalElement = document.getElementById('statusModal');
        const statusModal = new bootstrap.Modal(statusModalElement);
        const confirmationModalElement = document.getElementById('ConfirmationModal');
        const confirmationModal = new bootstrap.Modal(confirmationModalElement);
        const confirmationModalBody = document.getElementById('ConfirmationModalBody');
        const proceedActionBtn = document.getElementById('proceedActionBtn');

        function showStatusModal(isSuccess, message = 'Update successful!') {
            const modalIcon = document.getElementById('statusModalIcon');
            const modalMessage = document.getElementById('statusModalMessage');

            if (isSuccess) {
                modalIcon.className = 'fa-regular fa-circle-check check-icon';
                modalMessage.innerText = message;
            } else {
                modalIcon.className = 'fa-regular fa-circle-exclamation exclamation-icon';
                modalMessage.innerText = message || 'An error occurred!';
            }

            statusModal.show();
        }

        // Handle "Accept Order" button click
        if (confirmButton) {
            confirmButton.addEventListener('click', function() {
                if (paymentMethod === 'COD' || (paymentMethod === 'GCash' && paymentStatus)) {
                    showConfirmationModal('Are you sure you want to accept this order? It will be marked as "To-Ship".', true, 'To-Ship');
                } else if (paymentMethod === 'GCash' && !paymentStatus) {
                    showConfirmationModal('GCash orders must be marked as "Paid" to accept.', false);
                }
            });
        }

        // Handle "Decline" button click
        if (declineButton) {
            declineButton.addEventListener('click', function() {
                if (currentOrderStatus || paymentStatus) {
                    showConfirmationModal('This order cannot be declined because the payment is marked as Paid.');
                    return;
                }
                showConfirmationModal('Are you sure you want to decline this order? This action cannot be undone.', true, 'Declined');
            });
        }

        // Function to show the confirmation modal
        function showConfirmationModal(message, showProceedBtn, status = '') {
            confirmationModalBody.textContent = message;
            proceedActionBtn.dataset.status = status; // Set the status to update on confirmation

            if (showProceedBtn) {
                proceedActionBtn.style.display = 'inline-block';
            } else {
                proceedActionBtn.style.display = 'none';
            }

            confirmationModal.show();
        }

        // Handle "Yes, Proceed" button click in the confirmation modal
        if (proceedActionBtn) {
            proceedActionBtn.addEventListener('click', function() {
                const status = this.dataset.status;

                // Show loading spinner
                showLoadingSpinner();

                // Hide the confirmation modal
                confirmationModal.hide();

                // Update the order status
                updateOrderStatus(status);
            });
        }

        // Function to show the loading spinner
        function showLoadingSpinner() {
            loadingSpinner.style.display = 'block';
        }

        // Function to hide the loading spinner
        function hideLoadingSpinner() {
            loadingSpinner.style.display = 'none';
        }

        // Function to update order status
        function updateOrderStatus(status) {
            const orderId = {{ $orderData['order_id'] }}; // Use server-side order ID

            fetch('/update-order-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order_id: orderId, order_status: status })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Failed to update order status.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the displayed order status on the page
                    const orderStatusElement = document.getElementById('orderStatus');
                    if (orderStatusElement) {
                        orderStatusElement.innerHTML = `<strong>Order Status:</strong> ${status}`;
                    }

                    // Show a success message based on the updated status
                    if (status === 'To-Ship') {
                        showStatusModal(data.success, data.message);
                        // showStatusModal(`The order has been successfully updated to 'To-Ship'. We kindly ask you to prepare the product for shipment. Thank you!`);
                        setTimeout(() => {
                            window.location.href = '/orders';
                        }, 2000);
                    } else if (status === 'Declined') {
                        showStatusModal('The order has been marked as "Declined". Thank you for reviewing it.');
                        setTimeout(() => {
                            window.location.href = '/orders';
                        }, 2000);
                    }
                } else {
                    showStatusModal(data.message, false);
                }
            })
            .catch(error => {
                // showConfirmationModal(`An error occurred while updating the order status: ${error.message}`, false);
            })
            .finally(() => {
                // Hide loading spinner
                hideLoadingSpinner();
            });
        }
    });
</script> --}}


@endsection

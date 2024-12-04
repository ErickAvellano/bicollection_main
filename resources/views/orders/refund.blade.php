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

    .card {
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #fff;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .receipt-image {
        width: 100%;
        height: auto;
        max-width: 250px;
        background-color: #fff;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
    }

    .section {
        border: 1px solid #ccc;
        padding: 15px;
        background-color: #fff;
        margin-top: 15px;
    }

    .summary-details, .profile-details {
        display: flex;
        flex-direction: column;
    }

    button {
        padding: 5px 15px;
        cursor: pointer;
    }
    .customer-details{
        margin-top: 13px;
    }
    .summary-details p,
    .customer-details p{
        margin-left: 15px !important;
        margin-bottom: 5px;
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
        <li class="breadcrumb-item active">Refund request</li>
    </ol>

    <!-- Order Details Container -->
    <div class="container">
        <div class="row d-flex justify-content-center">
            <!-- Submitted Receipt Section -->
            <div class="col-md-4">
                <div class="card receipt-section">
                    <h3 class="card-title">Submitted Receipt</h3>
                    <div class="card-content text-center">
                        <img src="{{ asset('storage/' . $orderData['receipt_img']) }}" alt="Receipt QR Code" class="receipt-image">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="#" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#confirmationModal" id="verifyBtn">Verified</a>
                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationModal" id="notMatchBtn">Not Match</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary and Customer Profile Section -->
            <div class="col-md-6">
                <div class="row">
                    <div class="card summary-details">
                        <h3 class="card-title">Order Summary</h3>
                        <div class="card-content summary-details">
                            <p><strong>Order ID:</strong> {{ $orderData['order_id'] }}</p>
                            <p><strong>Product Name:</strong> {{ $orderData['order_items'][0]->product_name }}</p>
                            <p><strong>Quantity:</strong> {{ $orderData['order_items'][0]->quantity }}</p>

                            <p><strong>Mode of Payment:</strong> <span id="payment-method">{{ $orderData['payment_method'] }}</span></p>
                        </div>
                    </div>
                    <!-- Customer Profile -->
                    <div class="card customer-details">
                        <h3 class="card-title">Customer Profile</h3>
                        <div class="card-content profile-details">
                            <div class="d-flex justify-content-between">
                                <p><strong>Customer Name:</strong> {{ $orderData['customer_username'] }}</p>
                                <p><strong>Contact:</strong> {{ $orderData['contact_number'] }}</p>
                            </div>
                            <h3 class="card-title mt-3">G-Cash Account</h3>
                            <p><strong>Customer Name:</strong> {{ $orderData['customer_name'] }}</p>
                            <p><strong>Gcash Number:</strong> {{ $orderData['contact_number'] }}</p>
                            <p><strong>Amount to Return:</strong> ₱{{ $orderData['total_amount'] }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <button class="btn btn-custom" id="doneButton" disabled>Done</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Return</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation Required</h5>
            </div>
            <div class="modal-body" id="confirmationMessage">
                <!-- Dynamic message will be inserted here based on the button clicked -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" id="confirmActionBtn">Yes, Confirm</button>
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
    // Button and modal element references
    const verifyBtn = document.getElementById('verifyBtn');
    const notMatchBtn = document.getElementById('notMatchBtn');
    const confirmationMessage = document.getElementById('confirmationMessage');
    const confirmActionBtn = document.getElementById('confirmActionBtn');
    const doneButton = document.getElementById('doneButton'); // Reference the "Done" button

    let actionType;

    // Event listeners to set the confirmation message and action type
    verifyBtn.addEventListener('click', () => {
        confirmationMessage.innerHTML = "Are you sure that the reference number you received matches the submitted reference number? If yes, please proceed with sending back the cash to the customer's GCash account. Once the refund is complete, press 'Done' to notify the customer about the successful refund.";
        actionType = 'verify';
    });

    notMatchBtn.addEventListener('click', () => {
        confirmationMessage.innerHTML = "Are you sure you haven't received a payment with the same reference number shown in the images? If yes, click 'Yes, Confirm' to automatically cancel the transaction. You will be redirected to the Order page afterward.";
        actionType = 'notMatch';
    });

    // Handle "Done" button click
    doneButton.addEventListener('click', () => {
        confirmationMessage.innerHTML = "Are you sure you already refunded the money to the customer? If yes, confirm to finalize the process.";
        actionType = 'done'; // Set action type for "Done"

        // Show the confirmation modal
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();
    });

    // Confirmation action handler
    confirmActionBtn.addEventListener('click', () => {
        const orderId = {{ $orderData['order_id'] }}; // Dynamically set the order ID

        let url, successMessage;

        if (actionType === 'verify') {
            url = '/order/verify';
            successMessage = "Now please send the cash back via Gcash";
        } else if (actionType === 'notMatch') {
            url = '/order/not-match';
            successMessage = "Order has been cancelled due to reference mismatch.";
        } else if (actionType === 'done') {
            url = '/order/done';
            successMessage = "Refund marked as completed!";
        }

        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block'; // Show loading spinner

        // Hide confirmation modal before sending the request
        const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
        confirmationModal.hide(); // Hide the confirmation modal

        // AJAX request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => response.json())
        .then(data => {
            // Hide the spinner after the request
            document.getElementById('loadingSpinner').style.display = 'none';

            // Handle success
            if (data.status === 'success') {
                // Show the status modal with success message
                showStatusModal(true, successMessage);
                doneButton.disabled = false; // Enable the button

            } else {
                // If the request was not successful, show the status modal with the failure message
                showStatusModal(false, data.message);
            }

            // Redirect for Not Match action
            if (actionType === 'notMatch') {
                setTimeout(() => {
                    window.location.href = "/orders"; // Redirect to orders page
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            showStatusModal(false, "An error occurred. Please try again.");
        });
    });

    // Function to display the status modal
    function showStatusModal(isSuccess, message) {
        const modalIcon = document.getElementById('statusModalIcon');
        const modalMessage = document.getElementById('statusModalMessage');

        modalIcon.className = isSuccess
            ? 'fa-regular fa-circle-check text-success'
            : 'fa-regular fa-circle-exclamation text-danger';
        modalMessage.innerText = message;

        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }
</script>



{{-- <script>
    // Get references to the buttons
    const verifyBtn = document.getElementById('verifyBtn');
    const notMatchBtn = document.getElementById('notMatchBtn');
    const confirmationMessage = document.getElementById('confirmationMessage');
    const confirmActionBtn = document.getElementById('confirmActionBtn');

    let actionType;

    // Event listeners for each button to set the appropriate confirmation message
    verifyBtn.addEventListener('click', () => {
        confirmationMessage.innerHTML = "Are you sure that the reference number you received matches the submitted reference number? If yes, please proceed with sending back the cash to the customer's GCash account. Once the refund is complete, press 'Done' to notify the customer about the successful refund.";
        actionType = 'verify';
        // confirmActionBtn.onclick = processVerifiedAction;
    });

    notMatchBtn.addEventListener('click', () => {
        confirmationMessage.innerHTML = "Are you sure you haven't received a payment with the same reference number shown in the images? If yes, click 'Yes, Confirm' to automatically cancel the transaction. You will be redirected to the Order page afterward.";
        actionType = 'notMatch';
        // confirmActionBtn.onclick = processNotMatchAction;
    });

    / Function to handle the confirmation action
    confirmActionBtn.addEventListener('click', () => {
        let orderId = {{ $orderData['order_id'] }}; // Replace this with the actual order ID

        let url = actionType === 'verify' ? '/order/verify' : '/order/not-match';
        let message = actionType === 'verify' ? "Refund processed successfully!" : "Order has been cancelled due to reference mismatch.";

        // Close the confirmation modal
        let confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.hide();

        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block';

        // AJAX request to the backend
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => response.json())
        .then(data => {
            // Hide the loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';

            if (data.status === 'success') {
                showStatusModal(true, message);
            } else {
                showStatusModal(false, data.message);
            }

            // Redirect to Orders page after 2 seconds if Not Match
            if (actionType === 'notMatch') {
                setTimeout(() => {
                    window.location.href = "/orders"; // Replace with your Orders page URL
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            showStatusModal(false, "An error occurred. Please try again.");
        });
    });

    // Function to handle the Verified action
    function processVerifiedAction() {
        // Close the confirmation modal
        let confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.hide();

        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block';

        // Simulate a delay for processing (e.g., 2 seconds)
        setTimeout(function () {
            // Hide the loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';

            // Show success status modal
            showStatusModal(true, "Refund processed successfully! The customer will be notified about the refund.");
        }, 2000);
    }

    // Function to handle the Not Match action
    function processNotMatchAction() {
        // Close the confirmation modal
        let confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.hide();

        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block';

        // Simulate a delay for processing (e.g., 2 seconds)
        setTimeout(function () {
            // Hide the loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';

            // Show cancellation status modal and redirect
            showStatusModal(false, "Order has been cancelled due to reference mismatch. Redirecting to the Orders page...");

            // Redirect to Orders page after 2 seconds
            setTimeout(() => {
                window.location.href = "/orders"; // Replace "/orders" with your actual Orders page URL
            }, 2000);
        }, 2000);
    }

    // Function to show status modal with a custom message
    function showStatusModal(isSuccess, message) {
        const modalIcon = document.getElementById('statusModalIcon');
        const modalMessage = document.getElementById('statusModalMessage');

        if (isSuccess) {
            modalIcon.className = 'fa-regular fa-circle-check text-success';
        } else {
            modalIcon.className = 'fa-regular fa-circle-exclamation text-danger';
        }
        modalMessage.innerText = message;

        // Show the status modal
        let statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    }

</script> --}}


{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewPaymentBtn = document.querySelector('#reviewPaymentBtn');
        const paymentReceiptModalElement = document.getElementById('paymentReceiptModal');
        const paymentReceiptModal = new bootstrap.Modal(paymentReceiptModalElement);
        const paymentReceiptImage = document.getElementById('paymentReceiptImage');
        const confirmButtonPaymentReceipt = document.getElementById('confirmButtonPaymentReceipt');
        const declineButtonPaymentReceipt = document.getElementById('declineButtonPaymentReceipt');

        // Updated modal element references
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

        function showStatusModal(isSuccess) {
            const modalIcon = document.getElementById('statusModalIcon');
            const modalMessage = document.getElementById('statusModalMessage');

            if (isSuccess) {
                modalIcon.className = 'fa-regular fa-circle-check check-icon';
                modalMessage.innerText = 'Update successful!';
            } else {
                modalIcon.className = 'fa-regular fa-circle-exclamation exclamation-icon';
                modalMessage.innerText = 'An error occurred!';
            }

            statusModal.show();
        }

        if (reviewPaymentBtn) {
            reviewPaymentBtn.addEventListener('click', function() {
                const receiptImgUrl = reviewPaymentBtn.getAttribute('data-receipt-img');
                if (paymentReceiptImage) {
                    paymentReceiptImage.src = receiptImgUrl || '';
                    paymentReceiptModal.show();
                }
            });
        }

        if (confirmButtonPaymentReceipt) {
            confirmButtonPaymentReceipt.addEventListener('click', function() {
                statusModal.show();
                paymentReceiptModal.hide();
            });
        }

        if (declineButtonPaymentReceipt) {
            declineButtonPaymentReceipt.addEventListener('click', function() {
                declineConfirmationModal.show();
                paymentReceiptModal.hide();
            });
        }

        if (confirmPaymentBtn) {
            confirmPaymentBtn.addEventListener('click', function() {
                showLoadingSpinner();
                updatePaymentStatus('Paid');
                statusModal.hide();
            });
        }

        if (confirmDeclineBtn) {
            confirmDeclineBtn.addEventListener('click', function() {
                showLoadingSpinner();
                updatePaymentStatus('To-pay');
                declineConfirmationModal.hide();
            });
        }

        function updatePaymentStatus(status) {
            const orderId = {{ $orderData['order_id'] }};

            fetch('/update-payment-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId, payment_status: status })
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingSpinner();
                if (data.success) {
                    document.getElementById('paymentStatus').innerHTML = `<strong>Payment Status:</strong> ${status}`;
                    showStatusModal(true);
                    setTimeout(() => {
                        window.location.href = '/orders'; // Redirect to /orders after success
                    }, 2000);
                } else {
                    showStatusModal(false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoadingSpinner();
                showStatusModal(false);
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
        const confirmationModalElement = document.getElementById('ConfirmationModal');
        const confirmationModal = new bootstrap.Modal(confirmationModalElement);
        const confirmationModalBody = document.getElementById('ConfirmationModalBody');
        const proceedActionBtn = document.getElementById('proceedActionBtn');

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
                        showConfirmationModal(`The order has been successfully updated to 'To-Ship'. We kindly ask you to prepare the product for shipment. Thank you!`, false);
                        setTimeout(() => {
                            window.location.href = '/orders';
                        }, 2000);
                    } else if (status === 'Declined') {
                        showConfirmationModal('The order has been marked as "Declined". Thank you for reviewing it.', false);
                        setTimeout(() => {
                            window.location.href = '/orders';
                        }, 2000);
                    }
                } else {
                    showConfirmationModal(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                showConfirmationModal(`An error occurred while updating the order status: ${error.message}`, false);
            })
            .finally(() => {
                // Hide loading spinner
                hideLoadingSpinner();
            });
        }
    });
</script> --}}


@endsection

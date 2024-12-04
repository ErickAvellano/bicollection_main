@extends('Components.layout')

@section('styles')
<style>
    /* General UI Styling */
    .search-control,
    .nav-pills,
    .search-icon {
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

    .card {
        border: none;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
    }

    .gcash-qr {
        width: 100%;
        max-width: 259px;
        height: auto;
        margin: 0 auto 15px;
        display: block;
        border: 2px solid #28a745;
        border-radius: 5px;
    }

    .form-group label {
        font-weight: bold;
    }

    .info-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #228b22;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        animation: bounce 2s infinite;
    }

    .info-button:hover {
        transform: scale(1.1);
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }
    .btn-custom:hover,
    .btn-custom:focus {
        background-color: #fafafa; /* Custom hover background color */
        border-color: #228b22; /* Custom hover border color */
        color: black;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container mt-3 mb-4">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item active">GCash Payment</li>
    </ol>

    <!-- GCash Payment and Order Details -->
    <div class="row justify-content-center">
        <!-- GCash QR Code Section -->
        <div class="col-md-5 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    @if($merchantMop && $merchantMop->account_type === 'GCash')
                        <img src="{{ asset('storage/' . $merchantMop->gcash_qr_code) }}"
                             alt="GCash QR Code"
                             class="gcash-qr">
                    @else
                        <p>No GCash account information available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details Section -->
        <div class="col-md-6 mb-4">
            <!-- GCash Details Card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">GCash Details</h4>
                    <p class="mt-3"><strong>Account Name:</strong> {{ $merchantMop->account_name }}</p>
                    <p><strong>GCash Number:</strong> {{ $merchantMop->account_number }}</p>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Details</h4>
                    <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
                    <p><strong>Total Amount to Pay:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                    <h5 class="mt-4">Upload GCash Receipt</h5>
                    <form id="gcashPaymentForm" action="{{ route('confirmGcashPayment') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-3">
                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                            <input type="file" class="form-control" id="uploadReceipt" name="gcash_receipt" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mt-4" id="proceedButton">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Instruction Modal -->
<div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instructionModalLabel">Complete Your Purchase</h5>
                <button type="button" class="btn-close d-flex justify-content-center" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <h6><strong>Step-by-Step Instructions:</strong></h6>
                <ul class="list-group">
                    <li class="list-group-item"><strong>1. Scan the QR Code or use the GCash number:</strong> Open your GCash app and either scan the QR code or manually enter the GCash number shown.</li>
                    <li class="list-group-item"><strong>2. Complete the payment:</strong> Enter the amount and confirm the transaction.</li>
                    <li class="list-group-item"><strong>3. Download the receipt:</strong> Screenshot or save the transaction receipt.</li>
                    <li class="list-group-item"><strong>4. Upload the receipt:</strong> Use the "Upload GCash Receipt" field to upload your payment receipt and click "Submit Payment".</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom" data-bs-dismiss="modal">Got It!</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Payment Received</h5>
            </div>
            <div class="modal-body">
                Your payment has been submitted successfully and is now under review. We will notify you once it is confirmed. Thank you for your patience!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom" id="goToDashboard">Return</button>
            </div>
        </div>
    </div>
</div>

<!-- "i" Button for Re-opening Modal -->
<div class="info-button" id="infoButton" data-bs-toggle="tooltip" data-bs-placement="left" title="Instructions">
    <i class="fas fa-info"></i>
</div>
<!-- Spinner Overlay -->
<div id="loadingSpinner" class="spinner-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>


@endsection
@section('scripts')
<!-- JavaScript for Spinner and Form Submission -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('gcashPaymentForm');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const successModalElement = new bootstrap.Modal(document.getElementById('successModal'));
        const instructionModal = new bootstrap.Modal(document.getElementById('instructionModal'));

        // Ensure the spinner is hidden initially
        loadingSpinner.style.display = 'none';

        // Show instruction modal on page load
        instructionModal.show();

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Reopen modal when "i" button is clicked
        document.getElementById('infoButton').addEventListener('click', function() {
            instructionModal.show();
        });

        // Prevent form submission if no file is uploaded
        document.getElementById('proceedButton').addEventListener('click', function(e) {
            const uploadReceipt = document.getElementById('uploadReceipt');
            if (!uploadReceipt.files.length) {
                alert('Please upload a receipt before submitting.');
                e.preventDefault();
            }
        });

        // Handle form submission using AJAX
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const uploadReceipt = document.getElementById('uploadReceipt');
            if (uploadReceipt.files.length > 0) {
                // Show spinner on form submission
                loadingSpinner.style.display = 'flex';

                // Prepare form data for AJAX request
                const formData = new FormData(form);

                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide spinner after response
                    loadingSpinner.style.display = 'none';

                    if (data.success) {
                        // Show success modal if payment is successful
                        successModalElement.show();
                        setTimeout(function() {
                            window.location.href = '/dashboard';
                        }, 4000);

                    } else {
                        // Show error message if payment fails
                        alert(data.message || 'Payment confirmation failed.');
                    }
                })
                .catch(error => {
                    // Hide spinner and show error message in case of network error
                    loadingSpinner.style.display = 'none';
                    alert('An error occurred while confirming the payment. Please try again.');
                    console.error('Error:', error);
                });
            }
        });

        // Handle the "Go to Dashboard" button click
        document.getElementById('goToDashboard').addEventListener('click', function() {
            window.location.href = '/dashboard'; // Redirect to dashboard
        });
    });


</script>
@endsection

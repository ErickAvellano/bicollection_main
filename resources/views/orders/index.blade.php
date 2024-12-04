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
        .order-actions {
            display: flex;
            gap: 5px;
        }
        .text-custom {
            color: #333;
        }
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        .btn-accept {
            background-color: #28a745;
            color: white;
        }
        .btn-decline {
            background-color: #dc3545;
            color: white;
        }

    </style>
@endsection

@section('content')
<div class="container mt-3 mb-5">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mystore') }}">My Store</a></li>
        <li class="breadcrumb-item active">Order</li>
    </ol>

    <!-- Order Status Tabs -->
    <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="#" data-status="pending" role="tab">
                Pending Orders <span class="text-custom">({{ $statusCounts['pending'] ?? 0 }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-ship" role="tab">
                Orders to Ship <span class="text-custom">({{ $statusCounts['to-ship'] ?? 0 }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="to-refund" role="tab">
                Refund Request <span class="text-custom">({{ $statusCounts['to-refund'] ?? 0 }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="completed" role="tab">
                Completed Orders <span class="text-custom">({{ $statusCounts['completed'] ?? 0 }})</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#" data-status="canceled" role="tab">
                Canceled Orders <span class="text-custom">({{ $statusCounts['cancel'] ?? 0 }})</span>
            </a>
        </li>
    </ul>

    <!-- Sort By Dropdown -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Orders</h4>
        <div>
            <label for="sortBy">Sort by:</label>
            <select id="sortBy" class="form-control form-control-sm">
                <option value="">Select Sort Option</option>
                <option value="GCash">MOP: GCash</option>
                <option value="COD">MOP: COD</option>
                <option value="date">Date</option>
            </select>
        </div>
    </div>

    <!-- Orders List -->
    <div id="orderContent" class="mt-3">
        @if ($orders->isEmpty())
            <p>No {{ ucfirst($status) }} orders found.</p>
        @else
            <div class="list-group">
                @foreach ($orders as $order)
                    <div class="list-group-item order-card d-flex p-3 align-items-start">
                        <!-- Product Image -->
                        <div class="me-3">
                            @php
                                $orderItem = $order->orderItems->first();
                                $product = $orderItem ? $orderItem->product : null;
                                $image = $product ? $product->images->first() : null;
                                $imagePath = $image ? $image->product_img_path1 : 'https://via.placeholder.com/60';
                            @endphp
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Product Image"
                                class="img-fluid border" loading="lazy"
                                style="width: 170px; height: 170px; object-fit: cover;">
                        </div>

                        <!-- Order Details -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <!-- Left Side Details -->
                                <div>
                                    <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
                                    <p><strong>Username:</strong> {{ $order->customer->username ?? 'N/A' }}</p>
                                    <p><strong>Product Name:</strong> {{ $orderItem->product->product_name ?? 'N/A' }}</p>
                                    <p><strong>Mode of Payment:</strong> {{ $order->payment->payment_method ?? 'N/A' }}</p>
                                    <p><strong>Customer Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                                </div>
                                <!-- Right Side Details -->
                                <div class="text-end">
                                    <p><strong>Status:</strong> {{ ucfirst($order->order_status) }}</p>
                                    <p class="text-start"><strong>Qty:</strong> {{ $order->orderItems->sum('quantity') }}</p>
                                    <p class="text-start"><strong>Total:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end mt-3">
                                @if ($order->order_status === 'pending')
                                    @if ($order->payment->payment_method === 'COD')
                                        <!-- Show all buttons (View, Accept, Decline) for COD and Pending orders -->
                                        <a href="{{ route('orders.detail', ['order_id' => $order->order_id]) }}"
                                            class="btn btn-view me-2"
                                            data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            data-content="View order details">
                                             View
                                         </a>
                                         <form action="{{ route('index.update.order.status', ['order_id' => $order->order_id]) }}" method="POST" class="d-inline me-2" id="acceptForm_{{ $order->order_id }}">
                                            @csrf
                                            <input type="hidden" name="order_status" value="To-Ship">
                                            <a href="javascript:void(0)" class="btn btn-accept" id="confirmAccept_{{ $order->order_id }}">Accept</a>
                                        </form>

                                        <!-- Decline Form -->
                                        <form action="{{ route('index.update.order.status', ['order_id' => $order->order_id]) }}" method="POST" class="d-inline" id="declineForm_{{ $order->order_id }}">
                                            @csrf
                                            <input type="hidden" name="order_status" value="Declined">
                                            <a href="javascript:void(0)" class="btn btn-decline" id="confirmDecline_{{ $order->order_id }}">Decline</a>
                                        </form>
                                    @elseif ($order->payment->payment_method === 'GCash')
                                        <!-- Show only the View button for GCash and Pending orders -->
                                        <a href="{{ route('orders.detail', ['order_id' => $order->order_id]) }}"
                                            class="btn btn-view me-2"
                                            data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            data-content="View order details">
                                             View
                                         </a>
                                    @endif
                                @elseif ($order->order_status === 'to-ship')
                                    <!-- Show only the Ready button for To-Ship orders -->
                                    <form action="{{ route('index.update.order.status', ['order_id' => $order->order_id]) }}" method="POST" class="d-inline me-2" id="readyForm_{{ $order->order_id }}">
                                        @csrf
                                        <input type="hidden" name="order_status" value="Ready">
                                        <a href="javascript:void(0)" class="btn btn-outline-custom" id="confirmReady_{{ $order->order_id }}">Ready</a>
                                    </form>
                                @elseif ($order->order_status === 'completed' || $order->order_status === 'canceled')
                                    <!-- No buttons for Completed or Canceled orders -->
                                @elseif ($order->order_status === 'to-refund')
                                    <a href="{{ route('orders.refund', ['order_id' => $order->order_id]) }}" class="btn btn-custom me-2">View Refund Details</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#orderTabs a.nav-link');
            const sortBy = document.getElementById('sortBy');
            const orderContent = document.getElementById('orderContent');

            // Function to load orders based on selected tab and sorting
            function loadOrders(status, sort = '') {
                // Construct the URL with status and sorting parameters
                const url = new URL(window.location.origin + '/orders');
                url.searchParams.append('status', status);
                if (sort) {
                    url.searchParams.append('sort', sort);
                }

                // Fetch data with AJAX
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
                    console.log('Response Data:', data); // Debugging the response

                    if (data.html) {
                        // Update the order content if 'html' is present in the response
                        orderContent.innerHTML = data.html;
                    } else {
                        console.error('No HTML content found in the response.');
                        orderContent.innerHTML = '<p>Error loading orders.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    orderContent.innerHTML = '<p>Failed to load orders. Please try again.</p>';
                });
            }

            // Handle tab clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all tabs and set the clicked tab as active
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Get the status from the clicked tab
                    const status = this.getAttribute('data-status');

                    // Reset the sort dropdown to default
                    sortBy.value = '';

                    // Load orders based on status and default sort option
                    loadOrders(status);
                });
            });

            // Handle sorting dropdown change
            sortBy.addEventListener('change', function() {
                const activeTab = document.querySelector('#orderTabs .nav-link.active');
                const status = activeTab ? activeTab.getAttribute('data-status') : 'pending';
                const selectedSort = sortBy.value;

                // Load orders based on status and selected sort option
                loadOrders(status, selectedSort);
            });

            // On initial load, load orders for the active tab and default sort
            const activeTab = document.querySelector('#orderTabs .nav-link.active');
            const initialStatus = activeTab ? activeTab.getAttribute('data-status') : 'pending';
            const initialSort = sortBy.value;
            loadOrders(initialStatus, initialSort);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event delegation for dynamically loaded elements
            document.body.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-accept') || event.target.classList.contains('btn-decline') || event.target.classList.contains('btn-outline-custom')) {
                    const action = event.target.classList.contains('btn-accept') ? 'accept' :
                                event.target.classList.contains('btn-decline') ? 'decline' : 'ready';
                    const orderId = event.target.id.split('_')[1];
                    const formToSubmit = document.getElementById(`${action}Form_${orderId}`);
                    showConfirmationModal(action, formToSubmit);
                }
            });

            function showConfirmationModal(action, form) {
                const confirmationModal = new bootstrap.Modal(document.getElementById('ConfirmationModal'));
                const confirmationModalBody = document.getElementById('ConfirmationModalBody');
                const proceedActionBtn = document.getElementById('proceedActionBtn');
                const loadingSpinner = document.getElementById('loadingSpinner');

                // Set modal message based on action
                if (action === 'accept') {
                    confirmationModalBody.textContent = 'Are you sure you want to accept this order?';
                } else if (action === 'decline') {
                    confirmationModalBody.textContent = 'Are you sure you want to decline this order?';
                } else if (action === 'ready') {
                    confirmationModalBody.textContent = 'Are you sure this order is ready for shipment?';
                }

                // Action on confirmation
                proceedActionBtn.onclick = function() {
                    if (form) {
                        // Hide modal and show loading spinner
                        confirmationModal.hide();
                        loadingSpinner.style.display = 'block';

                        // Submit the form with AJAX instead of traditional form submission
                        const formData = new FormData(form);
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': formData.get('_token')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Hide the loading spinner
                            loadingSpinner.style.display = 'none';

                            // Update the modal body with success or error message
                            confirmationModalBody.textContent = data.success
                                ? data.message
                                : (data.message || 'Failed to update order status.');

                            // Show the modal again with the message
                            confirmationModal.show();

                            // Redirect if the update was successful
                            if (data.success) {
                                setTimeout(() => {
                                    confirmationModal.hide();
                                    window.location.href = "{{ route('orders.index') }}";
                                }, 1500);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loadingSpinner.style.display = 'none';
                            confirmationModalBody.textContent = 'An error occurred. Please try again.';
                            confirmationModal.show();
                        });
                    }
                };

                confirmationModal.show();
            }
        });

    </script>
@endsection



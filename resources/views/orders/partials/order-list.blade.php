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

                    <img src="{{ asset('storage/' . $imagePath) }}"
                        alt="Product Image"
                        class="img-fluid border"
                        loading="lazy"
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
                            <p><strong>Status:</strong>
                                @if ($order->order_status === 'declined')
                                    Canceled by Merchant
                                @else
                                    {{ ucfirst($order->order_status) }}
                                @endif
                            </p>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="ConfirmationModal" tabindex="-1" aria-labelledby="ConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ConfirmationModalLabel">Confirm Action</h5>
            </div>
            <div class="modal-body" id="ConfirmationModalBody"></div>
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


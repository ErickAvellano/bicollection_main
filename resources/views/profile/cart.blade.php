
@extends('Components.layout')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
<style>
    .breadcrumb a {
        text-decoration: none;
        font-size: 15px;
        color: #666;
    }

    .breadcrumb .breadcrumb-item.active {
        font-size: 15px;
        font-weight: bold;
        color: #228b22;
    }

    /* Custom styled checkbox */
    .form-check-input.custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 15px;
        height: 15px;
        background-color: #ddd;
        border: 2px solid #ccc;
        border-radius: 4px;
        outline: none;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .form-check-input.custom-checkbox:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.5);
    }

    .form-check-input.custom-checkbox:checked {
        background-color: #228b22;
        border-color: #228b22;
    }

    /* Sticky cart summary */
    .sticky-cart-summary {
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
    }
    .nav-pills{
        display : none;
    }
    .fa-stack {
        display: inline-block;
        position: relative;
        width: 1.5em; /* Adjust size */
        height: 1.5em;
        line-height: 1.5em;
        vertical-align: middle;
        transition: transform 0.2s ease, color 0.2s ease; /* Smooth transition on hover */
    }

    .fa-certificate {
        color: rgba(34, 139, 34, 0.5); /* Certificate icon color */
        transition: transform 0.2s ease, color 0.2s ease; /* Smooth transition */
    }

    .fa-check {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* Center the check icon */
        color: white; /* Checkmark color */
        font-size: 0.5em; /* Adjust the size to fit inside the certificate */
        transition: transform 0.2s ease, color 0.2s ease; /* Smooth transition */
    }
    /* Hover effect on the parent span */
    .fa-stack:hover .fa-certificate {
        color: #228b22; /* Change color to full green on hover */
    }
    .quantity-control input {
        border-radius: 0;
        text-align: center;
        width: 60px; /* Adjust width as per your layout */
    }

    .quantity-control button {
        border-radius: 0;
    }

</style>
@endsection

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb" style="margin-left:20px">
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="#">BiCollection</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cart</li>
        </ol>
    </nav>
    <div class="row mt-3 d-flex justify-content-center align-items-center">
        <!-- Product Section -->
        <div class="col-md-10">
            @if($groupedCartItems->isNotEmpty())
                @foreach($groupedCartItems as $shopName => $cartItems)
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <!-- Store Header -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="store-name ms-2 "><i class="fa fa-store"></i> {{ $shopName }}</span>
                                <span class="fa-stack custom-fa-stack">
                                    <!-- Certificate Icon -->
                                    <i class="fa-solid fa-certificate fa-stack-1x"></i>
                                    <!-- Check Icon placed inside the certificate -->
                                    <i class="fa-solid fa-check fa-inverse"></i>
                                </span>
                            </div>
                        </div>
                        <hr>

                        <!-- Products for the Store -->
                        @foreach($cartItems as $cartItem)
                        <div class="d-flex mb-3">
                            <!-- Product Checkbox -->
                            <div class="product-checkbox me-2 d-flex justify-content-center align-items-center">
                                <input type="checkbox" class="form-check-input cart-item-checkbox custom-checkbox"
                                       data-price="{{ $cartItem->product->price }}"
                                       data-quantity="{{ $cartItem->quantity }}"
                                       id="cart-item-{{ $cartItem->cart_id }}"
                                       onchange="recalculateTotals()">
                            </div>

                            <!-- Product Image -->
                            <div class="product-image me-3">
                                <img src="/storage/{{ $cartItem->product->images->first()->product_img_path1 }}" alt="Product Image" class="img-fluid rounded" style="width:80px; height:80px; object-fit: cover;">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $cartItem->product->product_name }}</h6>
                                <div class="text-muted">
                                    <span>Variations:</span> <span>{{ $cartItem->product_variation }}</span>
                                </div>
                                <div>
                                    <span class="text-muted text-decoration-line-through">₱{{ number_format($cartItem->product->price + 500, 2) }}</span>
                                    <span class="fw-bold ms-2 text-danger cart-item-price" data-price="{{ $cartItem->product->price }}">
                                        ₱{{ number_format($cartItem->product->price, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quantity & Price Section -->
                            <div class="d-flex flex-column justify-content-between text-end">
                                <!-- Quantity Control -->
                                <div class="quantity-control mb-2 input-group">
                                    <button class="btn btn-outline-secondary btn-sm change-quantity" data-action="decrease" data-id="{{ $cartItem->cart_id }}">-</button>
                                    <input type="text" value="{{ $cartItem->quantity }}" class="form-control text-center" style="width: 60px; border-radius: 0; text-align: center; padding: 0; height: 38px;" readonly data-price="{{ $cartItem->product->price }}" data-quantity="{{ $cartItem->quantity }}">
                                    <button class="btn btn-outline-secondary btn-sm change-quantity" data-action="increase" data-id="{{ $cartItem->cart_id }}">+</button>
                                </div>
                            </div>

                            <!-- Actions Section -->
                            <div class="d-flex flex-column justify-content-between text-end ms-4">
                                <button class="btn btn-link text-danger p-0 remove-item-cart" data-id="{{ $cartItem->cart_id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <p>Your cart is empty.</p>
            @endif
        </div>
    </div>
</div>

<!-- Sticky Cart Summary Section at the bottom -->
<div id="sticky-cart-footer" class="fixed-bottom bg-white shadow-lg p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Left Section: Select All and Other Actions -->
        <div class="d-flex align-items-center">
            <input type="checkbox" class="form-check-input custom-checkbox me-2" id="select-all-checkbox">
            <span>Select All (<span class="selected-items">0</span>)</span>
            <button class="btn btn-link text-danger ms-3">Delete</button>
        </div>

        <!-- Display the total items and price -->
        <div class="d-flex align-items-center">
            <span class="me-3">Total (<span class="total-items">0</span> item): <span class="fw-bold text-danger total-price">₱0.00</span></span>
            <button class="btn btn-custom px-5">Check Out</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let totalItems = 0;
    let totalPrice = 0;

    // Function to update total items and price display
    function updateTotalDisplay() {
        document.querySelector('.total-items').textContent = totalItems;
        document.querySelector('.total-price').textContent = '₱' + totalPrice.toFixed(2);
    }

    // Function to recalculate total items, price, and selected items count
    function recalculateTotals() {
        totalItems = 0;
        totalPrice = 0;

        // Loop through all checked checkboxes
        document.querySelectorAll('.cart-item-checkbox:checked').forEach(function(checkbox) {
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantity = parseInt(checkbox.getAttribute('data-quantity'));
            totalItems += quantity;
            totalPrice += price * quantity;
        });

        // Update the total display
        updateTotalDisplay();
    }

    // Event listener for the "Select All" checkbox
    document.getElementById('select-all-checkbox').addEventListener('change', function() {
        const isChecked = this.checked;

        // Select or deselect all checkboxes
        document.querySelectorAll('.cart-item-checkbox').forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });

        // Recalculate totals based on the selection
        recalculateTotals();
    });

    // Add event listeners for individual checkboxes to update totals
    document.querySelectorAll('.cart-item-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            recalculateTotals();
        });
    });

    // Handle quantity change (increase or decrease)
    $(document).on('click', '.change-quantity', function () {
        const action = $(this).data('action');
        const cartId = $(this).data('id');
        const inputField = $(this).siblings('input');
        let currentQuantity = parseInt(inputField.val());

        // Update quantity based on action
        if (action === 'increase') {
            currentQuantity += 1; // Increment the quantity
        } else if (action === 'decrease') {
            if (currentQuantity > 1) {
                currentQuantity -= 1; // Decrement the quantity only if greater than 1
            }
            updateCartCount();
        }

        // Update input field value
        inputField.val(currentQuantity);

        // Get the previous quantity from the input's data attribute
        const previousQuantity = parseInt(inputField.attr('data-quantity'));

        // Send AJAX request to update the quantity in the backend
        $.ajax({
            url: `/cart/update/${cartId}`, // URL to your update route
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                quantity: currentQuantity // Pass the updated quantity
            },
            success: function(response) {
                console.log('Quantity updated successfully:', response);

                // Assuming the response contains the updated price per item from the database
                const pricePerItem = parseFloat(response.updatedPrice); // Fetch the price from the backend response

                // Update total items and total price based on the new price from the backend
                totalItems = totalItems - previousQuantity + currentQuantity; // Update total items
                totalPrice = totalPrice - (pricePerItem * previousQuantity) + (pricePerItem * currentQuantity); // Adjust total price

                // Update the data attributes to reflect the new quantity and price
                inputField.attr('data-quantity', currentQuantity); // Update the data attribute for quantity

                // Update the total display after changing the quantity
                updateTotalDisplay();
            },
            error: function(xhr, status, error) {
                console.error('Error updating quantity:', error);
            }
        });
    });

    // Update totals on page load
    document.addEventListener('DOMContentLoaded', function() {
        recalculateTotals();
    });
</script>
    <script>
        $(document).ready(function () {
            // Event listener for delete button
            $(document).on('click', '.remove-item-cart', function (e) {
                e.preventDefault();

                const cart_id = $(this).data('id'); // Get the cart item id

                if (cart_id && confirm('Are you sure you want to delete this item?')) {
                    removeCartItem(cart_id, $(this));  // Call the remove function and pass 'this' to target the row
                }
            });

            // Function to remove cart item via AJAX
            function removeCartItem(cart_id, buttonElement) {
                $.ajax({
                    url: `/cart/remove/${cart_id}`,  // Ensure the URL matches your delete route
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token in headers
                    },
                    success: function(response) {
                        console.log('Response from server:', response);  // Log the server response
                        if (response.success) {
                            // Remove the deleted cart item from the DOM
                            buttonElement.closest('.card').remove(); // Remove the entire card that contains the product
                            alert('Item removed successfully.');
                            updateCartCount();  // Update the cart count after an item is removed
                        } else {
                            alert('Error removing item from cart.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('XHR:', xhr);  // Log the full error
                        console.log('Status:', status);
                        console.log('Error:', error);
                        alert('Error removing item from cart.');
                    }
                });
            }

            // Function to update cart count in the icon
            function updateCartCount() {
                $.ajax({
                    url: '{{ route("cart.count") }}', // Ensure this URL matches your route for fetching cart count
                    type: 'GET',
                    success: function(response) {
                        $('#cart-count').text(response.cartItemCount); // Update the cart count span with the value from the server
                    },
                    error: function() {
                        console.error('Failed to load cart item count');
                    }
                });
            }
        });
    </script>
@endsection


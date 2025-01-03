
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
    .d-flex.mb-3 {
        border-bottom: 1px solid #ddd; /* Adjust the color and thickness as needed */
        padding-bottom: 15px; /* Add some space below the content */
        margin-bottom: 15px; /* Add some spacing between rows */
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
                        <div id="cart-container">
                            @foreach($cartItems as $cartItem)
                                <div class="d-flex mb-3">
                                    <!-- Product Checkbox -->
                                    <div class="product-checkbox me-2 d-flex justify-content-center align-items-center">
                                        <input type="checkbox" class="form-check-input cart-item-checkbox custom-checkbox"
                                            data-cart-id="{{ $cartItem->cart_id }}"
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
                                        <div class="d-flex align-items-center">
                                            <label for="variation-select-{{ $cartItem->cart_id }}" class="me-2">Variations:</label>
                                            <select id="variation-select-{{ $cartItem->cart_id }}" class="form-select variation-select" style="width: 150px;">
                                                @foreach($cartItem->product->variations as $variation)
                                                    <option value="{{ $variation->product_variation_id }}"
                                                        {{ $cartItem->product_variation_id == $variation->product_variation_id ? 'selected' : '' }}>
                                                        {{ $variation->variation_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3">
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
                                            <input type="text"
                                                value="{{ $cartItem->quantity }}"
                                                class="form-control text-center"
                                                style="width: 60px; border-radius: 0; text-align: center; padding: 0; height: 38px;"
                                                readonly
                                                data-price="{{ $cartItem->product->price }}"
                                                data-quantity="{{ $cartItem->quantity }}"
                                                data-max="{{ $cartItem->product->quantity_item }}">
                                            <button class="btn btn-outline-secondary btn-sm change-quantity" data-action="increase" data-id="{{ $cartItem->cart_id }}">+</button>
                                        </div>
                                    </div>

                                    <!-- Actions Section -->
                                    <div class="d-flex flex-column justify-content-between text-end ms-4">
                                        <button class="btn btn-link text-danger p-0 remove-item-cart" style="text-decoration:none;" data-id="{{ $cartItem->cart_id }}">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-center">Your cart is empty.</p>
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
            <button class="btn btn-link text-danger ms-3" id="delete-selected" disabled>Delete</button>
        </div>

        <!-- Display the total items and price -->
        <div class="d-flex align-items-center">
            <span class="me-3">Total (<span class="total-items">0</span> item): <span class="fw-bold text-danger total-price">₱0.00</span></span>
            <button class="btn btn-custom px-5" id="checkout-button">Check Out</button>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item from your cart?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Remove</button>
            </div>
        </div>
    </div>
</div>


@include('Components.status-modal')
@endsection

@section('scripts')
<script>
    let totalItems = 0;
    let totalPrice = 0;

    // Event listener for the "Select All" checkbox
    document.getElementById('select-all-checkbox').addEventListener('change', function () {
        const isChecked = this.checked;

        // Select or deselect all checkboxes
        document.querySelectorAll('.cart-item-checkbox').forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });

        // Recalculate totals based on the selection
        recalculateTotals();
    });

    // Handle quantity change (increase or decrease)
    $(document).on('click', '.change-quantity', function () {
        const action = $(this).data('action');
        const cartId = $(this).data('id');
        const inputField = $(this).siblings('input');
        let currentQuantity = parseInt(inputField.val());
        const maxQuantity = parseInt(inputField.data('max'));
        const pricePerItem = parseFloat(inputField.data('price'));

        // Prevent decreasing below 1 and show delete confirmation
        if (action === 'decrease' && currentQuantity === 1) {
            $('#deleteConfirmationModal').modal('show');

            $('#confirmDelete').off('click').on('click', function () {
                removeCartItem(cartId);
                $('#deleteConfirmationModal').modal('hide');
            });

            return;
        }

        // Update quantity based on action
        if (action === 'increase') {
            if (currentQuantity < maxQuantity) {
                currentQuantity += 1;
            } else {
                $('#statusModalIcon').removeClass('fa-xmark').addClass('fa-solid fa-circle-exclamation text-danger');
                $('#statusModalMessage').text('You cannot add more than the available stock.');
                $('#statusModal').modal('show');

                setTimeout(() => {
                    $('#statusModal').modal('hide');
                }, 1000);
                return;
            }
        } else if (action === 'decrease' && currentQuantity > 1) {
            currentQuantity -= 1;
        }

        // Update input field value and data attribute
        inputField.val(currentQuantity);
        // inputField.attr('data-quantity', currentQuantity);

        // Send AJAX request to update the backend
        $.ajax({
            url: `/cart/update/${cartId}`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: { quantity: currentQuantity },
            success: function (response) {
                const checkbox = document.getElementById(`cart-item-${cartId}`);
                if (checkbox) {
                    const updatedPrice = parseFloat(response.updatedPrice); // Assume the backend returns the updated price
                    checkbox.setAttribute('data-quantity', currentQuantity); // Update data-quantity attribute
                    checkbox.setAttribute('data-price', updatedPrice); // Update data-price if needed
                }

                // Recalculate totals if the checkbox is checked
                if (checkbox && checkbox.checked) {
                    recalculateTotals();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error updating quantity:', error);
            },
        });
    });

    // Function to remove a cart item
    function removeCartItem(cartId) {
        $.ajax({
            url: `/cart/remove/${cartId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                    $(`#cart-item-${cartId}`).closest('.d-flex.mb-3').remove();
                    recalculateTotals();
                    updateCartCount();
                } else {
                    alert('Failed to remove item from cart.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error removing cart item:', error);
            },
        });
    }

    // Function to recalculate total items and price
    function recalculateTotals() {
        totalItems = 0;
        totalPrice = 0;

        // Loop through all checked checkboxes
        document.querySelectorAll('.cart-item-checkbox:checked').forEach(function (checkbox) {
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantity = parseInt(checkbox.getAttribute('data-quantity'));
            totalItems += quantity;
            totalPrice += price * quantity;
        });

        // Update the total display
        updateTotalDisplay();
    }

    function updateTotalDisplay() {
        document.querySelector('.total-items').textContent = totalItems;
        document.querySelector('.total-price').textContent = '₱' + totalPrice.toFixed(2);
    }

    // Update totals on page load
    document.addEventListener('DOMContentLoaded', function () {
        recalculateTotals();
    });
</script>
<script>
    $(document).ready(function () {
        // Event listener for delete button
        $(document).on('click', '.remove-item-cart', function (e) {
            e.preventDefault();

            const cart_id = $(this).data('id'); // Get the cart item id
            if (cart_id) {
                removeCartItem(cart_id, $(this)); // Call the remove function and pass 'this' to target the row
            }
        });

        function removeCartItem(cart_id, buttonElement) {
            $.ajax({
                url: `/cart/remove/${cart_id}`, // Ensure the URL matches your delete route
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in headers
                },
                success: function(response) {
                    if (response.success) {
                        // Select the product row to be removed
                        const productRow = buttonElement.closest('.d-flex.mb-3');

                        // Remove the product row
                        productRow.remove();

                        // Check if the parent card is empty after removing the product
                        const cardBody = buttonElement.closest('.card-body');
                        if ($(cardBody).find('.d-flex.mb-3').length === 0) {
                            // If no products remain in the card, remove the entire card
                            buttonElement.closest('.card').remove();
                        }

                        // Close the modal
                        $('#changeContactModal').modal('hide');
                        // Show success modal
                        $('#statusModalIcon').removeClass('fa-xmark').addClass('fa-solid fa-circle-check check-icon'); // Success icon
                        $('#statusModalMessage').text('Item removed successfully');
                        $('#statusModal').modal('show');

                        setTimeout(() => {
                            $('#statusModal').modal('hide');
                        }, 1000);

                        updateCartCount(); // Update the cart count after an item is removed
                        recalculateTotals(); // Recalculate totals after deletion
                    } else {
                        alert('Error removing item from cart.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('XHR:', xhr); // Log the full error
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
{{-- changing product vartiations --}}
<script>
    $(document).on('change', '.variation-select', function () {
        const cartId = $(this).attr('id').split('-')[2]; // Extract cart ID from the select element's ID
        const selectedVariationId = $(this).val(); // Get the selected variation ID

        // Send AJAX request to update the variation in the backend
        $.ajax({
            url: `/cart/update-variation/${cartId}`, // Update route for the variation
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            },
            data: {
                product_variation_id: selectedVariationId, // Send the selected variation ID
            },
            success: function (response) {
                if (response.success) {
                } else {
                }
            },
            error: function (xhr, status, error) {
            },
        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
        const selectedItemsSpan = document.querySelector('.selected-items');
        const deleteSelectedButton = document.getElementById('delete-selected');

        // Update "Select All" checkbox and selected count
        function updateSelectedCount() {
            const checkedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked).length;
            const totalCount = itemCheckboxes.length;

            // Update the selected items count
            selectedItemsSpan.textContent = checkedCount;

            // Enable/Disable "Delete" button based on selection
            deleteSelectedButton.disabled = checkedCount === 0;

            // Sync "Select All" checkbox with individual checkboxes
            if (checkedCount === totalCount) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCount === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        // "Select All" functionality
        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = selectAllCheckbox.checked;
            itemCheckboxes.forEach((checkbox) => (checkbox.checked = isChecked));
            updateSelectedCount();
        });

        // Update count on individual checkbox change
        itemCheckboxes.forEach((checkbox) =>
            checkbox.addEventListener('change', updateSelectedCount)
        );

        // Bulk delete functionality
        deleteSelectedButton.addEventListener('click', function () {
            const selectedIds = Array.from(
                document.querySelectorAll('.cart-item-checkbox:checked')
            ).map((checkbox) => checkbox.dataset.cartId); // Ensure `data-cart-id` matches `cart_id`

            $.ajax({
                url: `/cart/delete-selected`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: { cartIds: selectedIds },
                success: function (response) {
                    
                    selectedIds.forEach((id) => {
                        const itemRow = document.querySelector(`[data-cart-id="${id}"]`).closest('.cart-item-row');
                        if (itemRow) itemRow.remove();
                    });
                    $('#statusModalIcon').removeClass('fa-xmark').addClass('fa-solid fa-circle-check check-icon'); // Success icon
                        $('#statusModalMessage').text('Items removed successfully');
                        $('#statusModal').modal('show');

                        setTimeout(() => {
                            $('#statusModal').modal('hide');
                            location.reload();
                        }, 1000);

                    updateSelectedCount(); 
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON.error);
                },
            });
        });
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


<script>
    $('#checkout-button').on('click', function () {
        // Collect selected cart item IDs
        const selectedCartIds = [];
        $('.cart-item-checkbox:checked').each(function () {
            const cartId = $(this).attr('id').split('-')[2]; // Extract cart_id from checkbox ID
            selectedCartIds.push(cartId);
        });

        // Check if any items are selected
        if (selectedCartIds.length === 0) {
            alert('Please select at least one item to proceed to checkout.');
            return;
        }

        // Redirect to the existing checkout route with selected cart IDs as a query string
        const queryString = `cart_ids=${selectedCartIds.join(',')}`;
        window.location.href = `/checkout?${queryString}`;
    });


</script>




@endsection


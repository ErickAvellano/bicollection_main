<!-- resources/views/Components/add-to-cart.blade.php -->
<style>
    .align-center {
        display: flex;
        justify-content: center; /* Horizontally center */
        align-items: center; /* Vertically center */
        width: 40px; /* You can adjust this width as needed */
        height: 40px; /* You can adjust this height as needed */
        background-color: transparent;
        border: none;
        outline: none;
    }

    .align-center i {
        font-size: 20px; /* Adjust icon size */
    }
    .btn-continue{
        background-color: transparent;
    }
    .btn-continue:hover,
    .btn-continue:active{
        border-color: #228b22;
        color: #228b22;
    }
    .modal-content {
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
    .btn-continue:hover, .btn-continue:active {
        border-color: #228b22;
        color: #228b22;
    }
    .modal-footer .btn {
        padding: 10px 15px;
    }
</style>
<div class="modal fade" id="successModals-addtocart" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content max-width:600px;">
            <div class="modal-header" style="padding:0 10px;">
                <h5 class="modal-title" id="successModalLabel" style=" font-size:0.8rem;">
                    <i class="fas fa-check-circle" style="color: green;" ></i>
                    Product successfully added to your Cart
                </h5>
                <button type="button" class="btn-close align-center" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div id="productDetails" style="display: flex;">
                    <!-- Add the image element -->
                    <img id="productImage" src="" alt="Product Image" style="width: 80px; height: 80px; margin-right: 20px; object-fit: cover;">
                    <div class="d-flex flex-column justify-content-start align-items-start">
                        <p id="productName"></p>
                         <!-- Dropdown for selecting variations -->
                        <div style="display: flex; align-items: center;">
                            <label for="variation-Select" style="margin-right: 10px; font-size: 0.8rem;">Change Variation:</label>
                            <select id="variationSelect" class="form-select" style="width: auto; min-width: 100px; max-width: 300px; font-size: 0.7rem;">
                            </select>
                        </div>
                        <p style="font-size:0.8rem;" id="quantity"></p>
                        <p style="font-size:1rem;" id="cartTotal"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-continue" data-bs-dismiss="modal">Continue Shopping</button>
                <a id="checkoutLink" class="btn btn-custom">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


{{-- <script>
    $(document).on('click', '.add-to-cart', function (e) {
        e.preventDefault();
        console.log('Add to Cart button clicked'); // Debug log
    });
</script> --}}
<script>
    // Handle variation changes
    $(document).on('change', '.variation-select', function () {
        const cartId = $('#productDetails').data('cart-id'); // Get the cart ID from #productDetails
        const selectedVariationId = $(this).val(); // Get the selected variation ID
        // Ensure both cartId and selectedVariationId are valid

        if (!cartId || !selectedVariationId) {
            alert('Invalid cart or variation ID.');
            return;
        }

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


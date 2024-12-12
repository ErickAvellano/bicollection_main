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
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" style="display: none; overflow:none;">
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
                        <p style="font-size:0.8rem;" id="productVariation"></p>
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

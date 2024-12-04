<div id="orders-tooltip-container">
    <!-- Single row for all elements -->
    <div class="row align-items-start">
        <!-- Product Image -->
        <div class="col-auto">
            <img id="orderProductImage" class="rounded" alt="Product Image">
        </div>

        <!-- Order Details -->
        <div class="col p-0">
            <p id="orderID" class="mb-1"></p>
            <p id="customerUsername" class="mb-1"></p>
        </div>

        <!-- Quantity and Total -->
        <div class="col-auto text-end p-0">
            <p id="orderQuantity" class="orderQuantity mb-0"></p>
            <p id="orderTotal" class="mb-0"></p>
        </div>
    </div>

    <!-- Second row for product name, mode of payment, and view link -->
    <div class="row mt-2 align-items-center">
        <!-- Product Name and Mode of Payment -->
        <div class="col">
            <p id="orderProductName" class="mb-1"></p>
            <div class="d-flex align-items-center">
                <p id="paymentMethod" class="mb-1 me-2"></p>
                <!-- View Link aligned with mode of payment -->
                <a id="viewOrderLink" href="#"></a>
            </div>
        </div>
    </div>
</div>

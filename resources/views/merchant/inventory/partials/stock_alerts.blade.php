<h3>Stock Alerts</h3>
<p>Products low in stock:</p>

@if($lowStockProducts->isEmpty())
    <p class="mt-5 mb-5 text-center">No products are low in stock.</p>
@else
    <form id="stock-form" method="POST" action="{{ route('sales_report.pdf') }}"> <!-- Set your route name -->
        @csrf
        <div class="table-responsive">
            <table class="table table-striped table-bordered" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #4CAF50; color: white; text-align: center;">
                    <tr>
                        <th style="padding: 10px; font-size: 1rem;">Product ID</th>
                        <th style="padding: 10px; font-size: 1rem;">Product Images</th>
                        <th style="padding: 10px; font-size: 1rem;">Product Name</th>
                        <th style="padding: 10px; font-size: 1rem;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr data-product-id="{{ $product->product_id }}" style="text-align: center;">
                            <!-- Product ID input (hidden) -->
                            <input type="hidden" name="product_ids[]" value="{{ $product->product_id }}">

                            <td style="padding: 8px;">{{ $product->product_id }}</td>
                            <td style="padding: 8px;">
                                @if($product->images && $product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <img src="{{ asset('storage/' . $image->product_img_path1) }}" alt="Product Image" style="max-width: 50px; max-height: 50px; margin: 0 5px;">
                                    @endforeach
                                @else
                                    No images
                                @endif
                            </td>
                            <td style="padding: 8px;">{{ $product->product_name }}</td>
                            <td style="padding: 8px;">
                                <div class="quantity-container d-flex align-items-center justify-content-center" style="display: flex; align-items: center; justify-content: center;">
                                    <!-- Minus button -->
                                    <button type="button" class="btn btn-outline-custom quantity-btn decrease" data-action="decrease">
                                        -
                                    </button>

                                    <!-- Quantity Input (Visible) -->
                                    <input type="number" name="quantities[]" class="quantity-input mx-2 text-center" data-product-id="{{ $product->product_id }}" value="{{ $product->quantity_item }}" min="1" step="1" style="width: 50px; font-size: 16px; text-align: center; border: 1px solid #ccc; padding: 5px; border-radius: 5px; margin: 0 5px;" readonly>

                                    <!-- Plus button -->
                                    <button type="button" class="btn btn-outline-custom quantity-btn increase" data-action="increase">
                                        +
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Save Button -->
        <div class="mt-4 mb-5 d-flex justify-content-end">
            <button id="saveStockChangesBtn" type="submit" class="btn btn-custom">
                Save Changes
            </button>
        </div>
    </form>
@endif

<div class="text-end mb-3">
    <a href="{{ route('merchant.inventory.create') }}" class="btn btn-custom">Add New Product</a>
</div>

@php
    $page = request('page', 1); // Get the current page number or default to 1
    $perPage = 5; // Number of items per page
    $offset = ($page - 1) * $perPage;
    $paginatedProducts = $products->slice($offset, $perPage); // Slice the collection for manual pagination
@endphp

<!-- Inventory Table -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
            <th>Images</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paginatedProducts as $product) <!-- Use the sliced collection -->
            <tr>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity_item }}</td>
                <td>₱{{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->product_status == 1)
                        <span class="badge bg-sucess">On Display</span>
                    @else
                        <span class="badge bg-secondary">Sold</span>
                    @endif
                </td>
                <td>
                    @if($product->images && $product->images->isNotEmpty())
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->product_img_path1) }}" alt="Image 1" width="50">
                        @endforeach
                    @else
                        No images
                    @endif
                </td>
                <td>
                    <a href="{{ route('product.view', $product->product_id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('inventory.edit', ['id' => $product->product_id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="javascript:void(0);" 
                        class="btn btn-danger btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal-{{ $product->product_id }}">
                        Delete
                    </a>

                    <!-- Include the renamed modal component -->
                    @include('Components.delete-modal-product', [
                        'productId' => $product->product_id,
                        'productName' => $product->product_name,
                        'productPrice' => $product->price,
                        'productQuantity' => $product->quantity_item,
                        'productImage' => $product->images->isNotEmpty() ? $product->images->first()->product_img_path1 : null,
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Manual Pagination Links -->
<div class="d-flex justify-content-center">
    @for ($i = 1; $i <= ceil($products->count() / $perPage); $i++)
        <a href="{{ request()->url() }}?page={{ $i }}"
           class="btn {{ $i == $page ? 'btn-custom' : 'btn-outline-custom' }} btn-sm mx-1">
            {{ $i }}
        </a>
    @endfor
</div>


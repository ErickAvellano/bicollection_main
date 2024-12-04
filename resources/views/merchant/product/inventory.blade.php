<!-- resources/views/inventory/index.blade.php -->

@extends('Components.layout') <!-- Extend your main layout -->
@section('styles')
    <style>
        .nav-pills, .search-control, .search-icon{
            display: none;
        }
    </style>


@endsection
@section('content')
<div class="container mt-3">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mystore') }}">My Store</a></li>
        <li class="breadcrumb-item active">Inventory</li>
    </ol>

    <h4>Inventory Management</h4>
    <!-- Add New Product Button -->
    <div class="text-end mb-3">
        <a href="{{ route('merchant.product.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

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
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->quantity_item }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->product_status == 1)
                            <span class="badge bg-primary">On Display</span>
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
                        <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
@section('scripts')

@endsection

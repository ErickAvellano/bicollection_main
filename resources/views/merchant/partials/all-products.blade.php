<div class="col-md-12 mt-4 allproduct-products-container">
    <h3>All Products</h3>
    <hr>
    @if($products->isEmpty())
        <p>No products found.</p>
    @else
        <div class="row allproduct-products-row">
            @foreach($products as $product)
                <div class="col-md-4 product-card">
                    <div class="card" style="width: 12rem;">
                        <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}" class="card-img-top" alt="{{ $product->product_name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title">{{ $product->product_name }}</h6>
                            <p class="card-text"><strong>Price: ${{ $product->price }}</strong></p>
                            <a href="{{ route('products.edit', ['id' => $product->product_id]) }}" class="btn btn-custom btn-sm w-100">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

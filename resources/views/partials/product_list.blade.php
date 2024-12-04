<div class="d-flex flex-wrap justify-content-start">
    @foreach($products as $product)
        <div style="width: auto; margin: 0; padding: 0;">
            <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none" style="color: inherit;">
                <div class="product-item p-2 product-card-hover">
                    <div class="card" style="width: 11rem; border: none; transition: transform 0.3s; position: relative;">
                        <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
                            class="card-img-top"
                            alt="{{ $product->product_name }}"
                            style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem;">
                        <div class="card-body text-center">
                            <h6 class="card-title" style="font-size: 1rem; font-weight: bold;">{{ $product->product_name }}</h6>
                            <p class="card-text" style="font-size: 14px; color: #555;">
                                <strong>₱{{ number_format($product->price, 2) }}</strong>
                            </p>
                            <p class="card-text" style="font-size: 12px; color: #777;">No reviews</p>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <a href="#" class="btn btn-custom btn-sm add-to-cart" data-product-id="{{ $product->product_id }}">
                                    <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" style="width: 2rem;">
                                    <i class="fas fa-heart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

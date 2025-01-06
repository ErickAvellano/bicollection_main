<div id="product-list" style="min-height: 240px;">
    @if($products->isEmpty())
        <div class="text-center">
            <p class="card-text">No current product listed</p>
        </div>
    @else
        <div class="d-flex flex-wrap justify-content-start" >
            @foreach($products as $product)
                <a href="{{ route('product.view', $product->product_id) }}" class="text-decoration-none" data-category="{{ $product->category_id }}" style="color: inherit;">
                    <div class="product-item">
                        <div class="card product-card product-card-hover" style="width: 180px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.3s, border-color 0.3s;">
                            <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/200x130' }}" 
                                class="card-img-top" 
                                alt="{{ $product->product_name }}" 
                                style="width: 180px; height: 130px; object-fit: cover; border-bottom: 1px solid #ddd;">
                            <div class="card-body text-center" style="padding: 10px;">
                                <h6 class="card-title" style="font-size: 0.9rem; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->product_name }}</h6>
                                <p class="card-text" style="font-size: 13px; color: #555; margin: 5px 0;"><strong>₱{{ number_format($product->price, 2) }}</strong></p>
                                <p class="card-reviews">
                                    @if($product->averageRating > 0)
                                        {{-- Display stars based on the rating --}}
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->averageRating))
                                                <i class="fa-solid fa-star" style="color: #FFD700;"></i> {{-- Full star --}}
                                            @elseif ($i - $product->averageRating < 1)
                                                <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i> {{-- Half star --}}
                                            @else
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i> {{-- Empty star --}}
                                            @endif
                                        @endfor
                                        <span class="rating-value" style="font-size: 12px;">{{ $product->averageRating }}</span>

                                    @else
                                        {{-- Display empty stars if no reviews --}}
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    @endif
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-2" style="gap: 8px;">
                                    <a href="#" class="btn btn-custom btn-sm add-to-cart" data-product-id="{{ $product->product_id }}" style="font-size: 14px; flex: 1;">
                                        Add to Cart
                                    </a>
                                    <a href="#"
                                        class="btn {{ in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger'}} favorite-button" 
                                        data-product-id="{{ $product->product_id }}" 
                                        style="width: 2rem; padding: 0.2rem 0; font-size: 14px; text-align: center;"
                                        title="{{ in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                            <i class="fas fa-heart {{ in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
<div class="col-md-12 mt-4 featured-products-container">
    <h3>Featured Products</h3>
    <hr>
    <div class="homeallproduct-products-row d-flex flex-wrap justify-content-start">
        @foreach ($featuredProducts as $product)
            <a href="{{ route('product.view', $product->product_id) }}" class="product-link">
                <div class="product-item product-card-hover" style="padding: 5px; width: 16.66%;"> <!-- 16.66% ensures 6 items per row -->
                    <div class="card" style="width: 100%; height: 285px;">
                        <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}"
                             class="card-img-top"
                             alt="{{ $product->product_name }}">
                        <div class="card-body" style="padding:10px;">
                            <h6 class="card-title mb-1">{{ $product->product_name }}</h6>
                            <p class="card-price mb-1">₱{{ number_format($product->price, 2) }}</p>
                            <p class="card-reviews">
                                {{-- Display stars based on the rating if available --}}
                                @if($product->averageRating > 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product->averageRating))
                                            <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                        @elseif ($i - $product->averageRating < 1)
                                            <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i>
                                        @else
                                            <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        @endif
                                    @endfor
                                    <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                @else
                                    {{-- Display empty stars if no reviews --}}
                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                @endif
                            </p>
                            <div class="action-buttons" style="position: absolute; bottom: 10px; padding:6px;">
                                <a href="#" class="btn btn-custom add-to-cart" data-product-id="{{ $product->product_id }}">
                                    Add to Cart
                                </a>
                                <a href="#"
                                   class="btn {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'btn-danger' : 'btn-outline-danger' }} favorite-button"
                                   data-product-id="{{ $product->product_id }}"
                                   style="width: 2rem; padding: 0.2rem 0; "
                                   title="{{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'Unfavorite' : 'Favorite' }}">
                                    <i class="fas fa-heart {{ !empty($favorites) && in_array($product->product_id, $favorites) ? 'text-white' : '' }}"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        <!-- Add Featured Product Button/Card -->
        <div class="col-md-4 product-card">
            <div class="card text-center add-featured-card" style="width: 12rem; height: 16rem;">
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%;">
                    <a href="javascript:void(0);" id="openModalButton" class="btn btn-link-custom" style="border: none; background: none;">
                        <i class="fa fa-plus" style="font-size: 3rem;"></i>
                    </a>                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Merchant Ads Banner -->
<div class="col-md-12 mt-2 ads-container">
    <h3>Special Offer</h3>
    <hr>
    <div class="row ads-row">
        <!-- Card Template -->
        @foreach ([1, 2] as $cardNumber)
        <div class="col-md-6 ads-card">
            <div class="card text-center" style="width: 100%; height: 15rem; {{ ${'display'.$cardNumber} ? 'background-image: url(' . Storage::url(${'display'.$cardNumber}) . '); background-size: cover;' : '' }}" id="card{{ $cardNumber }}">
                <div class="card-body d-flex justify-content-center align-items-center position-relative" style="height: 100%;">

                    @if (${'display'.$cardNumber})
                        <!-- Edit Button (shown if an image exists) -->
                        <button type="button" id="triggerEdit{{ $cardNumber }}" class="btn btn-custom btn-sm position-absolute" style="bottom: 20px; right: 20px; z-index:10;">
                            Edit
                        </button>
                    @else
                        <!-- If no image exists, show a Font Awesome icon to add a new image -->
                        <button type="button" id="addImage{{ $cardNumber }}" class="btn btn-link" style="border: none; background: none;">
                            <i class="fas fa-plus fa-3x" id="faIcon{{ $cardNumber }}" style="color: #28a745;"></i>
                        </button>
                    @endif

                    <!-- Form for uploading image -->
                    <form action="{{ route('shop.updateDisplayImage') }}" method="POST" enctype="multipart/form-data" id="form{{ $cardNumber }}" class="w-100" style="display: none;">
                        @csrf
                        <input type="hidden" name="displayPosition" value="{{ $cardNumber }}">
                        <input type="file" name="image" id="imageUpload{{ $cardNumber }}" accept="image/*" style="display: none;">
                        <div id="editButtons{{ $cardNumber }}" class="d-flex justify-content-center gap-2" style="display: none;">
                            <button type="submit" class="btn btn-custom btn-sm">Save</button>
                            <button type="button" class="btn btn-primary btn-sm" id="changeImage{{ $cardNumber }}">Change</button>
                            <button type="button" class="btn btn-danger btn-sm" id="cancelImage{{ $cardNumber }}">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="col-md-12 mt-4 allproduct-products-container">
    <h3>Products</h3>
    <hr>
        @if($products->isEmpty())
            <p>No products found.</p>
        @else
            <div class="row allproduct-products-row">
                @foreach($products as $product)
                    <div class="col-md-4 product-card">
                        <div class="card" style="width: 12rem;">
                            <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/80' }}"
                                 class="card-img-top"
                                 alt="{{ $product->product_name }}"
                                 style="width: 100%; height: 200px; object-fit: cover;"> <!-- Ensures consistent size and aspect ratio -->
                            <div class="card-body">
                                <h6 class="card-title">{{ $product->product_name }}</h6>
                                <p class="card-text" style="font-size: 13px;"><strong>₱ {{ $product->price }}</strong></p>
                                    @if($product->averageRating > 0)
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->averageRating))
                                                <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                            @elseif ($i - $product->averageRating < 1)
                                                <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i>
                                            @else
                                                <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                            @endif
                                        @endfor
                                        <span class="rating-value">{{ $product->averageRating }} / 5</span>
                                    @else
                                        {{-- Display empty stars if no reviews --}}
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                        <i class="fa-regular fa-star" style="color: #C0C0C0;"></i>
                                    @endif
                                <!-- Edit Button -->
                                <a href="{{ route('products.edit', ['id' => $product->product_id]) }}" class="btn btn-custom btn-sm w-100">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>




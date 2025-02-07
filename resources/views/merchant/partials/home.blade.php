<div class="featured-products mt-4">
    <h3>Featured Products</h3>
    <hr>
    <div class="homeallproduct-products-row d-flex flex-wrap justify-content-start">
        @foreach ($featuredProducts as $product)
            <a href="{{ route('product.preview', $product->product_id) }}" class="product-link">
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
                            <a href="{{ route('products.edit', ['id' => $product->product_id]) }}" class="btn btn-custom btn-sm w-100 mt-3">Edit</a>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
        <!-- Add Featured Product Button/Card -->
        <div class="product-item product-card-hover" style="padding: 5px; width: 16.66%;"> <!-- Same size as other items -->
            <div class="card text-center add-featured-card" style="width: 100%; height: 285px;">
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%; flex-direction: column;">
                    <a href="javascript:void(0);" id="openModalButton" class="btn btn-link-custom" style="border: none; background: none;">
                        <i class="fa fa-plus" style="font-size: 3rem;"></i>
                    </a>
                    <span class="mt-2">Add Product</span> <!-- Optional label -->
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
<div class="col-md-12 mt-4 homeallproduct-products-container">
    <h3>Products</h3>
    <hr class="line">
    <div class="homeallproduct-products-row d-flex flex-wrap justify-content-start">
        @foreach($products as $product)
            <a href="{{ route('product.preview', $product->product_id) }}" class="product-link">
                <div class="product-item product-card-hover" style="padding: 5px; width: 16.66%;"> <!-- 16.66% ensures 6 items per row -->
                    <div class="card" style="width: 100%; height:285px;">
                        <img src="{{ $product->images->first() ? Storage::url($product->images->first()->product_img_path1) : 'https://via.placeholder.com/150' }}"
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
                            <a href="{{ route('products.edit', ['id' => $product->product_id]) }}" class="btn btn-custom btn-sm w-100 mt-3">Edit</a>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>





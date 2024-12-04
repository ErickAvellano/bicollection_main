@extends('Components.layout')

@section('styles')
    <style>
        body, html {
            overflow: auto;
            height: 100%;
            margin: 0;
        }

        .nav-pills {
            display: none;
        }

        .card-placeholder {
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            border: 1px solid #ddd;
            position: relative; /* Enable overlay positioning */
        }

        .main-card {
            width: 100%;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .main-image {
            width: 100%;
            height: 350px;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 10px;
            border: 1px solid #ddd;
            position: relative; /* Ensure overlay is positioned properly */
            background-size: cover; /* Prevent double zoom */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat;
        }

        .small-image {
            width: 80px;
            height: 80px;
            margin-right: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
        }

        .icon {
            font-size: 40px;
            color: black;
        }

        .d-flex {
            display: flex;
        }

        .product-info {
            background-color: #e0e0e0;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(34, 139, 34, 0.4); /* Semi-transparent green */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Image Carousel -->
        <div class="col-md-5 text-center">
            <div id="imageCarousel" class="carousel slide card main-card" style="height: 390px;" data-bs-ride="carousel">
                <div class="carousel-inner" style="width: 100%; height: 100%;">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $imagePath = 'product_img_path' . $i; // Dynamically generate the image path variable
                            $imageUrl = $images->$imagePath ? Storage::url($images->$imagePath) : null;
                        @endphp
                        <div class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                            <div class="main-image card-placeholder" style="background-image: url('{{ $imageUrl }}');" id="image{{ $i }}-placeholder">
                                @if ($imageUrl)
                                    <div class="overlay" onclick="document.getElementById('image-input-{{ $i }}').click();">Change</div>
                                @else
                                    <i class="fa fa-plus icon"></i> <!-- Only show the plus icon if no image is present -->
                                @endif
                                <input type="file" class="d-none image-upload" id="image-input-{{ $i }}" name="images[]" accept="image/*">
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Small Image Placeholders -->
            <div class="d-flex justify-content-center mt-3">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $imagePath = 'product_img_path' . $i; // Dynamically generate the image path variable
                    @endphp
                    <div class="small-image card-placeholder" id="small-image{{ $i }}-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="{{ $i - 1 }}">
                        @if ($images->$imagePath)
                            <img src="{{ Storage::url($images->$imagePath) }}" alt="Product Small Image {{ $i }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa fa-plus icon"></i>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <!-- Product Info Column -->
        <div class="col-md-6">
            <div class="card main-card">
                <div class="card-title">
                    <h4>Edit Product</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.update', ['id' => $product->product_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Input fields for images -->
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="file" class="d-none image-upload" id="image{{ $i }}-input" name="images[]" accept="image/*">
                        @endfor

                        <!-- Product Name -->
                        <div class="form-group mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                        </div>

                        <!-- Product Description -->
                        <div class="form-group mb-3">
                            <label for="productDescription" class="form-label">Product Description</label>
                            <textarea class="form-control" id="productDescription" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Product Price and Quantity -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productPrice" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="productPrice" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="productQuantity" name="quantity_item" value="{{ old('quantity_item', $product->quantity_item) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Product Category and Subcategory -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productCategory" class="form-label">Product Category</label>
                                    <select class="form-control" id="productCategory" name="category_id" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}" {{ $category->category_id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productSubcategory" class="form-label">Product Subcategory</label>
                                    <select class="form-control" id="productSubcategory" name="subcategory_id" required>
                                        <option value="">Select a subcategory</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->category_id }}"
                                                data-category="{{ $subcategory->parentcategoryID }}"
                                                {{ $subcategory->category_id == old('subcategory_id', $product->subcategory_id) ? 'selected' : '' }}>
                                                {{ $subcategory->category_name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Product Variations Section -->
                        <div class="form-group">
                            <label for="productVariations" class="form-label">Product Variations</label>
                            <div class="mb-2" id="variationContainer">
                                @foreach($product->variations as $variation)
                                    <div class="row mb-1 variation-card">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control variation-name" name="variations[{{ $variation->product_variation_id }}][attribute]" value="{{ old('variations.'.$variation->product_variation_id.'.attribute', $variation->variation_name) }}" placeholder="Variation Name (e.g., Color, Size)" required>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- Display 'Change Image' for existing variations -->
                                            <button type="button" class="btn btn-secondary upload-image-btn">
                                                {{ $variation->image ? 'Change Image' : 'Upload Image' }}
                                            </button>
                                            <input type="file" class="variation-image-input d-none" name="variations[{{ $variation->product_variation_id }}][image]" accept="image/*">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center">
                                            <!-- Change button label to 'Delete' for existing variations -->
                                            <button type="button" class="btn btn-danger remove-variation" data-id="{{ $variation->product_variation_id }}" data-name="{{ $variation->variation_name }}">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mb-2" id="addVariation">Add Variation</button>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Confirming Deletion -->
<div class="modal fade" id="deleteVariationModal" tabindex="-1" aria-labelledby="deleteVariationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVariationLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the variation <strong><span id="variation-name"></span></strong>?</p>
                <form id="deleteVariationForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="variation_id" id="variation-id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteVariationForm">Confirm Delete</button>
            </div>
        </div>
    </div>
</div>




@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imagePlaceholders = document.querySelectorAll('.main-image');
        const smallImagePlaceholders = document.querySelectorAll('.small-image');
        const variationContainer = document.getElementById('variationContainer');
        const addVariationBtn = document.getElementById('addVariation');
        const categorySelect = document.getElementById('productCategory');
        const subcategorySelect = document.getElementById('productSubcategory');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteVariationModal'));
        const deleteForm = document.getElementById('deleteVariationForm');
        const variationIdInput = document.getElementById('variation-id');
        const variationNameSpan = document.getElementById('variation-name');

        // Function to filter subcategories based on selected category
        function filterSubcategories() {
            const selectedCategory = categorySelect.value;

            if (selectedCategory) {
                subcategorySelect.disabled = false;

                for (let i = 0; i < subcategorySelect.options.length; i++) {
                    const option = subcategorySelect.options[i];
                    const parentCategory = option.getAttribute('data-category');

                    if (parentCategory === selectedCategory) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            } else {
                subcategorySelect.disabled = true;
                subcategorySelect.value = '';
            }
            subcategorySelect.value = '';
        }

        // Listen for changes in the category dropdown
        categorySelect.addEventListener('change', filterSubcategories);

        // Add event listener to each carousel image placeholder
        imagePlaceholders.forEach((placeholder, index) => {
            const fileInput = document.getElementById(`image${index + 1}-input`);
            placeholder.addEventListener('click', () => {
                fileInput.click(); // Trigger file input on click
            });

            // Listen for file selection
            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        placeholder.style.backgroundImage = `url(${e.target.result})`;
                        placeholder.style.backgroundSize = 'cover';
                        placeholder.style.backgroundPosition = 'center';
                        placeholder.style.backgroundRepeat = 'no-repeat';

                        // Show the "Change" overlay
                        placeholder.innerHTML = `
                            <div class="overlay">Change</div>
                        `;

                        // Update small image placeholder as well
                        smallImagePlaceholders[index].style.backgroundImage = `url(${e.target.result})`;
                        smallImagePlaceholders[index].style.backgroundSize = 'cover';
                        smallImagePlaceholders[index].style.backgroundPosition = 'center';
                        smallImagePlaceholders[index].style.backgroundRepeat = 'no-repeat';
                        smallImagePlaceholders[index].innerHTML = ''; // Remove the plus icon
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Add event listener to small image placeholders for carousel navigation
        smallImagePlaceholders.forEach((smallPlaceholder, index) => {
            smallPlaceholder.addEventListener('click', () => {
                const carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'));
                carousel.to(index); // Navigate to corresponding slide
            });
        });

        // Function to handle adding a new variation input row
        addVariationBtn.addEventListener('click', function () {
            const newVariationRow = document.createElement('div');
            newVariationRow.classList.add('variation-card', 'd-flex');

            newVariationRow.innerHTML = `
                <div class="row mb-1">
                    <div class="col-md-6">
                        <input type="text" class="form-control variation-name" name="variations[][attribute]" placeholder="Variation Name (e.g., Color, Size)" required>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-secondary upload-image-btn">Upload Image</button>
                        <input type="file" class="variation-image-input d-none" name="variations[][image]" accept="image/*">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="remove-variation btn btn-danger">Remove</button>
                    </div>
                </div>
            `;

            // Append the new row to the variation container
            variationContainer.appendChild(newVariationRow);

            // Set up image upload logic for the new variation
            setUploadImageEvent(newVariationRow);
            setRemoveVariationEvent(newVariationRow);
        });

        // Function to handle image upload for new variations
        function setUploadImageEvent(variationRow) {
            const uploadImageBtn = variationRow.querySelector('.upload-image-btn');
            const imageInput = variationRow.querySelector('.variation-image-input');

            uploadImageBtn.addEventListener('click', function () {
                imageInput.click(); // Trigger the hidden file input when the button is clicked
            });

            imageInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Find the first empty placeholder in the carousel
                        for (let i = 0; i < imagePlaceholders.length; i++) {
                            if (imagePlaceholders[i].style.backgroundImage === '' || imagePlaceholders[i].innerHTML.includes('plus')) {
                                imagePlaceholders[i].style.backgroundImage = `url(${e.target.result})`;
                                imagePlaceholders[i].style.backgroundSize = 'cover';
                                imagePlaceholders[i].style.backgroundPosition = 'center';
                                imagePlaceholders[i].style.backgroundRepeat = 'no-repeat';
                                imagePlaceholders[i].innerHTML = 'Change'; // Remove the plus icon

                                // Update corresponding small image placeholder
                                smallImagePlaceholders[i].style.backgroundImage = `url(${e.target.result})`;
                                smallImagePlaceholders[i].style.backgroundSize = 'cover';
                                smallImagePlaceholders[i].style.backgroundPosition = 'center';
                                smallImagePlaceholders[i].style.backgroundRepeat = 'no-repeat';
                                smallImagePlaceholders[i].innerHTML = ''; // Remove the plus icon
                                break;
                            }
                        }
                    };
                    reader.readAsDataURL(file); // Display the selected image
                }
            });
        }

        // Function to handle remove or delete of a variation
        function setRemoveVariationEvent(variationRow) {
            const removeBtn = variationRow.querySelector('.remove-variation');
            removeBtn.addEventListener('click', function () {
                const variationId = removeBtn.getAttribute('data-id');

                // Trigger the modal for confirmation
                const modal = new bootstrap.Modal(document.getElementById('deleteVariationModal'));
                document.getElementById('variation-id').value = variationId; // Set the hidden input with variation ID
                modal.show();
            });
        }
         // Function to handle remove or delete of a variation
        function setRemoveVariationEvent(variationRow) {
            const removeBtn = variationRow.querySelector('.remove-variation');
            removeBtn.addEventListener('click', function () {
                const variationId = removeBtn.getAttribute('data-id');
                const variationName = variationRow.querySelector('.variation-name').value;

                // Set the hidden input with variation ID
                variationIdInput.value = variationId;
                variationNameSpan.textContent = variationName;

                // Update the form action to include the variation ID for deletion
                deleteForm.action = `/variations/${variationId}`;

                // Trigger the modal for confirmation
                deleteModal.show();
            });
        }

        // Initialize remove events for existing variations
        document.querySelectorAll('.variation-card').forEach(function(row) {
            setRemoveVariationEvent(row);
        });

        // Initialize upload and remove events for existing variations
        document.querySelectorAll('.variation-card').forEach(function(row) {
            setUploadImageEvent(row);
            setRemoveVariationEvent(row);
        });
    });
</script>


@endsection

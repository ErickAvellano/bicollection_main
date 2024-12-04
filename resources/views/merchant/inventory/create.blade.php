@extends('Components.layout')

@section('styles')
    <style>
        body, html {
            overflow: auto;
            height: 100%;
            margin: 0; /* Ensure no default margin */
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
        }

        .main-card {
            width: 100%;
            padding: 20px; /* Padding to give space inside the card */
            border-radius: 10px;
            background-color: #f8f9fa; /* Optional: background color */
        }

        .main-image {
            width: 100%;
            height: 200px; /* Adjust this based on your desired height */
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .small-image {
            width: 80px; /* Adjust size for smaller square cards */
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

        /* Style for the variation card
        .variation-card {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }*/

        .variation-name {
            flex-grow: 1;
            margin-right: 15px;
        }

        .remove-variation {
            background-color: red;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size:15px;
            font-weight:bold;
        }

    </style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Single Card for Images -->
        <div class="col-md-5 text-center">
            <div id="imageCarousel" class="carousel slide card main-card" style="height: 390px;" data-bs-ride="carousel">
                <!-- Carousel Inner -->
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="main-image card-placeholder" style="height: 350px;" id="image1-placeholder">
                            <i class="fa fa-plus icon"></i>
                        </div>
                        
                    </div>
                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="main-image card-placeholder" style="height: 350px;"  id="image2-placeholder">
                            <i class="fa fa-plus icon"></i>
                        </div>
                       
                    </div>
                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="main-image card-placeholder" style="height: 350px;" id="image3-placeholder">
                            <i class="fa fa-plus icon"></i>
                        </div>
                       
                    </div>
                    <!-- Slide 4 -->
                    <div class="carousel-item">
                        <div class="main-image card-placeholder" style="height: 350px;" id="image4-placeholder">
                            <i class="fa fa-plus icon"></i>
                        </div>
                        
                    </div>
                    <!-- Slide 5 -->
                    <div class="carousel-item">
                        <div class="main-image card-placeholder" style="height: 350px;" id="image5-placeholder">
                            <i class="fa fa-plus icon"></i>
                        </div>
                        
                    </div>
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

            <!-- Small Image Placeholders in Flex Row -->
            <div class="d-flex justify-content-center mt-3">
                <div class="small-image card-placeholder" id="small-image1-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="0">
                    <i class="fa fa-plus icon"></i>
                </div>
                <div class="small-image card-placeholder" id="small-image2-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="1">
                    <i class="fa fa-plus icon"></i>
                </div>
                <div class="small-image card-placeholder" id="small-image3-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="2">
                    <i class="fa fa-plus icon"></i>
                </div>
                <div class="small-image card-placeholder" id="small-image4-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="3">
                    <i class="fa fa-plus icon"></i>
                </div>
                <div class="small-image card-placeholder" id="small-image5-placeholder" data-bs-target="#imageCarousel" data-bs-slide-to="4">
                    <i class="fa fa-plus icon"></i>
                </div>
            </div>
        </div>

       <!-- Product Info Column -->
        <div class="col-md-6">
            <div class="card main-card">
                <div class="card-title">
                    <h4>Product Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('merchant.inventory.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" class="d-none image-upload" id="image1-input" name="images[]" accept="image/*">
                        <input type="file" class="d-none image-upload" id="image2-input" name="images[]" accept="image/*">
                        <input type="file" class="d-none image-upload" id="image3-input" name="images[]" accept="image/*">
                        <input type="file" class="d-none image-upload" id="image4-input" name="images[]" accept="image/*">
                        <input type="file" class="d-none image-upload" id="image5-input" name="images[]" accept="image/*">
                        <!-- Product Name -->
                        <div class="form-group mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="product_name" placeholder="Enter product name" required>
                        </div>

                        <!-- Product Description -->
                        <div class="form-group mb-3">
                            <label for="productDescription" class="form-label">Product Description</label>
                            <small class="form-text text-muted mb-2">
                                Please include details such as:
                                <ul>
                                    <li>About the product</li>
                                    <li>Dimension (L x W x H) / Size of the Product</li>
                                    <li>Material and specifications</li>
                                </ul>
                            </small>
                            <textarea class="form-control" id="productDescription" name="description" rows="5" placeholder="Enter product description..." required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <!-- Product Price -->
                                <div class="form-group">
                                    <label for="productPrice" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="productPrice" name="price" step="0.01" placeholder="Enter product price" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <!-- Product Quantity -->
                                <div class="form-group">
                                    <label for="productQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="productQuantity" name="quantity_item" placeholder="Enter available quantity" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <!-- Product Category -->
                                <div class="form-group">
                                    <label for="productCategory" class="form-label">Product Category</label>
                                    <select class="form-control" id="productCategory" name="category_id" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Product Subcategory -->
                                <div class="form-group">
                                    <label for="productSubcategory" class="form-label">Product Subcategory</label>
                                    <select class="form-control" id="productSubcategory" name="subcategory_id" required disabled>
                                        <option value="">Select a subcategory</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->category_id }}" data-category="{{ $subcategory->parentcategoryID }}">
                                                {{ $subcategory->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Product Variations Section -->
                        <div class="form-group">
                            <label for="productVariation" class="form-label">Product Variations</label>
                            <div class="mb-2" id="variationContainer">
                                <!-- Existing Variations will go here -->
                            </div>
                            <button type="button" class="btn btn-secondary mb-2" id="addVariation">Add Variation</button>
                        </div>
                        <!-- Submit Button -->
                        <div class="text-end"> <!-- Corrected from 'text-start-end' to 'text-end' -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-custom">Add Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('productCategory');
        const subcategorySelect = document.getElementById('productSubcategory');
        const variationContainer = document.getElementById('variationContainer');
        const addVariationBtn = document.getElementById('addVariation');
        const imagePlaceholders = document.querySelectorAll('.main-image');
        const smallImagePlaceholders = document.querySelectorAll('.small-image');

        // Function to filter subcategories based on selected category
        function filterSubcategories() {
            const selectedCategory = categorySelect.value;

            if (selectedCategory) {
                subcategorySelect.disabled = false; // Enable the subcategory dropdown
            } else {
                subcategorySelect.disabled = true; // Disable the subcategory dropdown
                subcategorySelect.value = ''; // Reset the subcategory selection
                return; // Exit the function if no category is selected
            }

            // Show only subcategories that match the selected category
            for (let i = 0; i < subcategorySelect.options.length; i++) {
                const option = subcategorySelect.options[i];
                const parentCategory = option.getAttribute('data-category');

                if (parentCategory === selectedCategory) {
                    option.style.display = 'block'; // Show option
                } else {
                    option.style.display = 'none'; // Hide option
                }
            }

            // Reset the subcategory dropdown selection
            subcategorySelect.value = '';
        }

        // Listen for changes in the category dropdown
        categorySelect.addEventListener('change', filterSubcategories);

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
                        <button type="button" class="remove-variation btn btn-danger">&times;</button>
                    </div>
                </div>
            `;

            // Append the new row to the variation container
            variationContainer.appendChild(newVariationRow);

            // Set up image upload logic for the new variation
            const uploadImageBtn = newVariationRow.querySelector('.upload-image-btn');
            const imageInput = newVariationRow.querySelector('.variation-image-input');

            uploadImageBtn.addEventListener('click', function () {
                imageInput.click(); // Trigger the hidden file input when the button is clicked
            });

            // Handle the image upload for variations
            imageInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Find the first empty placeholder in the carousel
                        for (let i = 0; i < imagePlaceholders.length; i++) {
                            if (imagePlaceholders[i].style.backgroundImage === '' || imagePlaceholders[i].innerHTML.includes('plus')) {
                                imagePlaceholders[i].style.backgroundImage = `url(${e.target.result})`;
                                imagePlaceholders[i].style.backgroundSize = 'cover'; // Change to 'contain' if you want to maintain aspect ratio
                                imagePlaceholders[i].style.backgroundPosition = 'center'; // Center the background image
                                imagePlaceholders[i].style.backgroundRepeat = 'no-repeat'; // Prevent repeating the image
                                imagePlaceholders[i].innerHTML = 'Change'; // Remove the plus icon

                                // Update corresponding small image placeholder
                                smallImagePlaceholders[i].style.backgroundImage = `url(${e.target.result})`;
                                smallImagePlaceholders[i].style.backgroundSize = 'cover'; // Same for small image
                                smallImagePlaceholders[i].style.backgroundPosition = 'center '; // Center for small image
                                smallImagePlaceholders[i].style.backgroundRepeat = 'no-repeat'; // Prevent repeating the image
                                smallImagePlaceholders[i].innerHTML = ''; // Remove the plus icon
                                break; // Exit the loop after updating the first empty placeholder
                            }
                        }
                    };
                    reader.readAsDataURL(file); // Display the selected image
                }
            });

            // Function to remove the variation row
            newVariationRow.querySelector('.remove-variation').addEventListener('click', function () {
                newVariationRow.remove(); // Remove this variation card
            });
        });

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
                        placeholder.style.backgroundPosition = 'center'; // Center the background image
                        placeholder.style.backgroundRepeat = 'no-repeat'; // Prevent repeating the image
                        // Create a circular overlay with "Change"
                        placeholder.innerHTML = `
                            <div style="position: absolute;
                                        top: 50%;
                                        left: 50%;
                                        transform: translate(-50%, -50%);
                                        width: 100px;
                                        height: 100px;
                                        border-radius: 50%;
                                        background-color: rgba(34, 139, 34, 0.2); /* #228b22 with 50% opacity */
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: white;
                                        font-weight: bold;">
                                Change
                            </div>
                        `;


                        // Update small image placeholder as well
                        smallImagePlaceholders[index].style.backgroundImage = `url(${e.target.result})`;
                        smallImagePlaceholders[index].style.backgroundSize = 'cover';
                        smallImagePlaceholders[index].style.backgroundPosition = 'center'; // Center the background image
                        smallImagePlaceholders[index].style.backgroundRepeat = 'no-repeat'; // Prevent repeating the image
                        smallImagePlaceholders[index].innerHTML = ''; // Remove the plus icon
                    };
                    reader.readAsDataURL(file); // Display the selected image
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
    });
</script>
@endsection

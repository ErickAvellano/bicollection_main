@extends('Components.layout')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    body, html {
        font-family: 'Poppins', sans-serif;
    }

    main {
        padding: 80px;
        text-align: center;
        background-image: url({{ asset('images/assets/start-selling/startsellingbg.png') }});
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 350px;
    }

    .nav-pills, .search-container, .desktop-nav {
        display: none;
    }
    .support-search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        margin: 20px auto;
        width: 500px;
        z-index: 10;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.6); /* Shadow below the search bar */
        border-radius: 8px; /* Match the input field's border-radius */
        background-color: #fff; /* Add a background color for consistency */
    }

    .support-search-container span {
        position: absolute;
        left: 15px; /* Ensure proper spacing from the left */
        top: 50%; /* Center vertically */
        transform: translateY(-50%); /* Align icon in the middle */
        font-size: 16px; /* Adjust icon size */
        color: #888; /* Icon color */
        z-index: 11; /* Ensure it appears above the input field */
    }

    .support-search-container input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        background-color: #fff; /* Ensure background is white */
        z-index: 10; /* Input remains on top */
        position: relative;
    }

    #suggestions {
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 100%;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        z-index: -100; /* Dropdown below input field */
        top: 100%; /* Position the dropdown below the input */
        left: 0;
        display: none; /* Initially hidden */
    }
    .dropdown-item {
        padding: 5px;
        font-size: 14px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .no-results {
        padding: 5px;
        font-size: 14px;
        color: #999;
        cursor: default;
    }

    mark {
        background-color: #ffeeba; /* Light yellow */
        padding: 0;
        font-weight: bold;
        color: #d9534f; /* Optional: Change  color */
        border-radius: 3px;
    }

    /* Breadcrumb */
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin: 0 0 20px;
        font-size: 14px;
    }

    .breadcrumb-item a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Title and Subtitle */
    .guide-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .guide-subtitle {
        font-size: 18px;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .guide-updated {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 20px;
    }

    /* Content Section */
    .guide-content p {
        font-size: 16px;
        color: #343a40;
        margin-bottom: 15px;
    }

    /* Image Section */
    .guide-image-section {
        margin-top: 20px;
    }

    .file-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
        text-align: center;
    }

    .file-box p {
        font-size: 14px;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 10px;
    }

    .file-box .icons img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }


    @media only screen and (min-width: 360px) and (max-width: 425px) {
        body {
            font-size: 12px;
        }

        .btn {
            --bs-btn-font-size: 0.9rem;
        }

        .navbar-brand {
            font-size: 1.2rem;
        }

        .mobile-nav {s
            display: none !important;
        }

        main {
            padding: 50px;
        }

        .support-search-container {
            width: 250px; /* Make it responsive for smaller screens */
        }

        .support-search-container input {
            font-size: 12px;
        }
        .guide-step{
            max-width: auto !important;
        }
        .guide-image{
            width:300px;
        }
    }
</style>

@endsection

@section('content')
<div class="container mt-5">
    <h2>Create Customer Support Guide</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('customersupport.store-guide.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="guide_title" class="form-label">Guide Title</label>
            <input type="text" class="form-control @error('guide_title') is-invalid @enderror" id="guide_title" name="guide_title" value="{{ old('guide_title') }}" required>
            @error('guide_title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" required>
            @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <h5>Step 1</h5>
        <div class="mb-3">
            <label for="step_1" class="form-label">Step 1 Title</label>
            <input type="text" class="form-control @error('step_1') is-invalid @enderror" id="step_1" name="step_1" value="{{ old('step_1') }}">
            @error('step_1')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_1_description" class="form-label">Step 1 Description</label>
            <textarea class="form-control @error('step_1_description') is-invalid @enderror" id="step_1_description" name="step_1_description" rows="3">{{ old('step_1_description') }}</textarea>
            @error('step_1_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_1_has_image" class="form-label">Step 1 Image (Optional)</label>
            <input type="file" class="form-control @error('step_1_has_image') is-invalid @enderror" id="step_1_has_image" name="step_1_has_image" accept="image/*">
            @error('step_1_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 2</h5>
        <div class="mb-3">
            <label for="step_2" class="form-label">Step 2 Title</label>
            <input type="text" class="form-control @error('step_2') is-invalid @enderror" id="step_2" name="step_2" value="{{ old('step_2') }}">
            @error('step_2')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_2_description" class="form-label">Step 2 Description</label>
            <textarea class="form-control @error('step_2_description') is-invalid @enderror" id="step_2_description" name="step_2_description" rows="3">{{ old('step_2_description') }}</textarea>
            @error('step_2_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_2_has_image" class="form-label">Step 2 Image (Optional)</label>
            <input type="file" class="form-control @error('step_2_has_image') is-invalid @enderror" id="step_2_has_image" name="step_2_has_image" accept="image/*">
            @error('step_2_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 3</h5>
        <div class="mb-3">
            <label for="step_3" class="form-label">Step 3 Title</label>
            <input type="text" class="form-control @error('step_3') is-invalid @enderror" id="step_3" name="step_3" value="{{ old('step_3') }}">
            @error('step_3')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_3_description" class="form-label">Step 3 Description</label>
            <textarea class="form-control @error('step_3_description') is-invalid @enderror" id="step_3_description" name="step_3_description" rows="3">{{ old('step_3_description') }}</textarea>
            @error('step_3_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_3_has_image" class="form-label">Step 3 Image (Optional)</label>
            <input type="file" class="form-control @error('step_3_has_image') is-invalid @enderror" id="step_3_has_image" name="step_3_has_image" accept="image/*">
            @error('step_3_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 4</h5>
        <div class="mb-3">
            <label for="step_4" class="form-label">Step 4 Title</label>
            <input type="text" class="form-control @error('step_4') is-invalid @enderror" id="step_4" name="step_4" value="{{ old('step_4') }}">
            @error('step_4')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_4_description" class="form-label">Step 4 Description</label>
            <textarea class="form-control @error('step_4_description') is-invalid @enderror" id="step_4_description" name="step_4_description" rows="3">{{ old('step_4_description') }}</textarea>
            @error('step_4_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_4_has_image" class="form-label">Step 4 Image (Optional)</label>
            <input type="file" class="form-control @error('step_4_has_image') is-invalid @enderror" id="step_4_has_image" name="step_4_has_image" accept="image/*">
            @error('step_4_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 5</h5>
        <div class="mb-3">
            <label for="step_5" class="form-label">Step 5 Title</label>
            <input type="text" class="form-control @error('step_5') is-invalid @enderror" id="step_5" name="step_5" value="{{ old('step_5') }}">
            @error('step_5')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_5_description" class="form-label">Step 5 Description</label>
            <textarea class="form-control @error('step_5_description') is-invalid @enderror" id="step_5_description" name="step_5_description" rows="3">{{ old('step_5_description') }}</textarea>
            @error('step_5_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_5_has_image" class="form-label">Step 5 Image (Optional)</label>
            <input type="file" class="form-control @error('step_5_has_image') is-invalid @enderror" id="step_5_has_image" name="step_5_has_image" accept="image/*">
            @error('step_5_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 6</h5>
        <div class="mb-3">
            <label for="step_6" class="form-label">Step 6 Title</label>
            <input type="text" class="form-control @error('step_6') is-invalid @enderror" id="step_6" name="step_6" value="{{ old('step_6') }}">
            @error('step_6')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_6_description" class="form-label">Step 6 Description</label>
            <textarea class="form-control @error('step_6_description') is-invalid @enderror" id="step_6_description" name="step_6_description" rows="3">{{ old('step_6_description') }}</textarea>
            @error('step_6_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_6_has_image" class="form-label">Step 6 Image (Optional)</label>
            <input type="file" class="form-control @error('step_6_has_image') is-invalid @enderror" id="step_6_has_image" name="step_6_has_image" accept="image/*">
            @error('step_6_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 7</h5>
        <div class="mb-3">
            <label for="step_7" class="form-label">Step 7 Title</label>
            <input type="text" class="form-control @error('step_7') is-invalid @enderror" id="step_7" name="step_7" value="{{ old('step_7') }}">
            @error('step_7')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_7_description" class="form-label">Step 7 Description</label>
            <textarea class="form-control @error('step_7_description') is-invalid @enderror" id="step_7_description" name="step_7_description" rows="3">{{ old('step_7_description') }}</textarea>
            @error('step_7_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_7_has_image" class="form-label">Step 7 Image (Optional)</label>
            <input type="file" class="form-control @error('step_7_has_image') is-invalid @enderror" id="step_7_has_image" name="step_7_has_image" accept="image/*">
            @error('step_7_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 8</h5>
        <div class="mb-3">
            <label for="step_8" class="form-label">Step 8 Title</label>
            <input type="text" class="form-control @error('step_8') is-invalid @enderror" id="step_8" name="step_8" value="{{ old('step_8') }}">
            @error('step_8')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_8_description" class="form-label">Step 8 Description</label>
            <textarea class="form-control @error('step_8_description') is-invalid @enderror" id="step_8_description" name="step_8_description" rows="3">{{ old('step_8_description') }}</textarea>
            @error('step_8_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_8_has_image" class="form-label">Step 8 Image (Optional)</label>
            <input type="file" class="form-control @error('step_8_has_image') is-invalid @enderror" id="step_8_has_image" name="step_8_has_image" accept="image/*">
            @error('step_8_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 9</h5>
        <div class="mb-3">
            <label for="step_9" class="form-label">Step 9 Title</label>
            <input type="text" class="form-control @error('step_9') is-invalid @enderror" id="step_9" name="step_9" value="{{ old('step_9') }}">
            @error('step_9')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_9_description" class="form-label">Step 9 Description</label>
            <textarea class="form-control @error('step_9_description') is-invalid @enderror" id="step_9_description" name="step_9_description" rows="3">{{ old('step_9_description') }}</textarea>
            @error('step_9_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_9_has_image" class="form-label">Step 9 Image (Optional)</label>
            <input type="file" class="form-control @error('step_9_has_image') is-invalid @enderror" id="step_9_has_image" name="step_9_has_image" accept="image/*">
            @error('step_9_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>

        <h5>Step 10</h5>
        <div class="mb-3">
            <label for="step_10" class="form-label">Step 10 Title</label>
            <input type="text" class="form-control @error('step_10') is-invalid @enderror" id="step_10" name="step_10" value="{{ old('step_10') }}">
            @error('step_10')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_10_description" class="form-label">Step 10 Description</label>
            <textarea class="form-control @error('step_10_description') is-invalid @enderror" id="step_10_description" name="step_10_description" rows="3">{{ old('step_10_description') }}</textarea>
            @error('step_10_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="step_10_has_image" class="form-label">Step 10 Image (Optional)</label>
            <input type="file" class="form-control @error('step_10_has_image') is-invalid @enderror" id="step_10_has_image" name="step_10_has_image" accept="image/*">
            @error('step_10_has_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <hr>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@include('Components.footer')
@endsection

@section('scripts')


@endsection

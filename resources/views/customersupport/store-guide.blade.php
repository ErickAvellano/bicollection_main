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

        @for ($i = 1; $i <= 10; $i++)
            <h5>Step {{ $i }}</h5>
            <div class="mb-3">
                <label for="step_{{ $i }}" class="form-label">Step {{ $i }} Title</label>
                <input type="text" class="form-control @error('step_{{ $i }}') is-invalid @enderror" id="step_{{ $i }}" name="step_{{ $i }}" value="{{ old("step_{$i}") }}" required>
                @error("step_{$i}")
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="step_{{ $i }}_description" class="form-label">Step {{ $i }} Description</label>
                <textarea class="form-control @error('step_{{ $i }}_description') is-invalid @enderror" id="step_{{ $i }}_description" name="step_{{ $i }}_description" rows="3">{{ old("step_{$i}_description") }}</textarea>
                @error("step_{$i}_description")
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="step_{{ $i }}_has_image" class="form-label">Step {{ $i }} Image (Optional)</label>
                <input type="file" class="form-control @error('step_{{ $i }}_has_image') is-invalid @enderror" id="step_{{ $i }}_has_image" name="step_{{ $i }}_has_image" accept="image/*">
                @error("step_{$i}_has_image")
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <hr>
        @endfor

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@include('Components.footer')
@endsection

@section('scripts')


@endsection

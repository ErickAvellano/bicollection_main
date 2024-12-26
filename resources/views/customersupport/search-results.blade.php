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


    @media only screen and (max-width: 425px) {
        body {
            font-size: 12px;
        }

        .btn {
            --bs-btn-font-size: 0.9rem;
        }

        .navbar-brand {
            font-size: 1.2rem;
        }

        .mobile-nav {
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
    }
</style>

@endsection

@section('content')
<section>
    <div class="container-fluid">
        <!-- Search Form -->
        <div class="support-search-container">
            <span>
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input
                type="text"
                id="search-query"
                name="query"
                placeholder="Describe your issue"
                autocomplete="off"
                required
            >
            <div id="suggestions"></div>
        </div>
    </div>
</section>

<section class="guide-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Search</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $guide->category }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $guide->guide_title }}</li>
            </ol>
        </nav>

        <!-- Title and Subtitle -->
        <h1 class="guide-title">{{ $guide->guide_title }}</h1>
        <p class="guide-subtitle">{{ $guide->category }}</p>
        <p class="guide-updated">Updated {{ $guide->updated_at->diffForHumans() }}</p>

        <!-- Ratings -->
        <div class="guide-ratings">
            <span class="rating-stars">
                @php
                    $filledStars = floor($guide->ratings);
                    $halfStar = $guide->ratings - $filledStars >= 0.5 ? true : false;
                    $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                @endphp

                @for ($i = 0; $i < $filledStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor

                @if ($halfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star"></i>
                @endfor
            </span>
            <span class="rating-value">({{ $guide->ratings }}/5)</span>
        </div>

        <!-- Steps -->
        @for ($i = 1; $i <= 10; $i++)
            @php
                $step = 'step_' . $i;
                $stepDescription = 'step_' . $i . '_description';
                $stepHasImage = 'step_' . $i . '_has_image';
            @endphp

            @if (!empty($guide->$step) && !empty($guide->$stepDescription))
                <div class="guide-step">
                    <h4>Step {{ $i }}: {{ $guide->$step }}</h4>
                    <p>{{ $guide->$stepDescription }}</p>

                    @if ($guide->$stepHasImage)
                        <div class="guide-step-image">
                            <img src="/path/to/images/step_{{ $i }}.png" alt="Step {{ $i }} Image" />
                        </div>
                    @endif
                </div>
            @endif
        @endfor
    </div>
</section>




@include('Components.footer')
@endsection

@section('scripts')

<script>

</script>
@endsection

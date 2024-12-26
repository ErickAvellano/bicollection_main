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

<section class="guide-section">
    <div class="container mt-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Search</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $guide->category ?? null }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $guide->guide_title }}</li>
            </ol>
        </nav>

        <!-- Title and Subtitle -->
        <h2 class="guide-title">{{ $guide->guide_title }}</h2>
        <p class="guide-subtitle">{{ $guide->category }}</p>
        <p class="guide-updated">Updated {{ $guide->updated_at->diffForHumans() }}</p>

        <!-- Ratings -->
        <div class="guide-ratings">
            <span class="rating-stars">
                @php
                    $filledStars = floor($guide->ratings);
                    $halfStar = $guide->ratings - $filledStars >= 0.5;
                    $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                @endphp

                @for ($i = 0; $i < $filledStars; $i++)
                    <i class="fas fa-star text-warning"></i>
                @endfor

                @if ($halfStar)
                    <i class="fas fa-star-half-alt text-warning"></i>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star text-muted"></i>
                @endfor
            </span>
            <span class="rating-value">({{ $guide->ratings }}/5)</span>
        </div>
        <hr>

        <!-- Steps -->
        <div class="guide-steps">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $step = 'step_' . $i;
                    $stepDescription = 'step_' . $i . '_description';
                    $stepHasImage = 'step_' . $i . '_has_image';
                @endphp

                @if (!empty($guide->$step) && !empty($guide->$stepDescription))
                    <div class="guide-step mb-4">
                        <h5 class="ms-2">Step {{ $i }}: {{ $guide->$step }}</h5>
                        <p  class="ms-5 mt-3">{{ $guide->$stepDescription }}</p>

                        <!-- Check for images -->
                        @if ($guide->$stepHasImage)
                            <div class="guide-step-image">
                                <img src="{{ Storage::url() ?? 'https://via.placeholder.com/150' }}" alt="Step {{ $i }} Image" class="img-fluid rounded" />
                            </div>
                        @else
                            <p class="text-muted">No image available for this step.</p>
                        @endif
                    </div>
                @endif
            @endfor
        </div>
    </div>
</section>

<section class="mt-4">
    <h4 class="mt-4 text-center">Have we solved your problem?</h4>

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
                placeholder="Any other issue?"
                autocomplete="off"
                required
            >
            <div id="suggestions"></div>
        </div>
    </div>
    <h6 class="text-center">How would you rate our customer support guide</h6>
    <div class="text-center">
        <span class="fa fa-star"></span>
        <span class="fa fa-star"></span>
        <span class="fa fa-star"></span>
        <span class="fa fa-star"></span>
        <span class="fa fa-star"></span>
    </div>
</section>






@include('Components.footer')
@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        // Add CSRF token to AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Trigger search when typing
        $('#search-query').on('input', function () {
            let query = $(this).val();

            // Show suggestions only when the query is not empty
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('customersupport.autocomplete') }}",
                    method: "GET",
                    data: { query: query },
                    success: function (data) {
                        // Hide the error message if previously shown
                        $('.alert-danger').hide();

                        $('#suggestions').empty().show(); // Clear and show suggestions

                        $('#suggestions').append('<div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>');

                        if (data.length === 0) {
                            $('#suggestions').append('<div class="no-results">No matches found</div>');
                        } else {
                            $.each(data, function (index, item) {
                                // Highlight matching parts
                                let highlightedText = highlightMatch(item.guide_title, query);

                                // Include the suggestion id in the URL
                                $('#suggestions').append(`
                                    <a class="dropdown-item" href="{{ url('/customer-support/search') }}?query=${encodeURIComponent(item.guide_title)}">
                                        ${highlightedText}
                                    </a>
                                `);
                            });
                        }
                    },
                    error: function (xhr) {
                        // Clear existing suggestions
                        $('#suggestions').empty().hide();

                        if (xhr.status === 404) {
                            // Show the error message in the alert div
                            $('.alert-danger')
                                .text(xhr.responseJSON.error || "Guide not found! We couldn't find a guide matching your search.")
                                .show();
                        } else {
                            // Handle other error types with a generic message
                            $('.alert-danger')
                                .text("An unexpected error occurred. Please try again later.")
                                .show();
                        }
                    }
                });
            } else {
                $('#suggestions').hide(); // Hide suggestions if query is empty
            }
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.support-search-container').length) {
                $('#suggestions').hide();
            }
        });

        // Function to highlight matching text
        function highlightMatch(text, query) {
            const regex = new RegExp(`(${query})`, 'gi'); // Create a case-insensitive regex for the query
            return text.replace(regex, '<mark>$1</mark>'); // Wrap matching parts in <mark>
        }
    });
</script>
@endsection

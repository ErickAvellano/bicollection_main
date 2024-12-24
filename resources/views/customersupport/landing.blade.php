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

    form {
        margin-top: 20px;
    }

    .support-search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        margin: 20px auto;
        width: 500px;
        z-index: 10;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4); /* Shadow below the search bar */
        border-radius: 8px; /* Match the input field's border-radius */
        background-color: #fff; /* Add a background color for consistency */
    }

    .support-search-container span {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
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
        z-index: 5; /* Dropdown below input field */
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
        color: #d9534f; /* Optional: Change text color */
        border-radius: 3px;
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
<main>
    <h4 class="mt-4">Hi, how can we help you?</h4>

    <!-- Search Form -->
    <div class="support-search-container">
        <span>
            <i class="fas fa-search"></i>
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

    <!-- No Answer Section -->
    <div style="margin-top: 20px;">
        <strong>Can't find an answer?</strong>
    </div>
</main>

@include('Components.footer')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        $('#suggestions').empty().show(); // Clear and show suggestions

                        if (data.length === 0) {
                            $('#suggestions').append('<div class="no-results">No matches found</div>');
                        } else {
                            $.each(data, function (index, item) {
                                // Highlight matching parts
                                let highlightedText = highlightMatch(item.guide_title, query);

                                $('#suggestions').append(`
                                    <a class="dropdown-item" href="{{ url('/customer-support/search') }}?query=${encodeURIComponent(item.guide_title)}">
                                        ${highlightedText}
                                    </a>
                                `);
                            });
                        }
                    },
                    error: function () {
                        console.log('Error fetching suggestions.');
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

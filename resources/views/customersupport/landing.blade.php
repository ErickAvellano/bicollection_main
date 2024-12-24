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
    }

    .support-search-container span {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 16px;
    }

    .support-search-container input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        outline: none;
    }
    #suggestions {
        max-height: 200px;
        overflow-y: auto;
        width: 100%; /* Matches the input field width */
    }

    .dropdown-item {
        padding: 8px 16px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa; /* Light gray */
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
        main{
            padding:50px;
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
        <div id="suggestions" class="dropdown-menu" style="display: none; position: absolute; z-index: 1000;"></div>
    </div>

    <!-- No Answer Section -->
    <div style="margin-top: 20px;">
        <strong>Can't find an answer?</strong>
    </div>
</main>

@include('Components.footer')
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
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
                            $('#suggestions').append('<a class="dropdown-item disabled">No matches found</a>');
                        } else {
                            $.each(data, function (index, item) {
                                $('#suggestions').append(`
                                    <a class="dropdown-item" href="{{ url('/customer-support/search') }}?query=${item.guide_title}">
                                        ${item.guide_title}
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
    });
</script>

@endsection

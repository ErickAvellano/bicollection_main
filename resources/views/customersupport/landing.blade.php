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
        color: #d9534f; /* Optional: Change text color */
        border-radius: 3px;
    }
    .assistance-container {
        margin-top: 15px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9fc;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Header Section */
    .assistance-header {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 15px;
    }

    /* Step-by-step process */
    .steps {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    .step {
        display: flex;
        align-items: center;
    }

    .step-number {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background-color: #ddd;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 10px;
    }

    .step-number.active {
        background-color: #6c63ff; /* Purple for active step */
    }

    .step-text {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    /* Dropdown Selection */
    .dropdown-container {
        margin-bottom: 20px;
    }

    .dropdown {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
    }

    /* Live Chat Button */
    .live-chat-container {
        text-align: right;
    }

    .live-chat-button {
        background-color: #6c63ff;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .live-chat-button i {
        font-size: 14px;
    }

    .live-chat-button:hover {
        background-color: #5a54d1;
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

    <div style="margin-top: 20px;">
        <strong>Suggestion Search:</strong>
        <ul id="suggestion-list" style="margin-top: 5px; list-style: none; padding: 0;">
            <!-- Dynamic suggestions will be added here -->
        </ul>
    </div>

    <!-- No Answer Section -->
    <div style="margin-top: 20px;">
        <strong style="font-size: 18px;">Can't find an answer?</strong>

        <!-- Assistance Section -->
        <div class="assistance-container">
            <div class="assistance-header">
                Select a topic and order to get assistance
            </div>
            <div class="assistance-body">
                <!-- Step-by-step process -->
                <div class="steps">
                    <div class="step">
                        <div class="step-number active">1</div>
                        <div class="step-text">Select the topic</div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-text">Select the subscription</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-text">Get support from our team</div>
                    </div>
                </div>

                <!-- Dropdown Selection -->
                <div class="dropdown-container">
                    <select class="dropdown">
                        <option selected disabled>Select Topic</option>
                        <option value="1">Billing</option>
                        <option value="2">Technical Support</option>
                        <option value="3">Account Management</option>
                    </select>
                </div>

                <!-- Live Chat Button -->
                <div class="live-chat-container">
                    <button class="live-chat-button">
                        <i class="fas fa-comment-alt"></i> Start a live chat
                    </button>
                </div>
            </div>
        </div>
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
<script>
    $(document).ready(function () {
        // Fetch suggestions dynamically
        function fetchSuggestions() {
            $.ajax({
                url: "{{ route('customersupport.suggestions') }}", // Laravel route for fetching suggestions
                method: "GET",
                success: function (data) {
                    // Get the container for suggestions
                    let suggestionList = $('#suggestion-list');
                    suggestionList.empty(); // Clear existing suggestions

                    // Loop through the suggestions and append to the list
                    if (data.length > 0) {
                        data.slice(0, 5).forEach(function (item) {
                            suggestionList.append(`<li style="padding: 5px 0;"><a href="{{ url('/customer-support/search') }}?query=${encodeURIComponent(item.guide_title)}" style="text-decoration: none; color: #007bff;">${item.guide_title}</a></li>`);
                        });
                    } else {
                        suggestionList.append('<li>No suggestions available</li>');
                    }
                },
                error: function () {
                    console.log('Error fetching suggestions.');
                }
            });
        }

        // Fetch suggestions when the page loads
        fetchSuggestions();
    });

</script>
@endsection

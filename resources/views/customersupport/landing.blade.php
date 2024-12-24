@extends('Components.layout')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    body, html {
        font-family: 'Poppins', sans-serif; /* Use Poppins font */
    }
     main {
            padding: 80px;
            text-align: center;
            background-image: url({{ asset('images/assets/start-selling/startsellingbg.png') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height:350px;
        }
        .nav-pills, .search-container, .desktop-nav{
            display:none;
        }
    
        @media only screen and (min-width: 360px) and (max-width: 425px) {
        /* Styles for devices in this range */
        body {
            font-size: 12px;
        }
        .btn{
            --bs-btn-font-size: 0.9rem;
        }
        .navbar-brand{
            font-size: 1.2rem;
        }
        .mobile-nav{
            display:none;
        }

    }
</style>

@endsection

@section('content')
<main style="text-align: center;">
    <h4 class="mt-4">Hi, how can we help you?</h4>

    <!-- Search Form -->
    <form action="#" method="GET" style="margin-top: 20px;">
        <div style="
            display: flex; 
            justify-content: center; 
            align-items: center; 
            position: relative;
            margin: 20px auto;
            width: 500px;
        ">
            <span style="
                position: absolute; 
                left: 15px; 
                top: 50%; 
                transform: translateY(-50%);
                color: #888;
                font-size: 16px;">
                <i class="fas fa-search"></i>
            </span>
            <input 
                type="text" 
                name="query" 
                placeholder="Describe your issue" 
                style="
                    width: 100%; 
                    padding: 12px 15px 12px 40px; 
                    font-size: 16px; 
                    border: 1px solid #ddd; 
                    border-radius: 8px; 
                    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); 
                    outline: none;"
                required
            >
        </div>
    </form>

    <!-- No Answer Section -->
    <div style="margin-top: 20px;">
        <strong>Can't find an answer?</strong>
    </div>
</main>



@include('Components.footer')
@endsection
@section('scripts')

@endsection

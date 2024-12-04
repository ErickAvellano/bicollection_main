<!-- resources/views/auth/forgot-password.blade.php -->

@extends('Components.layout')

@section('styles')
<style>
    body, html {
        height: 100%;
        overflow: auto;
    }

    .logo-img {
        width: 300px;
        margin-bottom: 20px;
    }
    .nav-pills,
    .search-control,
    .search-icon,
    .desktop-nav{
        display:none;
    }

    .forgot-password-container {
        background: rgba(248, 249, 250, 0.9); /* Semi-transparent background */
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        border: 2px solid #228b22;
        position: relative;
        z-index: 2; /* Ensure the container is on top */
    }

    .form-control:focus {
        border-color: #228b22;
        box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25);
    }

    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }

    .btn-custom:hover,
    .btn-custom:focus {
        background-color: #fafafa; /* Custom hover background color */
        border-color: #228b22; /* Custom hover border color */
        color: black;
    }

    /* Hide return button on smaller screens */
    .btn-return {
        display: none;
    }

    @media (max-width: 576px) {
        .col-md-7 {
            display: none !important;
        }
    }

    /* Tablet View: Adjust styles for tablets */
    @media (min-width: 577px) and (max-width: 768px) {
        .col-md-7 {
            display: none !important;
        }
    }

    @media (min-width: 769px) and (max-width: 1000px) {
        .col-md-7 {
            display: flex !important;
        }

        .col-md-5 {
            display: flex !important;
        }
    }

    /* Large Desktop View: Default styles for large desktops */
    @media (min-width: 1025px) {
        .btn-return {
            display: inline-block;
            margin-top: 15px;
        }
    }
    .sidebar {
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex justify-content-center align-items-center flex-column">
            <div class="forgot-password-container">
                <div class="card-header text-center">
                    <h4>Forgot Your Password?</h4>
                </div>
                 <!-- Display Success or Error Messages -->
                 @if (session('status'))
                 <div class="alert alert-success alert-custom w-100" role="alert">
                     {{ session('status') }}
                 </div>
             @endif

                <div class="card-body text-center mt-3">
                    <p>Enter your email address below, and we will send you instructions on how to reset your password.</p>

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label mt-2">Email Address:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror text-center mb-3" id="email" name="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Send Password Reset Link</button>
                    </form>
                    <a href="{{ route('login') }}" class="btn btn-custom btn-return w-100">Return to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

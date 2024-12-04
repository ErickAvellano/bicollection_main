<!-- resources/views/auth/verify-email.blade.php -->

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

    .verification-container {
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

    .form-check-label {
        font-weight: normal;
    }

    .form-check-input:checked {
        background-color: #228b22; /* Custom checkbox color when checked */
        border-color: #228b22;
    }

    .forgot-text a {
        text-decoration: none;
        color: #0073ee;
    }

    .forgot-text a:hover {
        text-decoration: underline;
    }

    .sidebar {
        z-index: 10;
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

    @media (max-width: 576px) {
        body {
            background: url('{{ asset('images/assets/bicollectionlogowname2.png') }}') no-repeat center center fixed;
            background-size: contain; /* Adjust background size */
            background-position: center; /* Center the background image */
        }

        .col-md-7 {
            display: none !important;
        }
    }

    /* Tablet View: Adjust styles for tablets */
    @media (min-width: 577px) and (max-width: 768px) {
        body {
            background: url('{{ asset('images/assets/bicollectionlogowname2.png') }}') no-repeat center center fixed;
            background-size: 70%; /* Adjust background size */
            background-position: center; /* Center the background image */
        }

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

    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex justify-content-center align-items-center flex-column">
            <div class="verification-container">
                <div class="card-header text-center">
                    <h4>Verify Your Email Address</h4>
                </div>
                <div class="card-body text-center mt-3">
                    <p>We have sent you an email with a verification code. Please check your inbox and enter the code below to verify your email address.</p>

                    <form action="{{ route('verification.verify') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label mt-2">Verification Code:</label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror  text-center mb-3" id="otp" name="otp" required>
                            @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

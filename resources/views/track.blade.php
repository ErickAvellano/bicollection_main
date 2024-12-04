<!-- resources/views/track.blade.php -->

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

    .track-order-container {
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
        padding-bottom:10px;
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
            <div class="track-order-container">
                <div class="card-header text-center">
                    <h4>Track Your Order</h4>
                </div>
                 <!-- Display Success or Error Messages -->
                 @if (session('status'))
                 <div class="alert alert-success alert-custom w-100" role="alert">
                     {{ session('status') }}
                 </div>
             @endif

                <div class="card-body text-center mt-3">
                    <p>Enter your order reference number below to track your order.</p>

                    <form action="{{ route('order.track') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reference_number" class="form-label mt-2">Order Reference Number:</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror text-center mb-3" id="reference_number" name="reference_number" required>
                            @error('reference_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mb-1">Track Order</button>
                    </form>
                    <a href="{{ route('home') }}" class="btn btn-custom btn-return w-100 ">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

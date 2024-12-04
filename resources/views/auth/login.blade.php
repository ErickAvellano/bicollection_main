@extends('Components.layout')

@section('styles')
<style>
    body,html {
        overflow: auto;
        height: 100%;
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
    .login-container {
        background: rgba(248, 249, 250, 0.8); /* Semi-transparent background */
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
        color: #007bff;
    }
    .forgot-text a:hover {
        text-decoration: underline;
    }
    .sidebar{
        z-index: 10;
    }
    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }
    .btn-custom:hover,
    .btn-custom:focus {
        background-color: #fafafa;; /* Custom hover background color */
        border-color: #228b22;; /* Custom hover border color */
        color: black;
    }
    #togglePassword {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .invalid-feedback{
        font-size: 12px;
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
        .col-md-5{
            display: flex !important;
        }
    }

    /* Large Desktop View: Default styles for large desktops */
    @media (min-width: 1025px) {

    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 d-none d-lg-flex justify-content-center align-items-center flex-column">
            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="" class="logo-img">
            <h5>A Web-based Application for Native Products in Bicol Region</h5>
        </div>
        <div class="col-md-5 d-flex justify-content-center align-items-center flex-column">
            <div class="login-container">
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    <h4 class="text-center mb-4">Login</h4>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                        <span id="emailError" class="invalid-feedback" role="alert">
                            @error('email')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </span>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password"
                                id="password"
                                name="password"
                                required
                            >

                            <button class="btn btn-outline-secondary" style="width:50px;" type="button" id="togglePassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                            <span id="passwordError" class="invalid-feedback" role="alert">
                                @error('password')
                                    <strong>{{ $message }}</strong>
                                @enderror
                            </span>
                        </div>

                    </div>
                    <div class="mt-2 mb-2 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Login</button>
                </form>
                <div class="mt-4 mb-4 text-center forgot-text">
                    <a href="{{ route('password.request') }}" class="text-muted">Forgot Password?</a>
                </div>
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for login feedback -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center" id="feedbackMessage">
                <h1><i class="fa-regular" id="statusModalIcon"></i></h1>
                <p id="statusModalMessage"></p>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    // Password visibility toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('togglePassword');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const passwordIcon = toggleButton.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                }
            });
        }
    });
</script>

@endsection

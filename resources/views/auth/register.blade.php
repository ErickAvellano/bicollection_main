@extends('Components.layout')

@section('styles')
<style>
    body, html {
        height: 100%;
        overflow: auto; /* Ensure overflow is enabled */
    }
    .nav-pills,
    .search-control,
    .search-icon,
    .desktop-nav{
        display:none;
    }

    .logo-img {
        width: 300px;
        margin-bottom: 20px;
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
        color: #228b22; /* Custom label color */
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
    .error{
        font-size: 12px;
        color: red;
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

    /* Custom button styles */
    .btn-custom {
        background-color: #228b22; /* Custom background color */
        border-color: #228b22; /* Custom border color */
        color: #fff; /* Custom text color */
    }
    .btn-custom:hover {
        background-color: #1e7e1e; /* Custom hover background color */
        border-color: #1e7e1e; /* Custom hover border color */
    }
    .btn-custom:focus,
    .btn-custom:active {
        background-color: #228b22 !important; /* Ensure focus and active states match */
        border-color: #228b22 !important; /* Ensure border color matches */
        box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25); /* Adjust the box shadow */
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <!-- Hide on small screens -->
        <div class="col-md-7 d-none d-lg-flex justify-content-center align-items-center flex-column">
            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="" class="logo-img">
            <h5>A Web-based Application for Native Products in Bicol Region</h5>
        </div>
        <div class="col-md-5 d-flex justify-content-center align-items-center flex-column">
            <div class="login-container">
                <!-- Register Form -->
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <h4 class="text-center mb-4">Sign Up</h4>
                    <input type="hidden" name="type" value="customer">

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Email"
                               required>
                        @if ($errors->has('email'))
                            <span class="error" role="alert">
                                <ul>
                                    @foreach ($errors->get('email') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <span class="error" role="alert">
                                <ul>
                                    @foreach ($errors->get('password') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                        @endif
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password-confirm"
                                   name="password_confirmation"
                                   placeholder="Confirm Password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <span class="error" role="alert">
                                <ul>
                                    @foreach ($errors->get('password') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3">
                        <button class="btn btn-custom w-100" type="submit">Sign Up</button>
                    </div>

                    <!-- Login Link -->
                    <div class="mb-3 text-center">
                        <span>Already have an account? <a href="{{ route('login') }}" class="login">Log in</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password-confirm');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
@endsection

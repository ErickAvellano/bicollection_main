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

    .signup-container {
        background: rgba(248, 249, 250, 0.8);
        padding: 30px;
        border-radius: 8px;
        max-width: 400px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 2px solid #228b22;
        position: relative;
        z-index: 2;
    }

    .form-control:focus {
        border-color: #228b22;
        box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25);
    }

    .form-check-label {
        font-weight: normal;
        color: #228b22;
    }

    .form-check-input:checked {
        background-color: #228b22;
        border-color: #228b22;
    }

    .forgot-text a {
        text-decoration: none;
        color: #007bff;
    }

    .forgot-text a:hover {
        text-decoration: underline;
    }

    .btn-custom {
        background-color: #228b22;
        border-color: #228b22;
        color: #fff;
    }

    .btn-custom:hover {
        background-color: #1e7e1e;
        border-color: #1e7e1e;
    }

    .btn-custom:focus,
    .btn-custom:active {
        background-color: #228b22 !important;
        border-color: #228b22 !important;
        box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25);
    }
    .secondary-menu,
    .search-container,
    .desktop-nav,
    .search-icon {
        display: none;
    }
    /* Progress bar styles */
    .progress-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        width: 100%;
        max-width: 300px;
    }

    .progress-step {
        width: 30%;
        text-align: center;
        position: relative;
    }

    .progress-step::before {
        content: '\f00c'; /* FontAwesome checkmark icon */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 16px; /* Reduced the icon size */
        color: #ccc;
        background-color: white;
        border-radius: 50%;
        padding: 5px;
        border: 2px solid #228b22;
        display: inline-block;
        width:40px;
    }

    .progress-step.active::before {
        color: #228b22; /* Green when active */
    }

    .progress-step.completed::before {
        color: #fff;
        background-color: #228b22; /* Green circle for completed step */
    }

    .progress-step span {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        color: #333;
    }

    .progress-line {
        position: absolute;
        top: 17px;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #ccc;
        z-index: -1;
    }

    .progress-line.completed {
        background-color: #228b22;
    }

    .progress-step + .progress-step::before {
        margin-left: -10px;
    }
    .error{
        font-size: 12px;
        color: red;
    }


</style>
@endsection

@section('content')
<div class="container mt-4 d-flex flex-column align-items-center">
    <div class="progress-container">
        <div class="progress-step" id="progress-step-1">
            <div class="progress-line" id="progress-line-1"></div>
            <span>Email</span>
        </div>
        <div class="progress-step" id="progress-step-2">
            <div class="progress-line" id="progress-line-2"></div>
            <span>Business Detail</span>
        </div>
        <div class="progress-step" id="progress-step-3">
            <span>Terms &amp; Conditions</span>
        </div>
    </div>
    <div class="signup-container mb-2">
        <form action="{{ route('merchant.register') }}" method="POST">
            @csrf
            <h4 class="text-center mb-4">Sign Up</h4>
            <input type="hidden" name="type" value="merchant">
            <div class="form-step form-step-active step-1-container" id="step-1">
                <div class="mb-3 w-100">
                    <label for="shopname" class="form-label">Shop Name</label>
                    <input type="text" class="form-control email-input" placeholder="Store Name" id="shopname" name="shopname" required>
                </div>
                <div class="mb-3 w-100">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control email-input @error('email') is-invalid @enderror" placeholder="Email" id="email" name="email" value="{{ old('email') }}" required>
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
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control password-input @error('password') is-invalid @enderror"
                               placeholder="Password" id="password" name="password" required>
                        <button class="btn btn-outline-secondary" type="button" id="mregisterPassword">
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

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control password-input"
                               placeholder="Confirm Password" id="password-confirm" name="password_confirmation" required>
                        <button class="btn btn-outline-secondary" type="button" id="mregisterConfirmPassword">
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
                <div class="mb-3">
                    <button class="btn btn-custom w-100" type="submit" id="submitButton">Register</button>
                </div>
            </div>
            <div class="mb-3 text-center">
                <span>Already have an account? <a href="{{ route('login') }}" class="login">Log in</a></span>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the password and confirm password input fields
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password-confirm');

        // Toggle visibility for the password field
        document.getElementById('mregisterPassword').addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Toggle visibility for the confirm password field
        document.getElementById('mregisterConfirmPassword').addEventListener('click', function() {
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);

            // Toggle the eye icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>

@endsection

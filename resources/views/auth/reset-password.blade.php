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
        .col-md-7 {
            display: none !important;
        }
    }

    .spinner-overlay {
        display: none; /* Initially hidden */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 d-flex justify-content-center align-items-center flex-column">
            <div class="verification-container">
                <div class="card-header text-center">
                    <h4>{{ __('Reset Your Password') }}</h4>
                </div>
                <div class="card-body text-center mt-3">
                    <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="otp" value="{{ $otp }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation" id="password-confirm" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom btn-block">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
            </div>
            <div class="modal-body">
                Your password has been successfully changed!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="goBackToLogin">Go Back to Login</button>
            </div>
        </div>
    </div>
</div>

<div id="loadingSpinner" class="spinner-overlay">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission with loading spinner
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const loadingSpinner = document.getElementById('loadingSpinner');

        loadingSpinner.style.display = 'none';

        resetPasswordForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            loadingSpinner.style.display = 'flex'; // Show the spinner

            const formData = new FormData(resetPasswordForm);

            fetch("{{ route('password.update') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingSpinner.style.display = 'none'; // Hide the spinner

                if (data.success) {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();

                    document.getElementById('goBackToLogin').addEventListener('click', function() {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    alert('Error: ' + (data.message || 'Unable to reset password.'));
                }
            })
            .catch(error => {
                loadingSpinner.style.display = 'none';
                alert('Error occurred. Please try again.');
                console.error('Error:', error);
            });
        });

        // Toggle password visibility
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
    });
</script>
@endsection

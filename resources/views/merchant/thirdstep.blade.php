@extends('Components.layout')

@section('styles')
    <style>
        body {
            overflow: auto;
            height: auto;
        }

        .secondary-menu {
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

        .progress-step {
            width: 30%;
            text-align: center;
            position: relative;
        }

        .progress-step::before {
            content: '\f00c';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            font-size: 16px;
            color: #ccc;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            border: 2px solid #228b22;
            display: inline-block;
            width: 40px;
        }

        .progress-step.active::before {
            color: #228b22;
        }

        .progress-step.completed::before {
            color: #fff;
            background-color: #228b22;
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

        /* Styling the pill buttons */
        .btn-pill {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 11px;
            border-color: #228b22;
            color: #228b22;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Checked state styling */
        .btn-check:checked + .btn-pill {
            background-color: #228b22;
            color: white;
            border-color: #228b22;
        }

        .btn-pill:hover {
            background-color: #228b22;
            color: white;
        }

        .d-flex {
            display: flex;
            flex-wrap: wrap;
        }

        .gap-2 {
            gap: 10px;
        }

        .btn-custom {
            background-color: #228b22;
            border-color: #228b22;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #fafafa;
            border-color: #228b22;
            color: #228b22;
        }

        /* Highlight invalid fields */
        input.error, select.error, textarea.error {
            border: 2px solid red;
        }

        #categoryError {
            color: red;
            font-size: 12px;
        }

        .form-check-input:hover {
            border-color: #228b22; /* Highlight border on hover */
        }

        .form-check-input:checked {
            background-color: #228b22; /* Background color when checked */
            border-color: #228b22; /* Border color when checked */
            box-shadow: 0 0 5px rgba(34, 139, 34, 0.5);
        }

        .form-check-input:focus {
            outline: none; /* Remove default outline */
            box-shadow: 0 0 5px rgba(34, 139, 34, 0.5);
        }

        .form-check-input:disabled {
            background-color: #e9ecef; /* Gray background for disabled state */
            border-color: #dcdcdc; /* Light gray border */
            cursor: not-allowed; /* Show disabled cursor */
            opacity: 0.6; /* Reduce opacity */
        }
        .search-container, .search-icon .fa-map-location-dot, .desktop-nav {
            display: none;
        }

    </style>
@endsection

@section('content')
    <div class="form-step step-2-container container mt-4 d-flex flex-column align-items-center" id="step-2">
        <div class="progress-container">
            <div class="progress-step completed" id="progress-step-1">
                <div class="progress-line active" id="progress-line-1"></div>
                <span>Email</span>
            </div>
            <div class="progress-step completed" id="progress-step-2">
                <div class="progress-line active" id="progress-line-2"></div>
                <span>Business Detail</span>
            </div>
            <div class="progress-step" id="progress-step-3">
                <span>Terms & Conditions</span>
            </div>
        </div>
        <p class="text-center">Please review and agree to the terms and conditions to<br> complete your merchant registration</p>
        <!-- Form starts here -->
        <form action="{{ route('handleThirdStep') }}" method="POST">
            @csrf
            <div class="signup-container mb-2">
                <div class="form-step step-3-container" id="step-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="termsCheckbox" name="termsCheckbox">
                            <label class="form-check" for="termsCheckbox">
                                I agree to the <a href="terms-and-conditions.html" target="_blank">BiCollection Terms and Conditions</a>
                            </label>
                        </div>
                        @error('termsCheckbox')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-custom w-100" type="submit" id="submitButton">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>

</script>
@endsection

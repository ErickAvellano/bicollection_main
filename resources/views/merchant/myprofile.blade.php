@extends('Components.layout')

@section('styles')
<style>
    body, html {
        margin: 0;
        padding: 0;
        overflow: auto;
        font-family: 'Poppins', sans-serif;
    }
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
        background-color: #fff;
    }
    .card-body {
        padding: 20px;
    }
    .profile-picture img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 10px;
    }
    .form-control[readonly] {
        background-color: #f1f1f1;
        border: none;
    }
    .custom-link {
        color: #228b22;
        font-size: 15px;
        text-decoration: none;
    }
    .custom-link:hover {
        color: #1c6f1c;
    }
    .nav-pills,
    .search-control,
    .search-icon{
        display:none;
    }
    .cropper-container {
        height: 100% !important;
        width: 100% !important;
    }
    .img-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    #imageToCrop {
        min-width: 100%;
        max-height: 100%;
    }
    .check-icon{
        color: #228b22;
    }
    .exclamation-icon{
        color: #ffcc00;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile-picture d-flex align-items-center">
                        <img src="{{ $shop->shop_img ? asset('storage/' . $shop->shop_img) : asset('images/assets/default_profile.png') }}"
                             alt="Shop Image"
                             class="rounded-circle">
                        <div class="ml-3">
                            <strong>{{ $merchant->username }}</strong>
                            <br>
                            <a href="{{ route('merchant.editProfile') }}" id="triggerEditButton" style="font-size: 0.85rem; color: #228b22;">Edit Profile</a>
                        </div>
                    </div>
                    <hr class="w-100 mt-2 mb-4">
                    <div class="text-start">
                        <p class="mt-0" id="toggleAccount" style="cursor: pointer;">
                            <i class="fas fa-user custom-icon"></i> My Account
                        </p>
                        <ul class="list-unstyled" id="accountLinks">
                            <li><a href="#" class="custom-link" data-target="#profileContent">Profile</a></li>
                            <li><a href="#" class="custom-link" data-target="#banksContent">Banks & Cards</a></li>
                            <li><a href="#" class="custom-link" data-target="#addressesContent">Addresses</a></li>
                            <li><a href="#" class="custom-link" data-target="#changePasswordContent">Change Password</a></li>
                            <li><a href="#" class="custom-link" data-target="#settingsContent">Settings</a></li>
                        </ul>
                        <p>
                            <a href="/my-purchase" class="text-decoration-none" style="color: #2e2e2e;">
                                <i class="fas fa-clipboard custom-icon"></i> Orders
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-6" id="profileContent">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Merchant Profile</h5>
                    <form id="profileForm" action="{{ route('merchant.updateProfile') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Information -->
                        <div class="form-group">
                            <label for="shop_name">Shop Name:</label>
                            <input type="text" class="form-control" id="shop_name" name="shop_name"
                                   value="{{ old('shop_name', $shop->shop_name ?? '') }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" disabled>{{ old('description', $shop->description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="{{ old('first_name', $merchantInfo->firstname ?? '') }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="{{ old('last_name', $merchantInfo->lastname ?? '') }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Phone Number:</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="contact_number" name="contact_number"
                                       value="{{ old('contact_number', $merchantInfo->contact_number) }}" disabled>
                                       <button type="button" class="btn btn-outline-secondary change-btn" onclick="openUpdateModal('contact_number')" style="display: none;">Change</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $merchant->email) }}" disabled>
                                       <button type="button" class="btn btn-outline-secondary change-btn" onclick="openUpdateModal('email')" style="display: none;">Change</button>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" id="editButton" class="btn btn-custom">Edit</button>
                            <button type="button" id="cancelButton" class="btn btn-secondary" style="display: none;">Cancel</button>
                            <button type="submit" id="saveButton" class="btn btn-custom" style="display: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Banks & Cards Content Section -->
        <div class="col-md-6" id="banksContent" style="display: none;">
            <div class="card text-center">
                <div class="card-body" style="height: auto;">
                    <h5 class="card-title text-start">Mode of Payment</h5>

                    <!-- Payment Method Selection -->
                    <div class="form-group text-start mb-4">
                        <label for="paymentMethod">Select Payment Method:</label>
                        <select class="form-control" id="paymentMethod" name="payment_method">
                            <option value="" disabled selected>Select payment method</option>
                            <option value="COD" {{ isset($codMop) && $codMop->account_type == 'COD' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="GCash" {{ isset($merchantMop) && $merchantMop->account_type == 'GCash' ? 'selected' : '' }}>GCash</option>
                        </select>
                    </div>

                    <!-- COD Section -->
                    <div id="codSection" style="display: {{ isset($codMop) && $codMop->account_type == 'COD' ? 'none' : 'none' }};">
                        <p class="mb-3">
                            We will still require your GCash number and account name for potential future transactions.
                        </p>

                        <!-- GCash Inputs Required for COD -->
                        <div class="form-group mt-3">
                            <label for="gcashAccountName" class="form-label">GCash Account Name:</label>
                            <input type="text" class="form-control" id="gcashAccountName"
                                value="{{ isset($merchantMop) ? $merchantMop->account_name : '' }}"
                                placeholder="Enter your GCash Account Name" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="gcashNumber" class="form-label">GCash Number:</label>
                            <input type="text" class="form-control" id="gcashNumber"
                                value="{{ isset($merchantMop) ? $merchantMop->account_number : '' }}"
                                placeholder="Enter your GCash Number" required>
                        </div>

                        <p class="mb-3">
                            Before enabling COD as a payment method, please read and accept the terms and conditions.
                        </p>

                        <button type="button" class="btn btn-primary mb-3" id="codTermsBtn">Read COD Terms & Conditions</button>

                        <!-- Checkbox for accepting terms -->
                        <div class="form-group">
                            <input type="checkbox" id="acceptCodTerms" {{ isset($codMop) && $codMop->cod_terms_accepted ? 'checked' : '' }}>
                            <label for="acceptCodTerms"> I accept the Terms & Conditions of COD</label>

                        </div>


                        <div class="d-flex justify-content-end mt-2">
                            <!-- Enable/Disable COD Buttons -->
                            <button type="button" id="enableCodButton" class="btn btn-success mt-2"
                                style="{{ isset($codMop) && $codMop->account_type == 'COD' ? 'display: none;' : '' }}" disabled>
                                Enable COD
                            </button>
                            <button type="button" id="disableCodButton" class="btn btn-danger mt-2"
                                style="{{ isset($codMop) && $codMop->account_type == 'COD' ? '' : 'display: none;' }}">
                                Disable COD
                            </button>
                        </div>
                    </div>
                    <!-- GCash Section -->
                    <div id="gcashSection" style="display: {{ isset($merchantMop) && $merchantMop->account_type == 'GCash' ? 'block' : 'none' }};">
                        <img src="{{ asset('images/assets/gcash_img.png') }}" alt="GCash Logo" class="img-fluid mb-3" style="max-width: 200px; margin-top:10px; border:none;">

                        <p class="mb-4">
                            GCash is a mobile wallet that enables you to pay bills, send money, and make purchases securely and conveniently using your smartphone.
                        </p>

                        <p class="text-start"><strong>Instructions to generate QR code:</strong></p>
                        <ol class="text-start">
                            <li>Open and Login to GCash.</li>
                            <li>Click the QR menu.</li>
                            <li>Click the Generate QR option.</li>
                            <li>Select "Receive Money via QR Code".</li>
                            <li>Download the QR code.</li>
                            <li>Upload the downloaded QR code below.</li>
                        </ol>

                        <!-- If GCash details exist, show them -->
                        @if(isset($merchantMop) && $merchantMop->account_type == 'GCash')
                        <div id="gcashDetails" class="text-start mb-4">
                            <h6>Existing GCash Details:</h6>
                            <p><strong>Account Name:</strong> {{ $merchantMop->account_name }}</p>
                            <p><strong>GCash Number:</strong> {{ $merchantMop->account_number }}</p>

                            @if($merchantMop->gcash_qr_code)
                                <p><strong>QR Code:</strong></p>
                                <div class="d-flex justify-content-center mb-3">
                                    <img src="{{ asset('storage/' . $merchantMop->gcash_qr_code) }}"
                                        alt="GCash QR Code"
                                        class="img-fluid"
                                        style="max-width: 200px; border:none;">
                                </div>
                            @endif

                            <!-- Edit Button -->
                            <div class="text-end">
                                <button type="button" id="editGcashButton" class="btn btn-custom">Edit</button>
                            </div>
                        </div>
                        @endif

                        <!-- GCash Form for adding/updating -->
                        <form id="gcashForm" method="POST" action="{{ route('save.gcash.details') }}" enctype="multipart/form-data" style="display: {{ isset($merchantMop) && $merchantMop->account_type == 'GCash' ? 'none' : 'block' }};">
                            @csrf

                            <!-- Account Name Field -->
                            <div class="form-group text-start mb-3">
                                <label for="gcashAccountName" class="form-label">GCash Account Name:</label>
                                <input type="text" class="form-control" id="gcashAccountName" name="gcash_account_name"
                                    value="{{ isset($merchantMop) ? $merchantMop->account_name : '' }}"
                                    placeholder="Enter your GCash Account Name" required>
                            </div>

                            <!-- GCash Number Field -->
                            <div class="form-group text-start mb-3">
                                <label for="gcashNumberInput" class="form-label">GCash Number:</label>
                                <input type="text" class="form-control" id="gcashNumberInput" name="gcash_number"
                                    value="{{ isset($merchantMop) ? $merchantMop->gcash_number : '' }}"
                                    placeholder="Enter your GCash Number" required>
                            </div>

                            <!-- GCash QR Code Upload -->
                            <div class="form-group text-start mb-3">
                                <label for="gcashQrCode" class="form-label">Upload GCash QR Code:</label>
                                <input type="file" class="form-control" id="gcashQrCode" name="gcash_qr_code" accept="image/*">
                            </div>

                            <!-- Buttons -->
                            <div class="text-end">
                                <button type="submit" id="saveGcashButton" class="btn btn-custom">Save GCash Details</button>
                                <button type="button" id="cancelGcashButton" class="btn btn-danger" style="display: none;">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!-- COD Terms & Conditions Modal -->
                    <div id="codTermsModal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close-btn"><i class="fa-solid fa-xmark"></i></span>
                            <h4>COD Terms & Conditions</h4>
                            <p>
                                By enabling Cash on Delivery (COD), you agree to the following terms:
                                <ul>
                                    <li>Merchants must ensure the availability of sufficient inventory to fulfill COD orders.</li>
                                    <li>COD orders must be delivered within the specified delivery timeframe.</li>
                                    <li>Merchants are responsible for handling all COD-related customer service inquiries.</li>
                                    <li>Frequent refusals of COD deliveries by customers may result in additional handling costs, which the merchant must bear.</li>
                                    <li>COD-enabled orders are subject to stricter verification checks to prevent fraud.</li>
                                </ul>
                            </p>
                            <button type="button" class="btn btn-primary" id="closeCodModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6" id="addressesContent" style="display: none;">
            <div class="card">
                <div class="card-body" style="height: auto;">
                    <h5 class="card-title">Address</h5>
                    <form id="addressForm" method="POST" action="{{ route('save-address') }}">
                        @csrf
                        <input type="hidden" name="merchant_id" value="{{ $merchant->merchant_id }}">

                        <div class="mb-0">
                            <label for="house_street">Street Address:</label>
                            <input type="text" class="form-control" id="house_street" name="house_street"
                                   value="{{ $shop->shop_street ?? '' }}" placeholder="House No. / Street" required disabled>
                        </div>
                        <div class="mb-0">
                            <label for="province" class="form-label">Province:</label>
                            <select class="form-select" id="province" name="province" disabled>
                                @if($shop && $shop->province)
                                    <option selected>{{ $shop->province }}</option>
                                @else
                                    <option selected disabled>-- Select Province --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedProvince" name="selectedProvince" value="{{ $shop->province ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="city" class="form-label">City/Municipality:</label>
                            <select class="form-select" id="city" name="city" disabled>
                                @if($shop && $shop->city)
                                    <option selected>{{ $shop->city }}</option>
                                @else
                                    <option selected disabled>-- Select City/Municipality --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedCity" name="selectedCity" value="{{ $shop->city ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="barangay" class="form-label">Barangay:</label>
                            <select class="form-select" id="barangay" name="barangay" disabled>
                                @if($shop && $shop->barangay)
                                    <option selected>{{ $shop->barangay }}</option>
                                @else
                                    <option selected disabled>-- Select Barangay --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedBarangay" name="selectedBarangay" value="{{ $shop->barangay ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="postalcode" class="form-label">Postal Code:</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode"
                                   value="{{ $shop->postal_code ?? '' }}" placeholder="Enter postal code" disabled>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" id="editButtonaddress" class="btn btn-custom">Edit</button>
                            <button type="submit" id="saveButtonaddress" class="btn btn-custom" style="display: none;">Save</button>
                            <button type="button" id="cancelButtonaddress" class="btn btn-danger" style="display: none;">Cancel</button>
                        </div>
                    </form>
                    <div id="message" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="changePasswordContent" style="display: none;"> <!-- Initially hidden -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Change Password</h5>
                    <p>Please enter your current password and your new password below.</p>
                    <form id="changePasswordForm" method="POST" action="{{ route('change.password') }}">
                        @csrf <!-- Include CSRF token for security -->

                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password:</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Enter your current password" required disabled>
                            @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password:</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter your new password" required disabled>
                            @error('new_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">Confirm New Password:</label>
                            <input type="password" class="form-control" id="confirmNewPassword" name="new_password_confirmation" placeholder="Confirm your new password" required disabled>
                            @error('new_password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" id="changePasswordButton" class="btn btn-custom">Change</button>
                            <button type="submit" id="submitButtonPassword" class="btn btn-custom" style="display: none;">Save Password</button>
                            <button type="button" id="cancelPasswordButton" class="btn btn-danger" style="display: none;">Cancel</button>
                        </div>
                    </form>

                    <div id="passwordMessage" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDisableModal" tabindex="-1" role="dialog" aria-labelledby="confirmDisableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="confirmDisableLabel">
                        <i class="fa-solid fa-triangle-exclamation exclamation-icon"></i> Confirm Disable COD
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to disable COD?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="modalConfirmDisableButton">Disable COD</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fa-regular fa-circle-check check-icon"></i> Success
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="successModalCloseBtn" style="background: transparent; border: none;">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="successModalMessage">
                    <!-- Success message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="successModalOkBtn" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Enter New <span id="modalFieldLabel"></span></h5>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="modalFieldValue" placeholder="Enter new value">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" onclick="saveField()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h1><i class="fa-regular fa-circle-check check-icon"></i></h1>
                <p>Update successful!</p>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    document.getElementById('editButton').addEventListener('click', function () {
        // Enable all form inputs except specific ones
        document.querySelectorAll('#profileForm input, #profileForm textarea').forEach(input => {
            if (input.id !== 'contact_number' && input.id !== 'email') {
                input.disabled = false;
            }
        });

        // Set contact_number and email as readonly to prevent editing
        document.getElementById('contact_number').readOnly = true;
        document.getElementById('email').readOnly = true;

        // Show save and cancel buttons
        document.getElementById('saveButton').style.display = 'inline-block';
        document.getElementById('cancelButton').style.display = 'inline-block';

        // Hide edit button
        document.getElementById('editButton').style.display = 'none';

        // Show all "Change" buttons
        document.querySelectorAll('.change-btn').forEach(button => {
            button.style.display = 'inline-block';
        });
    });


    document.getElementById('cancelButton').addEventListener('click', function() {
        // Disable all form inputs
        document.querySelectorAll('#profileForm input, #profileForm textarea').forEach(input => {
            input.disabled = true;
        });

        // Reset form values to original
        document.getElementById('profileForm').reset();

        // Show edit button
        document.getElementById('editButton').style.display = 'inline-block';
        // Hide save and cancel buttons
        document.getElementById('saveButton').style.display = 'none';
        document.getElementById('cancelButton').style.display = 'none';

        // Hide all "Change" buttons
        document.querySelectorAll('.change-btn').forEach(button => {
            button.style.display = 'none';
        });
    });

    function toggleField(fieldId) {
        const field = document.getElementById(fieldId);
        field.disabled = !field.disabled;
    }
    // Display confirmation modal if session success message exists
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();

            // Automatically close the confirmation modal after 4 seconds
            setTimeout(() => {
                confirmationModal.hide();
            }, 2000);
        @endif
    });

</script>
<script>
    let currentField = '';

    // Function to open the update modal and set the correct field label and value
    function openUpdateModal(field) {
        currentField = field;
        const fieldLabel = field === 'contact_number' ? 'Contact Number' : 'Email';

        document.getElementById('modalFieldLabel').innerText = fieldLabel;
        document.getElementById('modalFieldValue').value = document.getElementById(field).value;

        const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        updateModal.show();
    }

    // Function to save the field and send an AJAX request based on the current field being updated
    function saveField() {
        const newValue = document.getElementById('modalFieldValue').value;

        if (currentField === 'contact_number') {
            updateContactNumber(newValue);
        } else if (currentField === 'email') {
            updateEmail(newValue);
        }
    }

    // AJAX function to update contact number
    function updateContactNumber(newContactNumber) {
        fetch("{{ route('merchant.updateContactNumber') }}", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                contact_number: newContactNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Update the displayed value in the main form
                document.getElementById('contact_number').value = newContactNumber;

                // Close update modal
                const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
                updateModal.hide();

                // Show confirmation modal and auto-close after 4 seconds
                showConfirmationModal();
            } else {
                alert(data.message || "Failed to update contact number.");
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // AJAX function to update email
    function updateEmail(newEmail) {
        fetch("{{ route('merchant.updateEmail') }}", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                email: newEmail
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Update the displayed value in the main form
                document.getElementById('email').value = newEmail;

                // Close update modal
                const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
                updateModal.hide();

                // Show confirmation modal and auto-close after 4 seconds
                showConfirmationModal();
            } else {
                alert(data.message || "Failed to update email.");
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // Function to show the confirmation modal and auto-close it after 4 seconds
    function showConfirmationModal() {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        // Set timeout to hide the modal after 4 seconds
        setTimeout(() => {
            confirmationModal.hide();
        }, 2000);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');
        const saveButton = document.getElementById('saveButton');
        const formFields = document.querySelectorAll('#profileForm input, #profileForm select');
        const triggerEditButton = document.getElementById('triggerEditButton');
        // Object to store original form data
        let originalFormData = {};

        // Function to capture original form data
        function captureOriginalFormData() {
            formFields.forEach(field => {
                originalFormData[field.id] = field.value;
            });
        }

        // Function to restore original form data
        function restoreOriginalFormData() {
            formFields.forEach(field => {
                if (originalFormData.hasOwnProperty(field.id)) {
                    field.value = originalFormData[field.id];
                }
            });
        }

        // Capture original data when the page loads
        captureOriginalFormData();

        editButton.addEventListener('click', function() {
            formFields.forEach(field => field.disabled = false);
            editButton.style.display = 'none';
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
        });

        cancelButton.addEventListener('click', function() {
            restoreOriginalFormData(); // Restore the original data
            formFields.forEach(field => field.disabled = true);
            editButton.style.display = 'inline-block';
            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
        });

        // Trigger editButton when the Edit Profile link is clicked
        triggerEditButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior
            editButton.click(); // Trigger the edit button's click event
        });

        // Image upload functionality
        document.getElementById('image-upload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-picture').src = e.target.result;
                    document.getElementById('profile-picture1').src = e.target.result;
                };
                reader.readAsDataURL(file);

                document.getElementById('change-label').style.display = 'none';
                document.getElementById('save-button').style.display = 'block';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all account links
        const links = document.querySelectorAll('.custom-link');

        // Sections for each account link
        const profileContent = document.getElementById('profileContent');
        const profileImageContent = document.getElementById('profileImageContent');
        const banksContent = document.getElementById('banksContent');
        const addressesContent = document.getElementById('addressesContent');
        const changePasswordContent = document.getElementById('changePasswordContent');
        const settingsContent = document.getElementById('settingsContent');

        // Hide all content sections initially
        function hideAllSections() {
            // List of all sections
            const sections = [profileContent, profileImageContent, banksContent, addressesContent, changePasswordContent, settingsContent];

            // Hide each section
            sections.forEach(section => {
                if (section) section.style.display = 'none';
            });
        }

        // Attach event listeners to account links
        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                hideAllSections(); // Hide all sections

                // Get the target section from the data-target attribute
                const targetId = this.getAttribute('data-target');
                const targetContent = document.querySelector(targetId);

                // Show the selected content section
                if (targetContent) {
                    targetContent.style.display = 'block'; // Show the target section

                    // Show profile image content only if the target is profileContent
                    if (targetId === '#profileContent' && profileImageContent) {
                        profileImageContent.style.display = 'block';
                    }
                }
            });
        });

        // Show the profile section and image section by default on initial load
        if (profileContent) profileContent.style.display = 'block';
        if (profileImageContent) profileImageContent.style.display = 'block';
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editButtonaddress = document.getElementById('editButtonaddress');
    const cancelButtonaddress = document.getElementById('cancelButtonaddress');
    const saveButtonaddress = document.getElementById('saveButtonaddress');
    const formFields = document.querySelectorAll('#addressForm input, #addressForm select');

    // Check if address data exists
    const hasAddressData = @json($shop ? true : false);

    // Variable to store original form data
    let originalFormData = {};

    if (hasAddressData) {
        editButtonaddress.style.display = 'inline-block';
        saveButtonaddress.style.display = 'none';
        cancelButtonaddress.style.display = 'none';
    } else {
        formFields.forEach(field => field.disabled = false);
        editButtonaddress.style.display = 'none';
        saveButtonaddress.style.display = 'inline-block';
        cancelButtonaddress.style.display = 'inline-block';
    }

    // Function to capture original form data
    function captureOriginalFormData() {
        formFields.forEach(field => {
            originalFormData[field.id] = field.value;
        });
    }

    // Function to restore original form data
    function restoreOriginalFormData() {
        formFields.forEach(field => {
            if (originalFormData.hasOwnProperty(field.id)) {
                field.value = originalFormData[field.id];
            }
        });
    }

    editButtonaddress.addEventListener('click', function() {
        captureOriginalFormData(); // Capture data before enabling editing
        formFields.forEach(field => field.disabled = false);
        editButtonaddress.style.display = 'none';
        saveButtonaddress.style.display = 'inline-block';
        cancelButtonaddress.style.display = 'inline-block';
    });

    cancelButtonaddress.addEventListener('click', function() {
        restoreOriginalFormData(); // Restore the original data
        formFields.forEach(field => field.disabled = true);
        editButtonaddress.style.display = 'inline-block';
        saveButtonaddress.style.display = 'none';
        cancelButtonaddress.style.display = 'none';
    });

    document.getElementById('province').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].textContent;
        document.getElementById('selectedProvince').value = selectedText;
        updateCities();
    });

    document.getElementById('city').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].textContent;
        document.getElementById('selectedCity').value = selectedText;
        updateBarangays();
    });

    document.getElementById('barangay').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].textContent;
        document.getElementById('selectedBarangay').value = selectedText;
    });

    function updateProvinces() {
        const regionCode = 5;
        const selectedProvince = document.getElementById('selectedProvince').value;


        fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces.json`)
            .then(response => response.json())
            .then(data => {
                const provinceSelect = document.getElementById('province');
                provinceSelect.innerHTML = '<option selected disabled>-- Select Province --</option>';

                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });

                // Pre-select province if data exists
                if (selectedProvince) {
                    const prefilledOption = Array.from(provinceSelect.options).find(option => option.textContent === selectedProvince);
                    if (prefilledOption) {
                        provinceSelect.value = prefilledOption.value;
                        updateCities();
                    }
                }
            })
            .catch(error => console.error('Error fetching provinces:', error));
    }

    function updateCities() {
        const provinceCode = document.getElementById('province').value;
        const selectedCity = document.getElementById('selectedCity').value;

        const fetchCities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities.json`);
        const fetchMunicipalities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/municipalities.json`);

        Promise.all([fetchCities, fetchMunicipalities])
            .then(responses => Promise.all(responses.map(response => response.json())))
            .then(dataArrays => {
                const [cities, municipalities] = dataArrays;
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option selected disabled>-- Select City/Municipality --</option>';

                // Combine cities and municipalities into one array
                const combinedData = [
                    ...cities.map(city => ({ ...city, isCity: true })),
                    ...municipalities.map(municipality => ({ ...municipality, isCity: false }))
                ];

                // Create options for each city/municipality
                combinedData.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.code;
                    option.textContent = location.name;
                    option.dataset.isCity = location.isCity; // Set the isCity attribute
                    citySelect.appendChild(option);
                });

                // Pre-select city/municipality if data exists
                if (selectedCity) {
                    const prefilledOption = Array.from(citySelect.options).find(option => option.textContent === selectedCity);
                    if (prefilledOption) {
                        citySelect.value = prefilledOption.value;
                        updateBarangays(); // Trigger barangay update
                    }
                }
            })
            .catch(error => console.error('Error fetching cities/municipalities:', error));
    }

    function updateBarangays() {
        const citySelect = document.getElementById('city');
        const cityCode = citySelect.value;
        const selectedBarangay = document.getElementById('selectedBarangay').value;
        const isCity = citySelect.selectedOptions[0].dataset.isCity === 'true'; // Correctly identify if it's a city

        const endpoint = isCity
            ? `https://psgc.gitlab.io/api/cities/${cityCode}/barangays.json`
            : `https://psgc.gitlab.io/api/municipalities/${cityCode}/barangays.json`;

        fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const barangaySelect = document.getElementById('barangay');
                barangaySelect.innerHTML = '<option selected disabled>-- Select Barangay --</option>';

                if (data.length === 0) {
                    const option = document.createElement('option');
                    option.textContent = 'No barangays available';
                    option.disabled = true;
                    barangaySelect.appendChild(option);
                } else {
                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.code;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                }

                // Pre-select barangay if data exists
                if (selectedBarangay) {
                    const prefilledOption = Array.from(barangaySelect.options).find(option => option.textContent === selectedBarangay);
                    if (prefilledOption) {
                        barangaySelect.value = prefilledOption.value;
                    }
                }

                barangaySelect.addEventListener('change', function() {
                    const selectedText = this.options[this.selectedIndex].textContent;
                    document.getElementById('selectedBarangay').value = selectedText;
                });
            })
            .catch(error => {
                console.error('Error fetching barangays:', error);
            });
    }

    });
    document.getElementById('toggleAccount1').addEventListener('click', function() {
        var accountDetails = document.getElementById('accountDetails');
        if (accountDetails.style.maxHeight === '0px' || accountDetails.style.maxHeight === '') {
            accountDetails.style.maxHeight = accountDetails.scrollHeight + 'px';
        } else {
            accountDetails.style.maxHeight = '0';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('editGcashButton');
        const saveButton = document.getElementById('saveGcashButton');
        const cancelButton = document.getElementById('cancelGcashButton');
        const gcashInput = document.getElementById('gcashNumberInput');
        const originalGcashNumber = gcashInput.value; // Store original value

        // Initially disable the input if it has a value
        if (gcashInput.value) {
            gcashInput.setAttribute('readonly', true);
        }

        // Event listener for Edit button
        editButton.addEventListener('click', function() {
            gcashInput.removeAttribute('readonly'); // Enable editing
            saveButton.style.display = 'inline-block'; // Show Save button
            cancelButton.style.display = 'inline-block'; // Show Cancel button
            editButton.style.display = 'none'; // Hide Edit button
            gcashInput.focus(); // Focus on the input field
        });

        // Event listener for Cancel button
        cancelButton.addEventListener('click', function() {
            gcashInput.setAttribute('readonly', true); // Make input readonly again
            gcashInput.value = originalGcashNumber; // Reset to original value
            saveButton.style.display = 'none'; // Hide Save button
            cancelButton.style.display = 'none'; // Hide Cancel button
            editButton.style.display = 'inline-block'; // Show Edit button
        });

        // Event listener for form submission
        saveButton.addEventListener('click', function() {
            // Ensure input is not empty
            if (gcashInput.value.trim() === '') {
                alert('GCash Number cannot be empty.');
                gcashInput.focus();
                return false; // Prevent form submission if empty
            }

            gcashInput.setAttribute('readonly', true); // Set input to readonly
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const changePasswordButton = document.getElementById('changePasswordButton');
        const submitButtonPassword = document.getElementById('submitButtonPassword');
        const cancelPasswordButton = document.getElementById('cancelPasswordButton');
        const passwordInputs = [
            document.getElementById('currentPassword'),
            document.getElementById('newPassword'),
            document.getElementById('confirmNewPassword')
        ];

        // Event listener for Change Password button
        changePasswordButton.addEventListener('click', function() {
            // Enable all password input fields
            passwordInputs.forEach(input => {
                input.removeAttribute('disabled'); // Enable the input field
            });

            // Show the submit button and the cancel button
            submitButtonPassword.style.display = 'inline-block'; // Show the Save Password button
            cancelPasswordButton.style.display = 'inline-block'; // Show the Cancel button
            changePasswordButton.style.display = 'none'; // Hide the Change Password button
        });

        // Event listener for Cancel button
        cancelPasswordButton.addEventListener('click', function() {
            // Disable all password input fields
            passwordInputs.forEach(input => {
                input.setAttribute('disabled', 'disabled'); // Disable the input field
                input.value = ''; // Clear the input field
            });

            // Hide the submit button and the cancel button
            submitButtonPassword.style.display = 'none'; // Hide the Save Password button
            cancelPasswordButton.style.display = 'none'; // Hide the Cancel button
            changePasswordButton.style.display = 'inline-block'; // Show the Change Password button
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Common variables
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const gcashSection = document.getElementById('gcashSection');
        const gcashForm = document.getElementById('gcashForm');
        const editGcashButton = document.getElementById('editGcashButton');
        const cancelGcashButton = document.getElementById('cancelGcashButton');
        const codSection = document.getElementById('codSection');
        const codTermsBtn = document.getElementById('codTermsBtn');
        const codTermsModal = document.getElementById('codTermsModal');
        const closeCodModal = document.getElementById('closeCodModal');
        const acceptCodTerms = document.getElementById('acceptCodTerms');
        const enableCodButton = document.getElementById('enableCodButton');
        const disableCodButton = document.getElementById('disableCodButton');
        const gcashAccountName = document.getElementById('gcashAccountName');
        const gcashNumber = document.getElementById('gcashNumber');
        const modalConfirmDisableButton = document.getElementById('modalConfirmDisableButton');
        const confirmDisableModal = new bootstrap.Modal(document.getElementById('confirmDisableModal'));
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        const successModalMessage = document.getElementById('successModalMessage');
        const successModalCloseBtn = document.getElementById('successModalCloseBtn');
        const successModalOkBtn = document.getElementById('successModalOkBtn');

        // Initial button visibility and form fields based on the checkbox state
        setInitialState();

        function setInitialState() {
            if (acceptCodTerms.checked) {
                enableCodButton.style.display = 'none';  // Hide "Enable COD"
                disableCodButton.style.display = 'inline-block';  // Show "Disable COD"
                gcashAccountName.disabled = true; // Disable form fields
                gcashNumber.disabled = true;
                acceptCodTerms.disabled = false;
            } else {
                enableCodButton.style.display = 'inline-block';  // Show "Enable COD"
                disableCodButton.style.display = 'none';  // Hide "Disable COD"
                acceptCodTerms.disabled = false;
            }
        }

        // Show/Hide GCash or COD sections based on selected payment method
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                gcashSection.style.display = (selectedValue === 'GCash') ? 'block' : 'none';
                codSection.style.display = (selectedValue === 'COD') ? 'block' : 'none';
            });
        }

        // Enable the "Enable COD" button only when terms are accepted and fields are filled
        acceptCodTerms.addEventListener('change', function() {
            checkCodEnableConditions();
        });

        if (gcashAccountName && gcashNumber) {
            gcashAccountName.addEventListener('input', checkCodEnableConditions);
            gcashNumber.addEventListener('input', checkCodEnableConditions);
        }

        function checkCodEnableConditions() {
            const isTermsAccepted = acceptCodTerms.checked;
            const isGcashNameFilled = gcashAccountName.value.trim() !== '';
            const isGcashNumberFilled = gcashNumber.value.trim() !== '';

            // Enable the COD button only if all conditions are met
            enableCodButton.disabled = !(isTermsAccepted && isGcashNameFilled && isGcashNumberFilled);
        }

        // Logic to enable COD when the "Enable COD" button is clicked
        if (enableCodButton) {
            enableCodButton.addEventListener('click', function() {
                if (acceptCodTerms.checked && gcashAccountName.value && gcashNumber.value) {
                    // AJAX request to enable COD with GCash details
                    fetch('{{ route('enable.cod') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: 'enable',
                            gcash_account_name: gcashAccountName.value,
                            gcash_number: gcashNumber.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessModal(data.success);  // Show success modal
                            enableCodButton.style.display = 'none';  // Hide "Enable COD"
                            disableCodButton.style.display = 'inline-block';  // Show "Disable COD"
                            gcashAccountName.disabled = false; // Enable form fields
                            gcashNumber.disabled = false;
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                } else {
                    alert('Please fill in all required fields and accept the terms.');
                }
            });
        }

        // Show confirmation modal when clicking "Disable COD"
        if (disableCodButton) {
            disableCodButton.addEventListener('click', function() {
                $('#confirmDisableModal').modal('show'); // Show modal
            });
        }

        // Logic to delete COD when the "Confirm Disable COD" button is clicked
        if (modalConfirmDisableButton) {
            modalConfirmDisableButton.addEventListener('click', function() {
                // AJAX request to delete COD
                fetch('{{ route('enable.cod') }}', {
                    method: 'POST',  // Use POST with _method: 'DELETE'
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE',
                        action: 'disable'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#confirmDisableModal').modal('hide');  // Hide modal
                        showSuccessModal(data.success);  // Show success modal
                        enableCodButton.style.display = 'inline-block';  // Show "Enable COD"
                        disableCodButton.style.display = 'none';  // Hide "Disable COD"
                        acceptCodTerms.checked = false; // Uncheck terms acceptance
                        gcashAccountName.value = ''; // Clear form fields
                        gcashNumber.value = '';
                        gcashAccountName.disabled = true; // Disable form fields
                        gcashNumber.disabled = true;
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }

        // Handle success modal "OK" and close button actions
        if (successModalCloseBtn) {
            successModalCloseBtn.addEventListener('click', function() {
                successModal.hide();
            });
        }

        if (successModalOkBtn) {
            successModalOkBtn.addEventListener('click', function() {
                successModal.hide();
            });
        }

        // Function to show the success modal
        function showSuccessModal(message) {
            successModalMessage.textContent = message;
            successModal.show();
        }

    });
</script>




@endsection

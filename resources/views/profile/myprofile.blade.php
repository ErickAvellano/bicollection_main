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
        padding: 30px;
        height: 80vh;
    }

    .card-title {
        font-weight: bold;
        color: #228b22;
    }

    .nav-link {
        color: #228b22;
        font-size: 16px;
    }

    .nav-link.active {
        font-weight: bold;
    }

    .nav-link:hover {
        color: #ffd700;
    }

    .card-body img {
        border: 2px solid #228b22;
        max-width: 150px;
    }

    .btn-out-dark {
        border-color: #228b22;
        color: #228b22;
    }

    .btn-out-dark:hover {
        background-color: #ffd700;
        border-color: #ffd700;
        color: white;
    }

    .form-control {
        border-radius: 10px;
    }

    .btn-success {
        background-color: #228b22;
        border: none;
        border-radius: 20px;
    }

    .btn-success:hover {
        background-color: #ffd700;
        color: #fff;
    }

    .profile-img img {
        max-width: 150px;
        border: 2px solid #228b22;
    }

    .profile-img .btn-secondary-outline {
        border-color: #228b22;
        color: #228b22;
    }

    .profile-img .btn-secondary-outline:hover {
        background-color: #ffd700;
        border-color: #ffd700;
        color: white;
    }

    /* Ensure that the image is circular */
    .profile-img img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
    }
    .nav.nav-pills {
        display: none;
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
        color: #228b22;
    }
    .custom-link {
    color: #228b22; /* Change to your desired color */
    font-size: 15px; /* Change to your desired font size */
    transition: color 0.3s ease; /* Optional: smooth color transition */
    text-decoration: none;
    margin-left: 20px;
    }

    .custom-link:hover {
        color: #1c6f1c; /* Color on hover */
    }
    .fas.fa-user,
    .fa-clipboard {
    color: #2e2e2e; /* Change color */
    font-size: 20px; /* Change size */
    transition: color 0.3s ease;
    margin-right: 10px;
    }
</style>
@endsection

@section('content')
<main class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile-picture d-flex align-items-center">
                        <img src="{{ $customer->customerImage ? asset('storage/' . $customer->customerImage->img_path) : asset('images/assets/default_profile.png') }}"
                             alt="Profile Picture"
                             id="profile-picture1"
                             class="rounded-circle"
                             style="height: 50px; width: 50px; margin-right:10px;">
                        <div class="ml-3">
                            <strong>{{ $customer->username }}</strong>
                            <br>
                            <a href="{{ route('profile.edit') }}" id="triggerEditButton" style="font-size: 0.85rem; text-decoration: none; color: #228b22;">Edit Profile</a>
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
                                <i class="fas fa-solid fa-clipboard custom-icon"></i> My Purchase
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="profileContent">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Profile</h5>
                    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Username Field -->
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="{{ old('username', $customer->username) }}" placeholder="Enter your Username" disabled>
                        </div>

                        <!-- First Name Field -->
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="{{ old('first_name', $customer->first_name) }}" placeholder="First Name" disabled>
                        </div>

                        <!-- Last Name Field -->
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="{{ old('last_name', $customer->last_name) }}" placeholder="Last Name" disabled>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $maskedEmail) }}" placeholder="Enter your Email" disabled>
                                <button type="button" id="emailbtn" class="btn btn-outline-secondary change-btn" onclick="showUnmaskedEmail()" style="display: none;">Change</button>
                            </div>
                        </div>

                        <!-- Phone Number Field -->
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="phone" name="contact_number"
                                       value="{{ old('contact_number', $maskedPhone) }}" placeholder="Enter your Phone Number" disabled>
                                <button type="button" id="phonebtn" class="btn btn-outline-secondary change-btn" onclick="showUnmaskedPhone()" style="display: none;">Change</button>
                            </div>
                        </div>

                        <!-- Gender Field -->
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select class="form-control" id="gender" name="gender" disabled>
                                <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $customer->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="text-end mt-4">
                            <button type="button" id="editButton" class="btn btn-custom">Edit</button>
                            <button type="button" id="cancelButton" class="btn btn-danger" style="display: none;">Cancel</button>
                            <button type="submit" id="saveButton" class="btn btn-custom" style="display: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3" id="profileImageContent" style="display: none;">
            <div class="card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <form id="profile-picture-form" action="{{ route('profile.uploadPicture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-img text-center mt-3">
                            <img src="{{ $customer->customerImage ? asset('storage/' . $customer->customerImage->img_path) : asset('images/assets/default_profile.png') }}"
                                 alt="User Avatar"
                                 id="profile-picture"
                                 class="rounded-circle mb-3"
                                 width="150" height="150">

                            <label for="image-upload" id="change-label" class="btn btn-outline-custom mt-2">Change</label>
                            <input type="file" id="image-upload" name="profile_picture" class="file-input" accept="image/*" style="display: none;">
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" id="save-button" class="btn btn-custom mt-3" style="display: none;">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Banks & Cards Content Section -->
        <div class="col-md-6" id="banksContent" style="display: none;"> <!-- Make it visible for testing -->
            <div class="card text-center">
                <div class="card-body" style="height: auto;">
                    <h5 class="card-title text-start">Banks & Cards</h5>
                    <img src="{{ asset('images/assets/gcash_img.png') }}" alt="GCash Logo" class="img-fluid mb-3" style="max-width: 200px; margin-top:10px; border:none;">
                    <p class="mb-4">
                        GCash is a mobile wallet that enables you to pay bills, send money, and make purchases securely and conveniently using your smartphone.
                    </p>
                    <div>
                    <!-- GCash Form -->
                    <form id="gcashForm" method="POST" action="{{ route('save.gcash.number') }}">
                        @csrf
                        <div class="d-flex flex-column align-items-center mb-3">
                            <label for="gcashNumberInput" class="form-label">Enter your GCash Number:</label>
                            <input type="text" class="form-control text-center" id="gcashNumberInput" name="gcashNumber"
                                   placeholder="Enter your GCash Number"
                                   value="{{ $gcashNumber }}"
                                   readonly required>
                        </div>


                        <!-- Buttons -->
                        <div class="text-end">
                            <button type="button" id="editGcashButton" class="btn btn-custom">Edit</button>
                            <button type="submit" id="saveGcashButton" class="btn btn-custom" style="display: none;">Save GCash Number</button>
                            <button type="button" id="cancelGcashButton" class="btn btn-danger" style="display: none;">Cancel</button>
                        </div>
                    </form>
                    <div id="gcashMessage" class="mt-2 text-danger"></div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" id="banksImageContent" style="display: none;"> <!-- Initially hidden -->
            <div class="card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <form id="banks-picture-form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-img text-center mt-3">
                            <img src="{{ $customer->banksImage ? asset('storage/' . $customer->banksImage->img_path) : asset('images/default_profile.png') }}"
                                 alt="Banks Image"
                                 id="banks-picture"
                                 class="rounded-circle mb-3"
                                 width="150" height="150">
                            <label for="banks-image-upload" class="btn btn-custom mt-2">Change</label>
                            <input type="file" id="banks-image-upload" name="banks_picture" class="file-input" accept="image/*" style="display: none;">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" id="save-banks-button" class="btn btn-custom mt-3" style="display: none;">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="addressesContent" style="display: none;">
            <div class="card">
                <div class="card-body" style="height: auto;">
                    <h5 class="card-title">Address</h5>
                    <form id="addressForm" method="POST" action="{{ route('save-address') }}">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                        <div class="mb-0">
                            <label for="house_street">Street Address:</label>
                            <input type="text" class="form-control" id="house_street" name="house_street"
                                   value="{{ $address->house_street ?? '' }}" placeholder="House No. / Street" required disabled>
                        </div>

                        <div class="mb-0">
                            <label for="region" class="form-label">Region:</label>
                            <select class="form-select" id="region" name="region" disabled>
                                @if($address && $address->region)
                                    <option selected>{{ $address->region }}</option>
                                @else
                                    <option selected disabled>-- Select Region --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedRegion" name="selectedRegion" value="{{ $address->region ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="province" class="form-label">Province:</label>
                            <select class="form-select" id="province" name="province" disabled>
                                @if($address && $address->province)
                                    <option selected>{{ $address->province }}</option>
                                @else
                                    <option selected disabled>-- Select Province --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedProvince" name="selectedProvince" value="{{ $address->province ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="city" class="form-label">City/Municipality:</label>
                            <select class="form-select" id="city" name="city" disabled>
                                @if($address && $address->city)
                                    <option selected>{{ $address->city }}</option>
                                @else
                                    <option selected disabled>-- Select City/Municipality --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedCity" name="selectedCity" value="{{ $address->city ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="barangay" class="form-label">Barangay:</label>
                            <select class="form-select" id="barangay" name="barangay" disabled>
                                @if($address && $address->barangay)
                                    <option selected>{{ $address->barangay }}</option>
                                @else
                                    <option selected disabled>-- Select Barangay --</option>
                                @endif
                            </select>
                            <input type="hidden" id="selectedBarangay" name="selectedBarangay" value="{{ $address->barangay ?? '' }}">
                        </div>

                        <div class="mb-0">
                            <label for="postalcode" class="form-label">Postal Code:</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode"
                                   value="{{ $address->postalcode ?? '' }}" placeholder="Enter postal code" disabled>
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



        <div class="col-md-6" id="settingsContent" style="display: none;"> <!-- Initially hidden -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Settings</h5>
                    <p>This is where the Settings content will go.</p>
                </div>
            </div>
        </div>
    </div>
<!-- Modal for updating email -->
<div class="modal fade" id="updateEmailModal" tabindex="-1" aria-labelledby="updateEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateEmailModalLabel">Update Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="email" class="form-control" id="emailModalField" placeholder="Enter new email">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" onclick="saveEmail()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for updating phone number -->
<div class="modal fade" id="updatePhoneModal" tabindex="-1" aria-labelledby="updatePhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePhoneModalLabel">Update Phone Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="tel" class="form-control" id="phoneModalField" placeholder="Enter new phone number">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-custom" onclick="savePhone()">Save</button>
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
</main>
@endsection

@section('scripts')
@if (session('status'))
    <script>
        // Show the confirmation modal when the status is success
        document.addEventListener('DOMContentLoaded', function () {
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show(); // Trigger the modal to show
        });
    </script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.custom-link'); // Select all account links
        const sections = {
            profileContent: document.getElementById('profileContent'),
            profileImageContent: document.getElementById('profileImageContent'),
            banksContent: document.getElementById('banksContent'),
            addressesContent: document.getElementById('addressesContent'),
            changePasswordContent: document.getElementById('changePasswordContent'),
            settingsContent: document.getElementById('settingsContent')
        };

        const profileLink = document.querySelector('a[data-target="#profileContent"]');

        // Function to hide all sections except for profileContent
        function hideAllSections() {
            Object.values(sections).forEach(section => {
                section.style.display = 'none';
            });
        }

        // Function to toggle profile image visibility
        function toggleProfileImageSection() {
            if (sections.profileImageContent.style.display === 'none' || sections.profileImageContent.style.display === '') {
                sections.profileImageContent.style.display = 'block'; // Show the profile image section
            } else {
                sections.profileImageContent.style.display = 'none'; // Hide the profile image section
            }
        }

        // Attach event listeners to account links
        links.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default anchor behavior

                hideAllSections(); // Hide all other sections

                const targetId = this.getAttribute('data-target');
                const targetContent = document.querySelector(targetId);

                if (targetContent) {
                    targetContent.style.display = 'block'; // Show the target section
                }

                // If Profile is clicked, toggle visibility of the profile image section
                if (targetId === '#profileContent') {
                    toggleProfileImageSection();
                }
            });
        });

        // By default, show the profile section and profile image section on initial load
        sections.profileContent.style.display = 'block'; // Show profile content
        sections.profileImageContent.style.display = 'block'; // Show profile image content
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');
        const saveButton = document.getElementById('saveButton');
        const emailButton = document.getElementById('emailbtn');
        const phoneButton = document.getElementById('phonebtn');
        const emailField = document.getElementById('email');
        const phoneField = document.getElementById('phone');
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

        // Define initial values for email and phone from the server
        let originalEmail = "{{ $customer->email }}"; 
        let maskedEmail = "{{ $maskedEmail }}"; 
        let originalPhone = "{{ $customer->contact_number }}"; 
        let maskedPhone = "{{ $maskedPhone }}";

        // Show unmasked email in the email update modal
        window.showUnmaskedEmail = function() {
            const emailModal = new bootstrap.Modal(document.getElementById('updateEmailModal'));
            document.getElementById('emailModalField').value = originalEmail;  // Set current email value in modal field
            emailModal.show();
        };

        // Show unmasked phone number in the phone number update modal
        window.showUnmaskedPhone = function() {
            const phoneModal = new bootstrap.Modal(document.getElementById('updatePhoneModal'));
            document.getElementById('phoneModalField').value = originalPhone;  // Set current phone number value in modal field
            phoneModal.show();
        };

        // Save the updated email via AJAX
        window.saveEmail = function() {
            const newEmail = document.getElementById('emailModalField').value;

            // Send AJAX request to update the email
            fetch("{{ route('profile.updateField') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field: 'email',
                    value: newEmail
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    emailField.value = newEmail; // Update the email field in the form
                    const emailModal = bootstrap.Modal.getInstance(document.getElementById('updateEmailModal'));
                    emailModal.hide();  // Close the modal
                       // Show the success confirmation modal
                        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                        confirmationModal.show();  // Show the success modal
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error updating the email');
                console.error(error);
            });
        };

        // Save the updated phone number via AJAX
        window.savePhone = function() {
            const newPhone = document.getElementById('phoneModalField').value;

            // Send AJAX request to update the phone number
            fetch("{{ route('profile.updateField') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field: 'contact_number',
                    value: newPhone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    phoneField.value = newPhone; // Update the phone field in the form
                    const phoneModal = bootstrap.Modal.getInstance(document.getElementById('updatePhoneModal'));
                    phoneModal.hide();  // Close the modal
                       // Show the success confirmation modal
                    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                    confirmationModal.show();  // Show the success modal
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error updating the phone number');
                console.error(error);
            });
        };

        // Enable fields for editing
        editButton.addEventListener('click', function() {
            emailField.value = maskedEmail; 
            phoneField.value = maskedPhone;
            // Enable form fields for editing
            const formFields = document.querySelectorAll('#profileForm input, #profileForm select');
            formFields.forEach(field => field.disabled = false);
            emailButton.style.display = 'block';
            phoneButton.style.display = 'block';
            editButton.style.display = 'none';
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
        });

        // Cancel editing and restore original data
        cancelButton.addEventListener('click', function() {
            emailField.value = maskedEmail;
            phoneField.value = maskedPhone;
            const formFields = document.querySelectorAll('#profileForm input, #profileForm select');
            formFields.forEach(field => field.disabled = true);
            emailButton.style.display = 'none';
            phoneButton.style.display = 'none';
            editButton.style.display = 'inline-block';
            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const changeLabel = document.getElementById('change-label'); // Label for file input
        const imageUpload = document.getElementById('image-upload'); // File input
        const saveButton = document.getElementById('save-button'); // Upload button
        const profilePicture = document.getElementById('profile-picture'); // Profile picture image
        const profileImageContent = document.getElementById('profileImageContent'); // Profile image content section
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal')); // Confirmation modal

        // Show the file input and upload button when user clicks on 'Change' label
        // Once a file is selected, preview the image
        imageUpload.addEventListener('change', function () {
            const file = imageUpload.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Preview the selected image in the profile picture
                    profilePicture.src = e.target.result;
                    saveButton.style.display = 'block'; // Show save button
                };
                reader.readAsDataURL(file);
            }
        });

        // Optionally, toggle the profile image section visibility (if needed)
        // Example: Toggle visibility on button click
        document.getElementById('editProfileImageButton').addEventListener('click', function () {
            profileImageContent.style.display = profileImageContent.style.display === 'none' ? 'block' : 'none';
        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editButtonaddress = document.getElementById('editButtonaddress');
    const cancelButtonaddress = document.getElementById('cancelButtonaddress');
    const saveButtonaddress = document.getElementById('saveButtonaddress');
    const formFields = document.querySelectorAll('#addressForm input, #addressForm select');


    const hasAddressData = @json($address ? true : false);

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


    function captureOriginalFormData() {
        formFields.forEach(field => {
            originalFormData[field.id] = field.value;
        });
    }


    function restoreOriginalFormData() {
        formFields.forEach(field => {
            if (originalFormData.hasOwnProperty(field.id)) {
                field.value = originalFormData[field.id];
            }
        });
    }

    editButtonaddress.addEventListener('click', function() {
        captureOriginalFormData();
        formFields.forEach(field => field.disabled = false);
        editButtonaddress.style.display = 'none';
        saveButtonaddress.style.display = 'inline-block';
        cancelButtonaddress.style.display = 'inline-block';
    });

    cancelButtonaddress.addEventListener('click', function() {
        restoreOriginalFormData(); 
        formFields.forEach(field => field.disabled = true);
        editButtonaddress.style.display = 'inline-block';
        saveButtonaddress.style.display = 'none';
        cancelButtonaddress.style.display = 'none';
    });


    populateRegions();


    document.getElementById('region').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].textContent;
        document.getElementById('selectedRegion').value = selectedText;
        updateProvinces();
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

    function populateRegions() {
        fetch('https://psgc.gitlab.io/api/regions.json')
            .then(response => response.json())
            .then(data => {
                const regionSelect = document.getElementById('region');
                regionSelect.innerHTML = '<option selected disabled>-- Select Region --</option>';

                data.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.code;
                    option.textContent = region.name;
                    regionSelect.appendChild(option);
                });

                // Pre-select region if data exists
                const selectedRegion = document.getElementById('selectedRegion').value;
                if (selectedRegion) {
                    const prefilledOption = Array.from(regionSelect.options).find(option => option.textContent === selectedRegion);
                    if (prefilledOption) {
                        regionSelect.value = prefilledOption.value;
                        updateProvinces();
                    }
                }
            })
            .catch(error => console.error('Error fetching regions:', error));
    }

    function updateProvinces() {
        const regionCode = document.getElementById('region').value;
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


                const combinedData = [
                    ...cities.map(city => ({ ...city, isCity: true })),
                    ...municipalities.map(municipality => ({ ...municipality, isCity: false }))
                ];

                combinedData.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.code;
                    option.textContent = location.name;
                    option.dataset.isCity = location.isCity; 
                    citySelect.appendChild(option);
                });


                if (selectedCity) {
                    const prefilledOption = Array.from(citySelect.options).find(option => option.textContent === selectedCity);
                    if (prefilledOption) {
                        citySelect.value = prefilledOption.value;
                        updateBarangays(); 
                    }
                }
            })
            .catch(error => console.error('Error fetching cities/municipalities:', error));
    }

    function updateBarangays() {
        const citySelect = document.getElementById('city');
        const cityCode = citySelect.value;
        const selectedBarangay = document.getElementById('selectedBarangay').value;
        const isCity = citySelect.selectedOptions[0].dataset.isCity === 'true'; 

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


@endsection

@extends('Components.layout')

@section('styles')
<style>
    /* Keep your existing styles intact */
    body{
        overflow-y: auto;

    }
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
        background-color: #fff;
    }

    .card-body {
        padding: 30px;

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
        background-color: #228b22;
        border-color: #228b22;
        color: #fff;
    }

    .btn-custom:hover,
    .btn-custom:focus {
        background-color: #fafafa;
        border-color: #228b22;
        color: #228b22;
    }

    .custom-link {
        color: #228b22;
        font-size: 15px;
        transition: color 0.3s ease;
        text-decoration: none;
        margin-left: 20px;
    }

    .custom-link:hover {
        color: #1c6f1c;
    }

    .fas.fa-user,
    .fa-clipboard {
        color: #2e2e2e;
        font-size: 20px;
        transition: color 0.3s ease;
        margin-right: 10px;
    }
    .nav.nav-pills {
        display: none;
    }
</style>
@endsection

@section('content')
<main class="container mt-1">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile-picture d-flex align-items-center">
                        <img src="{{ $customer->customerImage ? asset('storage/' . $customer->customerImage->img_path) : asset('images/default_profile.png') }}" alt="Profile Picture" id="profile-picture1" class="rounded-circle" style="height: 50px; width: 50px; margin-right:10px;">
                        <div class="ml-3">
                            <strong>{{ $customer->username }}</strong>
                            <br>
                            <a href="{{ route('profile.edit') }}" style="font-size: 0.85rem; text-decoration: none; color: #228b22;">Edit Profile</a>
                        </div>
                    </div>
                    <hr class="w-100 mt-2 mb-4">
                    <div class="text-start">
                        <p class="mt-0" id="toggleAccount1" style="cursor: pointer;"><i class="fas fa-user custom-icon"></i>My Account</p>
                        <ul class="list-unstyled" id="accountDetails" style="max-height: 0; overflow: hidden; transition: max-height 0.4s ease;">
                            <li><a href="/profile" class="custom-link">Profile</a></li>
                            <li><a href="/banks-cards" class="custom-link">Banks & Cards</a></li>
                            <li><a href="{{ route('profile.addresses') }}" class="custom-link">Addresses</a></li>
                            <li><a href="/change-password" class="custom-link">Change Password</a></li>
                            <li><a href="/settings" class="custom-link">Settings</a></li>

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
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
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
                            <button type="button" id="cancelButtonaddress" class="btn btn-danger" style="display: none;">Cancel</button>
                            <button type="submit" id="saveButtonaddress" class="btn btn-custom" style="display: none;">Save</button>
                        </div>
                    </form>
                    <div id="message" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editButtonaddress = document.getElementById('editButtonaddress');
    const cancelButtonaddress = document.getElementById('cancelButtonaddress');
    const saveButtonaddress = document.getElementById('saveButtonaddress');
    const formFields = document.querySelectorAll('#addressForm input, #addressForm select');

    // Check if address data exists
    const hasAddressData = @json($address ? true : false);

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

    // Populate the regions dropdown
    populateRegions();

    // Event listeners for dropdown changes
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

                const combinedData = [...cities, ...municipalities];
                combinedData.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.code;
                    option.textContent = location.name;
                    citySelect.appendChild(option);
                });

                // Pre-select city/municipality if data exists
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
@endsection

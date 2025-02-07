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
            max-width: 700px;
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
        .search-container, .search-icon .fa-map-location-dot, .desktop-nav {
            display: none;
        }
        .store{
            color: gray;
            cursor: not-allowed;
            opacity: 0.5;
            pointer-events: none;
        }
        .mystore, .orders{
            color: gray;
            cursor: not-allowed;
            opacity: 0.5;
            pointer-events: none;
        }
        .error{
            font-size: 12px;
            color: red;
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
            <div class="progress-step" id="progress-step-2">
                <div class="progress-line" id="progress-line-2"></div>
                <span>Business Detail</span>
            </div>
            <div class="progress-step" id="progress-step-3">
                <span>Terms & Conditions</span>
            </div>
        </div>

        <!-- Form starts here -->
        <form action="{{ route('handleSecondStep') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p class="text-center">Fill in the details below to complete your merchant registration</p>
            <div class="signup-container mb-2">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <label for="dti-cert" class="form-label">DTI Certificate</label>
                            </div>
                            <input type="file" class="form-control @error('dti_cert') is-invalid @enderror" id="dti_cert" name="dti_cert" accept="image/*" required>
                            @if ($errors->has('dti_cert'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('dti_cert') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>
        
                        <div class="mb-3">
                            <label for="business-permit" class="form-label">Business Permit</label>
                            <input type="file" class="form-control @error('business_permit') is-invalid @enderror" id="business_permit" name="business_permit" accept="image/*" required>
                            @if ($errors->has('business_permit'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('business_permit') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>
        
                        <div class="mb-3">
                            <label for="store-about" class="form-label">About Your Store</label>
                            <textarea class="form-control @error('about_store') is-invalid @enderror" id="store-about" name="about_store" rows="4" placeholder="Tell us about your store">{{ old('about_store') }}</textarea>
                            @if ($errors->has('about_store'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('about_store') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>
        
                        <div class="mb-3">
                            <label for="category" class="form-label">Select Product Categories:</label>
                            <div class="d-flex flex-wrap gap-2" id="categoriesContainer">
                                @php
                                    $categories = [
                                        'Abaca', 'Clay', 'Pili', 'Bamboo', 'Nito Products', 
                                        'Ceramic Products', 'Rattan Crafts', 'Coconut Shell Crafts', 
                                        'Leather Crafts', 'Pineapple Crafts', 'Karagumoy Products', 
                                        'Anahaw Products', 'Buri Products', 'Delicacies'
                                    ];
                                @endphp
                                @foreach ($categories as $category)
                                    <input type="checkbox" class="btn-check @error('categories') is-invalid @enderror" id="category{{ $loop->index + 1 }}" name="categories[]" value="{{ $category }}" {{ in_array($category, old('categories', [])) ? 'checked' : '' }}>
                                    <label class="btn btn-pill" for="category{{ $loop->index + 1 }}">{{ $category }}</label>
                                @endforeach
                            </div>
                            @if ($errors->has('categories'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('categories') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="shop-street" class="form-label">Store Address</label>
                            <input type="text" class="form-control @error('shop_street') is-invalid @enderror" id="shop-street" value="{{ old('shop_street') }}" name="shop_street" placeholder="Purok / Street">
                            @if ($errors->has('shop_street'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('shop_street') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="province" class="form-label">Province</label>
                            <select class="form-select @error('selectedProvince') is-invalid @enderror" id="province" name="province" required>
                                <option selected disabled>-- Select Province --</option>
                            </select>
                            <input type="hidden" id="selectedProvince" name="selectedProvince" value="{{ old('selectedProvince') ?? $shop->province ?? '' }}">
                            @if ($errors->has('selectedProvince'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('selectedProvince') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City/Municipality</label>
                            <select class="form-select @error('selectedCity') is-invalid @enderror" id="city" name="city" required>
                                <option selected disabled>-- Select City/Municipality --</option>
                            </select>
                            <input type="hidden" id="selectedCity" name="selectedCity" value="{{ old('selectedCity') ?? $shop->city ?? '' }}">
                            @if ($errors->has('selectedCity'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('selectedCity') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="barangay" class="form-label">Barangay</label>
                            <select class="form-select @error('barangay') is-invalid @enderror" id="barangay" name="barangay" required>
                                <option selected disabled>-- Select Barangay --</option>
                            </select>
                             <input type="hidden" id="selectedBarangay" name="selectedBarangay" value="{{ old('barangay') ?? $shop->barangay ?? '' }}">
                             @if ($errors->has('barangay'))
                                <span class="error" role="alert">
                                    <ul>
                                        @foreach ($errors->get('barangay') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Postal/Zip Code</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                   id="postal_code"
                                   name="postal_code"
                                   value="{{ old('postal_code') }}"
                                   placeholder="Postal/Zip Code">
                                   @if ($errors->has('postal_code'))
                                   <span class="error" role="alert">
                                       <ul>
                                           @foreach ($errors->get('postal_code') as $error)
                                               <li>{{ $error }}</li>
                                           @endforeach
                                       </ul>
                                   </span>
                               @endif
                        </div>
                    </div>
                </div>

                <!-- Submit button inside form -->
                <div class="mb-3 mt-3 text-center">
                    <button class="btn btn-custom w-50" type="submit">Next</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Province, city, and barangay fetching
        const selectedProvinceInput = document.getElementById('selectedProvince');  // Hidden input for Province
        const selectedCityInput = document.getElementById('selectedCity');          // Hidden input for City
        const selectedBarangayInput = document.getElementById('selectedBarangay');  // Hidden input for Barangay

        const provinceSelect = document.getElementById('province'); // Dropdown for Province
        const citySelect = document.getElementById('city');         // Dropdown for City
        const barangaySelect = document.getElementById('barangay'); // Dropdown for Barangay

        function populateProvinces() {
            const regionCode = '050000000';  // Region V (Bicol Region) code
            fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces.json`)
                .then(response => response.json())
                .then(data => {
                    provinceSelect.innerHTML = '<option selected disabled>-- Select Province --</option>';
                    data.forEach(province => {
                        const option = document.createElement('option');
                        // Store province code in option value, but display the province name
                        option.value = province.code;
                        option.textContent = province.name;  // Display province name in dropdown
                        provinceSelect.appendChild(option);
                    });

                    // If there's a pre-selected province, set it
                    if (selectedProvinceInput.value) {
                        const prefilledOption = Array.from(provinceSelect.options).find(option => option.textContent === selectedProvinceInput.value);
                        if (prefilledOption) {
                            provinceSelect.value = prefilledOption.value;  // Set the province code as value
                            updateCities(provinceSelect.value);  // Pass the province code to updateCities
                        }
                    }
                })
                .catch(error => console.error('Error fetching provinces:', error));
        }

        function updateCities(provinceCode) {
            const fetchCities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities.json`);
            const fetchMunicipalities = fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/municipalities.json`);

            Promise.all([fetchCities, fetchMunicipalities])
                .then(responses => Promise.all(responses.map(response => response.json())))
                .then(dataArrays => {
                    const [cities, municipalities] = dataArrays;

                    const combinedData = [...cities, ...municipalities];
                    const citySelect = document.getElementById('city');
                    citySelect.innerHTML = '<option selected disabled>-- Select City/Municipality --</option>';

                    combinedData.forEach(location => {
                        const option = document.createElement('option');
                        option.value = location.code;
                        option.textContent = location.name;

                        // Set data-is-city based on whether it's a city or municipality
                        option.setAttribute('data-is-city', cities.some(city => city.code === location.code) ? 'true' : 'false');

                        citySelect.appendChild(option);
                    });

                    // If there's a pre-selected city, set it
                    const selectedCityInput = document.getElementById('selectedCity');
                    if (selectedCityInput.value) {
                        const prefilledOption = Array.from(citySelect.options).find(option => option.textContent === selectedCityInput.value);
                        if (prefilledOption) {
                            citySelect.value = prefilledOption.value;  // Set the city code as value
                            updateBarangays(citySelect.value);  // Pass the city code to updateBarangays
                        }
                    }
                })
                .catch(error => console.error('Error fetching cities/municipalities:', error));
        }

        function updateBarangays(cityCode) {
            const citySelect = document.getElementById('city');
            const isCity = citySelect.selectedOptions[0].dataset.isCity === 'true';
            const endpoint = isCity
                ? `https://psgc.gitlab.io/api/cities/${cityCode}/barangays.json`
                : `https://psgc.gitlab.io/api/municipalities/${cityCode}/barangays.json`;

            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    const barangaySelect = document.getElementById('barangay');
                    barangaySelect.innerHTML = '<option selected disabled>-- Select Barangay --</option>';

                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.name;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });

                    // If there's a pre-selected barangay, set it
                    const selectedBarangayInput = document.getElementById('selectedBarangay');
                    if (selectedBarangayInput.value) {
                        const prefilledOption = Array.from(barangaySelect.options).find(option => option.textContent === selectedBarangayInput.value);
                        if (prefilledOption) {
                            barangaySelect.value = prefilledOption.value;
                        }
                    }
                })
                .catch(error => console.error('Error fetching barangays:', error));
        }

        // Update hidden input when a new province is selected
        provinceSelect.addEventListener('change', function() {
            const selectedProvinceName = provinceSelect.options[provinceSelect.selectedIndex].textContent;  // Get province name
            selectedProvinceInput.value = selectedProvinceName;  // Set the hidden input to store province name
            updateCities(provinceSelect.value);  // Pass the province code to updateCities
        });

        // Update hidden input when a new city is selected
        citySelect.addEventListener('change', function() {
            const selectedCityName = citySelect.options[citySelect.selectedIndex].textContent;  // Get city name
            selectedCityInput.value = selectedCityName;  // Set the hidden input to store city name
            updateBarangays(citySelect.value);  // Pass the city code to updateBarangays
        });

        // Update hidden input when a new barangay is selected
        //barangaySelect.addEventListener('change', function() {
            //const selectedBarangayName = barangaySelect.options[barangaySelect.selectedIndex].textContent;  // Get barangay name
           // selectedBarangayInput.value = selectedBarangayName;  // Set the hidden input to store barangay name
        //});

        populateProvinces();
    });
</script>

@endsection

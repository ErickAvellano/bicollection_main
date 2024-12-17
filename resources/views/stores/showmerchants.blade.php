@extends('Components.layout')

@section('styles')
<style>
    .content-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 1000px;
        margin: 20px auto;
        background-color: #ffffff;
        border: 2px solid #228b22;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: 0.5s;
        padding: 20px;
    }

    @media (min-width: 768px) {
        .content-container {
            flex-direction: row;
            height: 300px;
        }
    }

    .image-section {
        flex: 1;
        position: relative;
    }

    .store-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .info-section {
        flex: 2;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .store-name {
        font-size: 2em;
        font-weight: bold;
        color: #000000;
        margin-bottom: 10px;
    }

    .location, .reviews, .operating-hours {
        margin: 5px 0;
        font-size: 1em;
        color: #333333;
    }

    .tags {
        display: flex;
        flex-wrap: wrap;
        margin: 10px 0;
    }

    .tag {
        background: #228b22;
        color: #ffffff;
        padding: 5px 10px;
        margin: 5px;
        border-radius: 5px;
        font-size: 0.9em;
    }

    .about-store {
        font-size: 1em;
        color: #333333;
        margin-top: 10px;
    }

    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #228b22;
        color: #ffffff;
        font-size: 1.5em;
        padding: 10px;
        cursor: pointer;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    .nav-button:hover {
        opacity: 1;
    }

    .left-button {
        left: 10px;
    }

    .right-button {
        right: 10px;
    }

    .back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #228b22;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
        text-decoration: none;
    }

    .back-button:hover {
        background: #1e7b1e;
        color: #ffffff;
    }
</style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($shops as $shop)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm rounded-lg" style="position: relative; overflow: hidden; height: 320px;">
                        <!-- Cover Image -->
                        <div style="position: relative; height: 180px; overflow: hidden;">
                            <img src="{{ $shop->coverphotopath ? Storage::url($shop->coverphotopath) : asset('images/default-bg.jpg') }}"
                                alt="Cover Photo"
                                class="w-100"
                                style="object-fit: cover; height: 100%;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);"></div>
                        </div>

                        <!-- Profile Image -->
                        <div style="position: absolute; top: 120px; left: 20px; display: flex; align-items: center;">
                            <!-- Profile Image -->
                            <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : asset('images/assets/default_profile.png') }}"
                                alt="Shop Profile" 
                                class="rounded-circle border border-2 border-white me-3" 
                                style="width: 100px; height: 100px; object-fit: cover;">

                            <!-- Shop Name -->
                            <div style="position: absolute; top: 0px; left: 110px; width:400px; display: flex; flex-direction: column;">
                                <h5 class="fw-bold mb-0" id="shopName" style="display: inline-block; color:white;">
                                    {{ $shop->shop_name }}
                                    <i class="fa-solid fa-check-circle text-custom" title="Verified"></i>
                                </h5>
                                <div class="mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <div class="mb-0" style="display: grid; grid-template-columns: 20px auto; gap: 5px;">
                                    <i class="fa-solid fa-location-dot text-danger"></i>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->shop_street . ', ' . $shop->barangay . ', ' . $shop->city . ', ' . $shop->province) }}"
                                        target="_blank"
                                        style="text-decoration: none; color: inherit;">
                                        <span>{{ $shop->shop_street }}, {{ $shop->barangay }}, {{ $shop->city }}, {{ $shop->province }}</span>
                                    </a>

                                    <i class="fa-solid fa-phone"></i>
                                    @if ($shop->contact)
                                        <a href="tel:{{ $shop->contact }}" style="text-decoration: none; color: inherit;">
                                            <span>{{ $shop->contact }}</span>
                                        </a>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Shop Content -->
                        <div class="p-3 text-center" style="margin-top: 40px;">
                            <!-- Details -->
                            <div class="text-start px-4">
                                <div class="mb-2">
                                    @forelse ($shop->applications as $application)
                                        @if (!empty($application->decoded_categories))
                                            @foreach ($application->decoded_categories as $category)
                                                @if (!empty($category))
                                                    @php
                                                        // Clean up the category name by removing "Products" or "Craft"
                                                        $categoryName = str_replace(['Products', 'Craft', 'Crafts'], '', $category);

                                                        // List of categories that should have "Crafts" appended
                                                        $categoriesWithCrafts = ['Rattan', 'Coconut', 'Leather', 'Karagumoy', 'Buri', 'Anahaw', 'Nito'];
                                                        
                                                        // If the category matches, append "Crafts" to the name
                                                        if (in_array($categoryName, $categoriesWithCrafts)) {
                                                            $categoryName .= ' Crafts'; // Append "Crafts"
                                                        }
                                                    @endphp
                                                    <!-- Use urlencode for the category name to avoid issues with spaces and special characters -->
                                                    <a href="{{ route('category.product', ['category_name' => urlencode(trim($categoryName))]) }}" class="badge bg-success">
                                                        {{ $categoryName }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">No Categories</span>
                                        @endif
                                    @empty
                                        <span class="badge bg-secondary">No Applications</span>
                                    @endforelse
                                </div>
                                <!-- About Store -->
                                <p class="mb-0">
                                    <strong>About Store:</strong> {{ Str::limit($shop->description, 100, '...') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach    
        </div>
    </div>



@include('Components.footer')
@endsection

@section('scripts')

@endsection

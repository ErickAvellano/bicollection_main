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
                <div class="card shadow-sm rounded-lg" style="position: relative; overflow: hidden;">
                    <!-- Cover Image -->
                    <div style="height: 180px; overflow: hidden;">
                        <img src="{{ $shop->coverphotopath ? Storage::url($shop->coverphotopath) : asset('images/default-bg.jpg') }}" 
                             alt="Cover Photo" 
                             class="w-100" 
                             style="object-fit: cover; height: 100%;">
                    </div>

                    <!-- Profile Image -->
                    <div style="position: absolute; top: 120px; left: 20px; display: flex; align-items: center;">
                        <!-- Profile Image -->
                        <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : asset('images/assets/default_profile.png') }}" 
                             alt="Shop Profile" 
                             class="rounded-circle border border-2 border-white me-3"
                             style="width: 100px; height: 100px; object-fit: cover;">
                    
                        <!-- Shop Name and Edit -->
                        <div style="display: flex; flex-direction: column;">
                            <!-- Shop Name -->
                            <h4 class="fw-bold mb-0" id="shopName" contenteditable="true" style="cursor: text; display: inline-block;">
                                {{ $shop->shop_name }} 
                                <i class="fa-solid fa-check-circle text-primary"></i>
                            </h4>
                        </div>
                    </div>
                    <!-- Shop Content -->
                    <div class="p-3 text-center" style="margin-top: 40px;">
                        <!-- Ratings -->
                        <div class="mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star text-warning"></i>
                            @endfor
                        </div>

                        <!-- Details -->
                        <div class="text-start px-4">
                            <p><strong>Location:</strong> {{ $shop->province }}, {{ $shop->city }}</p>
                            <p><strong>Contact:</strong> {{ $shop->contact ?? 'N/A' }}</p>
                            <p><strong>About Store:</strong> {{ Str::limit($shop->description, 100, '...') }}</p>
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

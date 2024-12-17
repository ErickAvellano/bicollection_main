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
                <div class="card shadow-sm rounded-lg">
                    <!-- Shop Cover Image -->
                    <div style="position: relative; height: 180px; overflow: hidden;">
                        <img src="{{ $shop->coverphotopath ? Storage::url($shop->coverphotopath) : asset('images/default-bg.jpg') }}"
                             alt="{{ $shop->shop_name }}"
                             class="w-100"
                             style="object-fit: cover; height: 100%;">
                    </div>

                    <!-- Profile Image and Shop Name -->
                    <div class="p-3" style="position: relative;">
                        <img src="{{ $shop->shop_img ? Storage::url($shop->shop_img) : asset('images/assets/default_profile.png') }}"
                             alt="Shop Profile"
                             class="rounded-circle border border-2 border-white"
                             style="width: 80px; height: 80px; position: absolute; top: -40px; left: 20px; object-fit: cover;">

                        <h5 class="fw-bold" style="margin-left: 100px;">
                            {{ $shop->shop_name }}
                            <i class="fa-solid fa-check-circle text-success" title="Verified"></i>
                        </h5>

                        <!-- Categories as Badges -->
                        <div class="mb-2">
                            @forelse ($shop->applications as $application)
                                @if ($application->categories && $application->categories->isNotEmpty())
                                    @foreach ($application->categories as $category)
                                        <span class="badge bg-success">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">No Categories</span>
                                @endif
                            @empty
                                <span class="badge bg-secondary">No Applications</span>
                            @endforelse
                        </div>

                        <!-- Shop Description -->
                        <p class="text-muted">
                            <strong>About Store:</strong> {{ Str::limit($shop->description, 100, '...') }}
                        </p>
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

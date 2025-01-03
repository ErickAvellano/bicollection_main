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
    .card  {
        transition: border 0.3s ease-in-out;
    }

    .card:hover {
        border: 2px solid #228b22; /* Change this to the color you prefer */
        cursor: pointer;
    }
</style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($categories as $category)
                @php
                    $filename = str_replace(' ', '', $category->category_name) . '.jpg';
                @endphp
                <div class="col-md-6 mb-4">
                    <a href="#" class="card-link">
                        <div class="card shadow-sm rounded-lg" style="position: relative; overflow: hidden; height: 340px;">
                            <!-- Cover Image -->
                            <div style="position: relative; height: 170px; overflow: hidden;">
                                <img src="{{ asset('images/assets/category/' . $filename) }}" alt="{{ $category->category_name }}" class="img-fluid w-100 h-100">
                            </div>

                            <!-- Shop Content -->
                            <div class="p-3 text-center" style="margin-top: 60px;">
                                <!-- About Store -->
                                <p class="mb-0">
                                    <strong>About Store:</strong> {{ Str::limit($shop->description, 110, '...') }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>



@include('Components.footer')
@endsection
@section('scripts')

@endsection

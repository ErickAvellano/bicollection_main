@extends('Components.layout')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    body, html {
        overflow: auto;
        margin: 0;
        font-family: 'Poppins', sans-serif;
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
    .about-store {
        font-size: 1em;
        color: #333333;
        margin-top: 10px;
    }
    .card  {
        transition: border 0.3s ease-in-out;
    }
    .card-link{
        text-decoration:none;
    }
    .category-header{
        margin-top:20px;
        margin-bottom:20px;
    }
    .card:hover {
        border: 2px solid #228b22; /* Change this to the color you prefer */
        cursor: pointer;
    }
    .category_description{
        font-size:14px;
    }
    @media (min-width: 360px) and (max-width: 425px) {
        body {
            font-size: 12px;
        }
        .navbar-brand {
            font-size: 1.2rem;
        }
        .row .col-lg-3 {
            flex: 0 0 100%;
            max-width: 100%;
            
        }
        
    }

</style> 
@endsection

@section('content')

    <div class="container mt-3">
        <nav aria-label="breadcrumb" >
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/">BiCollection</a></li>
                <li class="breadcrumb-item active" aria-current="page">Category</li>
            </ol>
        </nav>
        <div class="row">
            <h4 class="category-header">CATEGORY</h4>
            @foreach ($categories as $category)
                @php
                    $filename = str_replace(' ', '', $category->category_name) . '.jpg';
                @endphp
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4" style="max-height:285px;"> <!-- Adjusted column classes -->
                    <a href="{{ route('category.product', ['category_name' => $category->category_name]) }}" class="card-link">
                        <div class="card shadow-sm rounded-lg" style="position: relative; overflow: hidden; height:100%;">
                            <!-- Cover Image -->
                            <div style="position: relative; height: 150px; overflow: hidden;">
                                <img src="{{ asset('images/assets/category/' . $filename) }}" alt="{{ $category->category_name }}" class="img-fluid w-100 h-100">
                            </div>
                            <div class="p-2 text-center">
                                <!-- About Store --> 
                                <h5>{{$category->category_name}}</h5>
                                <p class="mb-0 category_description">
                                    {{ Str::limit($category->category_description, 100, '...') }}
                                </p>
                            </div>
                            <!-- Shop Content -->
                           
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

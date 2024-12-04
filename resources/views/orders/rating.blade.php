@extends('Components.layout')<!-- Adjust this to the layout you're using -->

@section('styles')
<style>
    .nav-pills, .search-control, .search-icon{
        display:none;
    }
</style>
    

@endsection

@section('content')
<div class="container mt-3 mb-5">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item">My Purchase</li>
        <li class="breadcrumb-item active">Ratings</li>
    </ol>

            <p>You rated this product with:</p>
            <div class="text-warning my-3" id="displayRating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-{{ $i <= $rating ? 'solid' : 'regular' }} fa-star fa-2x"></i>
                @endfor

            <p class="text-muted mb-4">Product ID: <strong>{{ $productId }}</strong></p>
            
    </div>
</div>
@endsection

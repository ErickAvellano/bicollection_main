@extends('Components.layout')

@section('styles')
<style>
     main {
            padding: 80px;
            text-align: center;
            background-image: url({{ asset('images/assets/start-selling/startsellingbg.png') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height:350px;
        }

</style>

@endsection

@section('content')
<main>
    <h1>Showcase and sell your unique Bicol products with ease!</h1>
    <p class="mb-4">Crafted for your business success.</p>
    <a href="{{ route('merchant.register') }}" class="cta-btn">Get Started Now</a>
</main>



@include('Components.footer')
@endsection
@section('scripts')

@endsection

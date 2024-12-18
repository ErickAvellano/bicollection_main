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
        .nav-pills, .search-container, .desktop-nav{
            display:none;
        }

</style>

@endsection

@section('content')
<main>
    <h4>Hi, how can we help you?</h4>
    
</main>



@include('Components.footer')
@endsection
@section('scripts')

@endsection

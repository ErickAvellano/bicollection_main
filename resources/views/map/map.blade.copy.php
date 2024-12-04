@extends('Components.layout')

@section('styles')
<style>
    body {
        color: #000000;
        font-family: 'Lato', sans-serif;

        margin: 0;
        padding: 0;
        background: #89d8ec;
    }

    .background {
        background-image: url('{{ asset('images/assets/map/Map1.jpg') }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        height: 100vh;
        width: 100%;
        position: absolute;
        overflow: true;
        top: 18%;
        left: -12%;
        z-index: -1;
    }

    .btn-primary {
        margin-top: 3%;
        width: 100%;
        background-color: rgb(41, 141, 41);
        border: none;
    }

    .btn-primary:hover {
        background-color: #00742a;
    }

    .btn-primary:active,
    .btn-primary:focus {
        background-color: #00742a;
    }

    .bi-collection {
        margin-right: 10px;
    }

    .forot-text {
        color: #00B512;
    }

    .draggable {
        width: auto;
        height: auto;
        position: absolute;
    }

    .map-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        height: 0;
        padding-bottom: 70%;
        margin: auto;
        overflow: hidden;
    }

    .map-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: contain;
    }

    .region {
        position: absolute;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .region img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .region-name {
        position: absolute;
        transform: translateX(-50%);
        padding: 5px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        color: rgb(34, 34, 34);
        z-index: 1;
        transition: opacity 0.3s ease-in-out;
        display: none;
    }

    .region:hover {
        transform: scale(1.2);
        z-index: 10;
    }

    .region:hover .region-name {
        display: block;
        opacity: 1;
    }

    /* Hover overlay styles */
    .hover-overlay {
        display: none;
        position: absolute;
        top: 20px;
        right: -150px;
        width: 250px;
        height: 150px;
        background-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        z-index: 10;
        text-align: center;
        padding: 10px;
    }

    .hover-overlay img {
        width: 100%;
        height: auto;
        border-radius: 10px 10px 0 0;
    }

    .hover-content h4 {
        margin: 10px 0 5px;
        font-size: 16px;
        color: #2e6e4c;
    }

    .hover-content p {
        margin: 0;
        color: #00742a;
        font-size: 14px;
    }

    /* Show overlay on hover */
    .region:hover .hover-overlay {
        display: block;
    }

    /* Adjustments for each region */
    .region.camnorte {
        top: 3.1%;
        left: 33.4%;
        width: 170px;
    }

    .region.camnorte .region-name {
        top: 30%;
        left: 50%;
    }

    .region.camsur {
        top: 15.2%;
        left: 40.5%;
        width: 270px;
    }

    .region.camsur .region-name {
        top: 47%;
        left: 50%;
    }

    .region.albay {
        top: 33.9%;
        left: 57%;
        width: 185px;
    }

    .region.albay .region-name {
        top: 47%;
        left: 30%;
    }

    .region.sorsogon {
        top: 46.1%;
        left: 61.3%;
        width: 149px;
    }

    .region.sorsogon .region-name {
        top: 20%;
        left: 57%;
    }

    .region.catanduanes {
        top: 17%;
        left: 76%;
        width: 80px;
    }

    .region.catanduanes .region-name {
        top: 30%;
        left: 50%;
    }

    .region.masbate {
        top: 47%;
        left: 50%;
        width: 172px;
    }

    .region.masbate .region-name {
        top: 50%;
        left: 55%;
    }

    /* Custom styles for the modal */
    .modal-custom {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        height: 80%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px) saturate(180%);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 1050;
        padding: 1rem;
        border-radius: 15px;
        overflow-y: auto;
    }

    .modal-content-custom {
        height: calc(100% - 3rem);
        color: #ffffff;
    }

    .modal-header {
        background: rgba(255, 255, 255, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        padding: 1rem;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-title {
        color: #fff;
    }

    .modal-body {
        padding: 1rem;
        overflow-y: auto;
        color: #ffffff;
    }

    /* Close button style */
    .btn-close {
        background: rgba(255, 255, 255, 0.4);
        border: none;
        padding: 0.5rem;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
    }

    .btn-close:hover {
        background: rgba(255, 255, 255, 0.7);
    }

    .img-container {
        text-align: center;
        border-radius: 10px;
        padding: 1rem;
        height: 250px;
    }

    .img-fixed-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .img-fixed-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .map-name {
        text-align: center;
        font-weight: bold;
        border: 1px solid black;
        padding: 0.5rem;
        border-radius: 5px;
        margin-top: 1rem;
    }

    .content-section {
        border: 2px solid black;
        border-radius: 5px;
        padding: 0.5rem;
        margin-bottom: 1rem;
    }

    .header {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .content {
        padding: 2px;
    }

    .partnered-merchants-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        padding: 0;
        gap: 0.5rem;
    }

    .card {
        border: 1px solid black;
    }

    .card-body.merchant {
        display: flex;
        align-items: center;
        padding: 5px;
        height: auto;
    }

    .merchant-logo {
        max-width: 50px;
        margin-right: 10px;
    }

    .card-text {
        margin: 0;
    }

    .hover-content h4  {
        font-size: 16px;
        color: #fafafa;
    }
    .hover-content p{
        font-size: 12px;
        color: #fafafa;
    }
    .second-nav {
        box-shadow: none !important;
        background-color: transparent !important;
    }

    /* Media Queries for Mobile */
    @media (max-width: 768px) {
        .region {
            width: 90%;
        }

        .map-container {
            width: 100%;
            max-width: 100%;
            padding-bottom: 500px;
        }

        .modal-content-custom {
            height: 80%;
        }

        .img-container {
            height: 300px;
        }

        .region-name {
            display: block;
            font-size: 12px;
            top: 25%;
            left: 50%;
        }

        /* Adjustments for each region */
        .region.camnorte {
            top: 4%;
            left: 7%;
            width: 110px;
        }

        .region.camsur {
            top: 13.3%;
            left: 16.5%;
            width: 190px;
        }

        .region.albay {
            top: 29.9%;
            left: 44.9%;
            width: 130px;
        }

        .region.sorsogon {
            top: 40.7%;
            left: 52.4%;
            width: 105px;
        }

        .region.catanduanes {
            top: 14%;
            left: 78%;
            width: 60px;
        }

        .region.masbate {
            top: 40%;
            left: 30%;
            width: 150px;
        }
    }
</style>
@endsection
@section('content')
<div class="background">
    <div class="container-fluid" style="position: relative; overflow: visible;">
        <div class="row justify-content-center" style="position: relative;">
            <div class="col-md-8 d-flex justify-content-center align-items-center flex-column" style="z-index: 2; position: relative;">
                <div class="map-container" style="overflow: visible;">
                    <div class="map-content" style="overflow: visible;">
                        <!-- Add regions inside map-content -->
                        <a href="{{ route('maplanding', ['region' => 'camnorte']) }}" style="text-decoration: none;">
                            <div class="region camnorte" data-name="Camarines Norte">
                                <img src="{{ asset('images/assets/map/camarinesnorte2.png') }}" alt="Cam Norte" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name camnorte">Camarines Norte</div>
                                <div class="hover-overlay" style="left: 105%; top:; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/2.png') }}');">
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('maplanding', ['region' => 'camsur']) }}" style="text-decoration: none;">
                            <div class="region camsur" data-name="Camarines Sur">
                                <img src="{{ asset('images/assets/map/camarinessur2.png') }}" alt="Cam Sur" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name camsur">Camarines<br><span>Sur</span></div>
                                <div class="hover-overlay" style="left: 75%; top: 56%; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/3.png') }}');">
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('maplanding', ['region' => 'albay']) }}" style="text-decoration: none;">
                            <div class="region albay" data-name="Albay">
                                <img src="{{ asset('images/assets/map/albay2.png') }}" alt="Albay" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name albay">Albay</div>
                                <div class="hover-overlay" style="left: -137%; top: 30%; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/1.png') }}');">
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('maplanding', ['region' => 'sorsogon']) }}" style="text-decoration: none;">
                            <div class="region sorsogon" data-name="Sorsogon">
                                <img src="{{ asset('images/assets/map/sorsogon2.png') }}" alt="Sorsogon" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name sorsogon">Sorsogon</div>
                                <div class="hover-overlay" style="left: 100%; top: 20%; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/6.png') }}');" >
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</pclass=>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('maplanding', ['region' => 'catanduanes']) }}" style="text-decoration: none;">
                            <div class="region catanduanes" data-name="Catanduanes">
                                <img src="{{ asset('images/assets/map/catanduanes2.png') }}" alt="Catanduanes" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name catanduanes">Catanduanes</div>
                                <div class="hover-overlay" style="left: 120%; top: -30%; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/4.png') }}');">
                                <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('maplanding', ['region' => 'masbate']) }}" style="text-decoration: none;">
                            <div class="region masbate" data-name="Masbate">
                                <img src="{{ asset('images/assets/map/masbate2.png') }}" alt="Masbate" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name masbate">Masbate</div>
                                <div class="hover-overlay" style="left: -125%; top: 19%; background-size: cover; background-position: center; color: white; border-radius: 0.5rem; overflow: hidden; padding:0; background-image: url('{{ asset('images/assets/map/5.png') }}');">
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Second Column -->
            <div class="col-md-4 d-flex align-items-center flex-column" style="margin-top:200px; z-index: 1; position: relative;">
                <h3>Select a Province to Visit</h3>
                <h4>or</h4>
                <a class="rounded-full" href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/BICOLLECTION.png') }}" alt="BiCollection">
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>

</script>
@endsection

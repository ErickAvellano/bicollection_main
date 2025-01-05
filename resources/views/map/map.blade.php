@extends('Components.layout')

@section('styles')
<style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    body {
        color: #000000;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background: #89d8ec;
        overflow: hidden;
    }

    .background {
        max-width: 1356px;
        max-height: 879px;
        background-image: url('{{ asset('images/assets/map/Map1.jpg') }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        height: auto;
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
        text-shadow:
        1px 1px 0 rgba(250, 250, 250, 0.5),
        -1px -1px 0 rgba(250, 250, 250, 0.5),
        1px -1px 0 rgba(250, 250, 250, 0.5),
        -1px 1px 0 rgba(250, 250, 250, 0.5),
        2px 2px 5px rgba(250, 250, 250, 0.5); /* Stacked text shadows for depth */

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
    .camnorte-overlay{
        left: 105%;
        top: 19%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/2.png') }}');
    }
    .camsur-overlay{
        left: 75%;
        top: 56%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/3.png') }}');
    }
    .albay-overlay{
        left: -137%;
        top: 30%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/1.png') }}');
    }
    .sorsogon-overlay{
        left: 100%;
        top: 20%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/6.png') }}');
    }
    .catanduanes-overlay{
        left: 120%;
        top: -30%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/4.png') }}');
    }
    .masbate-overlay{
        left: -125%;
        top: 19%;
        background-size: cover;
        background-position: center;
        color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        padding:0;
        background-image: url('{{ asset('images/assets/map/5.png') }}');
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

    .modal-open{
        overflow: hidden !important;
        padding: 0 !important;
    }


    .modal.fade.show {
        display: flex !important; /* Ensure the modal is displayed properly */
        justify-content: center;  /* Center the modal horizontally */
        align-items: center;      /* Center the modal vertically */
        overflow: hidden;

    }

    .modal-content {
        border-radius: 10px;      /* Round the corners of the modal */
        padding: 20px;            /* Add padding inside the modal */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Shadow for better appearance */
    }

    .modal-header {
        border-bottom: none;      /* Remove the border from the header */
    }

    .modal-title {
        font-size: 1.25rem;       /* Adjust the title size */
        color: #333;              /* Color for the title text */
    }

    .modal-body {
        font-size: 1rem;          /* Adjust the font size in the modal body */
        color: #555;              /* Slightly lighter color for the body text */
    }

    .modal-body p {
        margin: 0;                /* Remove any margin from paragraphs */
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
        padding: 10px 20px;       /* Add some padding */
    }

    .modal-footer .btn {
        padding: 10px 15px;       /* Add some padding to the buttons */
    }

    .modal-footer .btn-primary {
        background-color: #28a745; /* Green color for Proceed to Checkout */
        border-color: #28a745;
    }

    .modal-footer .btn-primary:hover {
        background-color: #218838; /* Slightly darker on hover */
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d; /* Gray for Continue Shopping */
        border-color: #6c757d;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #5a6268; /* Darker on hover */
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
        inset
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
        z-index: -1;
    }
    .mapmodal-content{
        position: absolute;
        top: 75px;
        right: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(245, 245, 245, 0.3)); /* Mirror-like effect */
        backdrop-filter: blur(5px); /* Blurring for a glassy effect */
        padding: 10px;
        width:57%;
        height:89%;
        overflow-y: auto;
        box-shadow: -3px 0px 5px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-in-out;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 30px;
        background: none;
        border: none;
        cursor: pointer;
    }

    .show-map-sidebar {
        right: 0;
    }

    .region {
        cursor: pointer;
    }

    .hover-overlay {
        cursor: pointer;
    }
        /* Close Button */
    .mapmodal-close {
        position: absolute;
        top: 20px;  /* Top padding */
        right: 20px;  /* Right padding */
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    .mapmodal-close:hover {
        color: black;  /* Hover effect for close button */
    }

    /* Show the modal */
    .mapmodal.show {
        display: block;
        width: 400px;
    }

    @media only screen and (min-width: 2304px) and (max-height: 1296px) {
    /* Your styles for screens matching 2304x1296 or larger */
    body {
        transform: scale(0.8);
    }
    }


    @media only screen and (min-width: 360px) and (max-width: 425px) {
            /* Styles for devices in this range */
            body {
                font-size: 16px;
            }
            .container {
                padding: 10px;
            }
            /* Add other styles as needed */
            .background{
                top: -60px;
                left:30px;
                transform: scale(1.3); /* Zooms the image by 10% */
            }


        }


    /* Media Queries for Mobile */
    @media (max-width: 768px) {
        .mapmodal-content{
            height:100%;
        }
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
    @media only screen and (min-width: 1441px) and (max-width: 1920px) {
        .background{
            transform: scale(1.25);  /* Zoom to 125% */
            transform-origin: 0 0;
        }
        /* Hover overlay styles */
        .hover-overlay {
            width: 225px;
            height: 125px;
        }
        .albay-overlay{
            left: -125%;
        }
        .masbate-overlay{ 
            left: -110%;
        }
        .mapmodal-content{
            height:92%;
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
                        <a href="#" class="region-link" class="region-link" data-name="camnorte" style="text-decoration: none;">
                            <div class="region camnorte">
                                <img src="{{ asset('images/assets/map/camarinesnorte2.png') }}" loading="lazy" alt="Cam Norte" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name camnorte">Camarines Norte</div>
                                <div class="hover-overlay camnorte-overlay" >
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="region-link" data-name="camsur" style="text-decoration: none;">
                            <div class="region camsur" >
                                <img src="{{ asset('images/assets/map/camarinessur2.png') }}" loading="lazy" alt="Cam Sur" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name camsur">Camarines<br><span>Sur</span></div>
                                <div class="hover-overlay camsur-overlay" >
                                    <div class="hover-content " style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="region-link"  data-name="albay" style="text-decoration: none;">
                            <div class="region albay">
                                <img src="{{ asset('images/assets/map/albay2.png') }}" loading="lazy" alt="Albay" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name albay">Albay</div>
                                <div class="hover-overlay albay-overlay" >
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="region-link"   data-name="sorsogon" style="text-decoration: none;">
                            <div class="region sorsogon">
                                <img src="{{ asset('images/assets/map/sorsogon2.png') }}" loading="lazy" alt="Sorsogon" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name sorsogon">Sorsogon</div>
                                <div class="hover-overlay sorsogon-overlay" >
                                    <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</pclass=>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="region-link" data-name="catanduanes" style="text-decoration: none;">
                            <div class="region catanduanes" >
                                <img src="{{ asset('images/assets/map/catanduanes2.png') }}" loading="lazy" alt="Catanduanes" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name catanduanes">Catanduanes</div>
                                <div class="hover-overlay catanduanes-overlay">
                                <div class="hover-content" style="z-index: 3; position: absolute; bottom: 0; background: linear-gradient(to top, rgba(76, 175, 80, 0.9), rgba(76, 175, 80, 0.1)); width: 100%; text-align: center;">
                                        <p class="mb-1">Click to explore</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="region-link" data-name="masbate"  style="text-decoration: none;">
                            <div class="region masbate">
                                <img src="{{ asset('images/assets/map/masbate2.png') }}" loading="lazy" alt="Masbate" style="filter: drop-shadow(-2px 3px 2px rgba(0, 0, 0, 0.5));">
                                <div class="region-name masbate">Masbate</div>
                                <div class="hover-overlay masbate-overlay">
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
            <div class="col-md-4 d-flex align-items-center justify-content-center flex-column text-center" style="z-index: 1; position: relative; max-height:500px;">
                <h3>Select a Province to Visit</h3>
                <h4>or</h4>
                <a class="rounded-full" href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" loading="lazy" alt="BiCollection" style="width: 150px;">
                </a>
            </div>
        </div>
    </div>
</div>
@include('Components.add-to-cart')

<div id="regionDetailsMapModal" class="mapmodal" style="display:none;">
    <div class="mapmodal-content">
        <!-- Close Button -->
        <span class="mapmodal-close" style="cursor: pointer;">
            <i class="fa-solid fa-xmark"></i>
        </span>
        <div id="region-content">
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
       document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('regionDetailsMapModal');
        var closeBtn = modal.querySelector('.mapmodal-close');
        var modalBodyContent = document.getElementById('region-content');

        if (!modalBodyContent) {
            console.error('The modal content element (#region-content) is missing from the DOM.');
            return;
        }

        // Event listener for anchor tags with class 'region-link'
        document.querySelectorAll('.region-link').forEach(function (anchor) {
            anchor.addEventListener('click', function (event) {
                event.preventDefault();

                var name = this.getAttribute('data-name');  // Get the alias (e.g., camnorte)

                // Fetch region details via AJAX
                fetch(`/region-details/${name}`, { cache: "no-cache" }) // Prevent caching
                    .then(response => response.text())
                    .then(data => {
                        // Insert the fetched data into the modal
                        modalBodyContent.innerHTML = data;

                        // Show the modal
                        modal.style.display = 'block';

                        // Reinitialize scripts and magnifier
                        reinitializeRegionDetailsScripts();

                        // Reinitialize magnifier (if modal content has image)
                        initializeMagnifier();
                    })
                    .catch(error => {
                        console.error('Error loading region details:', error);
                        modalBodyContent.innerHTML = "Unable to load region details.";
                    });
            });
        });

        // Event listener to close the modal
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close modal if user clicks outside of the modal content
        window.addEventListener('click', function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Initialize the magnifier functionality (this runs for each modal open)
        function initializeMagnifier() {
            var sub_width = 0;
            var sub_height = 0;
            $(".large").css("background", "url('" + $(".small").attr("src") + "') no-repeat");

            $(".zoom-area").mousemove(function (e) {
                if (!sub_width && !sub_height) {
                    var image_object = new Image();
                    image_object.src = $(".small").attr("src");
                    sub_width = image_object.width;
                    sub_height = image_object.height;
                } else {
                    var magnify_position = $(this).offset();
                    var mx = e.pageX - magnify_position.left;
                    var my = e.pageY - magnify_position.top;

                    if (mx < $(this).width() && my < $(this).height() && mx > 0 && my > 0) {
                        $(".large").fadeIn(100);  // Show magnifier
                    } else {
                        $(".large").fadeOut(100);  // Hide magnifier when out of bounds
                    }

                    if ($(".large").is(":visible")) {

                        var zoomFactor = 1;

                        var rx = Math.round(mx / $(".small").width() * sub_width - $(".large").width() / 2) * -1 * zoomFactor;
                        var ry = Math.round(my / $(".small").height() * sub_height - $(".large").height() / 2) * -1 * zoomFactor;

                        var bgp = rx + "px " + ry + "px";
                        var px = mx - $(".large").width() / 2;
                        var py = my - $(".large").height() / 2;

                        $(".large").css({ left: px, top: py, backgroundPosition: bgp });
                    }
                }
            });

            // Hide magnifier when leaving the zoom area
            $(".zoom-area").mouseleave(function () {
                $(".large").fadeOut(100); // Hide magnifier
                $(".zoom-area").css("cursor", "default");
            });

            // Show cursor as none inside zoom area
            $(".zoom-area").mouseenter(function () {
                $(".zoom-area").css("cursor", "none");
            });
        }

        // Initialize magnifier on page load (if already visible content)
        initializeMagnifier();

        // Reinitialize region details event listeners
        function reinitializeRegionDetailsScripts() {
            const productLinks = document.querySelectorAll('.product-link-map');

            productLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Get selected category ID
                    const categoryId = this.getAttribute('data-category');
                    const regionName = document.getElementById('region_name').value;

                    fetch(`/products/filter/${categoryId}/${regionName}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json(); // Parse the JSON response
                        })
                        .then(data => {
                            // Get the product list container
                            const productList = document.getElementById('product-list');

                            // Clear current products
                            productList.innerHTML = '';

                            // Check if the data array is empty
                            if (data.length === 0) {
                                productList.innerHTML = `
                                    <div class="text-center">
                                        <p class ="card-text">No current products listed</p>
                                    </div>
                                `;
                            } else {
                                let productContent = `<div class="d-flex flex-wrap justify-content-start" style="padding:0 20px;">`;

                                data.forEach(product => {
                                    const productItem = `
                                        <a href="/product/${product.id}" class="text-decoration-none product-item" data-category="${product.category_id}" style="color: inherit;">
                                            <div class="product-item p-1">
                                                <div class="card product-card product-card-hover" style="width: 9rem; border: 2px solid transparent; transition: transform 0.3s, border-color 0.3s; position: relative;">
                                                    <img loading="lazy" src="${product.image_url}" class="card-img-top" alt="${product.name}" style="width: 100%; height: 110px; object-fit: cover;">
                                                    <div class="card-body text-center" style="padding: 10px 0;">
                                                        <h6 class="card-title" style="font-size: 0.85rem; font-weight: bold;">${product.name}</h6>
                                                        <p class="card-text" style="font-size: 12px; color: #555;"><strong>₱${product.price}</strong></p>
                                                        <p class="card-text" style="font-size: 11px; color: #555;">No reviews</p>
                                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                                            <a href="#" class="btn btn-custom btn-sm add-to-cart" data-product-id="${product.id}" style="font-size: 12px;">
                                                                <i class="fas fa-shopping-cart" style="margin-right: 4px;"></i> Add to Cart
                                                            </a>
                                                            <a href="#" class="btn btn-outline-danger btn-sm" style="width: 2rem; font-size: 12px;">
                                                                <i class="fas fa-heart"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>`;

                                    productContent += productItem;
                                });

                                productContent += '</div>';

                                productList.innerHTML = productContent;
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                        });

                    productLinks.forEach(link => link.classList.remove('active'));

                    this.classList.add('active');
                });
            });
        }
    });


</script>


@endsection

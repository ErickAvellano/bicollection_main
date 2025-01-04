@extends('Components.layout')

@section('styles')

<style>
    body{
        overflow: auto; /* Make sure this allows scrolling */
        height: auto;   /* Allow the content to define the height */
    }
    .secondary-menu,
    .form-control,
    .desktop-nav,
    .search-icon {
        display: none;
    }
    header {
            background-color: #228b22;
            padding: 10px 70px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #logo {
            max-height: 60px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            border-radius: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            flex-grow: 1;
        }

        .search-bar button {
            background-color: #ffd700;
            border: none;
            border-radius: 20px;
            padding: 10px;
            cursor: pointer;
        }

        .search-bar button img {
            width: 20px;
        }

        main {
            padding: 80px;
            text-align: center;
            background-image: url({{ asset('images/assets/start-selling/startsellingbg.png') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height:350px;
        }

        p {
            font-size: 1.2em;
            color: #555;
        }

        .cta-btn {
            background-color: #ffd700;
            color: #000;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .cta-btn:hover {
            background-color: #ffcc00;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .tabs {
            display: flex;
            justify-content: center;
            background-color: #fff;
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
        }

        .tabs a {
            text-decoration: none;
            color: #333;
            margin: 0 30px;
            font-size: 1.2em;
            padding-bottom: 10px;
            position: relative;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .tabs a.active {
            font-weight: bold;
            color: #228b22; /* Highlight color */
        }


        .tabs a:hover {
            color: #228b22;
            font-weight: bold;
        }

        .tabs a:hover::after {
            transform: scaleX(1);
        }

        .tab-content {
            display: none;
            padding: 50px 0;
            background-color: #ffffff;
            text-align: center;
        }

        .tab-content.active {
            display: block;
        }

        .tab-content h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .tab-content p {
            color: #777;
        }

        .feature-cards {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }

        .feature-card {
            width: 30%;
            text-align: center;
        }

        .feature-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #555;
        }

        #how-it-works {
            padding: 60px 0;
            background-color: #ffffff;
            text-align: center;
        }

        #how-it-works h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        #how-it-works p {
            color: #777;
            margin-bottom: 40px;
        }

        .how-it-works-cards {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .how-it-works-card {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            transition: transform 0.2s;
        }

        .how-it-works-card:hover {
            transform: scale(1.02);
        }

        .how-it-works-content {
            margin-right: 20px;
            text-align: left;
        }

        .how-it-works-card img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .contact-section {
            padding: 60px;
            background-image: url({{ asset('images/assets/start-selling/map.png') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
        }

        .contact-section h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #000000;
        }

        .contact-section p {
            color: #000000;
            margin-bottom: 40px;
        }

        .contact-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .form-field {
            flex: 1 1 35%;
            min-width: 250px;
        }

        .form-field input,
        .form-field textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 1em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .form-field input:focus,
        .form-field textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .form-field textarea {
            height: 150px;
            resize: none;
        }

        .submit-btn {
            background-color: #ffd700;
            color: #000;
            padding: 10px 15px;
            font-size: 1.2em;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #ffcc00;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

</style>

@endsection

@section('content')
<main>
    <h1>Showcase and sell your unique Bicol products with ease!</h1>
    <p class="mb-4">Crafted for your business success.</p>
    <a href="{{ route('merchant.register') }}" class="cta-btn">Get Started Now</a>
</main>

<nav class="tabs">
    <a href="#" onclick="openTab(event, 'benefits')" class="active">Benefits</a>
    <a href="#" onclick="openTab(event, 'how-it-works')">How It Works</a>
    <a href="#" onclick="openTab(event, 'faq')">FAQ</a>
</nav>

<section id="benefits" class="tab-content active">
    <h2>Benefits</h2>
    <p>Discover the benefits of our platform.</p>
    <div class="feature-cards">
        <div class="feature-card">
            <img src="{{ asset('images/assets/start-selling/1.png')}}" alt="Benefit 1 Icon">
            <h3>Start Selling with Ease</h3>
            <p>Focus on showcasing your unique Bicol products while we handle the rest. BiCollection takes care of the technical details, so you can concentrate on growing your business.</p>
        </div>
        <div class="feature-card">
            <img src="{{ asset('images/assets/start-selling/2.png')}}" alt="Benefit 2 Icon">
            <h3>Boost Your Online Presence</h3>
            <p>We help you create a stunning profile page that highlights your products, making it easier for customers to find and purchase from you.</p>
        </div>
        <div class="feature-card">
            <img src="{{ asset('images/assets/start-selling/3.png')}}" alt="Benefit 3 Icon">
            <h3>Connect with Customers</h3>
            <p>Build lasting relationships with your customers through our platform, ensuring their satisfaction and loyalty.</p>
        </div>
    </div>
</section>

<section id="how-it-works" class="tab-content">
    <h2>How It Works</h2>
    <p>Our simple four-step process.</p>
    <div class="how-it-works-cards">
        <div class="how-it-works-card">
            <img src="{{ asset('images/assets/start-selling/REGISTER.png')}}" alt="Step 1">
            <div class="how-it-works-content">
                <h3>Step One</h3>
                <p>Register for an account and create your profile.</p>
            </div>
        </div>
        <div class="how-it-works-card">
            <img src="{{ asset('images/assets/start-selling/ADD LIST.png')}}" alt="Step 2">
            <div class="how-it-works-content">
                <h3>Step Two</h3>
                <p>Add your products and details to attract customers.</p>
            </div>
        </div>
        <div class="how-it-works-card">
            <img src="{{ asset('images/assets/start-selling/SELL.png')}}" alt="Step 3">
            <div class="how-it-works-content">
                <h3>Step Three</h3>
                <p>Start selling and grow your business!</p>
            </div>
        </div>
        <div class="how-it-works-card">
            <img src="{{ asset('images/assets/start-selling/EARN.png')}}" alt="Step 4">
            <div class="how-it-works-content">
                <h3>Step Four</h3>
                <p>Earn and grow your business!</p>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="tab-content">
    <h2>FAQ</h2>
    <p>Find answers to your questions here.</p>
    <p>Contact us for more inquiries!</p>
</section>

{{-- <section id="contact" class="contact-section">
    <h2>CONTACT US</h2>
    <p>Send us a message and we will get back to you soon!</p>
    <div class="contact-form">
        <div class="form-field">
            <input type="text" placeholder="Your Name" required>
        </div>
        <div class="form-field">
            <input type="email" placeholder="Your Email" required>
        </div>
        <div class="form-field">
            <input type="text" placeholder="Contact Number" required>
        </div>
        <div class="form-field">
            <input type="text" placeholder="Purpose of Contact" required>
        </div>
        <div class="form-field" style="width: 90%;">
            <textarea placeholder="Write your message within 500 characters" required></textarea>
        </div>
    </div>
    <button class="submit-btn">SEND MESSAGE</button>
</section>
<footer class="text-center p-0 m-0 fc-white" style="background-color:#333;">
    <p class="p-0 m-0" style="color:white; font-size:12px;">&copy;2024 BiCollection. All rights reserved.</p>
</footer> --}}
@include('Components.footer')
@endsection

@section('scripts')
<script>
    function openTab(event, tabName) {
        // Get all tab links and remove the 'active' class
        let tabLinks = document.querySelectorAll('.tabs a');
        tabLinks.forEach(function(link) {
            link.classList.remove('active');
        });

        // Add 'active' class to the clicked tab
        event.currentTarget.classList.add('active');

        // You can now show/hide the corresponding tab content based on the tabName
        // Example of showing the content (assuming the content divs are visible or hidden by some other styles)
        let tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(function(content) {
            content.style.display = 'none'; // Hide all content
        });

        let activeTabContent = document.getElementById(tabName);
        if (activeTabContent) {
            activeTabContent.style.display = 'block'; // Show the selected tab content
        }
    }

</script>
@endsection

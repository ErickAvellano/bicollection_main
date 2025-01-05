@extends('Components.layout')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body, html {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .secondary-menu,
        .form-control,
        .desktop-nav,
        .search-icon {
            display: none;
        }

        .terms-container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .terms-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .terms-content {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .terms-content h2 {
            font-size: 22px;
            font-weight: 600;
            color: #222;
            margin-top: 20px;
        }

        .terms-content p {
            margin-bottom: 16px;
        }

        .terms-content ul {
            margin: 0;
            padding: 0;
            list-style-type: disc;
            margin-left: 20px;
        }

        .terms-content ul li {
            margin-bottom: 8px;
        }
        .footer-logo {
            max-height: 70px;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    
    <div class="terms-container mt-3">
        <div class="text-center">
            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="" class="footer-logo">
        </div>
        <h1 class="terms-title">Terms and Conditions</h1>
       
        <div class="terms-content">
            <p>Welcome to BiCollection! These Terms and Conditions ("Terms") govern your use of our web application (the "App") and the services offered by [Your Business Name] ("we," "us," or "our") through the App. By accessing or using the App, you agree to be bound by these Terms. If you disagree with any part of the Terms, then you may not access or use the App.</p>

            <h2>1. User Accounts</h2>
            <p>You may be required to create an account to access certain features of the App. You are responsible for maintaining the confidentiality of your account information, including your username and password. You are also responsible for all activities that occur under your account.</p>

            <h2>2. Products and Services</h2>
            <p>We offer a variety of native products from the Bicol Region through the App. We strive to provide accurate information about our products, but we cannot guarantee that all information is error-free. We reserve the right to change product offerings and prices at any time without prior notice.</p>

            <h2>3. Orders and Payment</h2>
            <p>To place an order, you must be at least 18 years old and have a valid payment method. We accept various payment methods as listed on the App. Your order will be confirmed once payment is authorized.</p>

            <h2>4. Delivery</h2>
            <p>We currently deliver within the Bicol Region only. Delivery fees will be calculated at checkout based on your location and order weight. We will use commercially reasonable efforts to deliver your order within the estimated timeframe, but we cannot guarantee specific delivery dates.</p>

            <h2>5. Returns and Refunds</h2>
            <p>We accept returns for unused and unopened items within [Number] days of delivery. Please refer to our Return Policy on the App for further details. Refunds will be issued to the original payment method used for the purchase.</p>

            <h2>6. Intellectual Property</h2>
            <p>The App and its content, including all logos, trademarks, and copyrights, are the property of [Your Business Name] or its licensors. You may not use any of these materials without our prior written consent.</p>

            <h2>7. Disclaimer</h2>
            <p>The App and its contents are provided "as is" and without warranties of any kind, express or implied. We disclaim all warranties, including, but not limited to, warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>

            <h2>8. Limitation of Liability</h2>
            <p>We will not be liable for any damages arising out of your use of the App, including, but not limited to, direct, indirect, incidental, consequential, or punitive damages.</p>

            <h2>9. Governing Law</h2>
            <p>These Terms shall be governed by and construed in accordance with the laws of the Philippines.</p>

            <h2>10. Dispute Resolution</h2>
            <p>Any dispute arising out of or relating to these Terms will be resolved by [Dispute Resolution Method, e.g., arbitration].</p>

            <h2>11. Termination</h2>
            <p>We may terminate your access to the App for any reason, at any time, without notice.</p>

            <h2>12. Entire Agreement</h2>
            <p>These Terms constitute the entire agreement between you and us regarding your use of the App.</p>

            <h2>13. Changes to the Terms</h2>
            <p>We may revise these Terms at any time by posting the revised Terms on the App. You are expected to review the Terms regularly so that you are aware of any changes. By continuing to access or use the App after the revised Terms become effective, you agree to be bound by the revised Terms.</p>

            <h2>14. Contact Us</h2>
            <p>If you have any questions about these Terms, please contact us via <a href="mailto:bicollection.noreply@gmail.com">bicollection.noreply@gmail.com</a></p>
        </div>
    </div>
@include('Components.supporticon')
@include('Components.footer')
@endsection

@section('scripts')
@endsection

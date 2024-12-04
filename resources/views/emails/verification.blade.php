<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .email-content {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
            border-collapse: collapse;
        }
        .email-header {
            padding: 20px;
            background-color: #228b22;
            color: #ffffff;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .email-body {
            padding: 20px;
            text-align: center;
        }
        .email-body p {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
        }
        .verification-code {
            font-size: 20px;
            font-weight: bold;
        }
        .verify-button {
            display: inline-block;
            font-size: 16px;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 18px;
            background-color: #28a745;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            <div class="email-header">
                <h1>Email Verification Code</h1>
            </div>
            <div class="email-body">
                <p style='font-size: 20px; font-family: Arial, sans-serif; color: #333333; margin-bottom: 20px; font-weight: bold'>Welcome to BiCollection!</p>
                <p style='font-size: 16px; font-family: Arial, sans-serif; color: #333333; margin-bottom: 30px;'>An E-Commerce Web-based Application for Native Products in Bicol Region</p>
                <p>Your verification code is:</p>
                <p class="verification-code">{{ $otp }}</p>
                <p>Please enter this code to verify your email address.</p>
            </div>
        </div>
    </div>
</body>
</html>

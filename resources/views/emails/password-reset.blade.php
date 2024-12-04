<!-- resources/views/emails/password-reset.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
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
        .reset-button {
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
                <h1>Reset Your Password</h1>
            </div>
            <div class="email-body">
                <p style="font-size: 20px; font-family: Arial, sans-serif; color: #333333; margin-bottom: 20px;">Hi,</p>
                <p style="font-size: 16px; font-family: Arial, sans-serif; color: #333333; margin-bottom: 30px;">
                    You are receiving this email because we received a password reset request for your account.
                </p>
                <p style="font-size: 16px; font-family: Arial, sans-serif; color: #333333; margin-bottom: 20px;">
                    Click the button below to reset your password:
                </p>
                <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
                <p style="font-size: 16px; font-family: Arial, sans-serif; color: #fafafa; margin-top: 20px;">
                    This password reset link will expire in 60 minutes.
                </p>
                <p style="font-size: 16px; font-family: Arial, sans-serif; color: #333333; margin-top: 10px;">
                    If you did not request a password reset, no further action is required.
                </p>
                <p style="font-size: 16px; font-family: Arial, sans-serif; color: #333333; margin-top: 20px;">
                    Best regards,
                </p>
                <p style="font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; color: #333333;">
                 {{ config('app.name') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>

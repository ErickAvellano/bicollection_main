<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Declined</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #228b22;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            margin: 8px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <h1>Order Declined</h1>

        <!-- Body -->
        <p>Dear {{ $order->customer->name }},</p>
        <p>We regret to inform you that your order <strong>#{{ $order->order_id }}</strong> has been declined.</p>
        <p>If you have any questions or need further assistance, please do not hesitate to contact our support team.</p>
        <p>We apologize for any inconvenience this may have caused.</p>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} BiCollection. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

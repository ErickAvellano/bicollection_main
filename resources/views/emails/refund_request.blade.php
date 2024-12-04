<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request Notification</title>
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
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            padding: 20px;
            background-color: #d9534f;
            color: #ffffff;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
        }
        .email-body p {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
        }
        .email-footer {
            padding: 10px;
            text-align: center;
            color: #888888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            <div class="email-header">
                <h1>Refund Request</h1>
            </div>
            <div class="email-body">
                <p>Dear {{ $merchant->username }},</p>
                <p style="margin-top:20px;">A customer has requested a refund for order  <strong>#{{ $order->order_id }}</strong>.</p>
                <p style="margin-top:20px;">
                    &emsp;&emsp;Please review the request and verify the reference number in the 
                    <strong>"Request Refund"</strong> section of the Order panel. Once you’ve checked, 
                    please take appropriate action to process the refund as requested.
                </p>
                <p>Thank you!</p>
                <p>Best Regards,<br>BiCollection Team</p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} BiCollection. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

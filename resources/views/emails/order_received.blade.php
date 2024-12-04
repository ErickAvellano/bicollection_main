<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successfully Received</title>
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
            background-color: #228b22;
            color: #ffffff;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
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
                <h1>{{ $isMerchant ? 'Order Delivered & Received' : 'Order Received' }}</h1>
            </div>
            <div class="email-body">
                <p>Dear {{ $isMerchant ? $order->merchant->name : $order->customer->name }},</p>

                <!-- Merchant Specific Message -->
                @if ($isMerchant)
                    <p>Your order #{{ $order->order_id }} has been successfully delivered and received by the customer!</p>
                    <p>The customer has confirmed receipt of the order, and the sale details are being saved in our records.</p>
                    <p>Here are the details of the order:</p>
                @else
                    <!-- Customer Specific Message -->
                    <p>Your order #{{ $order->order_id }} has been successfully received!</p>
                    <p>Thank you for shopping with us. We hope you enjoy your purchase.</p>
                @endif

                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr>
                            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Product Name</th>
                            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Quantity</th>
                            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $orderItem)
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">{{ $orderItem->product->product_name }}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">{{ $orderItem->quantity }}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${{ number_format($orderItem->quantity * $orderItem->product->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($isMerchant)
                    <p>The sale data for this order will now be saved to our records.</p>
                    <p>Thank you for your continued partnership with BiCollection!</p>
                @else
                    <p>We will notify you once your order is shipped. Thank you for shopping with us!</p>
                @endif

                <p>Best Regards,<br>BiCollection Team</p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} BiCollection. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

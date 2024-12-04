<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification - BiCollection</title>
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
        }
        h2 {
            color: #228b22;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .order-summary {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .order-item {
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Order Alert</h2>
        <p>Dear {{ $merchantName }},</p>
        <p>You have received a payment for Order ID: <strong>{{ $order->order_id }}</strong>. Please review and confirm the payment, then prepare the order for delivery.</p>
        
        <div class="order-summary">
            <h3>Order Details</h3>
            @foreach($orderItems as $item)
                <div class="order-item">
                    <p><strong>{{ $item->product_name }}</strong> (x{{ $item->quantity }}) - ₱{{ number_format($item->subtotal, 2) }}</p>
                    <p><small>Variation: {{ $item->variation_name ?? 'N/A' }}</small></p>
                </div>
            @endforeach
            <p><strong>Total Amount Paid:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
        </div>

        <p>Thank you for being a valued partner of BiCollection!</p>
        <p>Sincerely, <br>BiCollection Team</p>
    </div>
</body>
</html>

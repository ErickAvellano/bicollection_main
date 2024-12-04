<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation - BiCollection</title>
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
        .order-details {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Confirmation</h2>
        <p>Dear {{ $customerName }},</p>
        <p>We are pleased to inform you that your payment for Order ID: <strong>{{ $order->order_id }}</strong> has been successfully received.</p>
        <p><strong>The merchant is currently verifying your payment.</strong> Once your payment is verified, the order will be prepared for delivery.</p>

        <div class="order-details">
            <h3>Shipping Details</h3>
            <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
            <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Contact Number:</strong> {{ $order->contact_number }}</p>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            @foreach($orderItems as $item)
            <div class="order-item">
                <p><strong>{{ $item['product_name'] ?? 'Product Name Unavailable' }}</strong> (x{{ $item['quantity'] ?? 0 }}) - ₱{{ number_format($item['subtotal'] ?? 0, 2) }}</p>
                <p><small>Variation: {{ $item['variation_name'] ?? 'N/A' }}</small></p>
            </div>
            @endforeach

            <p><strong>Total Amount Paid:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
        </div>

        <p>Thank you for shopping with BiCollection! If you have any questions, please feel free to contact us.</p>
        <p>Sincerely, <br>BiCollection Team</p>
    </div>
</body>
</html>

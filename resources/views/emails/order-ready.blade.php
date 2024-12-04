<!DOCTYPE html>
<html>
<head>
    <title>Order Ready for Shipping - BiCollection</title>
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
        .tracking-info {
            margin-top: 20px;
            padding: 20px;
            background-color: #e8f5e9;
            border-radius: 8px;
            color: #2e7d32;
        }
        .tracking-info a {
            color: #228b22;
            text-decoration: none;
            font-weight: bold;
        }
        .tracking-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Order is Ready for Shipping</h2>
        <p>Dear {{ $customerName }},</p>
        <p>Your order with Order ID: <strong>{{ $order->order_id }}</strong> is now ready for shipping.</p>

        <div class="tracking-info">
            <h3>Track Your Order</h3>
            <p>To keep track of your order, please copy the Order ID above and visit our . Input your Order ID in the provided field and click <strong>Search</strong>.</p>
            <p>We will update you on current location of your shipment.</p>
        </div>

        <p>Thank you for shopping with BiCollection! If you have any questions, please feel free to contact us.</p>
        <p>Sincerely, <br>BiCollection Team</p>
    </div>
</body>
</html>

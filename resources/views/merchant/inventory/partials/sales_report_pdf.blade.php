<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <a href="{{ route('sales_report.pdf') }}" class="btn">Download Sales Report as PDF</a>
    <table>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Total Price</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $sale)
                <tr>
                    <td>{{ $sale->sales_id }}</td>
                    <td>{{ $sale->product->product_name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>₱{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('F j, Y, g:i a') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

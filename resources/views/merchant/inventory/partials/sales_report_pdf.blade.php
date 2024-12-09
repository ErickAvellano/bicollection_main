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
    <h2>Shop Name: {{ $merchantShopName }}</h2>
    <h3>Registered Owner: {{ $salesData->isNotEmpty() ? $salesData->first()->merchant->firstname . ' ' . $salesData->first()->merchant->lastname : 'No owner found' }}</h3>
    <h3>Report Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</h3>

    <h1>Sales Report Chart</h1>
    <h2>Sales per Day</h2>
    <img src="{{ $salesPerDayChartPath }}" alt="Sales per Day Chart" width="100%">

    <h2>Sales per Month</h2>
    <img src="{{ $salesPerMonthChartPath }}" alt="Sales per Month Chart" width="100%">

    <h2>Sales per Year</h2>
    <img src="{{ $salesPerYearChartPath }}" alt="Sales per Year Chart" width="100%">

    <h2>Popular Products</h2>
    <img src="{{ $popularProductsChartPath }}" alt="Popular Products Chart" width="100%">

    <h2>Category Sales Trends</h2>
    <img src="{{$categorySalesTrendChartPath}}" alt="Category Sales Trend Chart" width="100%">

    <h1>Sales Report</h1>
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
            @if($salesData->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No current data</td>
                </tr>
            @else
                @foreach($salesData as $sale)
                    <tr>
                        <td>{{ $sale->sales_id }}</td>
                        <td>
                            @if($sale->product)
                                {{ $sale->product->product_name }}
                            @else
                                <span class="text-danger">No product found</span>
                            @endif
                        </td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱ {{ number_format($sale->total_price, 2) }}</td>
                        <td>{{ $sale->sale_date }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }

        /* Custom badge styles */
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
        }

        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; }
        .bg-primary { background-color: #007bff; }
        .bg-info { background-color: #17a2b8; }
        .bg-secondary { background-color: #6c757d; }
    </style>
</head>
<body>
    <!-- Shop Information Section -->
    <h1>Order Report</h1>

    <h2>Shop Name: {{ $merchantShopName }}</h2>
    <h3>Registered Owner: {{ $orders->isNotEmpty() ? $orders->first()->merchant->firstname . ' ' . $orders->first()->merchant->lastname : 'No owner found' }}</h3>
    <h3>Report Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</h3>

    <h2>Revenue</h2>
    <p>Completed Revenue: {{ number_format($completedRevenue, 2) }}</p>
    <p>Total Revenue (Pending, Ready, Completed Orders):
        {{ number_format($orders->whereIn('order_status', ['pending', 'ready', 'completed'])->sum('total_amount'), 2) }}
    </p>

    <h3>Revenue by Payment Method</h3>
    <ul>
        <li>Gcash:
            {{ number_format($orders->filter(function ($order) {
                return $order->payment && $order->payment->payment_method == 'GCash';
            })->sum(function ($order) {
                return $order->payment->total_amount;
            }), 2) }}
        </li>

        <li>Cash on Delivery:
            {{ number_format($orders->filter(function ($order) {
                return $order->payment && $order->payment->payment_method == 'COD';
            })->sum(function ($order) {
                return $order->payment->total_amount;
            }), 2) }}
        </li>
    </ul>

    <h2>Order Statuses</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderStatuses as $status => $count)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Charts</h2>
    <h3>Order Status Chart</h3>
    <img src="{{ $orderStatusChartPath }}" alt="Order Status Chart" width="600">

    <h3>Refund Status Chart</h3>
    <img src="{{ $refundStatusChartPath }}" alt="Refund Status Chart" width="600">

    <h2>Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            @if($orders->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No current data</td>
                </tr>
            @else
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>
                            <span class="badge 
                                @if($order->order_status === 'completed') bg-success
                                @elseif($order->order_status === 'canceled') bg-danger
                                @elseif($order->order_status === 'pending') bg-warning
                                @elseif($order->order_status === 'to-pay') bg-primary
                                @elseif($order->order_status === 'ready') bg-info
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>{{ $order->customer ? $order->customer->username : 'Guest' }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>

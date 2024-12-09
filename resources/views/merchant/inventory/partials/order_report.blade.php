<div class="container mt-4 mb-5">
    <!-- Order Report Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Order Report</h3>
        <button id="downloadOrderReportBtn" class="btn" style="background-color: #228b22; color: white;">
            Download Order Report as PDF
        </button>
        
    </div>

    <!-- Chart Overview -->
    <div class="row g-4">
        <!-- Order Status Overview -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header" style="background-color: #228b22; color: white;">
                    <h5 class="card-title mb-0 text-center">Order Status Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="orderStatusChartContainer">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Status Overview -->
        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-center">Refund Status Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="refundStatusChartContainer">
                        <canvas id="refundStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <h3 class="mt-5 mb-3">Order History</h3>
    <table id="ordersTable" class="table table-bordered table-hover">
        <thead style="background-color: #228b22; color: white;">
            <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Status</th>
                <th scope="col">Customer</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Order Date</th>
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
</div>


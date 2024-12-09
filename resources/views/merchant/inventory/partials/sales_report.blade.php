<div class="d-flex justify-content-between align-items-center">
    <h3>Sales Report</h3>
    <a id="downloadSalesReportBtn" class="btn btn-custom">Download Sales Report as PDF</a>
</div>




<div class="container mt-3 mb-5">
    <div class="row g-4">
        <!-- Sales Per Day Chart -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-center">Sales Per Day</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="salesPerDayChartContainer">
                        <canvas id="salesPerDayChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Per Month Chart -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-center">Sales Per Month</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="salesPerMonthChartContainer">
                        <canvas id="salesPerMonthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Per Year Chart -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0 text-center">Sales Per Year</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="salesPerYearChartContainer">
                        <canvas id="salesPerYearChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <!-- Popular Products Chart -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0 text-center">Popular Products</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" id="popularProductsChartContainer">
                        <canvas id="popularProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder for Future Chart -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-center">Category Sales Trends</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categorySalesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped">
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

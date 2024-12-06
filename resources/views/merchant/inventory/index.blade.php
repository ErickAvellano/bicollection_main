@extends('Components.layout')

@section('styles')
    <style>
        .nav-pills, .search-control, .search-icon {
            display: none;
        }

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
<div class="container mt-3">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">BiCollection</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mystore') }}">My Store</a></li>
        <li class="breadcrumb-item active">Inventory Management</li>
    </ol>

    <h3>Inventory Management Dashboard</h3>

    <!-- Sales Analytics - Graphs -->
    <div class="chart-container" id="salesPerDayChartContainer">
        <canvas id="salesPerDayChart"></canvas>
    </div>

    <div class="chart-container" id="salesPerMonthChartContainer">
        <canvas id="salesPerMonthChart"></canvas>
    </div>

    <div class="chart-container" id="salesPerYearChartContainer">
        <canvas id="salesPerYearChart"></canvas>
    </div>

    <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
        <!-- Tab buttons here -->
    </ul>

    <div class="tab-content mt-3" id="inventoryTabsContent">
        <div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            @include('merchant.inventory.partials.inventory')
        </div>

        <div class="tab-pane fade" id="sales-report" role="tabpanel" aria-labelledby="sales-report-tab">
            @include('merchant.inventory.partials.sales_report')
        </div>

        <div class="tab-pane fade" id="order-report" role="tabpanel" aria-labelledby="order-report-tab">
            @include('merchant.inventory.partials.order_report')
        </div>

        <div class="tab-pane fade" id="stock-alerts" role="tabpanel" aria-labelledby="stock-alerts-tab">
            @include('merchant.inventory.partials.stock_alerts')
        </div>

        <div class="tab-pane fade" id="product-categories" role="tabpanel" aria-labelledby="product-categories-tab">
            @include('merchant.inventory.partials.product_categories')
        </div>
    </div>
</div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var salesPerDayChart, salesPerMonthChart, salesPerYearChart;

        // Function to load all charts
        function loadCharts() {
            // Sales per Day Chart
            var salesPerDayCtx = document.getElementById('salesPerDayChart').getContext('2d');
            salesPerDayChart = new Chart(salesPerDayCtx, {
                type: 'line',
                data: {
                    labels: @json($salesPerDay->pluck('date')),
                    datasets: [{
                        label: 'Sales per Day (₱)',
                        data: @json($salesPerDay->pluck('total_sales')),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    }]
                }
            });

            // Sales per Month Chart
            var salesPerMonthCtx = document.getElementById('salesPerMonthChart').getContext('2d');
            salesPerMonthChart = new Chart(salesPerMonthCtx, {
                type: 'bar',
                data: {
                    labels: @json($salesPerMonthData).map(function(item) { return item.label; }), // Map labels correctly
                    datasets: [{
                        label: 'Sales per Month (₱)',
                        data: @json($salesPerMonthData).map(function(item) { return item.total_sales; }), // Map total_sales correctly
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Sales per Year Chart
            var salesPerYearCtx = document.getElementById('salesPerYearChart').getContext('2d');
            salesPerYearChart = new Chart(salesPerYearCtx, {
                type: 'pie',
                data: {
                    labels: @json($salesPerYear->pluck('year')),
                    datasets: [{
                        label: 'Sales per Year (₱)',
                        data: @json($salesPerYear->pluck('total_sales')),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        }

        // Add event listener to the Sales Report tab to load the charts when clicked
        document.getElementById('sales-report-tab').addEventListener('click', function() {
            if (!salesPerDayChart && !salesPerMonthChart && !salesPerYearChart) {
                loadCharts(); // Only load charts when Sales Report tab is clicked
            }
        });
    </script>
@endsection


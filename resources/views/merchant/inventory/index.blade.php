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
        .pagination .page-item.active .page-link {
            background-color: #228b22; /* Change to your desired color */
            border-color: #228b22;
            color: white;
        }

        .pagination .page-link {
            color: #228b22; /* Change link color */
        }

        .pagination .page-link:hover {
            background-color: #228b22; /* Hover background */
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc; /* Disabled color */
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

    <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="true">Inventory</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sales-report-tab" data-bs-toggle="tab" data-bs-target="#sales-report" type="button" role="tab" aria-controls="sales-report" aria-selected="false">Sales Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="order-report-tab" data-bs-toggle="tab" data-bs-target="#order-report" type="button" role="tab" aria-controls="order-report" aria-selected="false">Order Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="stock-alerts-tab" data-bs-toggle="tab" data-bs-target="#stock-alerts" type="button" role="tab" aria-controls="stock-alerts" aria-selected="false">Stock Alerts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="product-categories-tab" data-bs-toggle="tab" data-bs-target="#product-categories" type="button" role="tab" aria-controls="product-categories" aria-selected="false">Product Categories</button>
        </li>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('nav') || 'inventory';

            // Always activate the 'inventory' tab
            const inventoryTabElement = document.querySelector('#inventory-tab');
            if (inventoryTabElement) {
                inventoryTabElement.classList.add('active');
                inventoryTabElement.setAttribute('aria-selected', 'true');
                const inventoryTabContent = document.querySelector(inventoryTabElement.getAttribute('data-bs-target'));
                inventoryTabContent.classList.add('show', 'active');
            }

            // Activate the additional tab specified in the 'nav' parameter
            if (activeTab !== 'inventory') {
                const activeTabElement = document.querySelector(`#${activeTab}-tab`);
                if (activeTabElement) {
                    activeTabElement.classList.add('active');
                    activeTabElement.setAttribute('aria-selected', 'true');
                    const activeTabContent = document.querySelector(activeTabElement.getAttribute('data-bs-target'));
                    if (activeTabContent) {
                        activeTabContent.classList.add('show', 'active');
                    }
                }
            }

            // Add event listeners for tab switching without reloading
            document.querySelectorAll('#inventoryTabs .nav-link').forEach(tab => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default behavior

                    const selectedTab = tab.id.replace('-tab', '');

                    // Update tab activation
                    document.querySelectorAll('#inventoryTabs .nav-link').forEach(link => {
                        link.classList.remove('active');
                        link.setAttribute('aria-selected', 'false');
                    });

                    tab.classList.add('active');
                    tab.setAttribute('aria-selected', 'true');

                    // Show the correct tab content
                    document.querySelectorAll('.tab-pane').forEach(content => {
                        content.classList.remove('show', 'active');
                    });

                    const targetContent = document.querySelector(tab.getAttribute('data-bs-target'));
                    if (targetContent) {
                        targetContent.classList.add('show', 'active');
                    }

                    // Update the URL without refreshing
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('nav', selectedTab);
                    window.history.replaceState(null, '', newUrl.toString());
                });
            });
        });
    </script>

    <script>
        var salesPerDayChart, salesPerMonthChart, salesPerYearChart, popularProductsChart, categorySalesTrendChart;

        // Function to display "No Data" message
        function showNoDataMessage(chartCtx) {
            chartCtx.font = '16px Arial';
            chartCtx.textAlign = 'center';
            chartCtx.fillStyle = 'rgba(0, 0, 0, 0.5)';
            chartCtx.fillText('No data available', chartCtx.canvas.width / 2, chartCtx.canvas.height / 2);
        }

        // Function to load all charts
        function loadCharts() {
            // Sales per Day Chart
            var salesPerDayCtx = document.getElementById('salesPerDayChart').getContext('2d');
            var salesPerDayData = @json($salesPerDay->toArray() ?? []);
            if (Array.isArray(salesPerDayData) && salesPerDayData.length === 0) {
                showNoDataMessage(salesPerDayCtx);
            } else {
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
            }

            // Sales per Month Chart
            var salesPerMonthCtx = document.getElementById('salesPerMonthChart').getContext('2d');
            var salesPerMonthData = @json($salesPerMonthData->toArray() ?? []);
            if (Array.isArray(salesPerMonthData) && salesPerMonthData.length === 0) {
                showNoDataMessage(salesPerMonthCtx);
            } else {
                salesPerMonthChart = new Chart(salesPerMonthCtx, {
                    type: 'bar',
                    data: {
                        labels: salesPerMonthData.map(function(item) { return item.label; }),
                        datasets: [{
                            label: 'Sales per Month (₱)',
                            data: salesPerMonthData.map(function(item) { return item.total_sales; }),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }

            // Sales per Year Chart
            var salesPerYearCtx = document.getElementById('salesPerYearChart').getContext('2d');
            var salesPerYearData = @json($salesPerYear->toArray() ?? []);
            if (Array.isArray(salesPerYearData) && salesPerYearData.length === 0) {
                showNoDataMessage(salesPerYearCtx);
            } else {
                salesPerYearChart = new Chart(salesPerYearCtx, {
                    type: 'bar',
                    data: {
                        labels: salesPerYearData.map(function(item) { return item.year; }),
                        datasets: [{
                            label: 'Sales per Year (₱)',
                            data: salesPerYearData.map(function(item) { return item.total_sales; }),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }

            // Popular Products Chart
            var popularProductsCtx = document.getElementById('popularProductsChart').getContext('2d');
            var popularProductData = @json($popularProductData->toArray() ?? []);
            if (Array.isArray(popularProductData) && popularProductData.length === 0) {
                showNoDataMessage(popularProductsCtx);
            } else {
                popularProductsChart = new Chart(popularProductsCtx, {
                    type: 'bar',
                    data: {
                        labels: popularProductData.map(function(item) { return item.product_name; }),
                        datasets: [{
                            label: 'Popular Products (₱)',
                            data: popularProductData.map(function(item) { return item.total_sales; }),
                            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                            borderWidth: 1
                        }]
                    }
                });
            }

            // Category Sales Trend Chart
            var categorySalesCtx = document.getElementById('categorySalesTrendChart').getContext('2d');
            var categorySalesTrendData = @json($categorySalesTrend->toArray() ?? []);
            if (!categorySalesTrendData || Object.keys(categorySalesTrendData).length === 0) {
                showNoDataMessage(categorySalesCtx);
            } else {
                var firstCategorySales = [];
                var datasets = [];
                const colors = ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'];

                Object.keys(categorySalesTrendData).forEach(function(categoryKey, index) {
                    var trend = categorySalesTrendData[categoryKey];
                    var salesData = trend.sales.map(function(item) { return item.total_sales; });
                    var labels = trend.sales.map(function(item) { return item.label; });

                    if (firstCategorySales.length === 0) {
                        firstCategorySales = labels;
                    }

                    var colorIndex = index % colors.length;
                    var backgroundColor = colors[colorIndex];
                    var borderColor = backgroundColor.replace('0.2', '1');

                    datasets.push({
                        label: categoryKey,
                        data: salesData,
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 1,
                        fill: true
                    });
                });

                categorySalesTrendChart = new Chart(categorySalesCtx, {
                    type: 'bar',
                    data: {
                        labels: firstCategorySales,
                        datasets: datasets
                    }
                });
            }
        }
        document.getElementById('downloadSalesReportBtn').addEventListener('click', function () {
            // Convert the charts to base64 images
            const salesPerDayBase64 = salesPerDayChart.canvas.toDataURL('image/png');
            const salesPerMonthBase64 = salesPerMonthChart.canvas.toDataURL('image/png');
            const salesPerYearBase64 = salesPerYearChart.canvas.toDataURL('image/png');
            const popularProductsBase64 = popularProductsChart.canvas.toDataURL('image/png');
            const categorySalesBase64 = categorySalesTrendChart.canvas.toDataURL('image/png');

            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('sales_report.pdf') }}";  // Adjust the route to match your backend
            form.style.display = 'none';

            // Add CSRF token input
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);

            // Add base64 chart data as input
            const salesPerDayInput = document.createElement('input');
            salesPerDayInput.type = 'hidden';
            salesPerDayInput.name = 'salesPerDayChart';
            salesPerDayInput.value = salesPerDayBase64;
            form.appendChild(salesPerDayInput);

            const salesPerMonthInput = document.createElement('input');
            salesPerMonthInput.type = 'hidden';
            salesPerMonthInput.name = 'salesPerMonthChart';
            salesPerMonthInput.value = salesPerMonthBase64;
            form.appendChild(salesPerMonthInput);

            const salesPerYearInput = document.createElement('input');
            salesPerYearInput.type = 'hidden';
            salesPerYearInput.name = 'salesPerYearChart';
            salesPerYearInput.value = salesPerYearBase64;
            form.appendChild(salesPerYearInput);

            const popularProductsInput = document.createElement('input');
            popularProductsInput.type = 'hidden';
            popularProductsInput.name = 'popularProductsChart';
            popularProductsInput.value = popularProductsBase64;
            form.appendChild(popularProductsInput);

            const categorySalesInput = document.createElement('input');
            categorySalesInput.type = 'hidden';
            categorySalesInput.name = 'categorySalesTrendChart';
            categorySalesInput.value = categorySalesBase64;
            form.appendChild(categorySalesInput);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        });
        // Load all charts after the page loads
        window.onload = loadCharts;

    </script>





    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Convert Laravel collections to arrays
            const orderStatuses = @json($orderStatuses->toArray() ?? []);
            const refundStatuses = @json($refundStatuses->toArray() ?? []);

            // Function to display "No Data" message
            function showNoDataMessage(chartCtx) {
                chartCtx.font = '16px Arial';
                chartCtx.textAlign = 'center';
                chartCtx.fillStyle = 'rgba(0, 0, 0, 0.5)';
                chartCtx.fillText('No data available', chartCtx.canvas.width / 2, chartCtx.canvas.height / 2);
            }

            // Function to load the charts
            function loadCharts() {
                // Order Status Chart
                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
                if (Object.keys(orderStatuses).length === 0) {
                    showNoDataMessage(orderStatusCtx);
                } else {
                    new Chart(orderStatusCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(orderStatuses),
                            datasets: [{
                                label: 'Order Status Distribution',
                                data: Object.values(orderStatuses),
                                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }

                // Refund Status Chart
                const refundStatusCtx = document.getElementById('refundStatusChart').getContext('2d');
                if (Object.keys(refundStatuses).length === 0) {
                    showNoDataMessage(refundStatusCtx);
                } else {
                    new Chart(refundStatusCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(refundStatuses),
                            datasets: [{
                                label: 'Refund Status',
                                data: Object.values(refundStatuses),
                                backgroundColor: ['rgba(255, 159, 64, 0.2)', 'rgba(201, 203, 207, 0.2)'],
                                borderColor: ['rgba(255, 159, 64, 1)', 'rgba(201, 203, 207, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            }

            // Call the loadCharts function to render the charts
            loadCharts();

            // Download Order Report as PDF
            document.getElementById('downloadOrderReportBtn').addEventListener('click', function () {
                // Convert the charts to base64 images
                const orderStatusChartBase64 = document.getElementById('orderStatusChart').toDataURL('image/png');
                const refundStatusChartBase64 = document.getElementById('refundStatusChart').toDataURL('image/png');

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('order_report.pdf') }}";
                form.style.display = 'none';

                // Add CSRF token input
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);

                // Add base64 chart data as input
                const orderStatusInput = document.createElement('input');
                orderStatusInput.type = 'hidden';
                orderStatusInput.name = 'orderStatusChart';
                orderStatusInput.value = orderStatusChartBase64;
                form.appendChild(orderStatusInput);

                const refundStatusInput = document.createElement('input');
                refundStatusInput.type = 'hidden';
                refundStatusInput.name = 'refundStatusChart';
                refundStatusInput.value = refundStatusChartBase64;
                form.appendChild(refundStatusInput);

                // Add the orderStatuses and refundStatuses data
                const orderStatusesInput = document.createElement('input');
                orderStatusesInput.type = 'hidden';
                orderStatusesInput.name = 'orderStatuses';
                orderStatusesInput.value = JSON.stringify(orderStatuses);
                form.appendChild(orderStatusesInput);

                const refundStatusesInput = document.createElement('input');
                refundStatusesInput.type = 'hidden';
                refundStatusesInput.name = 'refundStatuses';
                refundStatusesInput.value = JSON.stringify(refundStatuses);
                form.appendChild(refundStatusesInput);

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Initialize the DataTable with sorting by the 5th column (Order Date) descending
            $('#ordersTable').DataTable({
                order: [[4, 'desc']], // Default sort by Order Date (5th column) descending
                columnDefs: [
                    {
                        orderable: true,
                        targets: '_all'  // Enable sorting for all columns
                    }
                ],
                responsive: true,  // Make the table responsive for different screen sizes
                pageLength: 10,  // Set the default number of rows per page
                lengthChange: false, // Disable length change dropdown
                autoWidth: false,  // Adjust column width automatically
                searching: true,  // Enable search functionality
                info: true,  // Display table info (e.g., showing 1 to 10 of 50 entries)
                language: {
                    search: "Filter orders:", // Customizing search label
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event listener for plus and minus buttons
            const quantityButtons = document.querySelectorAll('.quantity-btn');

            quantityButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Get the row (tr) element
                    const row = this.closest('tr');

                    // Find the visible quantity input
                    const visibleInput = row.querySelector('.quantity-input');

                    let quantity = parseInt(visibleInput.value);

                    // Increase or decrease quantity based on the action
                    if (this.dataset.action === 'increase') {
                        quantity++;
                    } else if (this.dataset.action === 'decrease' && quantity > 1) { // Prevent negative quantity
                        quantity--;
                    }

                    // Update the visible input value
                    visibleInput.value = quantity;
                });
            });

            // Optional: Form submission with AJAX
            document.getElementById('stock-form').addEventListener('submit', function(event) {
                event.preventDefault();  // Prevent default form submission

                let formData = new FormData(this);

                // Append CSRF token manually for the AJAX request
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);
                fetch("{{ route('update.stock') }}", {  // Ensure this is the correct route
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Stock quantities updated successfully!');
                    } else {
                        alert('Error updating stock quantities.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error updating the stock quantities.');
                });
            });
        });
    </script>







@endsection


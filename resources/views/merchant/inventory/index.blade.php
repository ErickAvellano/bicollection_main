@extends('Components.layout') <!-- Extend your main layout -->

@section('styles')
    <style>
        .nav-pills, .search-control, .search-icon{
            display: none;
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

    <!-- Tabs Navigation -->
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

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="inventoryTabsContent">
        <!-- Inventory Section -->
        <div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            @include('merchant.inventory.partials.inventory')
        </div>

        <!-- Sales Report Section -->
        <div class="tab-pane fade" id="sales-report" role="tabpanel" aria-labelledby="sales-report-tab">
            @include('merchant.inventory.partials.sales_report')
        </div>

        <!-- Order Report Section -->
        <div class="tab-pane fade" id="order-report" role="tabpanel" aria-labelledby="order-report-tab">
            @include('merchant.inventory.partials.order_report')
        </div>

        <!-- Stock Alerts Section -->
        <div class="tab-pane fade" id="stock-alerts" role="tabpanel" aria-labelledby="stock-alerts-tab">
            @include('merchant.inventory.partials.stock_alerts')
        </div>

        <!-- Product Categories Section -->
        <div class="tab-pane fade" id="product-categories" role="tabpanel" aria-labelledby="product-categories-tab">
            @include('merchant.inventory.partials.product_categories')
        </div>
    </div>
</div>



@endsection

@extends('Components.layout')

@section('styles')
    <style>
        .nav-pills, .search-control, .search-icon {
            display: none;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow:hidden;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        :root {
            --poppins: 'Poppins', sans-serif;
            --lato: 'Lato', sans-serif;

            --light: #F9F9F9;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        html {
            overflow-x: hidden;
        }

        body.dark {
            --light: #0C0C1E;
            --grey: #060714;
            --dark: #FBFBFB;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
        }

        /* CONTENT */
        #content {
            position: relative;
            width: 100%;
            transition: .3s ease;
        }

        /* NAVBAR */
        #content nav {
            height: 56px;
            background: var(--light);
            padding: 0 24px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
            font-family: var(--lato);
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        #content nav::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            bottom: -40px;
            left: 0;
            border-radius: 50%;
            box-shadow: -20px -20px 0 var(--light);
        }
        #content nav a {
            color: var(--dark);
        }
        #content nav .bx.bx-menu {
            cursor: pointer;
            color: var(--dark);
        }
        #content nav .nav-link {
            font-size: 16px;
            transition: .3s ease;
        }
        #content nav .nav-link:hover {
            color: var(--blue);
        }
        #content nav form {
            max-width: 400px;
            width: 100%;
            margin-right: auto;
        }
        #content nav form .form-input {
            display: flex;
            align-items: center;
            height: 36px;
        }
        #content nav form .form-input input {
            flex-grow: 1;
            padding: 0 16px;
            height: 100%;
            border: none;
            background: var(--grey);
            border-radius: 36px 0 0 36px;
            outline: none;
            width: 100%;
            color: var(--dark);
        }
        #content nav form .form-input button {
            width: 36px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--blue);
            color: var(--light);
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 0 36px 36px 0;
            cursor: pointer;
        }
        #content nav .notification {
            font-size: 20px;
            position: relative;
        }
        #content nav .notification .num {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--light);
            background: var(--red);
            color: var(--light);
            font-weight: 700;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #content nav .profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
        }
        #content nav .switch-mode {
            display: block;
            min-width: 50px;
            height: 25px;
            border-radius: 25px;
            background: var(--grey);
            cursor: pointer;
            position: relative;
        }
        #content nav .switch-mode::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            bottom: 2px;
            width: calc(25px - 4px);
            background: var(--blue);
            border-radius: 50%;
            transition: all .3s ease;
        }
        #content nav #switch-mode:checked + .switch-mode::before {
            left: calc(100% - (25px - 4px) - 2px);
        }

        /* MAIN */
        #content main {
            width: 100%;
            padding: 36px 24px;
            font-family: var(--poppins);
            max-height: calc(100vh - 56px);
            overflow-y: auto;
        }
        #content main .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-gap: 16px;
            flex-wrap: wrap;
        }
        #content main .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }
        #content main .head-title .left .breadcrumb {
            display: flex;
            align-items: center;
            grid-gap: 16px;
        }
        #content main .head-title .left .breadcrumb li {
            color: var(--dark);
        }
        #content main .head-title .left .breadcrumb li a {
            color: var(--dark-grey);
            pointer-events: none;
        }
        #content main .head-title .left .breadcrumb li a.active {
            color: var(--blue);
            pointer-events: unset;
        }
        #content main .head-title .btn-download {
            height: 36px;
            padding: 0 16px;
            border-radius: 36px;
            background: var(--blue);
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: 10px;
            font-weight: 500;
        }

        #content main .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            grid-gap: 24px;
            margin-top: 36px;
        }
        #content main .box-info li {
            padding: 24px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
        }
        #content main .box-info li .bx {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #content main .box-info li:nth-child(1) .bx {
            background: var(--light-blue);
            color: var(--blue);
        }
        #content main .box-info li:nth-child(2) .bx {
            background: var(--light-yellow);
            color: var(--yellow);
        }
        #content main .box-info li:nth-child(3) .bx {
            background: var(--light-orange);
            color: var(--orange);
        }
        #content main .box-info li .text h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }
        #content main .box-info li .text p {
            color: var(--dark);
        }

        #content main .table-data {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 24px;
            margin-top: 24px;
            width: 100%;
            color: var(--dark);
        }
        #content main .table-data > div {
            border-radius: 20px;
            background: var(--light);
            padding: 24px;
            overflow-x: auto;
        }
        #content main .table-data .head {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            gap: 10px;
        }
        #content main .table-data .head h3 {
            margin: 10px;
            font-size: 28px;
            flex-grow: 1;
            text-align: center;
        }
        #content main .table-data .head i {
            position: absolute;
            right: 10px;
        }
        #content main .table-data .head .bx {
            cursor: pointer;
        }

        #content main .table-data .order {
            flex-grow: 1;
            flex-basis: 500px;
        }
        #content main .table-data .order table {
            width: 100%;
            border-collapse: collapse;
        }
        #content main .table-data .order table th {
            padding-bottom: 12px;
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
        }
        #content main .table-data .order table td {
            padding: 16px 0;
        }
        #content main .table-data .order table tr td:first-child {
            display: flex;
            align-items: center;
            grid-gap: 12px;
            padding-left: 6px;
        }
        #content main .table-data .order table td img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        #content main .table-data .order table tbody tr:hover {
            background: var(--grey);
        }
        #content main .table-data .order table tr td .status {
            font-size: 10px;
            padding: 6px 16px;
            color: var(--light);
            border-radius: 20px;
            font-weight: 700;
        /* Existing CSS for other statuses */
        }
        #content main .table-data .order table tr td .status {
            font-size: 10px;
            padding: 6px 16px;
            color: var(--light);
            border-radius: 20px;
            font-weight: 700;
        }

        #content main .table-data .order table tr td .status.active {
            background: var(--blue); /* Blue color for Active */
        }

        #content main .table-data .order table tr td .status.inactive {
            background: var(--red); /* Yellow color for Inactive */
        }
        /* Status Colors */
        .status.completed, .status.to-receive, .status.accepted, .status.confirmed {
            background: var(--blue);
        }

        .status.pending, .status.to-pay {
            background: var(--dark-grey);
        }

        .status.declined, .status.rejected {
            background: var(--red);
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 0.85em;
            font-weight: bold;
            border-radius: 4px;
            color: #fff;
            text-align: center;
        }

        /* Order Status Badges */
        .badge.to-rate {
            background-color: #ff5722; /* Bright Orange-Red for "To Rate" */
            color: #fff;
        }

        .badge.to-ship {
            background-color: #ff5722; /* Bright Orange-Red for "To Ship" */
            color: #fff;
        }

        .badge.completed {
            background-color: #28a745; /* Bright Green for "Completed" */
            color: #fff;
        }

        .badge.to-received {
            background-color: #61b675; /* Bright Green for "To Received" */
            color: #fff;
        }

        .badge.ready {
            background-color: #17a2b8; /* Teal for "Ready" */
            color: #fff;
        }

        .badge.accepted {
            background-color: #007bff; /* Vivid Blue for "Accepted" */
            color: #fff;
        }

        .badge.rejected {
            background-color: #dc3545; /* Bright Red for "Rejected" */
            color: #fff;
        }

        /* Payment Status Badges */
        .badge.to-review {
            background-color: #ff851b; /* Vibrant Orange for "To Review" */
            color: #fff;
        }

        .badge.to-pay {
            background-color: #ff851b; /* Vibrant Orange for "To Pay" */
            color: #fff;
        }

        .badge.to-receive {
            background-color: #28a745; /* Bright Green for "To Receive" */
            color: #fff;
        }

        .badge.pending {
            background-color: #ffc107; /* Bright Yellow for "Pending" */
            color: #000;
        }

        .badge.declined {
            background-color: #e74c3c; /* Strong Red for "Declined" */
            color: #fff;
        }

        .badge.confirmed,
        .badge.paid {
            background-color: #20c997; /* Mint Green for "Confirmed" and "Paid" */
            color: #fff;
        }
        .filter-dropdown {
            position: absolute;
            top: 50px;
            right: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 200px;
        }

        .filter-dropdown h5 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .filter-dropdown label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        .filter-dropdown select, .filter-dropdown button {
            width: 100%;
            margin-top: 5px;
        }


        @media screen and (max-width: 768px) {
            #sidebar {
                width: 200px;
            }

            #content {
                width: calc(100% - 60px);
                left: 200px;
            }

            #content nav .nav-link {
                display: none;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav form .form-input input {
                display: none;
            }

            #content nav form .form-input button {
                width: auto;
                height: auto;
                background: transparent;
                border-radius: none;
                color: var(--dark);
            }

            #content nav form.show .form-input input {
                display: block;
                width: 100%;
            }
            #content nav form.show .form-input button {
                width: 36px;
                height: 100%;
                border-radius: 0 36px 36px 0;
                color: var(--light);
                background: var(--red);
            }

            #content nav form.show ~ .notification,
            #content nav form.show ~ .profile {
                display: none;
            }

            #content main .box-info {
                grid-template-columns: 1fr;
            }

            #content main .table-data .head {
                min-width: 420px;
            }
            #content main .table-data .order table {
                min-width: 420px;
            }
            #content main .table-data .todo .todo-list {
                min-width: 420px;
            }
        }

    </style>
@endsection

@section('content')
@section('content')
<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h3>ADMIN GENERATED REPORT</h3>
            </div>
            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
        </div>

        <ul class="box-info">
            <li>
                <i class='bx bxs-group'></i>
                <a href="{{ route('admin.dashboard.merchants') }}"> <!-- Merchants link -->
                    <span class="text">
                        <h3>{{ $applications->count() }}</h3>
                        <p>Merchants</p>
                    </span>
                </a>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <a href="{{ route('admin.dashboard.customers') }}"> <!-- Customers link -->
                    <span class="text">
                        <h3>{{ $customerCount }}</h3> <!-- Dynamic customer count -->
                        <p>Customers</p>
                    </span>
                </a>
            </li>
            <li>
                <i class='bx bxs-shopping-bag'></i>
                <a href="{{ route('admin.dashboard.transactions') }}"> <!-- Transactions link -->
                    <span class="text">
                        <h3>{{ $transactionCount }}</h3><!-- Dynamic transaction count -->
                        <p>Transactions</p>
                    </span>
                </a>
            </li>
        </ul>

        <div class="table-data">
            @if($viewType == 'merchants') <!-- Check the viewType to determine which section to show -->
                <div class="order">
                    <div class="head">
                        <h4>MERCHANTS</h4>
                        <i class='bx bx-search'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <table class="mt-2">
                        <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Merchant ID</th>
                                <th>Email</th>
                                <th>Province</th>
                                <th>DTI Certificate</th>
                                <th>Mayor's Permit</th>
                                <th>Status</th>
                                <th>Action Button</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>{{ $application->shop->shop_name ?? 'N/A' }}</td>
                                <td>{{ $application->merchant_id }}</td>
                                <td>{{ optional($application->shop->merchant)->email }}</td>
                                <td>{{ $application->shop->province ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-outline-custom view-image" data-image-url="{{ asset('storage/' . $application->dti_cert_path) }}" data-bs-toggle="modal" data-bs-target="#dtiModal">View</button>
                                </td>
                                <td>
                                    <button class="btn btn-outline-custom view-image" data-image-url="{{ asset('storage/' . $application->mayors_permit_path) }}" data-bs-toggle="modal" data-bs-target="#mayorModal">View</button>
                                </td>
                                <td>
                                    @if ($application->shop->verification_status === 'Verified')
                                        <span class="badge" style="background-color: #28a745; color: white;">Verified</span>
                                    @elseif ($application->shop->verification_status === 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($application->shop->verification_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-success confirm-button" data-id="{{ $application->application_id }}" data-bs-toggle="modal" data-bs-target="#confirmModal">Confirm</button>
                                    <button class="btn btn-danger decline-button" data-id="{{ $application->application_id }}" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif($viewType == 'customers') <!-- Customers section -->
                    <div class="order">
                        <div class="head">
                            <h3>CUSTOMERS</h3>
                            <i class='bx bx-search'></i>
                            <i class='bx bx-filter'></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Customer ID</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer) <!-- Loop through customers -->
                                    <tr>
                                        <td><p>{{ $customer->username }}</p></td>
                                        <td>{{ $customer->customer_id }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->addresses->province ?? 'N/A' }}</td> <!-- You may need to adjust this if you have an address field -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @elseif($viewType == 'transactions')
                        <div class="order">
                            <div class="head">
                                <h3>TRANSACTIONS</h3>
                                <i class='bx bx-filter' id="filter-icon"></i>
                            </div>
                            <!-- Filter Dropdown -->
                            <div id="filter-dropdown" class="filter-dropdown" style="display: none;">
                                <h5>Filter Options</h5>

                                <!-- Mode of Payment (MOP) Filter -->
                                <label for="filter-mop">Mode of Payment</label>
                                <select id="filter-mop">
                                    <option value="">All</option>
                                    <option value="GCash">GCash</option>
                                    <option value="COD">COD</option>
                                </select>

                                <!-- Order Status Filter -->
                                <label for="filter-order-status">Order Status</label>
                                <select id="filter-order-status">
                                    <option value="">All</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="decline">Decline</option>
                                    <option value="to-rate">To Rate</option>
                                    <option value="to-received">To Received</option>
                                    <option value="ready">Ready</option>
                                    <option value="to-ship">To Ship</option>
                                </select>

                                <!-- Payment Status Filter -->
                                <label for="filter-payment-status">Payment Status</label>
                                <select id="filter-payment-status">
                                    <option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="to-review">To Review</option>
                                </select>

                                <!-- Apply Filter Button -->
                                <button onclick="applyFilters()" class="btn btn-primary">Apply Filters</button>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Merchant ID</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Mode of Payment</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td><p>{{ $transaction->customer_id }}</p></td>
                                            <td>{{ $transaction->merchant_id }}</td>
                                            <td>
                                                <span class="badge {{ strtolower(str_replace(' ', '-', $transaction->payment_status)) }}">
                                                    {{ $transaction->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ strtolower(str_replace(' ', '-', $transaction->order_status)) }}">
                                                    {{ $transaction->order_status }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction->payment_method }}</td>
                                            <td>
                                                @if($transaction->payment_method === 'GCash' && $transaction->receipt_img)
                                                    <button type="button" class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $transaction->customer_id }}">
                                                        View
                                                    </button>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
        </div>
    </main>
</section>

<!-- Modals for Receipt Images -->
@foreach($transactions as $transaction)
    @if($transaction->payment_method === 'GCash' && $transaction->receipt_img)
        <!-- Modal for Receipt Image -->
        <div class="modal fade" id="receiptModal{{ $transaction->customer_id }}" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Receipt Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('storage/' . $transaction->receipt_img) }}" class="img-fluid" alt="Receipt Image">
                    </div>
                    <div class="modal-footer text-end">
                        <!-- Paid Form -->
                        <form action="{{ route('update-payment-status') }}" method="POST" class="d-inline me-2" id="paidForm_{{ $transaction->order_id }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $transaction->order_id }}">
                            <input type="hidden" name="payment_status" value="Paid">
                            <a href="javascript:void(0)" class="btn btn-custom" id="confirmPaid_{{ $transaction->order_id }}">Accept</a>
                        </form>

                        <!-- Decline Payment Form -->
                        <form action="{{ route('update-payment-status') }}" method="POST" class="d-inline" id="declinePaymentForm_{{ $transaction->order_id }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $transaction->order_id }}">
                            <input type="hidden" name="payment_status" value="Declined">
                            <a href="javascript:void(0)" class="btn btn-danger" id="confirmDecline_{{ $transaction->order_id }}">Decline</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
@endforeach

<!-- DTI Certificate Modal -->
<div class="modal fade" id="dtiModal" tabindex="-1" aria-labelledby="dtiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dtiModalLabel">DTI Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <img src="" id="dtiModalImage" class="img-fluid" alt="DTI Certificate">
            </div>
        </div>
    </div>
</div>

<!-- Mayor's Permit Modal -->
<div class="modal fade" id="mayorModal" tabindex="-1" aria-labelledby="mayorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mayorModalLabel">Mayor's Permit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <img src="" id="mayorModalImage" class="img-fluid" alt="Mayor's Permit">
            </div>
        </div>
    </div>
</div>
<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to confirm this application?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-application">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to decline this application?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="decline-application">Decline</button>
            </div>
        </div>
    </div>
</div>


@include('Components.admin-icon')
@include('Components.chat-popup')

@endsection


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@section('scripts')
    <script>
        // SEARCH BUTTON TOGGLE FOR MOBILE
        const searchButton = document.querySelector('#content nav form .form-input button');
        const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
        const searchForm = document.querySelector('#content nav form');

        searchButton.addEventListener('click', function (e) {
            if(window.innerWidth < 576) {
                e.preventDefault();
                searchForm.classList.toggle('show');
                if(searchForm.classList.contains('show')) {
                    searchButtonIcon.classList.replace('bx-search', 'bx-x');
                } else {
                    searchButtonIcon.classList.replace('bx-x', 'bx-search');
                }
            }
        })

        // RESPONSIVE ADJUSTMENTS FOR SEARCH BUTTON AND FORM
        if(window.innerWidth > 576) {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
            searchForm.classList.remove('show');
        }

        window.addEventListener('resize', function () {
            if(this.innerWidth > 576) {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
                searchForm.classList.remove('show');
            }
        })

        // DARK MODE SWITCH
        const switchMode = document.getElementById('switch-mode');

        switchMode.addEventListener('change', function () {
            if(this.checked) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        })
    </script>
    <script>
        $(document).ready(function() {
            // Handle click for DTI Certificate button
            $('.view-image[data-bs-target="#dtiModal"]').on('click', function() {
                var imageUrl = $(this).data('image-url');
                $('#dtiModalImage').attr('src', imageUrl); // Set the image source for the DTI modal
            });

            // Handle click for Mayor's Permit button
            $('.view-image[data-bs-target="#mayorModal"]').on('click', function() {
                var imageUrl = $(this).data('image-url');
                $('#mayorModalImage').attr('src', imageUrl); // Set the image source for the Mayor's permit modal
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let applicationId; // Variable to store the application ID

            // Confirm button handler
            $('.confirm-button').on('click', function() {
                applicationId = $(this).data('id'); // Get the application ID from the button's data attribute
            });

            // Decline button handler
            $('.decline-button').on('click', function() {
                applicationId = $(this).data('id'); // Get the application ID from the button's data attribute
            });

            // Confirm application action
            $('#confirm-application').on('click', function() {
                $.ajax({
                    url: '/admin/applications/' + applicationId + '/confirm', // URL for confirming the application
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Handle success response
                        location.reload(); // Reload the page or update the UI accordingly
                    },
                    error: function(xhr) {
                        // Handle error response
                        alert('Error confirming the application.');
                    }
                });
                $('#confirmModal').modal('hide'); // Hide the modal
            });

            // Decline application action
            $('#decline-application').on('click', function() {
                $.ajax({
                    url: '/admin/applications/' + applicationId + '/decline', // URL for declining the application
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Handle success response
                        location.reload(); // Reload the page or update the UI accordingly
                    },
                    error: function(xhr) {
                        // Handle error response
                        alert('Error declining the application.');
                    }
                });
                $('#declineModal').modal('hide'); // Hide the modal
            });
        });
    </script>
    <script>
        // Toggle filter dropdown visibility
        document.getElementById('filter-icon').addEventListener('click', function() {
            const filterDropdown = document.getElementById('filter-dropdown');
            filterDropdown.style.display = (filterDropdown.style.display === 'none' || filterDropdown.style.display === '') ? 'block' : 'none';
        });

        // Function to apply filters
        function applyFilters() {
            const mop = document.getElementById('filter-mop').value;
            const orderStatus = document.getElementById('filter-order-status').value;
            const paymentStatus = document.getElementById('filter-payment-status').value;

            const rows = document.querySelectorAll('.order table tbody tr');

            rows.forEach(row => {
                const mopCell = row.cells[4].textContent.trim();
                const orderStatusCell = row.cells[3].textContent.trim();
                const paymentStatusCell = row.cells[2].textContent.trim();

                // Determine if the row should be shown based on filters
                const showRow =
                    (mop === '' || mop === mopCell) &&
                    (orderStatus === '' || orderStatus === orderStatusCell.toLowerCase()) &&
                    (paymentStatus === '' || paymentStatus === paymentStatusCell.toLowerCase());

                // Show or hide the row based on filters
                row.style.display = showRow ? '' : 'none';
            });

            // Hide the filter dropdown after applying filters
            document.getElementById('filter-dropdown').style.display = 'none';
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Paid button handler
            document.querySelectorAll("[id^='confirmPaid_']").forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.id.split('_')[1]; // Extract order ID from the button ID
                    const form = document.getElementById(`paidForm_${orderId}`);

                    submitPaymentStatusForm(form, orderId);
                });
            });

            // Decline Payment button handler
            document.querySelectorAll("[id^='confirmDecline_']").forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.id.split('_')[1]; // Extract order ID from the button ID
                    const form = document.getElementById(`declinePaymentForm_${orderId}`);

                    submitPaymentStatusForm(form, orderId);
                });
            });

            function submitPaymentStatusForm(form, orderId) {
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message); // Display success message
                        // Optionally, update the UI, e.g., mark order as processed or hide modal
                        document.getElementById(`receiptModal${orderId}`).classList.remove('show');
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error updating payment status:', error);
                    alert('An error occurred while updating the payment status.');
                });
            }
        });

    </script>


@endsection

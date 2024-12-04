@extends('Components.layout')

@section('styles')
    <style>
        /* Add any specific styles here if needed */
    </style>
@endsection

@section('content')
    <div class="payment-form">
        <form action="{{ route('gcash.payment') }}" method="POST">
            @csrf
            <label for="amount">Total Amount (PHP): 100</label>
            <label for="amount">Amount (PHP):</label>
            <input type="number" name="amount" id="amount" required>
            <button type="submit">Pay with GCash</button>
        </form>
    </div>
@endsection

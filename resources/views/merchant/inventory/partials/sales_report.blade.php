<h3>Sales Report</h3>
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
                <td>₱{{ number_format($sale->total_price, 2) }}</td>
                <td>{{ $sale->sale_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Stock Alerts</h3>
<p>Products low in stock:</p>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lowStockProducts as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity_item }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Product Categories</h3>
<ul class="list-group">
    @foreach($categories as $category)
        <li class="list-group-item">{{ $category->name }}</li>
    @endforeach
</ul>

<div class="modal fade" id="deleteModal-{{ $productId }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $productId }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $productId }}">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to delete this product?</p>
                <div class="card p-2">
                    <div class="d-flex align-items-center" style="padding:0 20px;">
                        @if(!empty($productImage))
                            <img src="{{ asset('storage/' . $productImage) }}" 
                                alt="Product Image" 
                                style="height: 50px; width: 50px; object-fit: cover; border: 1px solid #ccc; border-radius: 5px;" 
                                class="me-3">
                        @else
                            <div class="me-3" style="height: 50px; width: 50px; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 5px;">
                                <span style="font-size: 12px; color: #888;">No Image</span>
                            </div>
                        @endif
                        <div class="w-100" style="padding:0 10px;">
                            <strong class="d-block mb-1">{{ $productName }}</strong>
                            <div class="d-flex justify-content-between">
                                <span>Price: ₱{{ number_format($productPrice, 2) }}</span>
                                <span class="text-end">Quantity: {{ $productQuantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <form action="{{ route('inventory.destroy', $productId) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

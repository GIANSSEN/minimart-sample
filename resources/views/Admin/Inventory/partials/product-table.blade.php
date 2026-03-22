{{-- resources/views/Admin/Inventory/partials/product-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(is_countable($products) ? count($products) > 0 : !empty($products))
@foreach($products as $product)
            <tr>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->stock->quantity ?? 0 }}</td>
                <td>
                    @if (($product->stock->quantity ?? 0) <= 0)
                        <span class="badge bg-danger">Out of Stock</span>
                    @elseif (($product->stock->quantity ?? 0) <= $product->reorder_level)
                        <span class="badge bg-warning">Low Stock</span>
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </td>
            </tr>
            @endforeach
@else
            <tr>
                <td colspan="5" class="text-center">No products found</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

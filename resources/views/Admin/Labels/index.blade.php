@extends('layouts.admin')

@section('title', 'Product Labels')

@section('content')
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                <i class="fas fa-tag"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Product Labels</h1>
                <p class="page-subtitle">Select products for label printing and generation</p>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-header-action btn-header-secondary" id="selectAll">
                <i class="fas fa-check-double"></i>
                <span>Select All</span>
            </button>
            <button type="button" class="btn-header-action btn-header-secondary" id="deselectAll">
                <i class="fas fa-times"></i>
                <span>Deselect All</span>
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Select Products for Label Printing</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.labels.generate') }}" method="POST" target="_blank">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Label Size</label>
                        <select name="label_size" class="form-control" required>
                            <option value="small">Small (2x1 inch)</option>
                            <option value="medium">Medium (3x2 inch)</option>
                            <option value="large">Large (4x3 inch)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_price" value="1" checked>
                            <label class="form-check-label">Show Price</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_barcode" value="1" checked>
                            <label class="form-check-label">Show Barcode</label>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <label>&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-secondary" id="selectAll">
                                <i class="fas fa-check-double"></i> Select All
                            </button>
                            <button type="button" class="btn btn-secondary" id="deselectAll">
                                <i class="fas fa-times"></i> Deselect All
                            </button>
                        </div>
                    </div>
                </div>

                @if ($products->count() > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllCheckbox">
                                </th>
                                <th>Product Code</th>
                                <th>Barcode</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox">
                                </td>
                                <td>{{ $product->product_code }}</td>
                                <td>{{ $product->barcode ?? 'N/A' }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($product->selling_price, 2) }}</td>
                                <td>{{ $product->stock->quantity ?? 0 }} {{ $product->unit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i> Generate Labels
                    </button>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No products found. Please add products first.
                    </div>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#selectAllCheckbox').change(function() {
        $('.product-checkbox').prop('checked', $(this).prop('checked'));
    });

    $('#selectAll').click(function() {
        $('.product-checkbox').prop('checked', true);
        $('#selectAllCheckbox').prop('checked', true);
    });

    $('#deselectAll').click(function() {
        $('.product-checkbox').prop('checked', false);
        $('#selectAllCheckbox').prop('checked', false);
    });
</script>
@endpush

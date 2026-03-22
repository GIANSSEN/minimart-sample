@extends('layouts.admin')

@section('title', 'Product Details - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box product-header-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">{{ $product->product_name }}</h1>
                <p class="page-subtitle">Product Details & Inventory Information</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-header-action btn-header-primary" aria-label="Edit product">
                <i class="fas fa-edit"></i>
                <span class="d-none d-sm-inline">Edit Product</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to list">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row mx-0">
        <!-- Left Column -->
        <div class="col-lg-4 px-2">
            <!-- Product Image Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="product-image-wrapper mb-3">
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" 
                                 class="img-fluid rounded-3">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No image uploaded</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <span class="badge-{{ $product->status == 'active' ? 'success' : 'secondary' }} px-3 py-2">
                            <i class="fas fa-circle me-1 small"></i>
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="action-buttons">
                        <a href="{{ route('admin.inventory.stock-in', ['product_id' => $product->id]) }}" 
                           class="action-btn btn-success">
                            <i class="fas fa-arrow-down"></i>
                            <span>Stock In</span>
                        </a>
                        <a href="{{ route('admin.inventory.stock-out', ['product_id' => $product->id]) }}" 
                           class="action-btn btn-warning">
                            <i class="fas fa-arrow-up"></i>
                            <span>Stock Out</span>
                        </a>
                        <a href="{{ route('admin.inventory.history', $product->id) }}" 
                           class="action-btn btn-info text-white">
                            <i class="fas fa-history"></i>
                            <span>History</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8 px-2">
            <!-- Information Tabs -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#basic-info">
                                <i class="fas fa-info-circle me-2"></i>Basic Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pricing">
                                <i class="fas fa-tag me-2"></i>Pricing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#inventory">
                                <i class="fas fa-boxes me-2"></i>Inventory
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic-info">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Product Code</span>
                                    <span class="info-value">{{ $product->product_code }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Product Name</span>
                                    <span class="info-value">{{ $product->product_name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Category</span>
                                    <span class="info-value">
                                        @if ($product->category)
                                            <span class="badge-info-light">
                                                {{ $product->category->category_name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Supplier</span>
                                    <span class="info-value">{{ $product->supplier->supplier_name ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Barcode/SKU</span>
                                    <span class="info-value">{{ $product->barcode ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Brand</span>
                                    <span class="info-value">{{ $product->brand ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Unit</span>
                                    <span class="info-value">{{ $product->unit }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Current Stock</span>
                                    <span class="info-value stock-value {{ $product->current_stock <= 0 ? 'text-danger' : ($product->current_stock <= $product->reorder_level ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($product->current_stock) }} {{ $product->unit }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Stock Status</span>
                                    <span class="info-value">
                                        <span class="badge-{{ $product->stock_badge_class }}-light">
                                            {{ $product->stock_status_label }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Product Type</span>
                                    <span class="info-value">
                                        @php
                                            $typeLabels = [
                                                'perishable' => 'Perishable',
                                                'non_perishable' => 'Non-Perishable',
                                                'equipment' => 'Equipment'
                                            ];
                                        @endphp
                                        <span class="badge-secondary-light">
                                            {{ $typeLabels[$product->product_type] ?? ucfirst($product->product_type) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item-full">
                                    <span class="info-label">Description</span>
                                    <span class="info-value">{{ $product->description ?? 'No description provided' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Tab -->
                        <div class="tab-pane fade" id="pricing">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Cost Price</span>
                                    <span class="info-value price">₱{{ number_format($product->cost_price, 2) }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Selling Price</span>
                                    <span class="info-value price text-primary fw-bold">₱{{ number_format($product->selling_price, 2) }}</span>
                                </div>
                                @if ($product->wholesale_price)
                                <div class="info-item">
                                    <span class="info-label">Wholesale Price</span>
                                    <span class="info-value price">₱{{ number_format($product->wholesale_price, 2) }}</span>
                                </div>
                                @endif
                                <div class="info-item">
                                    <span class="info-label">Discount</span>
                                    <span class="info-value">{{ $product->discount_percent ?? 0 }}%</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tax Rate</span>
                                    <span class="info-value">{{ $product->tax_rate ?? 0 }}%</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Profit Margin</span>
                                    <span class="info-value">
                                        @php
                                            $margin = $product->cost_price > 0 ? (($product->selling_price - $product->cost_price) / $product->selling_price) * 100 : 0;
                                        @endphp
                                        <span class="badge-{{ $margin >= 30 ? 'success' : ($margin >= 20 ? 'warning' : 'danger') }}-light">
                                            {{ number_format($margin, 2) }}%
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Tab -->
                        <div class="tab-pane fade" id="inventory">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Current Stock</span>
                                    <span class="info-value stock-value {{ $product->current_stock <= 0 ? 'text-danger' : ($product->current_stock <= $product->reorder_level ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($product->current_stock) }} {{ $product->unit }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Reorder Level</span>
                                    <span class="info-value">{{ $product->reorder_level }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Reorder Quantity</span>
                                    <span class="info-value">{{ $product->reorder_quantity ?? 'Not set' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Min Stock Level</span>
                                    <span class="info-value">{{ $product->min_level ?? 'Not set' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Max Stock Level</span>
                                    <span class="info-value">{{ $product->max_level ?? 'Not set' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Shelf Location</span>
                                    <span class="info-value">{{ $product->shelf_location ?? 'A1' }}</span>
                                </div>
                                @if ($product->has_expiry)
                                <div class="info-item">
                                    <span class="info-label">Expiry Date</span>
                                    <span class="info-value">
                                        @if ($product->expiry_date)
                                            {{ $product->expiry_date->format('M d, Y') }}
                                            @php
                                                $daysLeft = now()->diffInDays($product->expiry_date, false);
                                            @endphp
                                            <span class="badge-{{ $daysLeft < 0 ? 'danger' : ($daysLeft <= 30 ? 'warning' : 'success') }}-light ms-2">
                                                {{ $daysLeft < 0 ? 'Expired' : $daysLeft . ' days left' }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                                @endif
                                <div class="info-item">
                                    <span class="info-label">Expiry Status</span>
                                    <span class="info-value">{!! $product->expiry_badge !!}</span>
                                </div>
                                @if ($product->is_phase_out)
                                <div class="info-item-full">
                                    <span class="info-label">Phase Out Reason</span>
                                    <span class="info-value">
                                        <span class="badge-warning-light">Phasing Out</span>
                                        <div class="mt-2 text-muted">{{ $product->phase_out_reason ?? 'No reason provided' }}</div>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    @if ($transactions->count() > 0)
    <div class="row mx-0 mt-4">
        <div class="col-12 px-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0">Recent Stock Movements</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Date & Time</th>
                                    <th class="py-3">Type</th>
                                    <th class="py-3">Quantity</th>
                                    <th class="py-3">Previous</th>
                                    <th class="py-3">New</th>
                                    <th class="py-3">Reason</th>
                                    <th class="py-3">Reference</th>
                                    <th class="py-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-medium">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($transaction->type == 'in')
                                            <span class="badge-success-light">
                                                <i class="fas fa-arrow-down me-1"></i> IN
                                            </span>
                                        @else
                                            <span class="badge-warning-light">
                                                <i class="fas fa-arrow-up me-1"></i> OUT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $transaction->quantity }}</td>
                                    <td class="text-muted">{{ $transaction->previous_quantity }}</td>
                                    <td class="fw-bold">{{ $transaction->new_quantity }}</td>
                                    <td>
                                        @php
                                            $reasonColors = [
                                                'sale' => 'success',
                                                'purchase' => 'primary',
                                                'damage' => 'danger',
                                                'expired' => 'danger',
                                                'adjustment' => 'info',
                                                'return' => 'primary',
                                                'restock' => 'success',
                                                'initial_stock' => 'secondary'
                                            ];
                                            $reason = $transaction->reason ?? 'unknown';
                                            $color = $reasonColors[$reason] ?? 'secondary';
                                        @endphp
                                        <span class="badge-{{ $color }}-light">
                                            {{ ucfirst(str_replace('_', ' ', $reason)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($transaction->reference)
                                            <span class="badge-secondary-light">{{ $transaction->reference }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->user->name ?? $transaction->user->full_name ?? 'System' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($transactions->hasPages())
                <div class="card-footer bg-transparent border-0 pt-0 pb-4 px-4">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
:root {
    --primary-color: #4361ee;
    --success-color: #2ecc71;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #3498db;
    --secondary-color: #95a5a6;
}

/* Page Header */
.page-header {
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.15);
}

/* Cards */
.card {
    border-radius: 20px;
    background: white;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04) !important;
    border: 1px solid rgba(0,0,0,0.02);
}

.card-header {
    background: transparent;
    padding: 1.5rem 1.5rem 0.5rem 1.5rem;
}

/* Tabs */
.nav-tabs {
    border-bottom: 1px solid #edf2f9;
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    margin-right: 0.5rem;
    border-radius: 10px 10px 0 0;
    background: transparent;
    transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
    color: var(--primary-color);
    background: rgba(67, 97, 238, 0.03);
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    background: transparent;
    border-bottom: 2px solid var(--primary-color);
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.info-item, .info-item-full {
    min-width: 0;
}

.info-item-full {
    grid-column: 1 / -1;
}

.info-label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    margin-bottom: 0.375rem;
}

.info-value {
    font-size: 1rem;
    color: #2d3436;
    word-break: break-word;
}

.info-value.price {
    font-size: 1.125rem;
    font-weight: 600;
}

.info-value.stock-value {
    font-size: 1.25rem;
    font-weight: 600;
}

/* Badges */
.badge-success-light,
.badge-warning-light,
.badge-danger-light,
.badge-info-light,
.badge-primary-light,
.badge-secondary-light {
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
}

.badge-success-light {
    background: rgba(46, 204, 113, 0.1);
    color: #27ae60;
}

.badge-warning-light {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
}

.badge-danger-light {
    background: rgba(231, 76, 60, 0.1);
    color: #c0392b;
}

.badge-info-light {
    background: rgba(52, 152, 219, 0.1);
    color: #2980b9;
}

.badge-primary-light {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
}

.badge-secondary-light {
    background: rgba(149, 165, 166, 0.1);
    color: #7f8c8d;
}

/* Quick Action Buttons */
.action-buttons {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.action-btn i {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.action-btn span {
    font-size: 0.75rem;
    font-weight: 500;
}

.action-btn.btn-success {
    background: rgba(46, 204, 113, 0.1);
    color: #27ae60;
}

.action-btn.btn-success:hover {
    background: #27ae60;
    color: white;
}

.action-btn.btn-warning {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
}

.action-btn.btn-warning:hover {
    background: #f39c12;
    color: white;
}

.action-btn.btn-info {
    background: rgba(52, 152, 219, 0.1);
    color: #2980b9;
}

.action-btn.btn-info:hover {
    background: #2980b9;
    color: white;
}

/* Product Image */
.product-image-wrapper {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 16px;
    padding: 1.5rem;
}

.no-image-placeholder {
    padding: 2rem;
    text-align: center;
}

/* Table */
.table {
    margin-bottom: 0;
}

.table th {
    color: #6c757d;
    font-weight: 500;
    border-bottom: 1px solid #edf2f9;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #edf2f9;
}

.table tr:last-child td {
    border-bottom: none;
}

.table-hover tbody tr:hover {
    background: rgba(67, 97, 238, 0.02);
}

/* Responsive */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .action-btn {
        flex-direction: row;
        justify-content: flex-start;
        gap: 0.75rem;
    }
    
    .action-btn i {
        margin-bottom: 0;
    }
}
</style>
@endpush

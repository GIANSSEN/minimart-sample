@extends('layouts.admin')

@section('title', 'Supplier Details - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box supplier-header-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Supplier Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">Suppliers</a></li>
                        <li class="breadcrumb-item active">{{ $supplier->supplier_code }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn-header-action btn-header-primary" aria-label="Edit supplier">
                <i class="fas fa-edit"></i>
                <span class="d-none d-sm-inline">Edit Supplier</span>
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to list">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="status-bar d-flex align-items-center gap-3 py-2 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="status-label">Status:</span>
                    @if ($supplier->status == 'active')
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-circle fa-2xs me-1"></i> Active
                        </span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="fas fa-circle fa-2xs me-1"></i> Inactive
                        </span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-label">Supplier Code:</span>
                    <span class="fw-semibold">{{ $supplier->supplier_code }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-label">Added:</span>
                    <span>{{ $supplier->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Company Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-building text-primary me-2"></i>
                        Company Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="company-logo mx-auto mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-4 d-inline-block">
                                <i class="fas fa-building fa-4x text-primary"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $supplier->supplier_name }}</h3>
                        <p class="text-muted">{{ $supplier->supplier_code }}</p>
                    </div>

                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Contact Person</span>
                                <span class="info-value">{{ $supplier->contact_person ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Email Address</span>
                                @if ($supplier->email)
                                    <a href="mailto:{{ $supplier->email }}" class="info-value text-decoration-none">{{ $supplier->email }}</a>
                                @else
                                    <span class="info-value">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Phone</span>
                                @if ($supplier->phone)
                                    <a href="tel:{{ $supplier->phone }}" class="info-value text-decoration-none">{{ $supplier->phone }}</a>
                                @else
                                    <span class="info-value">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Mobile</span>
                                @if ($supplier->mobile)
                                    <a href="tel:{{ $supplier->mobile }}" class="info-value text-decoration-none">{{ $supplier->mobile }}</a>
                                @else
                                    <span class="info-value">—</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-fax"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Fax</span>
                                <span class="info-value">{{ $supplier->fax ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Website</span>
                                @if ($supplier->website)
                                    <a href="{{ $supplier->website }}" target="_blank" class="info-value text-decoration-none">{{ $supplier->website }}</a>
                                @else
                                    <span class="info-value">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Business Details & Products -->
        <div class="col-lg-8">
            <!-- Business Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase text-primary me-2"></i>
                        Business Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-box">
                                <span class="detail-label">Payment Terms</span>
                                <span class="detail-value">{{ $supplier->payment_terms ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-box">
                                <span class="detail-label">Tax ID / TIN</span>
                                <span class="detail-value">{{ $supplier->tax_id ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-box">
                                <span class="detail-label">Credit Limit</span>
                                <span class="detail-value">{{ $supplier->credit_limit ? '₱' . number_format($supplier->credit_limit, 2) : '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-box">
                                <span class="detail-label">Payment Method</span>
                                <span class="detail-value">{{ $supplier->payment_method ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-box">
                                <span class="detail-label">Address</span>
                                <span class="detail-value">{{ $supplier->address ?? '—' }}</span>
                            </div>
                        </div>
                        @if ($supplier->notes)
                        <div class="col-12">
                            <div class="detail-box">
                                <span class="detail-label">Notes</span>
                                <span class="detail-value">{{ $supplier->notes }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products from this Supplier -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes text-primary me-2"></i>
                        Products from this Supplier
                        <span class="badge bg-secondary ms-2">{{ $supplier->products_count ?? $products->count() ?? 0 }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="py-3">Code</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3">Stock</th>
                                    <th class="text-end px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($products ?? $supplier->products ?? []) ? count($products ?? $supplier->products ?? []) > 0 : !empty($products ?? $supplier->products ?? []))
@foreach($products ?? $supplier->products ?? [] as $product)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="product-image me-3">
                                                @if ($product->image)
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" 
                                                         class="rounded-3" width="40" height="40" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box fa-lg text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $product->product_name }}</div>
                                                <small class="text-muted">{{ $product->brand ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            {{ $product->product_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            {{ $product->category->category_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">₱{{ number_format($product->selling_price, 2) }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $stockQty = $product->stock->quantity ?? 0;
                                            $stockClass = $stockQty <= 0 ? 'danger' : ($stockQty <= ($product->reorder_level ?? 5) ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $stockClass }} bg-opacity-10 text-{{ $stockClass }} px-3 py-2">
                                            <i class="fas fa-{{ $stockQty <= 0 ? 'times-circle' : 'check-circle' }} me-1"></i>
                                            {{ $stockQty }} {{ $product->unit ?? 'pcs' }}
                                        </span>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit Product">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state small">
                                            <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No products from this supplier</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .modern-header {
        background: white;
        border-bottom: 2px solid #0d6efd;
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 8px 15px rgba(102,126,234,0.25);
        flex-shrink: 0;
        z-index: 2;
    }

    /* Status Bar */
    .status-bar {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        font-size: 0.9rem;
    }

    .status-label {
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* Info List */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .info-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }

    /* Detail Box */
    .detail-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        height: 100%;
    }

    .detail-label {
        display: block;
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
        display: block;
    }

    /* Card Styling */
    .card {
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: 1px solid #e9ecef;
    }

    /* Product Table */
    .product-image {
        transition: transform 0.2s;
    }

    tr:hover .product-image {
        transform: scale(1.1);
    }

    /* Empty State */
    .empty-state.small {
        padding: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            padding: 15px !important;
        }
        
        .header-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }

        .status-bar {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .info-item {
            flex-wrap: wrap;
        }

        .info-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }

        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 10px;
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 8px 10px;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #495057;
            margin-right: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-label attributes for mobile
        document.querySelectorAll('.table tbody td').forEach((td, index) => {
            const headers = ['Product', 'Code', 'Category', 'Price', 'Stock', 'Actions'];
            td.setAttribute('data-label', headers[index % 6]);
        });
    });
</script>
@endpush

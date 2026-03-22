@extends('layouts.admin')

@section('title', 'Product Variations - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box variations-header-icon">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Product Variations</h1>
                <p class="page-subtitle">Manage product variations like size, color, flavor, etc.</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.variations.export') }}" class="btn-header-action btn-header-success" aria-label="Export variations">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Export</span>
            </a>
            <a href="{{ route('admin.variations.create') }}" class="btn-header-action btn-header-primary" aria-label="Add new variation">
                <i class="fas fa-plus-circle"></i>
                <span class="d-none d-sm-inline">Add Variation</span>
            </a>
        </div>
    </div>

    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="stat-card-modern p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div>
                        <span class="stat-label text-uppercase text-muted small d-block">Total Variations</span>
                        <span class="stat-value fw-bold h3">{{ $totalVariations }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
                        <h5 class="mb-0 fw-semibold h6">
                            <i class="fas fa-filter text-primary me-2"></i>Filter Variations
                        </h5>
                        <a href="{{ route('admin.variations.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-redo-alt me-1"></i>Reset
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.variations.index') }}">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1 d-block d-md-none">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Search by name, value, SKU..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1 d-block d-md-none">Product</label>
                                <select name="product_id" class="form-select">
                                    <option value="">All Products</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ Str::limit($product->product_name, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1 d-none d-md-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters Tags -->
                    @if (request()->anyFilled(['search', 'product_id']))
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="small text-muted me-2">Active filters:</span>
                            @if (request('search'))
                                <span class="badge bg-primary">"{{ request('search') }}" 
                                    <a href="{{ route('admin.variations.index', array_merge(request()->except('search', 'page'))) }}" class="text-white ms-1"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                            @if (request('product_id') && $selectedProduct = $products->firstWhere('id', request('product_id')))
                                <span class="badge bg-info">{{ $selectedProduct->product_name }}
                                    <a href="{{ route('admin.variations.index', array_merge(request()->except('product_id', 'page'))) }}" class="text-white ms-1"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Variations Table -->
    <div class="row mx-0 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 px-3 px-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h5 class="mb-0 fw-semibold h6">
                        <i class="fas fa-list-alt text-primary me-2"></i>
                        Variation List
                        <span class="badge bg-secondary ms-2">{{ $variations->total() }}</span>
                    </h5>
                    <select name="per_page" class="form-select form-select-sm w-auto" onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ request('per_page',15)==15?'selected':'' }}>15 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page')==25?'selected':'' }}>25 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page')==50?'selected':'' }}>50 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page')==100?'selected':'' }}>100 per page</option>
                    </select>
                </div>

                <div class="card-body p-0">
                    <!-- Desktop Table -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">VARIATION</th>
                                    <th class="py-3">PRODUCT</th>
                                    <th class="py-3">SKU</th>
                                    <th class="py-3 text-end">PRICE</th>
                                    <th class="text-end px-4 py-3">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($variations) ? count($variations) > 0 : !empty($variations))
@foreach($variations as $variation)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($variation->image)
                                                <img src="{{ asset($variation->image) }}" alt="{{ $variation->name }}" 
                                                     class="rounded-3" width="40" height="40" style="object-fit:cover;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                                     style="width:40px;height:40px;">
                                                    <i class="fas fa-tag text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $variation->name }}</div>
                                                @if ($variation->value)
                                                    <small class="text-muted">{{ $variation->value }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($variation->product)
                                            <div>{{ $variation->product->product_name }}</div>
                                            <small class="text-muted">{{ $variation->product->product_code }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $variation->sku ?? '—' }}</code></td>
                                    <td class="text-end">
                                        @if ($variation->selling_price)
                                            ₱{{ number_format($variation->selling_price, 2) }}
                                            @if ($variation->cost_price)
                                                <br><small class="text-muted">Cost: ₱{{ number_format($variation->cost_price, 2) }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.variations.show', $variation->id) }}" class="btn-view" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.variations.edit', $variation->id) }}" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn-del" 
                                                    onclick="deleteVariation({{ $variation->id }}, '{{ addslashes($variation->name) }}')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-cubes fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Variations Found</h5>
                                            <p class="text-muted mb-3">Get started by creating your first variation.</p>
                                            <a href="{{ route('admin.variations.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i>Add Variation
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="d-block d-md-none p-3">
                        @if(is_countable($variations) ? count($variations) > 0 : !empty($variations))
@foreach($variations as $variation)
                        <div class="mobile-card mb-3">
                            <div class="d-flex gap-2 mb-2">
                                @if ($variation->image)
                                    <img src="{{ asset($variation->image) }}" alt="{{ $variation->name }}" 
                                         class="rounded-3" width="50" height="50" style="object-fit:cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                         style="width:50px;height:50px;">
                                        <i class="fas fa-tag text-muted fa-lg"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $variation->name }}</div>
                                    @if ($variation->value)
                                        <small class="text-muted">{{ $variation->value }}</small>
                                    @endif
                                </div>

                            </div>
                            <div class="row g-1 mb-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Product</small>
                                    <span class="small">{{ $variation->product->product_name ?? '—' }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">SKU</small>
                                    <span class="small"><code>{{ $variation->sku ?? '—' }}</code></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Selling Price</small>
                                    <span class="small fw-bold">₱{{ number_format($variation->selling_price, 2) }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Cost</small>
                                    <span class="small">₱{{ number_format($variation->cost_price, 2) }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                <a href="{{ route('admin.variations.show', $variation->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('admin.variations.edit', $variation->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteVariation({{ $variation->id }}, '{{ addslashes($variation->name) }}')">Delete</button>
                            </div>
                        </div>
                        @endforeach
@else
                        <div class="text-center py-5">
                            <i class="fas fa-cubes fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Variations Found</h5>
                            <a href="{{ route('admin.variations.create') }}" class="btn btn-primary mt-2">Add Variation</a>
                        </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if ($variations->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 px-3 px-md-4 py-3 border-top">
                        <div class="text-muted small text-center text-md-start">
                            Showing {{ $variations->firstItem() }} to {{ $variations->lastItem() }} of {{ $variations->total() }} entries
                        </div>
                        <div class="pagination-responsive">
                            {{ $variations->withQueryString()->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern Header */
    .modern-header {
        background: white;
        border-bottom: 2px solid #667eea;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .header-icon {
        width: clamp(40px, 6vw, 55px);
        height: clamp(40px, 6vw, 55px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.2rem, 4vw, 1.8rem);
        flex-shrink: 0;
    }

    /* Stat Cards */
    .stat-card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(102,126,234,0.1);
        height: 100%;
    }
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .stat-icon.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #11998e, #38ef7d); }

    /* Action Buttons */
/* Action buttons use global styles in admin layout */

    /* Mobile Card */
    .mobile-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    /* Pagination */
    .pagination-responsive .pagination {
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.25rem;
    }
    .pagination-responsive .page-link {
        padding: 0.3rem 0.7rem;
        font-size: 0.8rem;
        border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteVariation(id, name) {
        Swal.fire({
            title: 'Delete Variation?',
            html: `Are you sure you want to delete <strong>${name}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/variations/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Tax Rates - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <i class="fas fa-percent"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Tax Rates</h1>
                <p class="page-subtitle">Manage VAT and other tax rates for your products</p>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-header-action btn-header-primary border-0" data-bs-toggle="modal" data-bs-target="#createTaxModal">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Tax Rate</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mx-0 g-3 mb-4">
        <!-- Total Tax Rates -->
        <div class="col-md-3 d-flex px-2">
            <div class="card flex-fill w-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1);">
                        <i class="fas fa-percent fs-3 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column text-dark">
                        <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Tax Rates</span>
                        <span class="fw-bold lh-1 mt-1 text-dark" style="font-size: 1.7rem;">{{ $stats['total'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active -->
        <div class="col-md-3 d-flex px-2">
            <div class="card flex-fill w-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1);">
                        <i class="fas fa-check-circle fs-3 text-success"></i>
                    </div>
                    <div class="d-flex flex-column text-dark">
                        <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Active</span>
                        <span class="fw-bold lh-1 mt-1 text-dark" style="font-size: 1.7rem;">{{ $stats['active'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inclusive -->
        <div class="col-md-3 d-flex px-2">
            <div class="card flex-fill w-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(139, 92, 246, 0.1);">
                        <i class="fas fa-tag fs-3 text-purple" style="color: #8b5cf6;"></i>
                    </div>
                    <div class="d-flex flex-column text-dark">
                        <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Inclusive</span>
                        <span class="fw-bold lh-1 mt-1 text-dark" style="font-size: 1.7rem;">{{ $stats['inclusive'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Default -->
        <div class="col-md-3 d-flex px-2">
            <div class="card flex-fill w-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1);">
                        <i class="fas fa-star fs-3 text-warning"></i>
                    </div>
                    <div class="d-flex flex-column text-dark">
                        <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Default</span>
                        <span class="fw-bold lh-1 mt-1 text-dark" style="font-size: 1.7rem;">{{ $stats['default'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mx-0 mb-4">
        <div class="col-12 px-0">
            <div class="card premium-form-card border-0 shadow-lg">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-filter text-primary me-2"></i>
                            Filter Tax Rates
                        </h5>
                        <a href="{{ route('admin.taxes.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-redo-alt me-1"></i>Reset Filters
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.taxes.index') }}">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Search by name, code, description..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters -->
                    @if (request()->anyFilled(['search', 'type', 'status']))
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark p-2">
                                <i class="fas fa-sliders-h me-1"></i>Active Filters:
                            </span>
                            @if (request('search'))
                                <span class="badge bg-primary">
                                    "{{ request('search') }}" 
                                    <a href="{{ route('admin.taxes.index', array_merge(request()->except('search', 'page'))) }}" 
                                       class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if (request('type'))
                                <span class="badge bg-info">
                                    {{ ucfirst(request('type')) }}
                                    <a href="{{ route('admin.taxes.index', array_merge(request()->except('type', 'page'))) }}" 
                                       class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if (request('status'))
                                <span class="badge bg-success">
                                    {{ ucfirst(request('status')) }}
                                    <a href="{{ route('admin.taxes.index', array_merge(request()->except('status', 'page'))) }}" 
                                       class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Rates Table -->
    <div class="row mx-0">
        <div class="col-12 px-0">
            <div class="card premium-form-card border-0 shadow-lg overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 px-4">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt text-primary me-2"></i>
                        Tax Rates List
                        <span class="badge bg-secondary ms-2">{{ $taxRates->total() }} total</span>
                    </h5>
                    <div>
                        <select name="per_page" class="form-select form-select-sm" style="width: 130px;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ request('per_page',15)==15?'selected':'' }}>15 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page')==25?'selected':'' }}>25 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page')==50?'selected':'' }}>50 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page')==100?'selected':'' }}>100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Tax Code</th>
                                    <th class="py-3">Name</th>
                                    <th class="py-3">Rate</th>
                                    <th class="py-3">Type</th>
                                    <th class="py-3">Products</th>
                                    <th class="py-3">Effective Period</th>
                                    <th class="py-3">Status</th>
                                    <th class="text-end px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($taxRates) ? count($taxRates) > 0 : !empty($taxRates))
@foreach($taxRates as $tax)
                                <tr>
                                    <td class="px-4">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            {{ $tax->tax_code }}
                                        </span>
                                        @if ($tax->is_default)
                                            <span class="badge bg-warning ms-1">Default</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $tax->name }}</div>
                                        @if ($tax->description)
                                            <small class="text-muted">{{ Str::limit($tax->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ number_format($tax->rate, 2) }}%</span>
                                    </td>
                                    <td>{!! $tax->type_badge !!}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            <i class="fas fa-boxes me-1"></i>
                                            {{ $tax->products_count }} Products
                                        </span>
                                    </td>
                                    <td>
                                        @if ($tax->effective_from || $tax->effective_to)
                                            <div class="small">
                                                @if ($tax->effective_from)
                                                    <div>From: {{ $tax->effective_from->format('M d, Y') }}</div>
                                                @endif
                                                @if ($tax->effective_to)
                                                    <div>To: {{ $tax->effective_to->format('M d, Y') }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Always</span>
                                        @endif
                                    </td>
                                    <td>{!! $tax->status_badge !!}</td>
                                    <td class="text-end px-4">
                                        <div class="d-flex gap-1 justify-content-end">
                                            @if (!$tax->is_default)
                                                <button class="btn btn-sm btn-outline-warning" onclick="setDefault({{ $tax->id }})" title="Set as Default">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.taxes.edit', $tax->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-{{ $tax->status == 'active' ? 'danger' : 'success' }}" 
                                                    onclick="toggleStatus({{ $tax->id }}, '{{ $tax->name }}')" 
                                                    title="{{ $tax->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $tax->status == 'active' ? 'ban' : 'check' }}"></i>
                                            </button>
                                            @if (!$tax->is_default && $tax->products_count == 0)
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTax({{ $tax->id }}, '{{ $tax->name }}')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-percent fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Tax Rates Found</h5>
                                            <p class="text-muted mb-3">Get started by creating your first tax rate</p>
                                            <a href="{{ route('admin.taxes.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i>Add New Tax Rate
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($taxRates->hasPages())
                    <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="text-muted small">
                            <i class="fas fa-database me-1"></i>
                            Showing {{ $taxRates->firstItem() }} to {{ $taxRates->lastItem() }} of {{ $taxRates->total() }} entries
                        </div>
                        <div class="pagination-modern">
                            {{ $taxRates->withQueryString()->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Tax Rate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteTaxName"></strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone. Tax rates assigned to products cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Tax Rate</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create Tax Rate Modal -->
<div class="modal fade" id="createTaxModal" tabindex="-1" aria-labelledby="createTaxModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content premium-modal border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 pb-3 bg-light opacity-100" style="background: #f8fafc !important;">
                <h5 class="modal-title fw-bold" id="createTaxModalLabel" style="color: #1e293b; font-size: 1.25rem;">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Tax Rate
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                <form id="createTaxForm" class="needs-validation" novalidate>
                    <!-- Visual Separator styling -->
                    <div class="mb-4 pb-2 border-bottom">
                        <h6 class="fw-bold text-primary mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Basic Information</h6>
                        <small class="text-muted">Enter the core details defining this tax rate.</small>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Tax Code <span class="text-danger">*</span></label>
                            <div class="input-group premium-input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-barcode"></i></span>
                                <input type="text" name="tax_code" class="form-control border-start-0 ps-0 shadow-none premium-input" placeholder="e.g., VAT-12" required>
                            </div>
                            <div class="invalid-feedback" id="tax_code_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Rate (%) <span class="text-danger">*</span></label>
                            <div class="input-group premium-input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-percent"></i></span>
                                <input type="number" name="rate" class="form-control border-start-0 ps-0 shadow-none premium-input" step="0.01" min="0" placeholder="e.g., 12.00" required>
                            </div>
                            <div class="invalid-feedback" id="rate_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark mb-1">Tax Name <span class="text-danger">*</span></label>
                            <div class="input-group premium-input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-heading"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0 shadow-none premium-input" placeholder="e.g., Value Added Tax" required>
                            </div>
                            <div class="invalid-feedback" id="name_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>
                    </div>

                    <div class="mb-4 pb-2 border-bottom">
                        <h6 class="fw-bold text-primary mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Configuration</h6>
                        <small class="text-muted">Set how and when this tax applies.</small>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select shadow-none premium-select" required>
                                <option value="exclusive">Exclusive (Added on top of price)</option>
                                <option value="inclusive">Inclusive (Embedded in price)</option>
                            </select>
                            <div class="invalid-feedback" id="type_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select shadow-none premium-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Effective From (Optional)</label>
                            <div class="input-group premium-input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="effective_from" class="form-control border-start-0 ps-0 shadow-none premium-input">
                            </div>
                            <div class="invalid-feedback" id="effective_from_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark mb-1">Effective To (Optional)</label>
                            <div class="input-group premium-input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-calendar-times"></i></span>
                                <input type="date" name="effective_to" class="form-control border-start-0 ps-0 shadow-none premium-input">
                            </div>
                            <div class="invalid-feedback" id="effective_to_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark mb-1">Description</label>
                            <textarea name="description" class="form-control shadow-none premium-textarea" rows="2" placeholder="Add any operational notes about this tax rate..."></textarea>
                            <div class="invalid-feedback" id="description_error" style="font-size: 0.8rem; margin-top: 4px;"></div>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background: rgba(59, 130, 246, 0.05); border: 1px dashed rgba(59, 130, 246, 0.3);">
                                <div class="form-check form-switch custom-switch flex-grow-1 mb-0 ps-5 ms-1">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_default" name="is_default" value="1" style="width: 2.5em; height: 1.25em;">
                                    <label class="form-check-label fw-bold text-primary ms-2 mt-1 display-block" for="is_default" style="cursor:pointer;">Set as Default Tax Rate</label>
                                    <span class="d-block text-muted small ms-2 mt-1">This will be automatically selected for new products.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer border-top p-3" style="background: #f8fafc !important;">
                <button type="button" class="btn btn-light rounded-3 px-4 fw-semibold border text-muted hover-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" id="saveTaxBtn" onclick="submitTaxForm()">
                    <i class="fas fa-save me-2"></i>Save Tax Rate
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* FULL WIDTH FIXES */
    .container-fluid.px-0 {
        width: 100%;
        max-width: 100%;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .row.mx-0 {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .col-12.px-0,
    [class*="col-"].px-2 {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    /* PREMIUM MODAL & INPUTS */
    .premium-modal {
        border-radius: 24px;
        overflow: hidden;
    }

    .premium-input-group {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1.5px solid #e2e8f0;
    }

    .premium-input-group:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .premium-input-group .input-group-text {
        border: none;
        color: #94a3b8;
    }

    .premium-input, .premium-select, .premium-textarea {
        border: none !important;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: #1e293b;
    }

    .premium-input::placeholder {
        color: #cbd5e1;
    }

    .premium-input:focus, .premium-select:focus, .premium-textarea:focus {
        box-shadow: none !important;
    }

    .premium-select {
        background-color: white;
        cursor: pointer;
    }

    /* STAT CARDS (REFINED) */
    .card.bg-white {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .card.bg-white:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05) !important;
    }

    /* MODERN HEADER */
    .modern-header {
        background: white;
        border-bottom: 2px solid #667eea;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: min(250px, 30vw);
        height: min(250px, 30vw);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: clamp(45px, 6vw, 55px);
        height: clamp(45px, 6vw, 55px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.5rem, 4vw, 1.8rem);
        box-shadow: 0 8px 15px rgba(102,126,234,0.25);
        flex-shrink: 0;
        z-index: 2;
    }

    /* STAT CARDS */
    .stat-card {
        border-radius: 16px;
        padding: 1.2rem 1rem;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 110px;
        width: 100%;
    }

    .stat-card .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
        height: 100%;
    }

    .stat-card .stat-icon {
        width: clamp(40px, 5vw, 45px);
        height: clamp(40px, 5vw, 45px);
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(1.3rem, 4vw, 1.6rem);
        backdrop-filter: blur(5px);
        flex-shrink: 0;
    }

    .stat-card .stat-label {
        font-size: clamp(0.7rem, 1.5vw, 0.8rem);
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        display: block;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .stat-card .stat-value {
        font-size: clamp(1.3rem, 3vw, 1.6rem);
        font-weight: 700;
        line-height: 1.2;
        display: block;
    }

    /* PAGINATION */
    .pagination-modern .pagination {
        gap: 0.3rem;
        margin: 0;
    }

    .pagination-modern .page-link {
        border: none;
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .premium-form-card {
        border-radius: 20px;
        background: #ffffff;
    }
    
    [data-theme="dark"] .premium-form-card {
        background: rgba(30, 41, 59, 0.9);
        border: 1px solid rgba(148, 163, 184, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleStatus(id, name) {
        Swal.fire({
            title: 'Change Status?',
            text: `Change status for ${name}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, change'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/taxes/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function setDefault(id) {
        Swal.fire({
            title: 'Set as Default?',
            text: 'This will be the default tax rate for new products.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, set as default'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/taxes/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function deleteTax(id, name) {
        document.getElementById('deleteTaxName').innerText = name;
        document.getElementById('deleteForm').action = `/admin/taxes/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    function submitTaxForm() {
        const form = document.getElementById('createTaxForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.is_default = document.getElementById('is_default').checked ? 1 : 0;
        
        // Reset validation UI
        document.querySelectorAll('.premium-input-group').forEach(el => el.classList.remove('border-danger'));
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => { el.innerText = ''; el.style.display = 'none'; });
        
        // Simple client-side check
        if (!data.tax_code || !data.rate || !data.name) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please fill in all required fields marked with *',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        const btn = document.getElementById('saveTaxBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
        btn.disabled = true;

        fetch('{{ route('admin.taxes.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            const resData = await response.json();
            if (!response.ok) {
                if (response.status === 422) {
                    const errors = resData.errors;
                    for (const field in errors) {
                        const input = form.querySelector(`[name="${field}"]`);
                        const errorDiv = document.getElementById(`${field}_error`);
                        if (input) {
                            input.classList.add('is-invalid');
                            // Also highlight the parent input group if it exists
                            const group = input.closest('.premium-input-group');
                            if (group) group.classList.add('border-danger');
                        }
                        if (errorDiv) {
                            errorDiv.innerText = errors[field][0];
                            errorDiv.style.display = 'block';
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check the form for errors.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire('Error!', resData.message || 'An error occurred.', 'error');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            } else {
                bootstrap.Modal.getInstance(document.getElementById('createTaxModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: resData.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'A network error occurred.', 'error');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    document.getElementById('createTaxModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('createTaxForm').reset();
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => { el.innerText = ''; el.style.display = 'none'; });
    });
</script>
@endpush

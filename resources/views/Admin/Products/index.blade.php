@extends('layouts.admin')

@section('title', 'Product List - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box products-header-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Product Management</h1>
                <p class="page-subtitle">Manage inventory, track stock levels, and monitor expiry</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" id="exportBtn" title="Export products">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn-header-action btn-header-primary" title="Add new product">
                <i class="fas fa-plus-circle"></i>
                <span class="d-none d-sm-inline">Add Product</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4" id="statsContainer">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Total</span>
                        <h3 class="stat-value fw-bold mb-0">{{ number_format($totalProducts ?? $products->total()) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Categories</span>
                        <h3 class="stat-value fw-bold mb-0">{{ $categories->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Suppliers</span>
                        <h3 class="stat-value fw-bold mb-0">{{ $suppliers->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Low Stock</span>
                        <h3 class="stat-value fw-bold mb-0 text-danger">{{ $lowStockCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden filter-card-enhanced">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center">
                            <i class="fas fa-sliders-h text-gradient-secondary me-2"></i>Filter Products
                        </h5>
                        <button class="btn btn-sm btn-link text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-secondary"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" 
                                               placeholder="Search name, code, barcode..." value="{{ request('search') }}"
                                               id="searchInput" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="category" class="form-select form-select-enhanced" id="categorySelect">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="supplier" class="form-select form-select-enhanced" id="supplierSelect">
                                        <option value="">All Suppliers</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->supplier_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-gradient-secondary w-100" id="applyFilterBtn">
                                        <i class="fas fa-filter me-2"></i><span class="d-none d-sm-inline">Apply</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <!-- Bulk Actions Bar -->
    <div class="row mx-0 mb-3 px-4 sticky-top" style="top: 70px; z-index: 100;">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-3 bg-white bulk-actions-bar-enhanced" id="bulkBar">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" aria-label="Select all products">
                            <label class="form-check-label small fw-medium" for="selectAll">Select All</label>
                        </div>
                        <div class="vr"></div>
                        <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" disabled>
                            <i class="fas fa-trash me-1"></i><span class="d-none d-sm-inline">Delete</span>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="bulkExportBtn" disabled>
                            <i class="fas fa-download me-1"></i><span class="d-none d-sm-inline">Export</span>
                        </button>
                        <span class="small text-muted-600 ms-auto" id="selectedCount">0 selected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Container -->
    <div class="row mx-0 px-4" id="productsTableContainer">
        @include('admin.products.partials.table') {{-- or include inline if you prefer --}}
    </div>
</div>

<!-- Hidden form for CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Loading Spinner Template -->
<template id="loading-spinner">
    <div class="text-center py-5">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>

<!-- ===================== SHOW PRODUCT MODAL ===================== -->
<div class="modal fade" id="productShowModal" tabindex="-1" aria-labelledby="productShowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius:20px;overflow:hidden;">
            <div class="modal-header border-0 py-4 px-4" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="productShowModalLabel">Product Details</h5>
                        <small class="text-white" style="opacity:0.8;">View product information</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="productShowBody">
                <!-- Content injected by JS -->
                <div class="text-center py-5">
                    <div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="showModalEditBtn"><i class="fas fa-edit me-2"></i>Edit Product</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== EDIT PRODUCT MODAL ===================== -->
<div class="modal fade" id="productEditModal" tabindex="-1" aria-labelledby="productEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable product-edit-modal-dialog">
        <div class="modal-content border-0 shadow product-edit-modal-content">
            <div class="modal-header border-0 py-4 px-4 product-edit-modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="productEditModalLabel">Edit Product</h5>
                        <small class="text-white" style="opacity:0.8;">Update product information</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 product-edit-modal-body" id="productEditBody">
                <!-- Content injected by JS -->
                <div class="text-center py-5">
                    <div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 product-edit-modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditBtn" onclick="submitEditForm()">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* ========== DESIGN SYSTEM ========== */
    :root {
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-secondary: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        --shadow-soft: 0 10px 30px -12px rgba(0, 0, 0, 0.15);
        --shadow-hover: 0 20px 40px -15px rgba(0, 0, 0, 0.25);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Header */
    .modern-header-enhanced {
        background: white;
        border-bottom: 2px solid #667eea;
        position: relative;
        overflow: hidden;
        animation: slideDownIn 0.6s ease-out;
    }
    .modern-header-enhanced::before {
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
        pointer-events: none;
    }
    .header-icon-enhanced {
        width: clamp(45px, 6vw, 55px);
        height: clamp(45px, 6vw, 55px);
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.2rem, 3vw, 1.8rem);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        animation: scaleIn 0.6s ease-out;
    }

    /* Stat Cards */
    .stat-card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(102,126,234,0.1);
        transition: var(--transition-smooth);
        animation: fadeInUp 0.6s ease-out;
    }
    .stat-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(102,126,234,0.15);
        border-color: #667eea;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-icon.bg-primary { background: var(--gradient-primary); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .stat-icon.bg-warning { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-icon.bg-danger { background: linear-gradient(135deg, #fa709a, #fee140); }

    /* Filter Card */
    .filter-card-enhanced {
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }
    .input-group-enhanced .form-control,
    .form-select-enhanced {
        border-radius: 10px;
        transition: var(--transition-smooth);
        border: 1px solid #e5e7eb;
    }
    .input-group-enhanced .form-control:focus,
    .form-select-enhanced:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn-gradient-secondary {
        background: var(--gradient-secondary);
        border: none;
        color: white;
        transition: var(--transition-smooth);
    }
    .btn-gradient-secondary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: white;
    }

    /* Bulk Actions Bar */
    .bulk-actions-bar-enhanced {
        animation: slideUpIn 0.4s ease-out;
        transition: var(--transition-smooth);
    }
    .bulk-actions-bar-enhanced:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Table */
    .table-modern {
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }
    .table-modern th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 2px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    .table-modern tbody tr {
        transition: var(--transition-smooth);
        border-bottom: 1px solid #e5e7eb;
    }
    .table-modern tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.03);
        box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.05);
    }
    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: var(--transition-smooth);
    }
    .avatar:hover {
        transform: scale(1.1);
    }
    .badge-modern {
        padding: 0.4em 1em;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.75rem;
        background: rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: var(--transition-smooth);
    }
    .badge-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Pagination */
    .pagination-modern .pagination {
        gap: 5px;
    }
    .pagination-modern .page-link {
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        color: #495057;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: var(--transition-smooth);
    }
    .pagination-modern .page-link:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    .pagination-modern .page-item.active .page-link {
        background: var(--gradient-secondary);
        color: white;
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    /* Edit Product Modal */
    .product-edit-modal-dialog {
        max-width: min(1280px, 96vw);
    }
    .product-edit-modal-content {
        border-radius: 20px;
        overflow: hidden;
        max-height: 92vh;
    }
    .product-edit-modal-body {
        background: #f8fafc;
        padding: 1.25rem 1.25rem 1.5rem !important;
    }
    .product-edit-modal-footer {
        border-top: 1px solid #e2e8f0 !important;
        background: #ffffff;
        padding-top: 0.9rem;
    }
    #productEditModal .form-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
    }
    #productEditModal .form-control,
    #productEditModal .form-select,
    #productEditModal .input-group-text {
        border-radius: 12px;
    }
    #productEditModal .input-group-text {
        min-width: 44px;
        justify-content: center;
        border-right: 0;
        background: #f8fafc;
    }
    #productEditModal .input-group .form-control {
        border-left: 0;
    }
    .product-edit-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1rem;
    }
    .product-edit-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.8rem;
    }
    .product-edit-image-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1rem;
    }
    .product-edit-upload-zone {
        background: rgba(102, 126, 234, 0.03);
        border: 2px dashed #cbd5e1 !important;
        border-radius: 14px;
        min-height: 240px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .product-edit-upload-zone:hover {
        background: rgba(102, 126, 234, 0.08);
        border-color: #667eea !important;
    }
    .product-edit-preview-image {
        max-height: 160px;
        max-width: 100%;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }

    /* Skeleton Loading */
    .skeleton-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton 1.5s infinite;
        border-radius: 8px;
    }
    @keyframes skeleton {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Animations */
    @keyframes slideDownIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUpIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }


    /* ===== SHOW PRODUCT MODAL REDESIGN ===== */
    #productShowModal .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }
    .show-modal-hero {
        background: linear-gradient(160deg, #f0f4ff 0%, #faf5ff 100%);
        border-bottom: 1px solid #e5e7eb;
        padding: 2rem 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .show-modal-img-wrap {
        width: 150px;
        height: 150px;
        border-radius: 18px;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 8px 32px rgba(102,126,234,0.18);
        margin-bottom: 1.25rem;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .show-modal-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .show-modal-img-wrap > div {
        width: 100% !important;
        height: 100% !important;
        border-radius: 0 !important;
    }
    .show-modal-identity {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    .show-modal-product-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
    }
    .show-modal-product-code {
        font-size: 0.85rem;
        color: #64748b;
        font-family: 'Courier New', monospace;
        background: rgba(102,126,234,0.08);
        padding: 0.2rem 0.75rem;
        border-radius: 20px;
        border: 1px solid rgba(102,126,234,0.15);
    }
    .show-modal-stock-badge {
        font-size: 0.82rem;
        padding: 0.45em 1.1em;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-top: 0.25rem;
    }
    .show-modal-info-body {
        padding: 1.5rem 1.75rem;
        background: #fff;
    }
    .show-modal-section-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #667eea;
        margin-bottom: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .show-modal-section-label::after {
        content: '';
        flex: 1;
        height: 1.5px;
        background: linear-gradient(to right, rgba(102,126,234,0.25), transparent);
        border-radius: 2px;
    }
    .show-modal-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .show-modal-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }
    .show-modal-info-item:hover {
        background: #f0f4ff;
        border-color: #c7d2fe;
    }
    .info-item-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.78rem;
        flex-shrink: 0;
        margin-top: 2px;
        box-shadow: 0 2px 8px rgba(102,126,234,0.25);
    }
    .info-item-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 0.2rem;
    }
    .info-item-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: #1e293b;
    }
    .show-modal-price-cards {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .price-card {
        flex: 1;
        min-width: 120px;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .price-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .price-card-selling {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
    }
    .price-card-cost {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
    }
    .price-card-wholesale {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: #fff;
    }
    .price-card-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
        margin-bottom: 0.4rem;
    }
    .price-card-value {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.01em;
    }
    .show-modal-description {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 3px solid #667eea;
        border-radius: 10px;
        padding: 0.9rem 1rem;
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.6;
    }
    @media (max-width: 576px) {
        .show-modal-info-grid { grid-template-columns: 1fr; }
        .show-modal-img-wrap { width: 120px; height: 120px; }
        .show-modal-hero { padding: 1.5rem 1rem 1.25rem; }
        .show-modal-info-body { padding: 1rem; }
        .price-card-value { font-size: 1.1rem; }
    }
    /* Responsive */
    @media (max-width: 768px) {
        .table-modern thead { display: none; }
        .table-modern tbody tr {
            display: block;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            box-shadow: var(--shadow-soft);
        }
        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem 0;
        }
        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
            font-size: 0.875rem;
            min-width: 100px;
        }
    }
    @media (max-width: 576px) {
        .stat-card-modern { padding: 12px !important; }
        .stat-icon { width: 44px; height: 44px; font-size: 1.4rem; }
        .stat-value { font-size: 1.3rem !important; }
        .product-edit-modal-body {
            padding: 1rem !important;
        }
        .product-edit-upload-zone {
            min-height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        'use strict';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentRequest = null;

        // ========== DEBOUNCE ==========
        const debounce = (func, delay) => {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        };

        // ========== SKELETON LOADER ==========
        const showSkeleton = () => {
            const container = document.getElementById('productsTableContainer');
            container.innerHTML = `
                <div class="col-12">
                    <div class="card border-0 shadow-soft rounded-4 p-4">
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px;"></div>
                    </div>
                </div>
            `;
        };

        // ========== FETCH PRODUCTS (AJAX) ==========
        const fetchProducts = async (url) => {
            if (currentRequest) currentRequest.abort();
            currentRequest = new AbortController();

            showSkeleton();
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: currentRequest.signal
                });
                if (!response.ok) throw new Error('Network error');
                const html = await response.text();
                document.getElementById('productsTableContainer').innerHTML = html;
                attachCheckboxHandlers();
                attachActionButtons();
            } catch (error) {
                if (error.name !== 'AbortError') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to load products.',
                        confirmButtonColor: '#667eea'
                    });
                }
            } finally {
                currentRequest = null;
            }
        };

        // ========== FILTER HANDLING ==========
        const searchInput = document.getElementById('searchInput');
        const categorySelect = document.getElementById('categorySelect');
        const supplierSelect = document.getElementById('supplierSelect');
        const filterForm = document.getElementById('filterForm');

        if (filterForm) {
            const debouncedFetch = debounce(() => {
                const params = new URLSearchParams(new FormData(filterForm)).toString();
                const url = `{{ route('admin.products.index') }}?${params}`;
                fetchProducts(url);
                window.history.pushState({}, '', url);
            }, 500);

            searchInput?.addEventListener('input', debouncedFetch);
            categorySelect?.addEventListener('change', debouncedFetch);
            supplierSelect?.addEventListener('change', debouncedFetch);

            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                debouncedFetch();
            });
        }

        // ========== BULK SELECTION ==========
        const selectAll = document.getElementById('selectAll');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkExportBtn = document.getElementById('bulkExportBtn');
        const selectedCountSpan = document.getElementById('selectedCount');

        function attachCheckboxHandlers() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const selectAllDesktop = document.getElementById('selectAllDesktop'); // if any

            function updateBulkActions() {
                const checked = Array.from(document.querySelectorAll('.product-checkbox:checked'));
                const count = checked.length;
                selectedCountSpan.innerText = `${count} selected`;
                bulkDeleteBtn.disabled = count === 0;
                bulkExportBtn.disabled = count === 0;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            if (selectAllDesktop) {
                selectAllDesktop.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });
        }

        // ========== BULK DELETE ==========
        bulkDeleteBtn?.addEventListener('click', async function() {
            const ids = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            const result = await Swal.fire({
                title: 'Bulk Delete',
                html: `Delete <strong>${ids.length}</strong> product(s)?<br><small class="text-muted">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });

            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch('{{ route("admin.products.bulk-delete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ ids })
                    });
                    if (!response.ok) throw new Error('Delete failed');
                    const data = await response.json();
                    if (data.success) {
                        ids.forEach(id => {
                            document.querySelectorAll(`.product-row-${id}`).forEach(el => {
                                el.style.animation = 'slideUpIn 0.3s ease-out reverse';
                                setTimeout(() => el.remove(), 300);
                            });
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        updateBulkActions();
                        fetchProducts(window.location.href);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not delete products.'
                    });
                }
            }
        });

        // ========== BULK EXPORT ==========
        bulkExportBtn?.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            Swal.fire({
                title: 'Exporting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const url = `{{ route('admin.products.export', 'csv') }}?ids=${ids.join(',')}`;
            window.location.href = url;
            setTimeout(() => Swal.close(), 1500);
        });

        // ========== EXPORT ALL ==========
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Exporting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            window.location.href = '{{ route('admin.products.export', 'csv') }}';
            setTimeout(() => Swal.close(), 1500);
        });

        // ========== SINGLE DELETE ==========
        window.deleteProduct = async function(id, name) {
            const result = await Swal.fire({
                title: 'Delete Product?',
                html: `Delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch(`/admin/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Delete failed');
                    const data = await response.json();
                    if (data.success) {
                        document.querySelectorAll(`.product-row-${id}`).forEach(el => {
                            el.style.animation = 'slideUpIn 0.3s ease-out reverse';
                            setTimeout(() => el.remove(), 300);
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => { fetchProducts(window.location.href); });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not delete product.'
                    });
                }
            }
        };

        // ========== INIT ==========
        attachCheckboxHandlers();

        window.addEventListener('popstate', () => {
            fetchProducts(window.location.href);
        });

        // Animate stats
        window.addEventListener('load', () => {
            const statCards = document.querySelectorAll('.stat-card-modern');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // ========== ATTACH ACTION BUTTONS ==========
        function attachActionButtons() {
            // re-attach is handled via global window.showProduct / window.editProduct / window.deleteProduct
        }

        // ========== SHOW PRODUCT MODAL ==========
        let currentShowProductId = null;

        window.showProduct = async function(id) {
            currentShowProductId = id;
            const modal = new bootstrap.Modal(document.getElementById('productShowModal'));
            document.getElementById('productShowBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>`;
            modal.show();

            try {
                const res = await fetch(`/admin/products/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Failed to load product');
                const p = await res.json();

                const stockBadgeClass = p.stock_status_color || 'secondary';
                const stockLabel = p.stock_status_label || 'Unknown';
                const stockQty = p.current_stock ?? 0;
                const productImagePlaceholder = `<div style="width:100%;height:100%;background:linear-gradient(135deg,#f0f4ff,#faf5ff);display:flex;align-items:center;justify-content:center;font-size:3rem;color:#667eea;"><i class="fas fa-box"></i></div>`;
                const imgHtml = p.image
                    ? `<img src="${normalizeImagePath(p.image)}" alt="" style="width:100%;height:220px;object-fit:cover;border-radius:12px;" onerror="this.outerHTML='${productImagePlaceholder.replace(/'/g, '&#39;')}';">`
                    : productImagePlaceholder;

                document.getElementById('productShowBody').innerHTML = `
                    <div class="show-modal-hero">
                        <div class="show-modal-img-wrap">
                            ${imgHtml}
                        </div>
                        <div class="show-modal-identity">
                            <h4 class="show-modal-product-name">${p.product_name}</h4>
                            <div class="show-modal-product-code"><i class="fas fa-barcode me-1"></i>${p.product_code}</div>
                            <span class="badge show-modal-stock-badge bg-${stockBadgeClass}">
                                <i class="fas fa-cubes me-1"></i>${stockLabel}: ${stockQty} ${p.unit}
                            </span>
                        </div>
                    </div>
                    <div class="show-modal-info-body">
                        <div class="show-modal-section-label">Product Information</div>
                        <div class="show-modal-info-grid">
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-tag"></i></span>
                                <div>
                                    <div class="info-item-label">Category</div>
                                    <div class="info-item-value">${p.category ? p.category.category_name : '&mdash;'}</div>
                                </div>
                            </div>
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-truck"></i></span>
                                <div>
                                    <div class="info-item-label">Supplier</div>
                                    <div class="info-item-value">${p.supplier ? p.supplier.supplier_name : '&mdash;'}</div>
                                </div>
                            </div>
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-certificate"></i></span>
                                <div>
                                    <div class="info-item-label">Brand</div>
                                    <div class="info-item-value">${p.brand || '&mdash;'}</div>
                                </div>
                            </div>
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-weight"></i></span>
                                <div>
                                    <div class="info-item-label">Unit</div>
                                    <div class="info-item-value">${p.unit || '&mdash;'}</div>
                                </div>
                            </div>
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-layer-group"></i></span>
                                <div>
                                    <div class="info-item-label">Product Type</div>
                                    <div class="info-item-value">${p.product_type ? p.product_type.replace('_',' ').replace(/\b\w/g,c=>c.toUpperCase()) : '&mdash;'}</div>
                                </div>
                            </div>
                            <div class="show-modal-info-item">
                                <span class="info-item-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div>
                                    <div class="info-item-label">Reorder Level</div>
                                    <div class="info-item-value">${p.reorder_level ?? '&mdash;'}</div>
                                </div>
                            </div>
                            ${p.shelf_location ? `<div class="show-modal-info-item"><span class="info-item-icon"><i class="fas fa-map-marker-alt"></i></span><div><div class="info-item-label">Shelf Location</div><div class="info-item-value">${p.shelf_location}</div></div></div>` : ''}
                            ${p.has_expiry && p.expiry_date ? `<div class="show-modal-info-item"><span class="info-item-icon"><i class="fas fa-calendar-times"></i></span><div><div class="info-item-label">Expiry Date</div><div class="info-item-value">${p.expiry_date}</div></div></div>` : ''}
                        </div>
                        <div class="show-modal-section-label mt-3">Pricing</div>
                        <div class="show-modal-price-cards">
                            <div class="price-card price-card-selling">
                                <div class="price-card-label">Selling Price</div>
                                <div class="price-card-value">&#8369;${parseFloat(p.selling_price).toFixed(2)}</div>
                            </div>
                            <div class="price-card price-card-cost">
                                <div class="price-card-label">Cost Price</div>
                                <div class="price-card-value">&#8369;${parseFloat(p.cost_price).toFixed(2)}</div>
                            </div>
                            ${p.wholesale_price ? `<div class="price-card price-card-wholesale"><div class="price-card-label">Wholesale Price</div><div class="price-card-value">&#8369;${parseFloat(p.wholesale_price).toFixed(2)}</div></div>` : ''}
                        </div>
                        ${p.description ? `<div class="show-modal-section-label mt-3">Description</div><div class="show-modal-description">${p.description}</div>` : ''}
                    </div>`;

                // Wire up the Edit button inside show modal
                document.getElementById('showModalEditBtn').onclick = () => {
                    bootstrap.Modal.getInstance(document.getElementById('productShowModal')).hide();
                    editProduct(id);
                };

            } catch(err) {
                document.getElementById('productShowBody').innerHTML = `
                    <div class="text-center py-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <p>Failed to load product details.</p>
                    </div>`;
            }
        };

        // ========== EDIT PRODUCT MODAL ==========
        let editingProductId = null;

        window.editProduct = async function(id) {
            editingProductId = id;
            const modal = new bootstrap.Modal(document.getElementById('productEditModal'));
            document.getElementById('productEditBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>`;
            modal.show();

            try {
                const res = await fetch(`/admin/products/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Failed to load');
                const p = await res.json();

                // Build category options
                const catOptions = @json($categories).map(c =>
                    `<option value="${c.id}" ${c.id == p.category_id ? 'selected' : ''}>${c.category_name}</option>`
                ).join('');

                // Build supplier options
                const supOptions = `<option value="">No Supplier</option>` + @json($suppliers).map(s =>
                    `<option value="${s.id}" ${s.id == p.supplier_id ? 'selected' : ''}>${s.supplier_name}</option>`
                ).join('');

                document.getElementById('productEditBody').innerHTML = `
                <form id="editProductForm" enctype="multipart/form-data" class="product-edit-form">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="product-edit-section">
                                <div class="product-edit-section-title">Product Details</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="product_name" class="form-control" value="${escHtml(p.product_name)}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                        <input type="text" name="product_code" class="form-control" value="${escHtml(p.product_code)}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Barcode</label>
                                        <input type="text" name="barcode" class="form-control" value="${escHtml(p.barcode || '')}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>${catOptions}</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_id" class="form-select">${supOptions}</select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Brand</label>
                                        <input type="text" name="brand" class="form-control" value="${escHtml(p.brand || '')}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                                        <input type="text" name="unit" class="form-control" value="${escHtml(p.unit)}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Product Type <span class="text-danger">*</span></label>
                                        <select name="product_type" class="form-select" required>
                                            <option value="perishable" ${p.product_type=='perishable'?'selected':''}>Perishable</option>
                                            <option value="non_perishable" ${p.product_type=='non_perishable'?'selected':''}>Non-Perishable</option>
                                            <option value="equipment" ${p.product_type=='equipment'?'selected':''}>Equipment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">&#8369;</span>
                                            <input type="number" name="cost_price" class="form-control" value="${p.cost_price}" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">&#8369;</span>
                                            <input type="number" name="selling_price" class="form-control" value="${p.selling_price}" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Wholesale Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">&#8369;</span>
                                            <input type="number" name="wholesale_price" class="form-control" value="${p.wholesale_price || ''}" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                                        <input type="number" name="reorder_level" class="form-control" value="${p.reorder_level}" min="0" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Min Level</label>
                                        <input type="number" name="min_level" class="form-control" value="${p.min_level || ''}" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Max Level</label>
                                        <input type="number" name="max_level" class="form-control" value="${p.max_level || ''}" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Discount (%)</label>
                                        <input type="number" name="discount_percent" class="form-control" value="${p.discount_percent || 0}" step="0.01" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tax Rate (%)</label>
                                        <input type="number" name="tax_rate" class="form-control" value="${p.tax_rate || 0}" step="0.01" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Shelf Location</label>
                                        <input type="text" name="shelf_location" class="form-control" value="${escHtml(p.shelf_location || '')}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="product-edit-image-card h-100">
                                <div class="product-edit-section-title"><i class="fas fa-image me-2"></i>Product Image</div>

                                <div class="image-upload-wrapper product-edit-upload-zone p-3 text-center d-flex align-items-center justify-content-center"
                                     onclick="document.getElementById('editProductImage').click()">

                                    <input type="file" name="image" id="editProductImage" class="d-none" accept="image/*" onchange="previewEditImage(this)">

                                    <div id="editImagePreviewContainer">
                                        ${p.image ? `
                                            <div class="position-relative d-inline-block">
                                                <img src="${normalizeImagePath(p.image)}" class="product-edit-preview-image">
                                            </div>
                                            <div class="mt-3">
                                                <p class="mb-1 fw-semibold text-primary"><i class="fas fa-sync-alt me-2"></i>Click to change image</p>
                                            </div>
                                        ` : `
                                            <div class="py-3">
                                                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-3" style="width:70px;height:70px;color:#667eea;">
                                                    <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                                </div>
                                                <p class="mb-1 fw-bold text-dark">Click to upload image</p>
                                            </div>
                                        `}
                                    </div>
                                </div>

                                ${p.image ? `
                                <div class="mt-3 text-center">
                                    <input type="checkbox" name="remove_image" value="1" id="removeImgCheck" class="d-none">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold"
                                        onclick="const cb=document.getElementById('removeImgCheck');cb.checked=!cb.checked;this.classList.toggle('active',cb.checked);this.innerHTML=cb.checked?'<i class=\'fas fa-undo me-1\'></i>Undo Remove':'<i class=\'fas fa-trash-alt me-1\'></i>Remove Current Image';">
                                        <i class="fas fa-trash-alt me-1"></i>Remove Current Image
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </form>`;

            } catch(err) {
                document.getElementById('productEditBody').innerHTML = `
                    <div class="text-center py-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <p>Failed to load product for editing.</p>
                    </div>`;
            }
        };

        // HTML escape helper
        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function normalizeImagePath(path) {
            if (!path) return '';
            let normalized = String(path).replace(/\\/g, '/').trim();
            if (!normalized) return '';

            if (normalized.startsWith('http://') || normalized.startsWith('https://') || normalized.startsWith('/')) {
                return normalized;
            }

            if (!normalized.includes('/')) {
                normalized = `uploads/products/${normalized}`;
            }

            return `/${normalized}`;
        }

        // ========== PREVIEW EDIT IMAGE ==========
        window.previewEditImage = function(input) {
            const container = document.getElementById('editImagePreviewContainer');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.innerHTML = `
                        <div class="position-relative d-inline-block pt-2">
                            <img src="${e.target.result}" class="product-edit-preview-image border border-2 border-primary" style="animation: scaleIn 0.3s ease-out;">
                            <div class="mt-3 text-success fw-bold d-flex align-items-center justify-content-center bg-success-subtle py-2 px-3 rounded-pill">
                                <i class="fas fa-check-circle me-2"></i> New image selected
                            </div>
                        </div>
                    `;
                    // Uncheck "remove image" if a new file is actually chosen
                    const removeCheck = document.getElementById('removeImgCheck');
                    if (removeCheck) removeCheck.checked = false;
                }
                reader.readAsDataURL(input.files[0]);
            }
        };

        // ========== SUBMIT EDIT FORM ==========
        window.submitEditForm = async function() {
            const form = document.getElementById('editProductForm');
            if (!form) return;
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const minLevel = parseInt(form.querySelector('[name="min_level"]')?.value || '', 10);
            const maxLevel = parseInt(form.querySelector('[name="max_level"]')?.value || '', 10);
            if (!Number.isNaN(minLevel) && !Number.isNaN(maxLevel) && minLevel > maxLevel) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Levels',
                    text: 'Min level cannot be greater than max level.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            const saveBtn = document.getElementById('saveEditBtn');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            try {
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                const res = await fetch(`/admin/products/${editingProductId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                let data = {};
                const contentType = res.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    data = await res.json();
                }

                if (res.ok && data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('productEditModal')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message || 'Product updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        fetchProducts(window.location.href);
                    });
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Update failed.');
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: errors, confirmButtonColor: '#667eea' });
                }
            } catch(err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Could not save product.', confirmButtonColor: '#667eea' });
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
            }
        };

    })();
</script>
@endpush


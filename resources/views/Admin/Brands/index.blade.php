@extends('layouts.admin')

@section('title', 'Brands - CJ\'s Minimart')

@section('content')
<div class="brands-page">

    {{-- ===== Alert Messages ===== --}}
    @if (session('success'))
    <div class="alert-toast alert-toast-success" id="successAlert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert-toast alert-toast-danger" id="errorAlert">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box brands-header-icon">
                <i class="fas fa-trademark"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Brands</h1>
                <p class="page-subtitle">Manage product brands and manufacturers</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.brands.create') }}" class="btn-header-action btn-header-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Brand</span>
            </a>
        </div>
    </div>

    {{-- ===== Stats Row ===== --}}
    <div class="brands-stats-row">
        <div class="brands-stat-card">
            <div class="brands-stat-icon stat-primary">
                <i class="fas fa-trademark"></i>
            </div>
            <div class="brands-stat-body">
                <span class="brands-stat-label">Total Brands</span>
                <span class="brands-stat-value">{{ $brands->total() }}</span>
            </div>
        </div>
        <div class="brands-stat-card">
            <div class="brands-stat-icon stat-info">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="brands-stat-body">
                <span class="brands-stat-label">Total Products</span>
                <span class="brands-stat-value">{{ $totalProducts ?? 0 }}</span>
            </div>
        </div>
    </div>


    {{-- ===== Filter Section ===== --}}
    <div class="brands-filter-card">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="brands-filter-form" id="filterForm">
            <div class="filter-search-wrap">
                <i class="fas fa-search filter-search-icon"></i>
                <input type="text" name="search" class="filter-search-input"
                       placeholder="Search by name or code..."
                       value="{{ request('search') }}"
                       id="searchInput">
                @if (request('search'))
                <button type="button" class="filter-clear-btn" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                </button>
                @endif
            </div>

            <button type="submit" class="filter-btn-submit">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            @if (request()->hasAny(['search']))
            <a href="{{ route('admin.brands.index') }}" class="filter-btn-reset">
                <i class="fas fa-undo"></i>
                <span>Reset</span>
            </a>
            @endif
        </form>
    </div>

    {{-- ===== Brands Table ===== --}}
    <div class="brands-table-card">
        <div class="brands-table-header">
            <div class="brands-table-title">
                <i class="fas fa-list-alt text-primary"></i>
                <h5>Brand List</h5>
                <span class="brands-count-badge">{{ $brands->total() }} total</span>
            </div>
            <div class="brands-table-actions">
                <a href="{{ route('admin.brands.export') }}" class="btn-export" title="Export CSV">
                    <i class="fas fa-download"></i>
                    <span class="d-none d-sm-inline">Export CSV</span>
                </a>
                <select class="per-page-select" onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ request('per_page',15)==15?'selected':'' }}>15 / page</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page')==25?'selected':'' }}>25 / page</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page')==50?'selected':'' }}>50 / page</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page')==100?'selected':'' }}>100 / page</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="brands-table" id="brandsTable">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort'=>'brand_name','direction'=>(request('sort')=='brand_name'&&request('direction')=='asc'?'desc':'asc')]) }}" class="sort-link">
                                Brand
                                @if (request('sort')=='brand_name')
                                    <i class="fas fa-sort-{{ request('direction')=='asc'?'up':'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Code</th>
                        <th>Website</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort'=>'products_count','direction'=>(request('sort')=='products_count'&&request('direction')=='asc'?'desc':'asc')]) }}" class="sort-link">
                                Products
                                @if (request('sort')=='products_count')
                                    <i class="fas fa-sort-{{ request('direction')=='asc'?'up':'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($brands) ? count($brands) > 0 : !empty($brands))
@foreach($brands as $brand)
                    <tr data-brand-id="{{ $brand->id }}">
                        {{-- Brand --}}
                        <td data-label="Brand">
                            <div class="brand-cell">
                                @if ($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->brand_name }}" class="brand-avatar">
                                @else
                                    <div class="brand-avatar-placeholder">
                                        {{ strtoupper(substr($brand->brand_name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="brand-info">
                                    <div class="brand-name">{{ $brand->brand_name }}</div>
                                    @if ($brand->description)
                                        <div class="brand-desc">{{ Str::limit($brand->description, 55) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Code --}}
                        <td data-label="Code">
                            <span class="brand-code-badge">{{ $brand->brand_code ?? 'N/A' }}</span>
                        </td>

                        {{-- Website --}}
                        <td data-label="Website">
                            @if ($brand->website)
                                <a href="{{ $brand->website }}" target="_blank" class="brand-website-link" title="{{ $brand->website }}">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    {{ Str::limit(str_replace(['https://','http://','www.'], '', $brand->website), 20) }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Products --}}
                        <td data-label="Products">
                            <span class="brand-products-badge {{ $brand->products_count > 0 ? 'has-products' : '' }}">
                                <i class="fas fa-box me-1"></i>{{ $brand->products_count }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td data-label="Actions">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.brands.show', $brand->id) }}"
                                   class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                   class="btn-edit" title="Edit Brand">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($brand->products_count == 0)
                                <button type="button"
                                        class="btn-del"
                                        onclick="confirmDelete('{{ $brand->id }}', '{{ addslashes($brand->brand_name) }}')"
                                        title="Delete Brand">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @else
                                <button type="button"
                                        class="btn-del opacity-50"
                                        title="Cannot delete: has {{ $brand->products_count }} product(s)"
                                        style="cursor: not-allowed;"
                                        disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="5">
                            <div class="brands-empty-state">
                                <div class="brands-empty-icon">
                                    <i class="fas fa-trademark"></i>
                                </div>
                                <h4>No Brands Found</h4>
                                <p>{{ request()->hasAny(['search']) ? 'No brands match your search criteria. Try adjusting your filters.' : 'Get started by adding your first brand.' }}</p>
                                @if (request()->hasAny(['search']))
                                    <a href="{{ route('admin.brands.index') }}" class="btn-add-brand">
                                        <i class="fas fa-undo"></i> Clear Filters
                                    </a>
                                @else
                                    <a href="{{ route('admin.brands.create') }}" class="btn-add-brand">
                                        <i class="fas fa-plus-circle"></i> Add New Brand
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($brands->hasPages())
        <div class="brands-pagination-wrap">
            <div class="pagination-info">
                <i class="fas fa-info-circle me-1"></i>
                Showing {{ $brands->firstItem() }}–{{ $brands->lastItem() }} of {{ $brands->total() }} brands
            </div>
            <div class="pagination-links">
                {{ $brands->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

@endsection

@push('styles')
<style>
/* =========================================================
   BRANDS PAGE — FULL RESPONSIVE STYLES
   ========================================================= */

.brands-page {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* --- Alert Toast --- */
.alert-toast {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}
.alert-toast-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-toast-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.alert-toast button  { margin-left: auto; background: none; border: none; cursor: pointer; color: inherit; opacity: 0.7; }
.alert-toast button:hover { opacity: 1; }
@keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

/* --- Page Header --- */
.brands-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem 2rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    flex-wrap: wrap;
}
.brands-header-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.brands-header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #fff;
    box-shadow: 0 4px 12px rgba(67,97,238,.25);
    flex-shrink: 0;
}
.brands-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1f2e;
    margin: 0 0 0.15rem;
}
.brands-subtitle {
    color: #6c757d;
    margin: 0;
    font-size: 0.95rem;
}
.btn-add-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.7rem 1.5rem;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(67,97,238,.2);
}
.btn-add-brand:hover {
    background: linear-gradient(135deg, #3a56d4, #2f4ab5);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(67,97,238,.3);
    color: #fff;
}

/* --- Stats Row --- */
.brands-stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
.brands-stat-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: all 0.2s;
}
.brands-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.brands-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}
.stat-primary { background: linear-gradient(135deg, #4361ee, #3a56d4); }
.stat-success { background: linear-gradient(135deg, #10b981, #059669); }
.stat-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-info    { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.brands-stat-body { flex: 1; min-width: 0; }
.brands-stat-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    margin-bottom: 0.4rem;
}
.brands-stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #1a1f2e;
    line-height: 1;
}

/* --- Filter Card --- */
.brands-filter-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.brands-filter-form {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.filter-search-wrap {
    position: relative;
    flex: 1;
    min-width: 180px;
}
.filter-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
    font-size: 0.9rem;
    pointer-events: none;
}
.filter-search-input {
    width: 100%;
    height: 46px;
    padding: 0 2.5rem 0 2.75rem;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    font-size: 0.95rem;
    background: #f8f9fa;
    transition: all 0.2s;
    color: #2d3436;
}
.filter-search-input:focus {
    outline: none;
    border-color: #4361ee;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
}
.filter-clear-btn {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #adb5bd;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
}
.filter-clear-btn:hover { color: #495057; }

.filter-btn-submit {
    height: 46px;
    padding: 0 1.5rem;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.filter-btn-submit:hover {
    background: linear-gradient(135deg, #3a56d4, #2f4ab5);
    transform: translateY(-1px);
}
.filter-btn-reset {
    height: 46px;
    padding: 0 1.25rem;
    border: 1px solid #dee2e6;
    color: #6c757d;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    background: #fff;
    transition: all 0.2s;
    white-space: nowrap;
}
.filter-btn-reset:hover { background: #f1f3f5; color: #343a40; }

/* --- Table Card --- */
.brands-table-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.brands-table-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
    flex-wrap: wrap;
}
.brands-table-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.brands-table-title h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1f2e;
    margin: 0;
}
.brands-count-badge {
    background: #e9ecef;
    color: #495057;
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}
.brands-table-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid #10b981;
    color: #10b981;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    background: #fff;
}
.btn-export:hover { background: #10b981; color: #fff; }
.per-page-select {
    height: 38px;
    padding: 0 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 0.9rem;
    background: #fff;
    cursor: pointer;
    color: #495057;
}
.per-page-select:focus { outline: none; border-color: #4361ee; }

/* --- Sort Links --- */
.sort-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: inherit;
    text-decoration: none;
    transition: color 0.15s;
}
.sort-link:hover { color: #4361ee; }

/* --- Brands Table --- */
.brands-table {
    width: 100%;
    border-collapse: collapse;
}
.brands-table thead th {
    background: #fff;
    padding: 1rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
}
.brands-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f3f5;
    vertical-align: middle;
    font-size: 0.95rem;
    color: #2d3436;
}
.brands-table tbody tr:last-child td { border-bottom: none; }
.brands-table tbody tr:hover { background: #fafbff; }

/* --- Brand Cell --- */
.brand-cell {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    min-width: 180px;
}
.brand-avatar {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e9ecef;
    flex-shrink: 0;
}
.brand-avatar-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4361ee22, #4361ee44);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
    color: #4361ee;
    flex-shrink: 0;
    border: 1px solid #4361ee22;
}
.brand-info { flex: 1; min-width: 0; }
.brand-name { font-weight: 700; color: #1a1f2e; margin-bottom: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.brand-desc { font-size: 0.82rem; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; }

/* --- Badges --- */
.brand-code-badge {
    display: inline-block;
    background: #f1f3f5;
    color: #495057;
    padding: 0.35rem 0.85rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}
.brand-products-badge {
    display: inline-flex;
    align-items: center;
    background: #eff6ff;
    color: #2563eb;
    padding: 0.35rem 0.85rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
}
.brand-products-badge.has-products { background: #dbeafe; }
.brand-website-link {
    color: #4361ee;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
}
.brand-website-link:hover { text-decoration: underline; }



/* Action buttons use global styles in admin layout */

/* --- Empty State --- */
.brands-empty-state {
    padding: 4rem 2rem;
    text-align: center;
}
.brands-empty-icon {
    width: 80px;
    height: 80px;
    background: #f1f3f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #adb5bd;
    margin: 0 auto 1.25rem;
}
.brands-empty-state h4 { font-weight: 700; color: #1a1f2e; margin-bottom: 0.5rem; }
.brands-empty-state p  { color: #6c757d; margin-bottom: 1.5rem; }

/* --- Pagination --- */
.brands-pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    flex-wrap: wrap;
    gap: 1rem;
}
.pagination-info { color: #6c757d; font-size: 0.9rem; }
.pagination-links .pagination { margin: 0; gap: 0.25rem; }
.pagination-links .page-link {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.45rem 0.85rem;
    color: #495057;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.pagination-links .page-link:hover { background: #4361ee; color: #fff; border-color: #4361ee; }
.pagination-links .page-item.active .page-link { background: #4361ee; color: #fff; border-color: #4361ee; }
.pagination-links .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; }

/* --- Modals --- */
.brands-modal { border: none; border-radius: 16px; overflow: hidden; }
.brands-modal-header {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}
.brands-modal-header.danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.brands-modal-header.info   { background: linear-gradient(135deg, #4361ee, #3a56d4); color: #fff; }
.brands-modal-header.success{ background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.brands-modal-icon {
    width: 44px;
    height: 44px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.brands-modal-title   { margin: 0; font-size: 1.1rem; font-weight: 700; }
.brands-modal-subtitle{ margin: 0; font-size: 0.85rem; opacity: 0.85; }
.brands-modal-close {
    margin-left: auto;
    background: rgba(255,255,255,.2);
    border: none;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}
.brands-modal-close:hover { background: rgba(255,255,255,.35); }
.brands-modal-body { padding: 1.5rem; }
.brands-modal-body p { margin: 0; color: #374151; }
.brands-modal-warning {
    margin-top: 0.9rem;
    padding: 0.75rem 1rem;
    background: #fef3c7;
    border-radius: 8px;
    color: #92400e;
    font-size: 0.9rem;
    font-weight: 500;
}
.brands-modal-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}
.btn-modal-cancel {
    padding: 0.6rem 1.25rem;
    border: 1px solid #dee2e6;
    border-radius: 9px;
    background: #fff;
    color: #6c757d;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-modal-cancel:hover { background: #f1f3f5; }
.btn-modal-danger {
    padding: 0.6rem 1.25rem;
    border: none;
    border-radius: 9px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-modal-danger:hover { filter: brightness(1.1); }
.btn-modal-confirm {
    padding: 0.6rem 1.25rem;
    border: none;
    border-radius: 9px;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-modal-confirm:hover { filter: brightness(1.1); }

/* ===========================================
   RESPONSIVE BREAKPOINTS
   =========================================== */

/* Tablet: 2-column stats */
@media (max-width: 1100px) {
    .brands-stats-row { grid-template-columns: repeat(2, 1fr); }
}

/* Small tablet */
@media (max-width: 768px) {
    .brands-page { padding: 1rem; }

    .brands-header { padding: 1.25rem; flex-direction: column; align-items: flex-start; }
    .btn-add-brand { width: 100%; justify-content: center; }

    .brands-stats-row { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    .brands-stat-card { padding: 1rem; }
    .brands-stat-value { font-size: 1.6rem; }

    .brands-filter-form { gap: 0.5rem; }
    .filter-search-wrap { min-width: 100%; }

    .filter-btn-submit, .filter-btn-reset { width: 100%; justify-content: center; }

    .brands-table-header { flex-direction: column; align-items: flex-start; }
    .brands-table-actions { width: 100%; justify-content: space-between; }

    /* Card-style table on mobile */
    .brands-table thead { display: none; }
    .brands-table tbody tr {
        display: block;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        padding: 0.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
    }
    .brands-table tbody tr:hover { background: #fafbff; }
    .brands-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0.25rem;
        border: none;
        border-bottom: 1px solid #f1f3f5;
        font-size: 0.9rem;
    }
    .brands-table tbody td:last-child { border-bottom: none; }
    .brands-table tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #6c757d;
        flex-shrink: 0;
        margin-right: 0.5rem;
    }
    .brand-cell { flex-direction: row; }
    .brands-action-group { justify-content: flex-end; }
    .brands-pagination-wrap { flex-direction: column; text-align: center; }

    /* Fix table wrapper overflow on mobile */
    .brands-table-card { overflow: visible; }
    .table-responsive { overflow-x: visible; }
}

/* Mobile: 1-column stats */
@media (max-width: 480px) {
    .brands-stats-row { grid-template-columns: 1fr 1fr; }
    .brands-stat-value { font-size: 1.4rem; }
    .brands-title { font-size: 1.4rem; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add data-label attributes for mobile table
    const labels = ['Brand', 'Code', 'Website', 'Products', 'Actions'];
    document.querySelectorAll('#brandsTable tbody tr').forEach(row => {
        if (!row.querySelector('td[colspan]')) {
            row.querySelectorAll('td').forEach((td, i) => {
                td.setAttribute('data-label', labels[i] || '');
            });
        }
    });

    // Auto-dismiss alerts after 5 seconds
    ['successAlert', 'errorAlert'].forEach(id => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => el.remove(), 5000);
    });
});

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Delete Brand?',
        html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/brands/' + id;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}



// ---- Clear Search ----
function clearSearch() {
    document.querySelector('.filter-search-input').value = '';
    document.getElementById('filterForm').submit();
}

// ---- Toast Helper ----
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert-toast alert-toast-${type === 'success' ? 'success' : 'danger'}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
    document.querySelector('.brands-page').prepend(toast);
    setTimeout(() => toast.remove(), 5000);
}
</script>
@endpush

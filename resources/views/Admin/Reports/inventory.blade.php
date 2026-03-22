@extends('layouts.admin')

@section('title', 'Inventory Report - CJ\'s Minimart')

@section('content')
<div class="inventory-report-wrapper">
    <div class="container-fluid px-3 px-md-4 py-3 py-md-4">
        
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Inventory Report</h1>
                <p class="page-subtitle">Track and manage your product stock levels</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" onclick="exportInventory()">
                <i class="fas fa-download"></i>
                <span>Export Inventory</span>
            </button>
            <button class="btn-header-action btn-header-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
                <i class="fas fa-plus-circle"></i>
                <span>Add Stock</span>
            </button>
        </div>
    </div>

        <!-- CALCULATE STATISTICS FROM PRODUCTS -->
        @php
            // Initialize counters
            $totalProducts = $products->total();
            $totalStock = 0;
            $totalValue = 0;
            $lowStockProducts = 0;
            $outOfStockProducts = 0;
            
            // Calculate totals from products collection
            foreach($products as $product) {
                $productStock = $product->stocks->sum('quantity') ?? 0;
                $totalStock += $productStock;
                $totalValue += $productStock * ($product->price ?? 0);
                
                // Count low stock products (quantity <= 10)
                if($productStock <= 10 && $productStock > 0) {
                    $lowStockProducts++;
                }
                
                // Count out of stock products (quantity <= 0)
                if($productStock <= 0) {
                    $outOfStockProducts++;
                }
            }
            
            // Add out of stock to low stock count for display
            $lowStockProducts = $lowStockProducts + $outOfStockProducts;
        @endphp

        <!-- Summary Cards -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card primary">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Products</span>
                            <h3 class="stat-value">{{ number_format($totalProducts) }}</h3>
                            <small class="stat-footer">Active products in catalog</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card success">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Stock Items</span>
                            <h3 class="stat-value">{{ number_format($totalStock) }}</h3>
                            <small class="stat-footer">Units in inventory</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card warning">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Low Stock</span>
                            <h3 class="stat-value">{{ number_format($lowStockProducts) }}</h3>
                            <small class="stat-footer">Items below reorder level</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card info">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-coin"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Value</span>
                            <h3 class="stat-value">₱{{ number_format($totalValue, 2) }}</h3>
                            <small class="stat-footer">Current inventory value</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Products Container -->
        <div class="filter-products-container mb-4">
            <div class="filter-header mb-3">
                <h6 class="fw-semibold mb-1">
                    <i class="fas fa-filter me-2 text-primary"></i>Filter Products
                </h6>
                <p class="text-muted small mb-0">Refine inventory list by search, category, or supplier</p>
            </div>
            
            <form method="GET" action="{{ route('admin.reports.inventory') }}" class="filter-form">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-12 col-lg-5">
                        <div class="search-input-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   name="search"
                                   class="form-control" 
                                   placeholder="Search name, code, barcode..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Categories Dropdown -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="select-wrapper">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <!-- Suppliers Dropdown -->
                    <div class="col-12 col-sm-6 col-lg-2">
                        <div class="select-wrapper">
                            <select name="supplier" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers ?? [] as $sup)
                                <option value="{{ $sup->id }}" {{ request('supplier') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn btn-primary w-100 apply-filter-btn">
                            <i class="fas fa-check-circle me-2"></i>Apply
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <!-- Active Filters Display -->
        @if (request('search') || request('category') || request('supplier'))
        <div class="active-filters-container mb-4">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="active-filters-label">Active Filters:</span>
                <div class="filter-tags">
                    @if (request('search'))
                    <span class="filter-tag">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('admin.reports.inventory', array_merge(request()->except(['search', 'page']))) }}" class="remove-filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if (request('category'))
                    @php $categoryName = isset($categories) ? $categories->firstWhere('id', request('category'))->name ?? 'Unknown' : 'Unknown'; @endphp
                    <span class="filter-tag">
                        Category: {{ $categoryName }}
                        <a href="{{ route('admin.reports.inventory', array_merge(request()->except(['category', 'page']))) }}" class="remove-filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if (request('supplier'))
                    @php $supplierName = isset($suppliers) ? $suppliers->firstWhere('id', request('supplier'))->name ?? 'Unknown' : 'Unknown'; @endphp
                    <span class="filter-tag">
                        Supplier: {{ $supplierName }}
                        <a href="{{ route('admin.reports.inventory', array_merge(request()->except(['supplier', 'page']))) }}" class="remove-filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
                <a href="{{ route('admin.reports.inventory') }}" class="btn btn-link btn-sm text-danger">
                    <i class="fas fa-times me-1"></i>Clear all
                </a>
            </div>
        </div>
        @endif

        <!-- Inventory Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Inventory List
                    </h5>
                    <small class="text-muted">{{ $products->total() }} products found</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Code/Barcode</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Current Stock</th>
                                <th>Price</th>
                                <th>Stock Value</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($products) ? count($products) > 0 : !empty($products))
@foreach($products as $product)
                            @php
                                $currentStock = $product->stocks->sum('quantity') ?? 0;
                                $stockValue = $currentStock * ($product->price ?? 0);
                                $stockClass = $currentStock <= 5 ? 'danger' : ($currentStock <= 10 ? 'warning' : 'success');
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="product-thumbnail">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="40" height="40" class="rounded">
                                            @else
                                                <div class="no-image">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block">{{ $product->name }}</span>
                                            <small class="text-muted">{{ Str::limit($product->description ?? '', 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $product->code ?? 'N/A' }}</span>
                                    @if ($product->barcode)
                                    <br><small class="text-muted">{{ $product->barcode }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="category-badge">
                                        <i class="fas fa-folder me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="supplier-badge">
                                        <i class="fas fa-truck me-1"></i>{{ $product->supplier->name ?? 'No Supplier' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="stock-badge stock-{{ $stockClass }}">
                                        {{ $currentStock }} units
                                    </span>
                                </td>
                                <td class="fw-bold">₱{{ number_format($product->price ?? 0, 2) }}</td>
                                <td class="fw-bold text-primary">₱{{ number_format($stockValue, 2) }}</td>
                                <td>
                                    @if ($currentStock <= 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>Out of Stock
                                        </span>
                                    @elseif ($currentStock <= 5)
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>Critical
                                        </span>
                                    @elseif ($currentStock <= 10)
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                            <i class="fas fa-chart-line me-1"></i>Low Stock
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>In Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="action-buttons">
                                        <button class="btn-action view" onclick="viewProduct({{ $product->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn-action stock" onclick="showStockInModal({{ $product->id }}, '{{ $product->name }}')" title="Add Stock">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
@else
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-box-open fa-4x mb-3 text-muted opacity-25"></i>
                                    <h6 class="text-muted">No Products Found</h6>
                                    <p class="text-muted small">Try adjusting your filters</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                    </small>
                    <div class="pagination-wrapper">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Add Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockInForm" method="POST" action="{{ route('admin.inventory.stock-in.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Product</label>
                        <select name="product_id" class="form-select" id="modal_product_id" required>
                            <option value="">Choose product...</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantity to Add</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="unit_cost" class="form-control" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reference/PO Number</label>
                        <input type="text" name="reference" class="form-control" placeholder="e.g., PO-2024-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.inventory-report-wrapper {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Categories Header Container */
.categories-header-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.03);
    transition: all 0.3s ease;
}

.categories-header-container:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
}

.categories-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.02);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.stat-card.primary { border-left: 4px solid #667eea; }
.stat-card.success { border-left: 4px solid #10b981; }
.stat-card.warning { border-left: 4px solid #f59e0b; }
.stat-card.info { border-left: 4px solid #0ea5e9; }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.stat-card.primary .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
.stat-card.success .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-card.info .stat-icon { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0.25rem 0;
    color: #1e293b;
}

.stat-footer {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Filter Products Container */
.filter-products-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.03);
}

.filter-header {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.75rem;
    margin-bottom: 1rem;
}

/* Search Input Group */
.search-input-group {
    position: relative;
    width: 100%;
}

.search-input-group .search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 10;
}

.search-input-group input {
    padding-left: 45px;
    height: 48px;
    border-radius: 12px;
    border: 2px solid #f1f5f9;
    background: #f8fafc;
    transition: all 0.3s;
}

.search-input-group input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

/* Select Wrapper */
.select-wrapper {
    position: relative;
    width: 100%;
}

.select-wrapper select {
    height: 48px;
    border-radius: 12px;
    border: 2px solid #f1f5f9;
    background: #f8fafc;
    padding-right: 40px;
    appearance: none;
    cursor: pointer;
    transition: all 0.3s;
}

.select-wrapper select:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.select-wrapper .select-arrow {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 0.85rem;
}

/* Apply Button */
.apply-filter-btn {
    height: 48px;
    border-radius: 12px;
    font-weight: 600;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s;
}

.apply-filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

/* Active Filters */
.active-filters-container {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    border: 1px solid #e9ecef;
}

.active-filters-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #64748b;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-tag {
    background: #f1f5f9;
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-tag .remove-filter {
    color: #94a3b8;
    transition: all 0.2s;
}

.filter-tag .remove-filter:hover {
    color: #ef4444;
}

/* Product Thumbnail */
.product-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    overflow: hidden;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 1.25rem;
}

/* Badges */
.category-badge {
    background: #f1f5f9;
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.supplier-badge {
    background: #e9e9ff;
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #5f63f2;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.stock-badge {
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.stock-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stock-warning {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stock-danger {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: transparent;
}

.btn-action.view {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.btn-action.edit {
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
}

.btn-action.stock {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
}

.btn-action:hover {
    transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .categories-header-container {
        padding: 1.25rem;
    }
    
    .filter-products-container {
        padding: 1.25rem;
    }
    
    .categories-icon {
        width: 45px;
        height: 45px;
        font-size: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .search-input-group input,
    .select-wrapper select,
    .apply-filter-btn {
        height: 44px;
    }
}

@media (max-width: 576px) {
    .categories-header-container {
        padding: 1rem;
    }
    
    .filter-products-container {
        padding: 1rem;
    }
    
    .categories-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
    
    .table th, .table td {
        white-space: nowrap;
    }
}

/* Table Styles */
.table th {
    font-weight: 600;
    font-size: 0.85rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f8fafc;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8fafc;
}

/* Pagination */
.pagination-wrapper .pagination {
    margin-bottom: 0;
    gap: 5px;
}

.pagination-wrapper .page-link {
    border: none;
    border-radius: 8px !important;
    padding: 0.5rem 1rem;
    color: #64748b;
    font-weight: 500;
    background: #f8fafc;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
}

.modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 1.5rem;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Show Stock In Modal with product pre-selected
function showStockInModal(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('addStockModal'));
    const select = document.getElementById('modal_product_id');
    
    // Set the selected product
    if (select) {
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value == id) {
                select.selectedIndex = i;
                break;
            }
        }
    }
    
    modal.show();
}

// Export Inventory
function exportInventory() {
    Swal.fire({
        title: 'Export Inventory',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Excel',
        denyButtonText: 'PDF',
        cancelButtonText: 'CSV'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Export Started',
                text: 'Your Excel report is being generated',
                timer: 1500,
                showConfirmButton: false
            });
        } else if (result.isDenied) {
            Swal.fire({
                icon: 'success',
                title: 'Export Started',
                text: 'Your PDF report is being generated',
                timer: 1500,
                showConfirmButton: false
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                icon: 'success',
                title: 'Export Started',
                text: 'Your CSV report is being generated',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// View Product
function viewProduct(id) {
    window.location.href = '/admin/products/' + id;
}

// Edit Product
function editProduct(id) {
    window.location.href = '/admin/products/' + id + '/edit';
}

// Auto-submit filters on change
document.querySelectorAll('select[name="category"], select[name="supplier"]').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Search with debounce
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.closest('form').submit();
    }, 500);
});

// Show success message if exists
@if (session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if (session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: "{{ session('error') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endpush\\\\\\\

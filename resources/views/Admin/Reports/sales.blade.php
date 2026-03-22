@extends('layouts.admin')

@section('title', 'Stock Management - CJ\'s Minimart')

@section('content')
<div class="stock-management-wrapper">
    <div class="container-fluid px-3 px-md-4 py-3 py-md-4">
        
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Sales Report</h1>
                <p class="page-subtitle">Detailed analysis of sales transactions and performance</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" onclick="exportProducts()">
                <i class="fas fa-download"></i>
                <span>Export Report</span>
            </button>
        </div>
    </div>

        <!-- Filter Products Container -->
        <div class="filter-products-container mb-4">
            <div class="filter-header mb-3">
                <h6 class="fw-semibold mb-1">
                    <i class="fas fa-filter me-2 text-primary"></i>Filter Products
                </h6>
                <p class="text-muted small mb-0">Refine product list by search, category, or supplier</p>
            </div>
            
            <div class="filter-form">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-12 col-md-5">
                        <div class="search-input-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   class="form-control" 
                                   placeholder="Search name, code, barcode..." 
                                   id="productSearch"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Categories Dropdown -->
                    <div class="col-12 col-md-3">
                        <div class="select-wrapper">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <!-- Suppliers Dropdown -->
                    <div class="col-12 col-md-2">
                        <div class="select-wrapper">
                            <select class="form-select" id="supplierFilter">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="col-12 col-md-2">
                        <button class="btn btn-primary w-100 apply-filter-btn" onclick="applyFilters()">
                            <i class="fas fa-check-circle me-2"></i>Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div class="active-filters-container mb-4" id="activeFilters" style="display: none;">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="active-filters-label">Active Filters:</span>
                <div class="filter-tags" id="filterTags"></div>
                <button class="btn btn-link btn-sm text-danger" onclick="clearAllFilters()">
                    <i class="fas fa-times me-1"></i>Clear all
                </button>
            </div>
        </div>

        <!-- Products Table Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-boxes me-2 text-primary"></i>
                        Products List
                    </h5>
                    <div class="d-flex gap-2 mt-2 mt-sm-0">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportProducts()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus-circle me-1"></i>Add Product
                        </button>
                    </div>
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
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($products ?? []) ? count($products ?? []) > 0 : !empty($products ?? []))
@foreach($products ?? [] as $product)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="product-thumbnail">
                                            @if ($product->image)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" width="40" height="40" class="rounded">
                                            @else
                                                <div class="no-image">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block">{{ $product->name }}</span>
                                            <small class="text-muted">{{ $product->description ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $product->code ?? 'N/A' }}</span>
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
                                    @php
                                        $stockClass = $product->stock <= 5 ? 'danger' : ($product->stock <= 10 ? 'warning' : 'success');
                                    @endphp
                                    <span class="stock-badge stock-{{ $stockClass }}">
                                        {{ $product->stock ?? 0 }} units
                                    </span>
                                </td>
                                <td class="fw-bold text-primary">₱{{ number_format($product->price ?? 0, 2) }}</td>
                                <td>
                                    @if ($product->is_active ?? true)
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="action-buttons">
                                        <button class="btn-action view" onclick="viewProduct({{ $product->id }})" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" onclick="editProduct({{ $product->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" onclick="deleteProduct({{ $product->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
@else
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-box-open fa-4x mb-3 text-muted opacity-25"></i>
                                    <h6 class="text-muted">No Products Found</h6>
                                    <p class="text-muted small">Try adjusting your filters or add a new product</p>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                        <i class="fas fa-plus-circle me-2"></i>Add Product
                                    </button>
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
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} entries
                    </small>
                    <nav>
                        {{ ($products ?? collect())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-folder-plus me-2 text-primary"></i>Add New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" placeholder="Enter category name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Enter category description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">
                    <i class="fas fa-save me-2"></i>Save Category
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-box-plus me-2 text-primary"></i>Add New Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" class="form-control" placeholder="Enter product name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Code</label>
                            <input type="text" class="form-control" placeholder="Enter product code">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select">
                                <option>Select category</option>
                                <option>Electronics</option>
                                <option>Clothing</option>
                                <option>Food & Beverages</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select class="form-select">
                                <option>Select supplier</option>
                                <option>Supplier A</option>
                                <option>Supplier B</option>
                                <option>Supplier C</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock</label>
                            <input type="number" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="fas fa-save me-2"></i>Save Product
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.stock-management-wrapper {
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

.add-category-btn {
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s;
}

.add-category-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
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

.filter-tag i {
    cursor: pointer;
    font-size: 0.75rem;
    color: #94a3b8;
    transition: all 0.2s;
}

.filter-tag i:hover {
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

.btn-action.delete {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
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
    
    .add-category-btn {
        width: 100%;
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
.pagination {
    margin-bottom: 0;
    gap: 5px;
}

.page-link {
    border: none;
    border-radius: 8px !important;
    padding: 0.5rem 1rem;
    color: #64748b;
    font-weight: 500;
    background: #f8fafc;
}

.page-item.active .page-link {
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
<script>
// Apply Filters Function
function applyFilters() {
    const search = document.getElementById('productSearch').value;
    const category = document.getElementById('categoryFilter').value;
    const supplier = document.getElementById('supplierFilter').value;
    
    // Show active filters container
    const activeFiltersDiv = document.getElementById('activeFilters');
    const filterTagsDiv = document.getElementById('filterTags');
    
    let filters = [];
    if (search) filters.push(`Search: "${search}"`);
    if (category) {
        const categoryText = document.getElementById('categoryFilter').selectedOptions[0].text;
        filters.push(`Category: ${categoryText}`);
    }
    if (supplier) {
        const supplierText = document.getElementById('supplierFilter').selectedOptions[0].text;
        filters.push(`Supplier: ${supplierText}`);
    }
    
    if (filters.length > 0) {
        filterTagsDiv.innerHTML = filters.map(filter => `
            <span class="filter-tag">
                ${filter}
                <i class="fas fa-times" onclick="removeFilter('${filter.split(':')[0].toLowerCase()}')"></i>
            </span>
        `).join('');
        activeFiltersDiv.style.display = 'block';
    } else {
        activeFiltersDiv.style.display = 'none';
    }
    
    // Here you would normally submit the form or make an AJAX request
    console.log('Applying filters:', { search, category, supplier });
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Filters Applied',
        text: 'Product list has been updated',
        timer: 1500,
        showConfirmButton: false
    });
}

// Remove specific filter
function removeFilter(filterType) {
    if (filterType.includes('search')) {
        document.getElementById('productSearch').value = '';
    } else if (filterType.includes('category')) {
        document.getElementById('categoryFilter').value = '';
    } else if (filterType.includes('supplier')) {
        document.getElementById('supplierFilter').value = '';
    }
    applyFilters();
}

// Clear all filters
function clearAllFilters() {
    document.getElementById('productSearch').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('supplierFilter').value = '';
    document.getElementById('activeFilters').style.display = 'none';
    
    // Refresh the page or make AJAX request
    location.reload();
}

// Export Products
function exportProducts() {
    Swal.fire({
        title: 'Export Products',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Excel',
        denyButtonText: 'PDF',
        cancelButtonText: 'CSV'
    }).then((result) => {
        if (result.isConfirmed || result.isDenied || result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                icon: 'success',
                title: 'Export Started',
                text: 'Your file is being generated',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// View Product
function viewProduct(id) {
    Swal.fire({
        title: 'Product Details',
        html: `
            <div class="text-start">
                <p><strong>Product ID:</strong> ${id}</p>
                <p><strong>Name:</strong> Sample Product</p>
                <p><strong>Stock:</strong> 25 units</p>
                <p><strong>Price:</strong> ₱150.00</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}

// Edit Product
function editProduct(id) {
    Swal.fire({
        title: 'Edit Product',
        text: `Editing product #${id}`,
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

// Delete Product
function deleteProduct(id) {
    Swal.fire({
        title: 'Delete Product?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'Product has been deleted.',
                'success'
            );
        }
    });
}

// Save Category
function saveCategory() {
    Swal.fire({
        icon: 'success',
        title: 'Category Saved',
        text: 'New category has been added successfully',
        timer: 1500,
        showConfirmButton: false
    });
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
}

// Save Product
function saveProduct() {
    Swal.fire({
        icon: 'success',
        title: 'Product Saved',
        text: 'New product has been added successfully',
        timer: 1500,
        showConfirmButton: false
    });
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
}

// Search with debounce
let searchTimeout;
document.getElementById('productSearch').addEventListener('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Dropdown change events
document.getElementById('categoryFilter').addEventListener('change', applyFilters);
document.getElementById('supplierFilter').addEventListener('change', applyFilters);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush    

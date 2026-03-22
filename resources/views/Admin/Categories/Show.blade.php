@extends('layouts.admin')

@section('title', 'Category Details - ' . $category->category_name)

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Premium Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, {{ $category->color ?? '#3B82F6' }} 0%, #1d4ed8 100%);">
                <i class="fas {{ $category->icon ?? 'fa-folder' }}"></i>
            </div>
            <div class="header-text">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 0.75rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a></li>
                        <li class="breadcrumb-item active">{{ $category->category_name }}</li>
                    </ol>
                </nav>
                <h1 class="page-title text-dark">{{ $category->category_name }}</h1>
                <p class="page-subtitle text-muted">Manage products and details for this category</p>
            </div>
        </div>
        <div class="header-actions">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-header-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                    <i class="fas fa-edit me-2"></i>Edit Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-header-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Category Info -->
        <div class="col-12 col-lg-4">
            <div class="detail-card h-100 border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="category-image-preview mb-3 mx-auto shadow-sm" style="width: 150px; height: 150px; border-radius: 20px; overflow: hidden; background: #f8fafc;">
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->category_name }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas {{ $category->icon ?? 'fa-folder-open' }} fa-4x text-primary opacity-25"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1">{{ $category->category_name }}</h4>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                            ID: #{{ $category->id }}
                        </span>
                    </div>

                    <div class="info-list">
                        <div class="info-item mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">Category Code</label>
                            <div class="fw-semibold">{{ $category->category_code ?: 'N/A' }}</div>
                        </div>
                        
                        <div class="info-item mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">Slug</label>
                            <div class="fw-semibold text-break"><code>{{ $category->slug }}</code></div>
                        </div>

                        <div class="info-item mb-3 p-3 bg-light bg-opacity-50 rounded-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">Activity Status</label>
                            <div class="d-flex flex-column gap-2" style="font-size: 0.85rem;">
                                <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-10 pb-2">
                                    <span class="text-muted">Created:</span>
                                    <span class="fw-medium">{{ $category->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-1">
                                    <span class="text-muted">Last Updated:</span>
                                    <span class="fw-medium">{{ $category->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($category->description)
                            <div class="info-item p-3 bg-light bg-opacity-50 rounded-3">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">Description</label>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem; line-height: 1.5;">{{ $category->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Products & Stats -->
        <div class="col-12 col-lg-8">
            <!-- Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="kpi-card p-4 border-0 shadow-sm h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="stat-icon p-3 bg-primary bg-opacity-10 rounded-3">
                                <i class="fas fa-boxes text-primary fa-lg"></i>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Total Items</span>
                        </div>
                        <h2 class="fw-bold mb-0 mt-3">{{ number_format($category->products->count()) }}</h2>
                        <p class="text-muted small mb-0 mt-1">Products in this category</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="kpi-card p-4 border-0 shadow-sm h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="stat-icon p-3 bg-warning bg-opacity-10 rounded-3">
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Low Stock</span>
                        </div>
                        @php
                            $lowStockCount = $category->products->filter(function($product) { 
                                $stock = $product->stock->quantity ?? 0;
                                return $stock > 0 && $stock <= ($product->reorder_level ?? 5);
                            })->count();
                        @endphp
                        <h2 class="fw-bold mb-0 mt-3 @if ($lowStockCount > 0) text-warning @endif">{{ $lowStockCount }}</h2>
                        <p class="text-muted small mb-0 mt-1">Items nearing reorder point</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="kpi-card p-4 border-0 shadow-sm h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="stat-icon p-3 bg-success bg-opacity-10 rounded-3">
                                <i class="fas fa-money-bill-wave text-success fa-lg"></i>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.65rem;">Value</span>
                        </div>
                        @php
                            $totalValue = $category->products->sum(function($product) {
                                return ($product->stock->quantity ?? 0) * $product->selling_price;
                            });
                        @endphp
                        <h2 class="fw-bold mb-0 mt-3 text-success">₱{{ number_format($totalValue, 0) }}</h2>
                        <p class="text-muted small mb-0 mt-1">Estimated inventory value</p>
                    </div>
                </div>
            </div>

            <!-- Products Table Card -->
            <div class="table-card border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-box text-primary me-2"></i>
                        Products List
                    </h5>
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-sm btn-primary rounded-3 px-3 shadow-none">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="ps-4 border-0">Product Info</th>
                                    <th class="border-0">Pricing</th>
                                    <th class="border-0">Availability</th>
                                    <th class="border-0">Status</th>
                                    <th class="text-end pe-4 border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($category->products) ? count($category->products) > 0 : !empty($category->products))
@foreach($category->products as $product)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 position-relative">
                                                    @if ($product->image)
                                                        <img src="{{ asset($product->image) }}" class="rounded shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 1px solid #eee;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; border: 1px solid #eee;">
                                                            <i class="fas fa-image text-muted opacity-25"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $product->product_name }}</div>
                                                    <div class="text-muted x-small" style="font-size: 0.75rem;">{{ $product->product_code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">₱{{ number_format($product->selling_price, 2) }}</div>
                                            <div class="text-muted small" style="font-size: 0.7rem;">Margin: {{ $product->cost_price > 0 ? number_format((($product->selling_price - $product->cost_price) / $product->selling_price) * 100, 1) . '%' : 'N/A' }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $stockQty = $product->stock->quantity ?? 0;
                                                $reorder = $product->reorder_level ?? 5;
                                                $barColor = $stockQty <= 0 ? 'bg-danger' : ($stockQty <= $reorder ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="d-flex flex-column" style="width: 130px;">
                                                <div class="d-flex justify-content-between mb-1 align-items-center">
                                                    <span class="fw-bold {{ $stockQty <= $reorder ? 'text-warning' : 'text-success' }}" style="font-size: 0.75rem;">{{ $stockQty }} in stock</span>
                                                </div>
                                                <div class="progress" style="height: 5px; background-color: #f1f5f9;">
                                                    <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ min(100, max(10, ($stockQty / 50) * 100)) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($product->status == 'active')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-2 py-1 rounded-pill" style="font-size: 0.65rem;">ACTIVE</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-2 py-1 rounded-pill" style="font-size: 0.65rem;">INACTIVE</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-white border-0 bg-white" title="View">
                                                    <i class="fas fa-eye text-info"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-white border-0 bg-white" title="Edit">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
@else
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-5 text-muted">
                                                <i class="fas fa-layer-group fa-4x opacity-10 mb-3"></i>
                                                <h5>Empty Category</h5>
                                                <p class="small mb-0">No products have been assigned yet.</p>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 bg-light bg-opacity-50 px-4 py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-edit text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold text-dark mb-0" id="editCategoryModalLabel">Modify Category</h4>
                        <p class="text-muted small mb-0">Update visual identity and details</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 pt-1">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-1">
                                <input type="text" name="category_name" id="catName" class="form-control bg-light border-0 @error('category_name') is-invalid @enderror" 
                                       value="{{ old('category_name', $category->category_name) }}" required placeholder="Category Name" style="border-radius: 12px;">
                                <label for="catName">Category Name <span class="text-danger">*</span></label>
                            </div>
                            @error('category_name')
                                <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-1">
                                <input type="text" name="slug" id="slug" class="form-control bg-light border-0 @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug', $category->slug) }}" placeholder="Slug" style="border-radius: 12px;">
                                <label for="slug">URL Slug (Keep empty for auto-gen)</label>
                            </div>
                            @error('slug')
                                <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label x-small fw-bold text-muted text-uppercase mb-2 ps-1">Visual Icon (FontAwesome)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-4"><i class="fas fa-icons text-muted"></i></span>
                                <input type="text" name="icon" class="form-control bg-light border-0 rounded-end-4 @error('icon') is-invalid @enderror" 
                                       value="{{ old('icon', $category->icon) }}" placeholder="e.g. fa-tag">
                            </div>
                            @error('icon')
                                <div class="text-danger x-small mt-1 ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label x-small fw-bold text-muted text-uppercase mb-2 ps-1">Accent Theme Color</label>
                            <div class="d-flex align-items-center gap-2 p-1 px-2 rounded-4 bg-light">
                                <input type="color" name="color" class="form-control form-control-color border-0 bg-transparent p-0" 
                                       value="{{ old('color', $category->color ?: '#3B82F6') }}" title="Accent Color" style="width: 38px; height: 38px; border-radius: 10px;">
                                <span class="text-muted small">Choose identifying color</span>
                            </div>
                            @error('color')
                                <div class="text-danger x-small mt-1 ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-1">
                                <textarea name="description" id="desc" class="form-control bg-light border-0 @error('description') is-invalid @enderror" 
                                          placeholder="Description" style="border-radius: 12px; height: 100px;">{{ old('description', $category->description) }}</textarea>
                                <label for="desc">Category Description</label>
                            </div>
                            @error('description')
                                <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label x-small fw-bold text-muted text-uppercase mb-2 ps-1">Update Showcase Image</label>
                            <div class="image-upload-wrapper border-0 bg-light rounded-4 p-4 text-center">
                                @if ($category->image)
                                    <div class="position-relative d-inline-block mb-3">
                                        <img src="{{ asset($category->image) }}" class="rounded shadow-sm" style="max-height: 80px; border: 2px solid white;">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-light">Current</span>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control bg-white border-0 @error('image') is-invalid @enderror" style="border-radius: 10px;">
                                <p class="text-muted x-small mt-3 mb-0">JPEG, PNG, JPG (Max 2MB)</p>
                            </div>
                            @error('image')
                                <div class="text-danger x-small mt-1 ps-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-header-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-header-primary px-5 shadow">
                        <i class="fas fa-save me-2"></i>Apply Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .kpi-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 20px !important; }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06) !important; }
    .detail-card { border-radius: 20px !important; }
    .table-card { border-radius: 20px !important; }
    
    .x-small { font-size: 0.7rem; }
    
    .form-floating > .form-control:focus {
        border-color: transparent !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        background-color: #f8fafc !important;
    }
    
    .btn-header-primary {
        background: #3B82F6;
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.2s;
    }
    
    .btn-header-primary:hover {
        background: #2563EB;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: white;
    }
    
    .btn-header-secondary {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-header-secondary:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    
    /* Responsive font scaling */
    @media (max-width: 768px) {
        .page-title { font-size: 1.25rem !important; }
        .kpi-card h2 { font-size: 1.5rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-open modal if there are validation errors
        @if ($errors->any())
            var editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        @endif

        // Auto-open modal if 'edit' query param is present
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('edit')) {
            var editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        }
    });
</script>
@endpush
@endsection

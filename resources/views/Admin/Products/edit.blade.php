@extends('layouts.admin')

@section('title', 'Edit Product - CJ\'s Minimart')

@section('content')
<div class="container-fluid">
    <!-- Simple Header (like create brand) -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box product-header-icon">
                <i class="fas fa-edit text-warning"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Edit Product</h1>
                <p class="page-subtitle">Editing: <span class="fw-semibold">{{ $product->product_name }}</span> <span class="badge bg-secondary ms-2">{{ $product->product_code }}</span></p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.products.show', $product->id) }}" class="btn-header-action btn-header-info" aria-label="View product">
                <i class="fas fa-eye"></i>
                <span class="d-none d-sm-inline">View</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to list">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards - Simple -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Current Stock</h6>
                            <h3 class="mb-0">{{ $product->current_stock }} <small>{{ $product->unit }}</small></h3>
                        </div>
                        <i class="fas fa-cubes fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Stock Status</h6>
                            <h3 class="mb-0">
                                <span class="badge bg-{{ $product->stock_badge_class }} fs-6">
                                    {{ $product->stock_status_label }}
                                </span>
                            </h3>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Selling Price</h6>
                            <h3 class="mb-0">₱{{ number_format($product->selling_price, 2) }}</h3>
                        </div>
                        <i class="fas fa-tag fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">Expiry Status</h6>
                            <h3 class="mb-0">{!! $product->expiry_badge !!}</h3>
                        </div>
                        <i class="fas fa-calendar fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Tabs -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <ul class="nav nav-pills" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab">
                        <i class="fas fa-edit me-2"></i>Basic Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                        <i class="fas fa-tag me-2"></i>Pricing
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">
                        <i class="fas fa-boxes me-2"></i>Inventory
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>Additional
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab">
                        <i class="fas fa-warehouse me-2"></i>Stock Management
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="productTabsContent">
        <!-- Edit Tab - Basic Info -->
        <div class="tab-pane fade show active" id="edit" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf @method('PUT')
                        
                        <h5 class="mb-3">Basic Information</h5>
                        <hr class="mt-0">
                        
                        <div class="row">
                            <!-- Product Code (Readonly) -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product Code</label>
                                <input type="text" class="form-control bg-light" value="{{ $product->product_code }}" readonly>
                            </div>

                            <!-- Barcode -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Barcode / SKU</label>
                                <input type="text" 
                                       class="form-control @error('barcode') is-invalid @enderror" 
                                       name="barcode" 
                                       value="{{ old('barcode', $product->barcode) }}"
                                       placeholder="Enter barcode">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('product_name') is-invalid @enderror" 
                                       name="product_name" 
                                       value="{{ old('product_name', $product->product_name) }}"
                                       required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" 
                                          rows="2">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                            {{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Type -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product Type</label>
                                <select class="form-select @error('product_type') is-invalid @enderror" name="product_type">
                                    <option value="perishable" {{ old('product_type', $product->product_type) == 'perishable' ? 'selected' : '' }}>Perishable</option>
                                    <option value="non_perishable" {{ old('product_type', $product->product_type) == 'non_perishable' ? 'selected' : '' }}>Non-Perishable</option>
                                    <option value="equipment" {{ old('product_type', $product->product_type) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                </select>
                                @error('product_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" id="brandSelect">
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->brand_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                               name="brand" id="brandInput" 
                                               value="{{ old('brand', $product->brand) }}" 
                                               placeholder="Or enter manually">
                                    </div>
                                </div>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit of Measurement -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <select class="form-select @error('uom_id') is-invalid @enderror" name="uom_id" id="uomSelect">
                                            <option value="">Select UOM</option>
                                            @foreach ($uoms as $uom)
                                                <option value="{{ $uom->id }}" {{ old('uom_id', $product->uom_id) == $uom->id ? 'selected' : '' }}>
                                                    {{ $uom->name }} ({{ $uom->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                               name="unit" id="unitInput" 
                                               value="{{ old('unit', $product->unit) }}" 
                                               placeholder="Or enter unit">
                                    </div>
                                </div>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pricing Tab -->
        <div class="tab-pane fade" id="pricing" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Pricing Information</h5>
                    <hr class="mt-0">
                    
                    <div class="row">
                        <!-- Cost Price -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" 
                                       class="form-control @error('cost_price') is-invalid @enderror price-input" 
                                       name="cost_price" id="costPrice"
                                       value="{{ old('cost_price', $product->cost_price) }}"
                                       required>
                            </div>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selling Price -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" 
                                       class="form-control @error('selling_price') is-invalid @enderror price-input" 
                                       name="selling_price" id="sellingPrice"
                                       value="{{ old('selling_price', $product->selling_price) }}"
                                       required>
                            </div>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Wholesale Price -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Wholesale Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" 
                                       class="form-control @error('wholesale_price') is-invalid @enderror" 
                                       name="wholesale_price" 
                                       value="{{ old('wholesale_price', $product->wholesale_price) }}">
                            </div>
                            @error('wholesale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profit Summary Card -->
                        <div class="col-12 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted">Profit Margin</small>
                                            <h5 class="mb-0" id="profitMargin">0%</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Markup</small>
                                            <h5 class="mb-0" id="markupPercentage">0%</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">After Discount</small>
                                            <h5 class="mb-0" id="finalPrice">₱0.00</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">After Tax</small>
                                            <h5 class="mb-0" id="priceAfterTax">₱0.00</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Percent -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Discount %</label>
                            <div class="input-group">
                                <input type="number" step="0.01" 
                                       class="form-control @error('discount_percent') is-invalid @enderror" 
                                       name="discount_percent" 
                                       value="{{ old('discount_percent', $product->discount_percent ?? 0) }}" 
                                       min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tax Rate</label>
                            <div class="input-group">
                                <input type="number" step="0.01" 
                                       class="form-control @error('tax_rate') is-invalid @enderror" 
                                       name="tax_rate" 
                                       value="{{ old('tax_rate', $product->tax_rate ?? 0) }}" 
                                       min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Tab -->
        <div class="tab-pane fade" id="inventory" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Inventory Settings</h5>
                    <hr class="mt-0">
                    
                    <div class="row">
                        <!-- Current Stock (Readonly) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light" 
                                       value="{{ $product->current_stock }}" readonly>
                                <span class="input-group-text">{{ $product->unit }}</span>
                            </div>
                            <small class="text-muted">Use Stock Management tab to adjust</small>
                        </div>

                        <!-- Reorder Level -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('reorder_level') is-invalid @enderror" 
                                   name="reorder_level" 
                                   value="{{ old('reorder_level', $product->reorder_level) }}" 
                                   min="0" required>
                            @error('reorder_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reorder Quantity -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reorder Quantity</label>
                            <input type="number" class="form-control @error('reorder_quantity') is-invalid @enderror" 
                                   name="reorder_quantity" 
                                   value="{{ old('reorder_quantity', $product->reorder_quantity) }}" 
                                   min="0">
                            @error('reorder_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Min Stock Level -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Min Stock Level</label>
                            <input type="number" class="form-control @error('min_level') is-invalid @enderror" 
                                   name="min_level" 
                                   value="{{ old('min_level', $product->min_level) }}" 
                                   min="0">
                            @error('min_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Max Stock Level -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Max Stock Level</label>
                            <input type="number" class="form-control @error('max_level') is-invalid @enderror" 
                                   name="max_level" 
                                   value="{{ old('max_level', $product->max_level ?? 1000) }}" 
                                   min="0">
                            @error('max_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Safety Stock (Readonly) -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Safety Stock</label>
                            <input type="number" class="form-control bg-light" 
                                   value="{{ max(0, ($product->reorder_level ?? 0) - ($product->min_level ?? 0)) }}" readonly>
                        </div>

                        <!-- Stock Turnover (Readonly) -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stock Turnover</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ $product->current_stock > 0 && $product->reorder_level > 0 ? round($product->current_stock / $product->reorder_level, 1) . 'x' : 'N/A' }}" readonly>
                        </div>

                        <!-- Shelf Location -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shelf Location</label>
                            <input type="text" class="form-control @error('shelf_location') is-invalid @enderror" 
                                   name="shelf_location" 
                                   value="{{ old('shelf_location', $product->shelf_location ?? 'A1') }}" 
                                   placeholder="e.g., A1, B2">
                            @error('shelf_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Storage Type -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Storage Type</label>
                            <select class="form-select" name="storage_type">
                                <option value="ambient" {{ ($product->storage_type ?? 'ambient') == 'ambient' ? 'selected' : '' }}>Ambient</option>
                                <option value="chilled" {{ ($product->storage_type ?? '') == 'chilled' ? 'selected' : '' }}>Chilled</option>
                                <option value="frozen" {{ ($product->storage_type ?? '') == 'frozen' ? 'selected' : '' }}>Frozen</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Tab -->
        <div class="tab-pane fade" id="additional" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Expiry Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Expiry Information</h5>
                            <hr class="mt-0">
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="has_expiry" id="hasExpiry" value="1" {{ old('has_expiry', $product->has_expiry) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hasExpiry">
                                        This product has an expiry date
                                    </label>
                                </div>
                            </div>

                            <div id="expiryFields" style="display: {{ old('has_expiry', $product->has_expiry) ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label class="form-label">Manufacturing Date</label>
                                    <input type="date" class="form-control @error('manufacturing_date') is-invalid @enderror" 
                                           name="manufacturing_date" 
                                           value="{{ old('manufacturing_date', $product->manufacturing_date ? $product->manufacturing_date->format('Y-m-d') : '') }}">
                                    @error('manufacturing_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           name="expiry_date" 
                                           value="{{ old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Shelf Life (days)</label>
                                    <input type="number" class="form-control @error('shelf_life_days') is-invalid @enderror" 
                                           name="shelf_life_days" 
                                           value="{{ old('shelf_life_days', $product->shelf_life_days) }}" 
                                           min="1">
                                    @error('shelf_life_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($product->has_expiry && $product->expiry_date)
                                    @php
                                        $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($product->expiry_date), false);
                                        $expiryClass = $daysLeft < 0 ? 'danger' : ($daysLeft <= 30 ? 'warning' : 'success');
                                        $expiryMessage = $daysLeft < 0 ? 'Expired ' . abs($daysLeft) . ' days ago' : ($daysLeft . ' days remaining');
                                    @endphp
                                    <div class="alert alert-{{ $expiryClass }} mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ $expiryMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status & Phase Out -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Status & Phase Out</h5>
                            <hr class="mt-0">
                            


                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_phase_out" id="isPhaseOut" value="1" {{ old('is_phase_out', $product->is_phase_out) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isPhaseOut">
                                        This product is being phased out
                                    </label>
                                </div>
                            </div>

                            <div id="phaseOutReason" style="display: {{ old('is_phase_out', $product->is_phase_out) ? 'block' : 'none' }};">
                                <label class="form-label">Phase Out Reason</label>
                                <textarea class="form-control @error('phase_out_reason') is-invalid @enderror" 
                                          name="phase_out_reason" rows="2">{{ old('phase_out_reason', $product->phase_out_reason) }}</textarea>
                                @error('phase_out_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Management Tab -->
        <div class="tab-pane fade" id="stock" role="tabpanel">
            <div class="row">
                <!-- Stock In -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-arrow-down me-2"></i>Stock In</h6>
                        </div>
                        <div class="card-body">
                            <form id="quickStockInForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="type" value="in">
                                
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference</label>
                                    <input type="text" name="reference" class="form-control" placeholder="PO-12345">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Add Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Stock Out -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0"><i class="fas fa-arrow-up me-2"></i>Stock Out</h6>
                        </div>
                        <div class="card-body">
                            <form id="quickStockOutForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="type" value="out">
                                
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" max="{{ $product->current_stock }}" required>
                                    <small class="text-muted">Max available: {{ $product->current_stock }}</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reason</label>
                                    <select name="reason" class="form-select" required>
                                        <option value="sale">Sale</option>
                                        <option value="damage">Damage</option>
                                        <option value="expired">Expired</option>
                                        <option value="adjustment">Adjustment</option>
                                        <option value="return">Return to Supplier</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference</label>
                                    <input type="text" name="reference" class="form-control" placeholder="SO-12345">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-minus-circle me-2"></i>Remove Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" form="productForm" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Image Modal (if needed) -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->product_name }}">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 8px;
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
    
    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
        color: #2d3748;
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
    }
    
    .nav-pills .nav-link:hover {
        background: #f8f9fa;
    }
    
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-info { background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%); }
    .bg-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    
    .opacity-50 { opacity: 0.5; }
    
    .input-group-text {
        background: #f8f9fa;
        border-radius: 8px 0 0 8px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.1);
    }
    
    @media (max-width: 768px) {
        .nav-pills .nav-link {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Price calculator
    const costInput = document.getElementById('costPrice');
    const sellingInput = document.getElementById('sellingPrice');
    const profitMargin = document.getElementById('profitMargin');
    const markupPercent = document.getElementById('markupPercentage');
    const finalPrice = document.getElementById('finalPrice');
    const priceAfterTax = document.getElementById('priceAfterTax');
    const discountInput = document.querySelector('input[name="discount_percent"]');
    const taxInput = document.querySelector('input[name="tax_rate"]');

    function calculateAll() {
        const cost = parseFloat(costInput?.value) || 0;
        const selling = parseFloat(sellingInput?.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;
        const tax = parseFloat(taxInput?.value) || 0;
        
        if (selling > 0) {
            // Profit margin
            if (cost > 0) {
                const margin = ((selling - cost) / selling) * 100;
                profitMargin.innerText = margin.toFixed(2) + '%';
                
                const markup = ((selling - cost) / cost) * 100;
                markupPercent.innerText = markup.toFixed(2) + '%';
            } else {
                profitMargin.innerText = '0%';
                markupPercent.innerText = '0%';
            }
            
            // Final price after discount
            const afterDiscount = selling * (1 - discount / 100);
            finalPrice.innerText = '₱' + afterDiscount.toFixed(2);
            
            // Price after tax
            const afterTax = afterDiscount * (1 + tax / 100);
            priceAfterTax.innerText = '₱' + afterTax.toFixed(2);
        } else {
            profitMargin.innerText = '0%';
            markupPercent.innerText = '0%';
            finalPrice.innerText = '₱0.00';
            priceAfterTax.innerText = '₱0.00';
        }
    }

    costInput?.addEventListener('input', calculateAll);
    sellingInput?.addEventListener('input', calculateAll);
    discountInput?.addEventListener('input', calculateAll);
    taxInput?.addEventListener('input', calculateAll);
    
    // Initial calculation
    calculateAll();

    // Toggle expiry fields
    document.getElementById('hasExpiry')?.addEventListener('change', function() {
        document.getElementById('expiryFields').style.display = this.checked ? 'block' : 'none';
    });

    // Toggle phase out reason
    document.getElementById('isPhaseOut')?.addEventListener('change', function() {
        document.getElementById('phaseOutReason').style.display = this.checked ? 'block' : 'none';
    });

    // Brand/UOM selection logic
    document.getElementById('brandSelect')?.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('brandInput').value = '';
            document.getElementById('brandInput').disabled = true;
        } else {
            document.getElementById('brandInput').disabled = false;
        }
    });

    document.getElementById('brandInput')?.addEventListener('input', function() {
        if (this.value) {
            document.getElementById('brandSelect').value = '';
            document.getElementById('brandSelect').disabled = true;
        } else {
            document.getElementById('brandSelect').disabled = false;
        }
    });

    document.getElementById('uomSelect')?.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('unitInput').value = '';
            document.getElementById('unitInput').disabled = true;
        } else {
            document.getElementById('unitInput').disabled = false;
        }
    });

    document.getElementById('unitInput')?.addEventListener('input', function() {
        if (this.value) {
            document.getElementById('uomSelect').value = '';
            document.getElementById('uomSelect').disabled = true;
        } else {
            document.getElementById('uomSelect').disabled = false;
        }
    });

    // Quick Stock In
    document.getElementById('quickStockInForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Processing Stock In...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("admin.inventory.process-stock-in") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Network error occurred.', 'error');
        });
    });

    // Quick Stock Out
    document.getElementById('quickStockOutForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Processing Stock Out...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("admin.inventory.process-stock-out") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Network error occurred.', 'error');
        });
    });
</script>
@endpush

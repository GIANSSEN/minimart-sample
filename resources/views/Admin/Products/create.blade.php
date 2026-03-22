@extends('layouts.admin')

@section('title', 'Add New Product - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0"> <!-- Full width container -->
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box products-header-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Add New Product</h1>
                <p class="page-subtitle">Fill in the product details below. Fields marked with <span class="text-danger">*</span> are required.</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.products.index') }}" class="btn-header-action btn-header-secondary" title="Back to List">
                <i class="fas fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>


    <!-- Main Form -->
    <div class="row mx-0">
        <div class="col-12 px-0">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- 1. Basic Information -->
                        <div class="section-group mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h5 class="fw-bold mb-0">Basic Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-box text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('product_name') is-invalid @enderror" 
                                               name="product_name" value="{{ old('product_name') }}" placeholder="Enter full product name" required>
                                    </div>
                                    @error('product_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">SKU / Barcode <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-barcode text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('barcode') is-invalid @enderror" 
                                               name="barcode" value="{{ old('barcode') }}" placeholder="Scan or enter barcode" required>
                                    </div>
                                    @error('barcode') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="product_category_id" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" required>
                                        <option value="">-- Select Brand --</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="3" placeholder="Enter detailed product description...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Pricing -->
                        <div class="section-group mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h5 class="fw-bold mb-0">Pricing</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Unit Cost <span class="text-danger">*</span> <small class="text-muted">(presyo mula sa supplier)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">₱</span>
                                        <input type="number" step="0.01" class="form-control border-start-0 ps-1 @error('cost_price') is-invalid @enderror" 
                                               name="cost_price" value="{{ old('cost_price', '0.00') }}" required>
                                    </div>
                                    @error('cost_price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Selling Price <span class="text-danger">*</span> <small class="text-muted">(presyo sa customer)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">₱</span>
                                        <input type="number" step="0.01" class="form-control border-start-0 ps-1 @error('selling_price') is-invalid @enderror" 
                                               name="selling_price" value="{{ old('selling_price', '0.00') }}" required>
                                    </div>
                                    @error('selling_price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 3. Inventory -->
                        <div class="section-group mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h5 class="fw-bold mb-0">Inventory</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Reorder Point <span class="text-danger">*</span> <small class="text-muted">(minimum stock bago mag-order ulit)</small></label>
                                    <input type="number" class="form-control @error('reorder_level') is-invalid @enderror" 
                                           name="reorder_level" value="{{ old('reorder_level', '0') }}" min="0" required>
                                    @error('reorder_level') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Current Stock <small class="text-muted">(initial quantity)</small></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           name="stock_quantity" value="{{ old('stock_quantity', '0') }}" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Unit of Measurement -->
                        <div class="section-group mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h5 class="fw-bold mb-0">Unit of Measurement</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Unit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('unit') is-invalid @enderror" name="unit" required>
                                        <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>pieces</option>
                                        <option value="kilograms" {{ old('unit') == 'kilograms' ? 'selected' : '' }}>kilograms</option>
                                        <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>liters</option>
                                        <option value="packs" {{ old('unit') == 'packs' ? 'selected' : '' }}>packs</option>
                                        <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>boxes</option>
                                    </select>
                                    @error('unit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 5. Location (Optional) -->
                        <div class="section-group mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h5 class="fw-bold mb-0">Location (Optional)</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Shelf Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" 
                                               name="shelf_location" value="{{ old('shelf_location') }}" placeholder="e.g. Aisle 1, Shelf B">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Product Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="product_code" value="{{ $productCode }}">
                        <input type="hidden" name="product_type" value="non_perishable">

                        <div class="form-actions d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light px-4 py-2 fw-semibold">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Save Product
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="category_name" name="category_name" required>
                        <div class="invalid-feedback" id="category_name_error"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" id="saveCategoryBtn">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
    
    .col-12.px-0 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* MODERN HEADER */
    .modern-header {
        background: white;
        border-bottom: 2px solid #28a745;
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
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: clamp(45px, 6vw, 55px);
        height: clamp(45px, 6vw, 55px);
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.5rem, 4vw, 1.8rem);
        box-shadow: 0 8px 15px rgba(17, 153, 142, 0.25);
        flex-shrink: 0;
        z-index: 2;
    }


    /* FORM SECTIONS */
    .section-title {
        font-size: 1.1rem;
        color: #2d3748;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
        color: #2d3748;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1px solid #dee2e6;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(17, 153, 142, 0.1);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        background: #f8f9fa;
    }

    /* CARD STYLES */
    .card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* BUTTONS */
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
    }

    /* IMAGE PREVIEW */
    .image-preview {
        width: 100px;
        height: 100px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .modern-header {
            padding: 15px !important;
        }
        
        .header-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }        .progress-steps {
            flex-wrap: wrap;
            gap: 10px;
        }
    }

    .shadow-soft {
        box-shadow: 0 0.75rem 2.5rem rgba(0, 0, 0, 0.05) !important;
    }
    
    .section-group {
        position: relative;
    }
    
    
    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-color: #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .form-label {
        font-size: 0.875rem;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Add Category Form Handler (AJAX)
    document.getElementById('addCategoryForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('saveCategoryBtn');
        const formData = new FormData(form);
        const categorySelect = document.getElementById('product_category_id');
        
        // Reset validation
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Add new category to select and select it
                const option = new Option(data.category.category_name, data.category.id, true, true);
                categorySelect.add(option);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                modal.hide();
                form.reset();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Category Added',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        const errorDiv = document.getElementById(`${key}_error`);
                        if (input) {
                            input.classList.add('is-invalid');
                            if (errorDiv) errorDiv.textContent = data.errors[key][0];
                        }
                    });
                }
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not create category. Please try again.'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Save Category';
        }
    });

    // Simple Form Validation Feedback
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Scroll to first error
            const firstError = form.querySelector(':invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            Swal.fire({
                title: 'Saving Product...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }
        form.classList.add('was-validated');
    }, false);

    // Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
push

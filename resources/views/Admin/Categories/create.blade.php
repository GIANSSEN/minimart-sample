@extends('layouts.admin')

@section('title', 'Create Category - CJ\'s Minimart')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-1">Create Category</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-folder-plus me-2"></i>
                                Add a new product category
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="header-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Category Information</h5>
                        <p class="text-muted small mb-0">Fill in the details below to create a new category</p>
                    </div>
                </div>

                <div class="form-card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" 
                                       value="{{ old('category_name') }}" placeholder="e.g., Beverages, Snacks, etc." required>
                                @error('category_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category Slug</label>
                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug') }}" placeholder="e.g., beverages, snacks">
                                <small class="text-muted">Leave blank to auto-generate from name</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="col-md-6">
                                <label class="form-label">Color Theme</label>
                                <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       value="{{ old('color', '#667eea') }}" style="height: 45px;">
                                <small class="text-muted">Choose a color for the category icon</small>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Icon</label>
                                <select name="icon" class="form-select @error('icon') is-invalid @enderror">
                                    <option value="fa-folder" {{ old('icon') == 'fa-folder' ? 'selected' : '' }}>Folder</option>
                                    <option value="fa-folder-open" {{ old('icon') == 'fa-folder-open' ? 'selected' : '' }}>Folder Open</option>
                                    <option value="fa-tag" {{ old('icon') == 'fa-tag' ? 'selected' : '' }}>Tag</option>
                                    <option value="fa-box" {{ old('icon') == 'fa-box' ? 'selected' : '' }}>Box</option>
                                    <option value="fa-cubes" {{ old('icon') == 'fa-cubes' ? 'selected' : '' }}>Cubes</option>
                                    <option value="fa-store" {{ old('icon') == 'fa-store' ? 'selected' : '' }}>Store</option>
                                </select>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="4" placeholder="Enter category description...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Create Category
                            </button>
                        </div>
                    </form>
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
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-card-header {
        padding: 25px 30px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .form-card-header .header-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .form-card-body {
        padding: 30px;
    }

    .form-label {
        font-weight: 500;
        color: #1e1e2d;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control-color {
        padding: 6px;
        height: 45px;
    }

    .form-actions {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        color: #6c757d;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .modern-header {
            padding: 20px;
        }

        .form-card-body {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

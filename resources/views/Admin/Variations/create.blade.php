@extends('layouts.admin')

@section('title', 'Create Variation - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Page Header -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="modern-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 py-3 px-3 px-md-4">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="header-icon">
                        <i class="fas fa-plus-circle text-success"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-0 h4 h3-md">Create New Variation</h1>
                        <p class="text-muted mb-0 small">Add a new product variation (size, color, flavor, etc.)</p>
                    </div>
                </div>
                <a href="{{ route('admin.variations.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('admin.variations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Product -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Product</label>
                                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                                    <option value="">Select Product (Optional)</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }} ({{ $product->product_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Variation Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Variation Value</label>
                                <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" 
                                       value="{{ old('value') }}" placeholder="e.g., XL, Red, Chocolate">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                       value="{{ old('sku') }}" placeholder="Optional">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cost Price -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" 
                                           value="{{ old('cost_price', 0) }}">
                                </div>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selling Price -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Selling Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" 
                                           value="{{ old('selling_price', 0) }}">
                                </div>
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Image -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Allowed: jpeg, png, jpg, gif (Max 2MB)</small>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Save Variation</button>
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
        border-bottom: 2px solid #28a745;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }
    .header-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    @media (min-width: 768px) {
        .header-icon {
            width: 55px;
            height: 55px;
            font-size: 1.8rem;
        }
    }
</style>
@endpush

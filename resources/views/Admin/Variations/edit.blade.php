@extends('layouts.admin')

@section('title', 'Edit Variation - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Page Header -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="modern-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 py-3 px-3 px-md-4">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="header-icon">
                        <i class="fas fa-edit text-primary"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-0 h4 h3-md">Edit Variation</h1>
                        <p class="text-muted mb-0 small">{{ $variation->name }}</p>
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
                    <form action="{{ route('admin.variations.update', $variation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                        <option value="{{ $product->id }}" {{ old('product_id', $variation->product_id) == $product->id ? 'selected' : '' }}>
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
                                       value="{{ old('name', $variation->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Value -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Variation Value</label>
                                <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" 
                                       value="{{ old('value', $variation->value) }}" placeholder="e.g., XL, Red, Chocolate">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                       value="{{ old('sku', $variation->sku) }}" placeholder="Optional">
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
                                           value="{{ old('cost_price', $variation->cost_price) }}">
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
                                           value="{{ old('selling_price', $variation->selling_price) }}">
                                </div>
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Image -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Image</label>
                                
                                @if ($variation->image)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset($variation->image) }}" alt="Current image" 
                                                 class="img-thumbnail" style="max-height:80px;">
                                            <div class="form-check">
                                                <input type="checkbox" name="remove_image" value="1" class="form-check-input" id="removeImage">
                                                <label class="form-check-label" for="removeImage">Remove current image</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                                          rows="3">{{ old('description', $variation->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Variation</button>
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
        border-bottom: 2px solid #007bff;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    .header-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
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

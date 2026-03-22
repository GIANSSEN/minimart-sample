@extends('layouts.admin')

@section('title', 'Edit Supplier - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box supplier-header-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Edit Supplier</h1>
                <p class="page-subtitle">Editing: {{ $supplier->supplier_name }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn-header-action btn-header-info" aria-label="View supplier">
                <i class="fas fa-eye"></i>
                <span class="d-none d-sm-inline">View</span>
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to list">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Supplier Code <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_code" class="form-control @error('supplier_code') is-invalid @enderror" 
                                       value="{{ old('supplier_code', $supplier->supplier_code) }}" required>
                                @error('supplier_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" 
                                       value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                                @error('supplier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                                       value="{{ old('contact_person', $supplier->contact_person) }}">
                                @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $supplier->email) }}">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $supplier->phone) }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mobile</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                       value="{{ old('mobile', $supplier->mobile) }}">
                                @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $supplier->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $supplier->notes) }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>



                            <div class="col-12 mt-4">
                                <hr>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>Update Supplier
                                    </button>
                                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                </div>
                            </div>
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
        border-bottom: 2px solid #0d6efd;
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 8px 15px rgba(102,126,234,0.25);
        flex-shrink: 0;
    }

    .card {
        border-radius: 16px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
    }
</style>
@endpush

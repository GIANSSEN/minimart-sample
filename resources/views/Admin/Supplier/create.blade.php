@extends('layouts.admin')

@section('title', 'Register New Partner - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Form Page */
.form-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
    max-width: 1000px;
    margin: 0 auto;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.form-card-premium {
    background: #fff;
    border-radius: 24px;
    border: 1px solid #edf2f7;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

.section-title {
    font-size: 0.8rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}

.premium-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.5rem;
}

.premium-input {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: #1e293b;
    transition: all 0.2s;
}

.premium-input:focus {
    background: #fff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    outline: none;
}

.premium-input::placeholder {
    color: #94a3b8;
}

.form-help-text {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.4rem;
}

.btn-premium-save {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 1rem 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.btn-premium-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    color: #fff;
}

.btn-premium-cancel {
    background: #f1f5f9;
    color: #64748b;
    border: none;
    border-radius: 14px;
    padding: 1rem 2rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s;
}

.btn-premium-cancel:hover {
    background: #e2e8f0;
    color: #475569;
}

@media (max-width: 768px) {
    .form-card-premium { padding: 1.5rem; }
    .btn-container { flex-direction: column; }
    .btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="form-page">
    {{-- Premium Header --}}
    <div class="page-header-premium mb-4">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <i class="fas fa-truck-loading"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Register Supplier</h1>
                <p class="page-subtitle">Add a new partnership record to the system</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.suppliers.index') }}" class="btn-header-action btn-header-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Directory</span>
            </a>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="form-card-premium">
        <form method="POST" action="{{ route('admin.suppliers.store') }}">
            @csrf

            {{-- Partner Identity Section --}}
            <div class="section-title">Partner Identity</div>
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="premium-label">Company Code <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_code" class="premium-input w-100 @error('supplier_code') is-invalid @enderror" 
                           placeholder="e.g. SUP-001" value="{{ old('supplier_code') }}" required>
                    @error('supplier_code') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                    <div class="form-help-text">Unique identifier for internal tracking</div>
                </div>

                <div class="col-md-6">
                    <label class="premium-label">Legal Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" class="premium-input w-100 @error('supplier_name') is-invalid @enderror" 
                           placeholder="e.g. Acme Corporation" value="{{ old('supplier_name') }}" required>
                    @error('supplier_name') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Contact Information Section --}}
            <div class="section-title">Communication Details</div>
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="premium-label">Contact Person</label>
                    <input type="text" name="contact_person" class="premium-input w-100" 
                           placeholder="Full name of representative" value="{{ old('contact_person') }}">
                </div>

                <div class="col-md-6">
                    <label class="premium-label">Official Email Address</label>
                    <input type="email" name="email" class="premium-input w-100" 
                           placeholder="company@example.com" value="{{ old('email') }}">
                </div>

                <div class="col-md-6">
                    <label class="premium-label">Landline Number</label>
                    <input type="text" name="phone" class="premium-input w-100" 
                           placeholder="(02) 8XXX XXXX" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6">
                    <label class="premium-label">Mobile Number</label>
                    <input type="text" name="mobile" class="premium-input w-100" 
                           placeholder="+63 9XX XXX XXXX" value="{{ old('mobile') }}">
                </div>
            </div>

            {{-- Logistics Section --}}
            <div class="section-title">Logistics & Additional Information</div>
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <label class="premium-label">Office / Warehouse Address</label>
                    <textarea name="address" class="premium-input w-100" rows="2" 
                              placeholder="Complete physical address">{{ old('address') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="premium-label">Internal Partnership Notes</label>
                    <textarea name="notes" class="premium-input w-100" rows="2" 
                              placeholder="Terms, delivery schedules, or special instructions">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="d-flex btn-container gap-3 mt-4 pt-4 border-top">
                <button type="submit" class="btn-premium-save">
                    <i class="fas fa-check-circle"></i>
                    Save Partnership Record
                </button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn-premium-cancel">
                    Cancel Registration
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

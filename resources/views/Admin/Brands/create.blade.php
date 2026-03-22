@extends('layouts.admin')

@section('title', 'Create Brand - CJ\'s Minimart')

@section('content')
<div class="brand-form-page">
    
    {{-- ===== Page Header ===== --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <h1 class="form-title">Create New Brand</h1>
                <p class="form-subtitle">Add a new manufacturer or brand to your inventory</p>
            </div>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Brands</span>
        </a>
    </div>

    {{-- ===== Form Content ===== --}}
    <div class="form-container">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="premium-form" id="brandForm">
            @csrf
            
            <div class="form-grid">
                {{-- Left Column: Basic Info --}}
                <div class="form-column">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h5>
                        
                        <div class="form-group">
                            <label for="brand_name" class="premium-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="brand_name"
                                   name="brand_name" 
                                   class="premium-input @error('brand_name') is-invalid @enderror" 
                                   value="{{ old('brand_name') }}" 
                                   placeholder="e.g. Coca-Cola"
                                   required
                                   autofocus>
                            @error('brand_name')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                            <div class="input-hint">The full visible name of the brand.</div>
                        </div>

                        <div class="form-group">
                            <label for="brand_code" class="premium-label">Brand Code <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" 
                                       id="brand_code"
                                       name="brand_code" 
                                       class="premium-input @error('brand_code') is-invalid @enderror" 
                                       value="{{ old('brand_code') }}" 
                                       placeholder="BRD-001"
                                       required>
                            </div>
                            @error('brand_code')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                            <div class="input-hint">Unique identifier for internal tracking.</div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-globe"></i> Online Presence</h5>
                        <div class="form-group">
                            <label for="website" class="premium-label">Website URL</label>
                            <div class="input-with-icon">
                                <i class="fas fa-link"></i>
                                <input type="url" 
                                       id="website"
                                       name="website" 
                                       class="premium-input @error('website') is-invalid @enderror" 
                                       value="{{ old('website') }}" 
                                       placeholder="https://www.example.com">
                            </div>
                            @error('website')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Right Column: Media --}}
                <div class="form-column">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-image"></i> Brand Branding</h5>
                        
                        <div class="form-group">
                            <label class="premium-label">Brand Logo</label>
                            <div class="logo-upload-wrapper">
                                <div class="logo-preview-box" id="logoPreview">
                                    <i class="fas fa-cloud-upload-alt upload-placeholder"></i>
                                    <img src="" alt="Preview" class="preview-img d-none">
                                </div>
                                <div class="upload-controls">
                                    <input type="file" 
                                           id="logoInput"
                                           name="logo" 
                                           class="d-none" 
                                           accept="image/*">
                                    <button type="button" class="btn-upload" onclick="document.getElementById('logoInput').click()">
                                        <i class="fas fa-camera"></i> Select Logo
                                    </button>
                                    <button type="button" class="btn-remove-logo d-none" id="removeLogoBtn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="upload-info">Max size: 2MB (JPG, PNG, GIF)</div>
                                </div>
                            </div>
                            @error('logo')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                </div>

                {{-- Full Width: Description --}}
                <div class="form-column span-full">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-align-left"></i> Description</h5>
                        <div class="form-group">
                            <textarea name="description" 
                                      id="description"
                                      class="premium-textarea @error('description') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Provide some details about this brand or manufacturer...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="form-footer">
                <div class="footer-left text-muted">
                    <i class="fas fa-lock me-1"></i> Form is secured and validated
                </div>
                <div class="footer-right">
                    <a href="{{ route('admin.brands.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        <span>Create Brand</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* =========================================================
   CREATE BRAND — PREMIUM RESPONSIVE STYLES
   ========================================================= */

.brand-form-page {
    padding: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* --- Form Header --- */
.form-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.5rem 2rem;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    flex-wrap: wrap;
}
.form-header-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.form-header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: #fff;
    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
}
.form-title { font-size: 1.6rem; font-weight: 800; color: #1a1f2e; margin: 0 0 0.2rem; }
.form-subtitle { color: #64748b; margin: 0; font-size: 0.95rem; }
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.65rem 1.25rem;
    border: 1px solid #e2e8f0;
    color: #475569;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s;
    background: #fff;
}
.btn-back:hover { background: #f8fafc; color: #1e293b; border-color: #cbd5e1; transform: translateX(-3px); }

/* --- Form Container --- */
.form-container {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e9ecef;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    overflow: hidden;
}
.premium-form { padding: 0; }
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 2.5rem;
}
.span-full { grid-column: 1 / -1; }

/* --- Sections --- */
.form-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 0.5rem;
}
.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* --- Form Groups --- */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.premium-label {
    font-size: 0.95rem;
    font-weight: 600;
    color: #334155;
}
.premium-input, .premium-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    background: #f8fafc;
    transition: all 0.2s ease;
    color: #1e293b;
}
.premium-input:focus, .premium-textarea:focus {
    outline: none;
    border-color: #10b981;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
}
.premium-input.is-invalid {
    border-color: #ef4444;
    background: #fef2f2;
}
.premium-feedback {
    color: #ef4444;
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: 0.25rem;
}
.input-hint { font-size: 0.82rem; color: #94a3b8; font-italic: italic; }

/* --- Icons in Inputs --- */
.input-with-icon { position: relative; }
.input-with-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
}
.input-with-icon .premium-input { padding-left: 2.75rem; }

/* --- Logo Upload --- */
.logo-upload-wrapper {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: #f8fafc;
    padding: 1.25rem;
    border-radius: 14px;
    border: 1.5px dashed #cbd5e1;
}
.logo-preview-box {
    width: 100px;
    height: 100px;
    background: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.upload-placeholder { font-size: 2rem; color: #cbd5e1; }
.preview-img { width: 100%; height: 100%; object-fit: contain; }
.upload-controls { flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }
.btn-upload {
    background: #fff;
    border: 1px solid #cbd5e1;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
}
.btn-upload:hover { background: #f1f5f9; border-color: #94a3b8; }
.btn-remove-logo {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    background: #ef4444;
    color: #fff;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
}
.upload-info { font-size: 0.75rem; color: #94a3b8; }



/* --- Form Footer --- */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 2.5rem;
    background: #f8fafc;
    border-top: 1px solid #e9ecef;
}
.footer-right { display: flex; gap: 1rem; }
.btn-cancel {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
.btn-submit {
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(67,97,238,0.3);
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(67,97,238,0.4);
    filter: brightness(1.1);
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .brand-form-page { padding: 1rem; }
    .form-header { padding: 1.25rem; flex-direction: column; align-items: flex-start; }
    .btn-back { width: 100%; justify-content: center; }
    .form-grid { grid-template-columns: 1fr; padding: 1.5rem; gap: 1.5rem; }
    .form-footer { flex-direction: column; gap: 1.25rem; padding: 1.5rem; text-align: center; }
    .footer-right { width: 100%; flex-direction: column; }
    .btn-submit { order: -1; width: 100%; justify-content: center; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = logoPreview.querySelector('.preview-img');
    const placeholder = logoPreview.querySelector('.upload-placeholder');
    const removeBtn = document.getElementById('removeLogoBtn');

    // Logo Preview Logic
    logoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                placeholder.classList.add('d-none');
                removeBtn.classList.remove('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Remove Logo Logic
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        logoInput.value = '';
        previewImg.src = '';
        previewImg.classList.add('d-none');
        placeholder.classList.remove('d-none');
        removeBtn.classList.add('d-none');
    });

    // Auto-generate Code Recommendation (Optional)
    const nameInput = document.getElementById('brand_name');
    const codeInput = document.getElementById('brand_code');
    
    nameInput.addEventListener('blur', function() {
        if (!codeInput.value) {
            const code = this.value
                .trim()
                .substring(0, 3)
                .toUpperCase()
                .replace(/[^A-Z]/g, '');
            if (code) {
                const random = Math.floor(100 + Math.random() * 900);
                codeInput.value = code + '-' + random;
            }
        }
    });
});
</script>
@endpush

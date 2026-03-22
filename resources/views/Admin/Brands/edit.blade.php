@extends('layouts.admin')

@section('title', 'Edit Brand - CJ\'s Minimart')

@section('content')
<div class="brand-form-page">
    
    {{-- ===== Page Header ===== --}}
    <div class="form-header">
        <div class="form-header-left">
            <div class="form-header-icon edit-mode">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h1 class="form-title">Edit Brand: {{ $brand->brand_name }}</h1>
                <p class="form-subtitle">Update brand details or branding</p>
            </div>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Brands</span>
        </a>
    </div>

    {{-- ===== Form Content ===== --}}
    <div class="form-container">
        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="premium-form" id="brandForm">
            @csrf
            @method('PUT')
            
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
                                   value="{{ old('brand_name', $brand->brand_name) }}" 
                                   placeholder="e.g. Coca-Cola"
                                   required
                                   autofocus>
                            @error('brand_name')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="brand_code" class="premium-label">Brand Code <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" 
                                       id="brand_code"
                                       name="brand_code" 
                                       class="premium-input @error('brand_code') is-invalid @enderror" 
                                       value="{{ old('brand_code', $brand->brand_code) }}" 
                                       placeholder="BRD-001"
                                       required>
                            </div>
                            @error('brand_code')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('website', $brand->website) }}" 
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
                                    @if ($brand->logo)
                                        <img src="{{ asset($brand->logo) }}" alt="Current Logo" class="preview-img">
                                        <i class="fas fa-cloud-upload-alt upload-placeholder d-none"></i>
                                    @else
                                        <i class="fas fa-cloud-upload-alt upload-placeholder"></i>
                                        <img src="" alt="Preview" class="preview-img d-none">
                                    @endif
                                </div>
                                <div class="upload-controls">
                                    <input type="file" 
                                           id="logoInput"
                                           name="logo" 
                                           class="d-none" 
                                           accept="image/*">
                                    <button type="button" class="btn-upload" onclick="document.getElementById('logoInput').click()">
                                        <i class="fas fa-camera"></i> Change Logo
                                    </button>
                                    
                                    @if ($brand->logo)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_logo" id="removeLogoCheck" value="1">
                                        <label class="form-check-label text-danger small fw-bold" for="removeLogoCheck">
                                            Remove Current Logo
                                        </label>
                                    </div>
                                    @endif
                                    
                                    <button type="button" class="btn-remove-logo d-none" id="clearSelectedBtn">
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
                                      placeholder="Provide some details about this brand or manufacturer...">{{ old('description', $brand->description) }}</textarea>
                            @error('description')
                                <div class="premium-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="form-footer">
                <div class="footer-left">
                    <span class="text-muted small">
                        <i class="fas fa-clock me-1"></i> Last updated: {{ $brand->updated_at->diffForHumans() }}
                    </span>
                </div>
                <div class="footer-right">
                    <a href="{{ route('admin.brands.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit btn-update">
                        <i class="fas fa-sync-alt"></i>
                        <span>Update Brand</span>
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
   EDIT BRAND — PREMIUM RESPONSIVE STYLES
   (Inherits base from create but with specific tweaks)
   ========================================================= */

.brand-form-page {
    padding: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

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
.form-header-left { display: flex; align-items: center; gap: 1.25rem; }
.form-header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: #fff;
    box-shadow: 0 4px 12px rgba(67,97,238,0.3);
}
.form-header-icon.edit-mode { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245,158,11,0.3); }

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

.form-container {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e9ecef;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    overflow: hidden;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 2.5rem;
}
.span-full { grid-column: 1 / -1; }

.form-section { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 0.5rem; }
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

.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.premium-label { font-size: 0.95rem; font-weight: 600; color: #334155; }
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
.premium-input:focus, .premium-textarea:focus { outline: none; border-color: #4361ee; background: #fff; box-shadow: 0 0 0 4px rgba(67,97,238,0.1); }
.premium-input.is-invalid { border-color: #ef4444; background: #fef2f2; }
.premium-feedback { color: #ef4444; font-size: 0.85rem; font-weight: 500; margin-top: 0.25rem; }

.input-with-icon { position: relative; }
.input-with-icon i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
.input-with-icon .premium-input { padding-left: 2.75rem; }

.logo-upload-wrapper {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: #f8fafc;
    padding: 1.25rem;
    border-radius: 14px;
    border: 1.5px dashed #cbd5e1;
    position: relative;
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
    left: 100px;
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
    z-index: 10;
}
.upload-info { font-size: 0.75rem; color: #94a3b8; }



.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 2.5rem;
    background: #f8fafc;
    border-top: 1px solid #e9ecef;
}
.footer-right { display: flex; gap: 1rem; }
.btn-cancel { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; color: #64748b; text-decoration: none; transition: all 0.2s; }
.btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
.btn-submit {
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(245,158,11,0.4); filter: brightness(1.1); }

@media (max-width: 768px) {
    .brand-form-page { padding: 1rem; }
    .form-header { padding: 1.25rem; flex-direction: column; align-items: flex-start; }
    .btn-back { width: 100%; justify-content: center; }
    .form-grid { grid-template-columns: 1fr; padding: 1.5rem; gap: 1.5rem; }
    .logo-upload-wrapper { flex-direction: column; text-align: center; }
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
    const clearBtn = document.getElementById('clearSelectedBtn');
    const removeCheck = document.getElementById('removeLogoCheck');

    // Logo Preview Logic
    logoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                placeholder.classList.add('d-none');
                clearBtn.classList.remove('d-none');
                
                // Uncheck remove current logo if a new one is selected
                if (removeCheck) removeCheck.checked = false;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Clear Selected Logic (Local choice only)
    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        logoInput.value = '';
        
        // Restore original state or placeholder
        @if ($brand->logo)
            previewImg.src = "{{ asset($brand->logo) }}";
            previewImg.classList.remove('d-none');
            placeholder.classList.add('d-none');
        @else
            previewImg.src = "";
            previewImg.classList.add('d-none');
            placeholder.classList.remove('d-none');
        @endif
        
        clearBtn.classList.add('d-none');
    });
});
</script>
@endpush

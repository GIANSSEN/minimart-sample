@extends('layouts.admin')

@section('title', 'Create Role - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0"> <!-- Full width container - same as Stock In -->
    <!-- Page Header -->
    <div class="row mx-0 mb-4">
        <div class="col-12 px-0">
            <div class="modern-header d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size: clamp(1.5rem, 4vw, 1.8rem);">Create New Role</h1>
                        <p class="text-muted mb-0">Add a new role and assign permissions</p>
                    </div>
                </div>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row mx-0">
        <div class="col-12 px-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.roles.store') }}" id="roleForm">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Basic Information</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name') }}" required placeholder="e.g., Manager, Editor">
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Description</label>
                                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                                      rows="3" placeholder="Describe the role's purpose...">{{ old('description') }}</textarea>
                                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permissions -->
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0">Permissions</h6>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
                                                    <i class="fas fa-check-double me-1"></i>Select All
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="deselectAllPermissions()">
                                                    <i class="fas fa-times me-1"></i>Clear All
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="permissions-container" style="max-height: 400px; overflow-y: auto;">
                                            @foreach ($groupedPermissions as $group => $groupPermissions)
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <input type="checkbox" class="form-check-input me-2 group-checkbox" 
                                                               id="group_{{ Str::slug($group) }}" 
                                                               onchange="toggleGroup('{{ Str::slug($group) }}', this.checked)">
                                                        <label for="group_{{ Str::slug($group) }}" class="fw-semibold mb-0">
                                                            {{ $group ?: 'General' }}
                                                        </label>
                                                    </div>
                                                    <div class="row g-2" id="group_{{ Str::slug($group) }}_permissions">
                                                        @foreach ($groupPermissions as $permission)
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="{{ $permission->id }}" 
                                                                           class="form-check-input permission-checkbox group-{{ Str::slug($group) }}"
                                                                           id="perm_{{ $permission->id }}"
                                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Role
                                    </button>
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
        border-bottom: 2px solid #667eea;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: clamp(45px, 6vw, 55px);
        height: clamp(45px, 6vw, 55px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.5rem, 4vw, 1.8rem);
        box-shadow: 0 8px 15px rgba(102,126,234,0.25);
        flex-shrink: 0;
        z-index: 2;
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

    /* FORM STYLES */
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
        box-shadow: 0 0 0 0.25rem rgba(102,126,234,0.1);
    }

    /* PERMISSIONS CONTAINER */
    .permissions-container {
        padding-right: 0.5rem;
    }

    .permissions-container::-webkit-scrollbar {
        width: 5px;
    }

    .permissions-container::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 10px;
    }

    .permissions-container::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .form-check {
        margin-bottom: 0.5rem;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-label {
        cursor: pointer;
        font-size: 0.9rem;
    }

    /* BUTTONS */
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
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

    .btn-outline-primary {
        border-color: #dee2e6;
        color: #667eea;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }

    hr {
        opacity: 0.2;
        margin: 1.5rem 0;
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
        }

        .permissions-container {
            max-height: 300px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle all permissions in a group
    function toggleGroup(group, checked) {
        document.querySelectorAll(`.group-${group}`).forEach(checkbox => {
            checkbox.checked = checked;
        });
    }

    // Select all permissions
    function selectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        document.querySelectorAll('.group-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    // Deselect all permissions
    function deselectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('.group-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Update group checkbox based on individual permissions
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const group = this.className.match(/group-(\S+)/)[1];
            const groupCheckbox = document.getElementById(`group_${group}`);
            const groupPermissions = document.querySelectorAll(`.group-${group}`);
            const allChecked = Array.from(groupPermissions).every(cb => cb.checked);
            groupCheckbox.checked = allChecked;
        });
    });

    // Form submission with SweetAlert
    document.getElementById('roleForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Creating Role...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        this.submit();
    });
</script>
@endpush

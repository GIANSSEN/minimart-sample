@extends('layouts.admin')

@section('title', 'Categories - CJ\'s Minimart')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box products-header-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Categories</h1>
                <p class="page-subtitle">Organize your products with categories</p>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-header-action btn-header-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Category</span>
            </button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        @if(is_countable($categories) ? count($categories) > 0 : !empty($categories))
@foreach($categories as $category)
        <div class="col-xl-4 col-lg-6">
            <div class="category-card">
                <div class="category-card-header">
                    <div class="category-icon" style="background: {{ $category->color ?? '#667eea' }}15; color: {{ $category->color ?? '#667eea' }}">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="category-info">
                        <h5 class="category-name">{{ $category->category_name }}</h5>
                        <span class="category-meta">
                            <i class="fas fa-hashtag"></i> ID: {{ $category->id }}
                        </span>
                    </div>

                </div>
                
                <div class="category-card-body">
                    @if ($category->description)
                        <p class="category-description">{{ Str::limit($category->description, 100) }}</p>
                    @else
                        <p class="category-description text-muted fst-italic">No description provided</p>
                    @endif
                    
                    <div class="category-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $category->products_count }}</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $category->created_at->format('M d, Y') }}</span>
                            <span class="stat-label">Created</span>
                        </div>
                    </div>
                </div>
                
                <div class="category-card-footer">
                    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn-view" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.categories.show', ['category' => $category->id, 'edit' => 1]) }}" class="btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn-del" 
                            onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->category_name) }}')"
                            title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
@else
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>No Categories Found</h3>
                <p class="text-muted">Get started by creating your first category</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Create Category
                </a>
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="category_name" name="category_name" placeholder="e.g. Beverages" required>
                        <div class="invalid-feedback" id="category_name_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control rounded-3" id="description" name="description" rows="3" placeholder="Brief description of this category"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label fw-semibold">Theme Color</label>
                            <input type="color" class="form-control form-control-color w-100 rounded-3" id="color" name="color" value="#667eea" title="Choose category color">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label fw-semibold">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-info-circle" id="iconPreview"></i></span>
                                <input type="text" class="form-control border-start-0 rounded-end-3" id="icon" name="icon" placeholder="fa-folder" value="fa-folder">
                            </div>
                            <small class="text-muted">FontAwesome class (e.g. fa-coffee)</small>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="image" class="form-label fw-semibold">Category Image</label>
                        <input type="file" class="form-control rounded-3" id="image" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm" id="saveCategoryBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

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
        background: var(--primary-gradient);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: var(--primary-gradient);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    /* Category Cards */
    .category-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
    }

    .category-card-header {
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .category-info {
        flex: 1;
    }

    .category-name {
        font-weight: 600;
        margin: 0 0 4px;
        color: #1e1e2d;
    }

    .category-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .category-meta i {
        font-size: 0.7rem;
    }



    .category-card-body {
        padding: 20px;
    }

    .category-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
        min-height: 60px;
    }

    .category-stats {
        display: flex;
        gap: 20px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .stat-item {
        flex: 1;
        text-align: center;
    }

    .stat-item .stat-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1e1e2d;
        display: block;
        margin-bottom: 4px;
    }

    .stat-item .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .category-card-footer {
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .category-card-footer .btn {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .category-card-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Action buttons use global styles in admin layout */

    /* Badge Styles */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #495057) !important;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        color: white;
        font-size: 3rem;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }

    .empty-state h3 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1e1e2d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }

    /* Toast Container */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        min-width: 300px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .toast.success { border-left-color: #10b981; }
    .toast.error { border-left-color: #ef4444; }
    .toast.warning { border-left-color: #f59e0b; }
    .toast.info { border-left-color: #3b82f6; }

    .toast-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast.success .toast-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        margin-bottom: 2px;
        color: #1e1e2d;
    }

    .toast-message {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .toast-close {
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        padding: 4px;
    }

    /* Pagination */
    .pagination {
        gap: 5px;
    }

    .page-link {
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        color: #6c757d;
        transition: all 0.3s;
    }

    .page-link:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            padding: 20px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .category-card-header {
            flex-wrap: wrap;
        }

        .category-status {
            margin-left: 0;
            width: 100%;
        }

        .category-card-footer {
            flex-wrap: wrap;
        }

        .category-card-footer .btn {
            flex: 1;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteCategory(id, name) {
        Swal.fire({
            title: 'Delete Category?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone if products are not re-assigned.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-4 border-0 shadow-lg',
                confirmButton: 'rounded-3 px-4',
                cancelButton: 'rounded-3 px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Add Category Form Handler
    document.getElementById('addCategoryForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('saveCategoryBtn');
        const spinner = btn.querySelector('.spinner-border');
        const formData = new FormData(form);
        
        // Reset validation
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        btn.disabled = true;
        spinner.classList.remove('d-none');
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-4 border-0 shadow-lg'
                    }
                }).then(() => {
                    window.location.reload();
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
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message,
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg'
                }
            });
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // Icon Preview Update
    document.getElementById('icon')?.addEventListener('input', function() {
        const iconPreview = document.getElementById('iconPreview');
        iconPreview.className = `fas ${this.value || 'fa-info-circle'}`;
    });
</script>
@endpush

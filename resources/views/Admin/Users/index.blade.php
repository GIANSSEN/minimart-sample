@extends('layouts.admin')

@section('title', 'All Users - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box users-header-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">User Management</h1>
                <p class="page-subtitle">Manage system users, roles, and permissions</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" id="exportBtn" title="Export users">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn-header-action btn-header-primary" title="Add new user">
                <i class="fas fa-plus-circle"></i>
                <span class="d-none d-sm-inline">Add User</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards - Enhanced with Animations -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4" id="statsContainer" role="region" aria-label="User statistics">
        @include('admin.users.partials.stats')
    </div>

    <!-- Filter Card -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden filter-card-enhanced">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center">
                            <i class="fas fa-sliders-h text-gradient-secondary me-2"></i>Filter Users
                        </h5>
                        <button class="btn btn-sm btn-link text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-12 col-lg-5">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-secondary"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" 
                                               placeholder="Search by name, email, username..." value="{{ request('search') }}"
                                               id="searchInput" autocomplete="off" aria-label="Search users">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <select name="role" class="form-select form-select-enhanced" id="roleSelect" aria-label="Filter by role">
                                        <option value="">All Roles</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2">
                                    <select name="approval_status" class="form-select form-select-enhanced" id="approvalSelect" aria-label="Filter by approval status">
                                        <option value="">All Approval</option>
                                        <option value="approved" {{ request('approval_status')=='approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="pending" {{ request('approval_status')=='pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ request('approval_status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-2">
                                    <button type="submit" class="btn btn-gradient-secondary w-100" id="applyFilterBtn" aria-label="Apply filters">
                                        <i class="fas fa-filter me-2"></i>Apply
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar (sticky) -->
    <div class="row mx-0 mb-3 px-4 sticky-top" style="top: 70px; z-index: 100;">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-3 bg-white bulk-actions-bar-enhanced" id="bulkBar" role="region" aria-label="Bulk actions">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" aria-label="Select all users">
                            <label class="form-check-label small fw-medium" for="selectAll">Select All</label>
                        </div>
                        <div class="vr"></div>
                        <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" disabled aria-label="Delete selected users">
                            <i class="fas fa-trash me-1"></i><span class="d-none d-sm-inline">Delete</span>
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="bulkApproveBtn" disabled aria-label="Approve selected users">
                            <i class="fas fa-check-circle me-1"></i><span class="d-none d-sm-inline">Approve</span>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="bulkExportBtn" disabled aria-label="Export selected users">
                            <i class="fas fa-download me-1"></i><span class="d-none d-sm-inline">Export</span>
                        </button>
                        <span class="small text-muted-600 ms-auto" id="selectedCount" aria-live="polite" aria-atomic="true">0 selected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Container -->
    <div class="row mx-0 px-4" id="usersTableContainer" role="region" aria-label="Users table">
        @include('admin.users.partials.table')
    </div>
</div>

<!-- Hidden form for CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Loading Spinner Template (hidden) -->
<template id="loading-spinner">
    <div class="text-center py-5">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>
@endsection

@push('styles')
<style>
    /* Root Variables */
    :root {
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-secondary: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        --shadow-soft: 0 10px 30px -12px rgba(0, 0, 0, 0.15);
        --shadow-hover: 0 20px 40px -15px rgba(0, 0, 0, 0.25);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Enhanced Header */
    .modern-header-enhanced {
        background: white;
        border-bottom: 2px solid #667eea;
        position: relative;
        overflow: hidden;
        width: 100%;
        z-index: 1;
        animation: slideDownIn 0.6s ease-out;
    }

    .modern-header-enhanced::before {
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
        pointer-events: none;
        z-index: 0;
    }

    .header-icon-enhanced {
        width: clamp(45px, 6vw, 55px);
        height: clamp(45px, 6vw, 55px);
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.2rem, 3vw, 1.8rem);
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        animation: scaleIn 0.6s ease-out;
    }

    /* Stat Cards Enhanced */
    .stat-card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(102,126,234,0.1);
        transition: var(--transition-smooth);
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(102,126,234,0.15);
        border-color: #667eea;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-icon.bg-primary { background: var(--gradient-primary); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .stat-icon.bg-warning { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-icon.bg-danger { background: linear-gradient(135deg, #fa709a, #fee140); }

    /* Filter Card Enhanced */
    .filter-card-enhanced {
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .input-group-enhanced .form-control,
    .form-select-enhanced {
        border-radius: 10px;
        transition: var(--transition-smooth);
        border: 1px solid #e5e7eb;
    }

    .input-group-enhanced .form-control:focus,
    .form-select-enhanced:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Bulk Actions Bar Enhanced */
    .bulk-actions-bar-enhanced {
        animation: slideUpIn 0.4s ease-out;
        transition: var(--transition-smooth);
    }

    .bulk-actions-bar-enhanced:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Button Styles */
    .btn {
        transition: var(--transition-smooth);
        border-radius: 10px;
    }

    .btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-gradient-secondary {
        background: var(--gradient-secondary);
        border: none;
        color: white;
    }

    .btn-gradient-secondary:hover:not(:disabled) {
        color: white;
    }

    .btn-gradient-secondary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Table Styles */
    .table-modern {
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .table-modern th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 2px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .table-modern tbody tr {
        transition: var(--transition-smooth);
        border-bottom: 1px solid #e5e7eb;
    }

    .table-modern tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.03);
        box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.05);
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: var(--transition-smooth);
    }

    .avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .badge-modern {
        padding: 0.4em 1em;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.75rem;
        background: rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: var(--transition-smooth);
    }

    .badge-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .badge-modern i {
        font-size: 0.6rem;
    }

    /* Pagination */
    .pagination-modern .pagination {
        gap: 5px;
    }

    .pagination-modern .page-link {
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        color: #495057;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: var(--transition-smooth);
    }

    .pagination-modern .page-link:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .pagination-modern .page-item.active .page-link {
        background: var(--gradient-secondary);
        color: white;
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }

    /* Skeleton Loading */
    .skeleton-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes skeleton {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Animations */
    @keyframes slideDownIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUpIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-modern thead {
            display: none;
        }

        .table-modern tbody tr {
            display: block;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            box-shadow: var(--shadow-soft);
            animation: slideUpIn 0.4s ease-out;
        }

        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem 0;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
            font-size: 0.875rem;
            min-width: 100px;
        }

        .modern-header-enhanced .d-flex:last-child {
            width: 100%;
            order: 3;
        }

        .modern-header-enhanced .btn {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .stat-card-modern {
            padding: 12px !important;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 1.4rem;
        }

        .stat-value {
            font-size: 1.3rem !important;
        }

        .stat-label {
            font-size: 0.65rem !important;
        }

        .bulk-actions-bar-enhanced .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }

        .bulk-actions-bar-enhanced .btn i {
            margin-right: 0;
        }

        .table-modern tbody td:before {
            min-width: 80px;
            font-size: 0.8rem;
        }
    }

    /* Accessibility */
    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }

    /* Print Styles */
    @media print {
        .modern-header-enhanced,
        .filter-card-enhanced,
        .bulk-actions-bar-enhanced {
            display: none;
        }

        .table-modern {
            box-shadow: none;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        'use strict';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentRequest = null;

        // ==================== UTILITIES ====================
        const debounce = (func, delay) => {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        };

        const showSkeleton = () => {
            const container = document.getElementById('usersTableContainer');
            container.innerHTML = `
                <div class="col-12">
                    <div class="card border-0 shadow-soft rounded-4 p-4">
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px; margin-bottom: 1rem;"></div>
                        <div class="skeleton-loading" style="height: 60px;"></div>
                    </div>
                </div>
            `;
        };

        const fetchUsers = async (url) => {
            if (currentRequest) currentRequest.abort();
            currentRequest = new AbortController();

            showSkeleton();
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: currentRequest.signal
                });
                if (!response.ok) throw new Error('Network error');
                const html = await response.text();
                document.getElementById('usersTableContainer').innerHTML = html;
                attachCheckboxHandlers();
                attachActionButtons();
            } catch (error) {
                if (error.name !== 'AbortError') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to load users.',
                        confirmButtonColor: '#667eea'
                    });
                }
            } finally {
                currentRequest = null;
            }
        };

        // ==================== FILTER FUNCTIONS ====================
        const searchInput = document.getElementById('searchInput');
        const roleSelect = document.getElementById('roleSelect');
        const approvalSelect = document.getElementById('approvalSelect');
        const filterForm = document.getElementById('filterForm');

        if (filterForm) {
            const debouncedFetch = debounce(() => {
                const params = new URLSearchParams(new FormData(filterForm)).toString();
                const url = `{{ route('admin.users.index') }}?${params}`;
                fetchUsers(url);
                window.history.pushState({}, '', url);
            }, 500);

            searchInput?.addEventListener('input', debouncedFetch);
            roleSelect?.addEventListener('change', debouncedFetch);
            approvalSelect?.addEventListener('change', debouncedFetch);

            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                debouncedFetch();
            });
        }

        // ==================== BULK SELECTION ====================
        const selectAll = document.getElementById('selectAll');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkApproveBtn = document.getElementById('bulkApproveBtn');
        const bulkExportBtn = document.getElementById('bulkExportBtn');
        const selectedCountSpan = document.getElementById('selectedCount');

        function attachCheckboxHandlers() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const selectAllDesktop = document.getElementById('selectAllDesktop');

            function updateBulkActions() {
                const checked = Array.from(document.querySelectorAll('.user-checkbox:checked'));
                const count = checked.length;
                selectedCountSpan.innerText = `${count} selected`;
                bulkDeleteBtn.disabled = count === 0;
                bulkApproveBtn.disabled = count === 0;
                bulkExportBtn.disabled = count === 0;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            if (selectAllDesktop) {
                selectAllDesktop.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });
        }

        // ==================== BULK DELETE ====================
        bulkDeleteBtn?.addEventListener('click', async function() {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            const result = await Swal.fire({
                title: 'Bulk Delete',
                html: `Delete <strong>${ids.length}</strong> user(s)?<br><small class="text-muted">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });

            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch('{{ route("admin.users.bulk-delete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ ids })
                    });
                    if (!response.ok) throw new Error('Delete failed');
                    const data = await response.json();
                    if (data.success) {
                        ids.forEach(id => {
                            document.querySelectorAll(`.user-row-${id}`).forEach(el => {
                                el.style.animation = 'slideUpIn 0.3s ease-out reverse';
                                setTimeout(() => el.remove(), 300);
                            });
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#667eea'
                        });
                        updateBulkActions();
                        fetch('{{ route("admin.users.stats") }}')
                            .then(r => r.text())
                            .then(html => document.getElementById('statsContainer').innerHTML = html);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not delete users.',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        });

        // ==================== BULK APPROVE ====================
        bulkApproveBtn?.addEventListener('click', async function() {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            const result = await Swal.fire({
                title: 'Bulk Approve',
                html: `Approve <strong>${ids.length}</strong> user(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, approve',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });

            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Approving...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch('{{ route("admin.users.bulk-approve") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ ids })
                    });
                    if (!response.ok) throw new Error('Approve failed');
                    const data = await response.json();
                    if (data.success) {
                        ids.forEach(id => {
                            const row = document.querySelector(`.user-row-${id}`);
                            if (row) {
                                const approvalBadge = row.querySelector('.approval-badge');
                                if (approvalBadge) {
                                    approvalBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Approved';
                                    approvalBadge.className = 'badge-modern text-success approval-badge';
                                    approvalBadge.style.animation = 'fadeInUp 0.3s ease-out';
                                }
                            }
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#667eea'
                        });
                        fetch('{{ route("admin.users.stats") }}')
                            .then(r => r.text())
                            .then(html => document.getElementById('statsContainer').innerHTML = html);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not approve users.',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        });

        // ==================== BULK EXPORT ====================
        bulkExportBtn?.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            Swal.fire({
                title: 'Exporting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const url = `{{ route("admin.users.export") }}?ids=${ids.join(',')}`;
            window.location.href = url;

            setTimeout(() => Swal.close(), 1500);
        });

        // ==================== SINGLE DELETE ====================
        window.deleteUser = async function(id, name) {
            const result = await Swal.fire({
                title: 'Delete User?',
                html: `Delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch(`/admin/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Delete failed');
                    const data = await response.json();
                    if (data.success) {
                        document.querySelectorAll(`.user-row-${id}`).forEach(el => {
                            el.style.animation = 'slideUpIn 0.3s ease-out reverse';
                            setTimeout(() => el.remove(), 300);
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#667eea'
                        });
                        fetch('{{ route("admin.users.stats") }}')
                            .then(r => r.text())
                            .then(html => document.getElementById('statsContainer').innerHTML = html);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not delete user.',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        };

        // ==================== SINGLE APPROVE ====================
        window.approveUser = async function(id, name) {
            const result = await Swal.fire({
                title: 'Approve User?',
                html: `Approve <strong>${name}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, approve',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            });
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Approving...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch(`/admin/users/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Approve failed');
                    const data = await response.json();
                    if (data.success) {
                        const row = document.querySelector(`.user-row-${id}`);
                        if (row) {
                            const approvalBadge = row.querySelector('.approval-badge');
                            if (approvalBadge) {
                                approvalBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Approved';
                                approvalBadge.className = 'badge-modern text-success approval-badge';
                                approvalBadge.style.animation = 'fadeInUp 0.3s ease-out';
                            }
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#667eea'
                        });
                        fetch('{{ route("admin.users.stats") }}')
                            .then(r => r.text())
                            .then(html => document.getElementById('statsContainer').innerHTML = html);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not approve user.',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        };

        // ==================== EXPORT ====================
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Exporting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            window.location.href = '{{ route("admin.users.export") }}';
            setTimeout(() => Swal.close(), 1500);
        });

        // ==================== INITIALIZATION ====================
        attachCheckboxHandlers();

        // Handle browser back/forward
        window.addEventListener('popstate', () => {
            fetchUsers(window.location.href);
        });

        // Animate stats on load
        window.addEventListener('load', () => {
            const statCards = document.querySelectorAll('.stat-card-modern');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    })();
</script>
@endpush

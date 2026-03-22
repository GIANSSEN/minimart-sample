@extends('layouts.admin')

@section('title', 'Suppliers - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Suppliers Page */
.suppliers-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.stat-card-premium {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #edf2f7;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}

.stat-card-premium:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border-color: #e2e8f0;
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info .stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

.stat-info .stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Filter Card */
.filter-card-premium {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #edf2f7;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}

.search-input-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.search-input-group:focus-within {
    background: #fff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.search-input-group input {
    background: transparent;
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.95rem;
    color: #1e293b;
}

.filter-select {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.95rem;
    color: #1e293b;
    outline: none;
    transition: all 0.2s;
}

.filter-select:focus {
    border-color: #3b82f6;
    background: #fff;
}

/* Table Enhancements */
.content-card-premium {
    background: #fff;
    border-radius: 24px;
    border: 1px solid #edf2f7;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

.premium-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.premium-table thead th {
    background: #f8fafc;
    padding: 1.25rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.premium-table tbody tr:hover {
    background: #f8fafc;
}

.premium-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.supplier-identity {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.supplier-icon-box {
    width: 44px;
    height: 44px;
    background: #eff6ff;
    color: #3b82f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.supplier-name {
    display: block;
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}

.supplier-code-badge {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: monospace;
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.status-active { background: #ecfdf5; color: #10b981; }
.status-inactive { background: #f1f5f9; color: #94a3b8; }

/* Action buttons use global styles in admin layout */

/* Modal Premium Styling */
.modal-premium .modal-content {
    border: none;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
}

.modal-premium .modal-header {
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    padding: 1.5rem 2rem;
    border-radius: 24px 24px 0 0;
}

.modal-premium .modal-title {
    font-weight: 800;
    color: #1e293b;
}

.modal-premium .modal-body {
    padding: 2rem;
}

.modal-premium .modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 1.25rem 2rem;
    border-radius: 0 0 24px 24px;
}
</style>
@endpush

@section('content')
<div class="suppliers-page">
    {{-- Premium Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);">
                <i class="fas fa-truck"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Suppliers</h1>
                <p class="page-subtitle">Manage inventory partners and procurement sources</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.purchase-history.index') }}" class="btn-header-action btn-header-light">
                <i class="fas fa-history"></i>
                <span>History</span>
            </a>
            <a href="{{ route('admin.suppliers.create') }}" class="btn-header-action btn-header-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Register Supplier</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($suppliers->total()) }}</span>
                <span class="stat-label">Registered</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-check-shield"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($activeCount) }}</span>
                <span class="stat-label">Active Partners</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($totalProducts) }}</span>
                <span class="stat-label">Unique Products</span>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card-premium">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" class="row g-3">
            <div class="col-lg-7 col-md-12">
                <div class="search-input-group">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" name="search" placeholder="Search by name, code, contact person..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <select name="status" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold" style="background: #3b82f6; border: none;">
                    <i class="fas fa-filter me-2"></i>Filter Results
                </button>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="content-card-premium">
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="ps-4">Supplier Identity</th>
                        <th>Primary Contact</th>
                        <th>Location</th>
                        <th>Catalog</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($suppliers) ? count($suppliers) > 0 : !empty($suppliers))
@foreach($suppliers as $supplier)
                    <tr>
                        <td class="ps-4">
                            <div class="supplier-identity">
                                <div class="supplier-icon-box">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <span class="supplier-name">{{ $supplier->supplier_name }}</span>
                                    <span class="supplier-code-badge">{{ $supplier->supplier_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $supplier->contact_person ?? 'No Representative' }}</span>
                                <span class="text-muted small">{{ $supplier->email ?? $supplier->phone ?? 'No contact info' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small d-inline-block text-truncate" style="max-width: 150px;">
                                {{ $supplier->address ?? 'No address set' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-blue-soft text-primary fw-bold" style="font-size: 0.75rem;">
                                {{ $supplier->products_count }} Skus
                            </span>
                        </td>
                        <td>
                            @if ($supplier->status == 'active')
                                <div class="status-badge status-active">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Active</span>
                                </div>
                            @else
                                <div class="status-badge status-inactive">
                                    <i class="fas fa-minus-circle"></i>
                                    <span>Inactive</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button onclick="showSupplier({{ $supplier->id }})" class="btn-view" title="View Catalog">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editSupplier({{ $supplier->id }})" class="btn-edit" title="Edit Partner">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteSupplier({{ $supplier->id }}, '{{ addslashes($supplier->supplier_name) }}')" class="btn-del" title="Terminate Partnership">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="opacity-50">
                                <i class="fas fa-truck-monster fa-4x mb-3 text-muted"></i>
                                <h3 class="text-muted fw-bold">No Suppliers Found</h3>
                                <p>Start by registering your first supplier partner</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($suppliers->hasPages())
        <div class="p-4 border-top">
            {{ $suppliers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Standard Modals will be injected here via Scripts --}}
<div id="modal-container"></div>

@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';

// Show Supplier
async function showSupplier(id) {
    Swal.fire({ title: 'Loading...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`/admin/suppliers/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const s = await res.json();
        Swal.close();
        
        let html = `
            <div class="text-start p-2">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-1">${s.supplier_name}</h4>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill">${s.supplier_code}</span>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Representative</label>
                        <p class="mb-0 fw-semibold">${s.contact_person || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Email Address</label>
                        <p class="mb-0 fw-semibold">${s.email || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Contact Number</label>
                        <p class="mb-0 fw-semibold">${s.phone || s.mobile || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold text-uppercase">Status</label>
                        <div>
                            <span class="badge ${s.status === 'active' ? 'bg-success' : 'bg-secondary'} px-3 py-1 rounded-pill">
                                ${s.status.toUpperCase()}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted fw-bold text-uppercase">Office Address</label>
                        <p class="mb-0 fw-semibold">${s.address || 'N/A'}</p>
                    </div>
                    <div class="col-12 p-3 bg-light rounded-4">
                        <label class="small text-muted fw-bold text-uppercase">Internal Memo</label>
                        <p class="mb-0 small text-muted">${s.notes || 'No notes for this partner.'}</p>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: '',
            html: html,
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-edit me-2"></i>Edit Detail',
            confirmButtonColor: '#3b82f6',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) editSupplier(id);
        });
    } catch (e) {
        Swal.fire('Error', 'Failed to load details', 'error');
    }
}

// Edit Supplier
async function editSupplier(id) {
    Swal.fire({ title: 'Initializing...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`/admin/suppliers/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const s = await res.json();
        Swal.close();

        Swal.fire({
            title: 'Update Supplier Partner',
            html: `
                <form id="swalEditForm" class="text-start p-1">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">PARTNER CODE</label>
                            <input type="text" id="swal_code" class="form-control rounded-3" value="${s.supplier_code}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">PARTNER NAME</label>
                            <input type="text" id="swal_name" class="form-control rounded-3" value="${s.supplier_name}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">REPRESENTATIVE</label>
                            <input type="text" id="swal_contact" class="form-control rounded-3" value="${s.contact_person || ''}">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">EMAIL</label>
                            <input type="email" id="swal_email" class="form-control rounded-3" value="${s.email || ''}">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">PHONE</label>
                            <input type="text" id="swal_phone" class="form-control rounded-3" value="${s.phone || ''}">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">STATUS</label>
                            <select id="swal_status" class="form-select rounded-3">
                                <option value="active" ${s.status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${s.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted">ADDRESS</label>
                            <textarea id="swal_address" class="form-control rounded-3" rows="2">${s.address || ''}</textarea>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Partnership',
            confirmButtonColor: '#3b82f6',
            width: '650px',
            preConfirm: () => {
                const code = document.getElementById('swal_code').value;
                const name = document.getElementById('swal_name').value;
                if (!code || !name) return Swal.showValidationMessage('Code and Name are required');
                return {
                    supplier_code: code,
                    supplier_name: name,
                    contact_person: document.getElementById('swal_contact').value,
                    email: document.getElementById('swal_email').value,
                    phone: document.getElementById('swal_phone').value,
                    status: document.getElementById('swal_status').value,
                    address: document.getElementById('swal_address').value
                };
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Saving...', didOpen: () => Swal.showLoading() });
                const response = await fetch(`/admin/suppliers/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify(result.value)
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire('Updated!', 'Partner record has been updated.', 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to update', 'error');
                }
            }
        });
    } catch (e) {
        Swal.fire('Error', 'Failed to load editor', 'error');
    }
}

// Delete Supplier
function deleteSupplier(id, name) {
    Swal.fire({
        title: 'Terminate Partnership?',
        text: `Are you sure you want to remove ${name}? This cannot be undone if they have no active products.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Deleting...', didOpen: () => Swal.showLoading() });
            try {
                const res = await fetch(`/admin/suppliers/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) {
                    Swal.fire('Deleted!', 'Supplier has been removed.', 'success').then(() => window.location.reload());
                } else {
                    const data = await res.json();
                    Swal.fire('Failed', data.message || 'Could not delete partner', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Network error', 'error');
            }
        }
    });
}
</script>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ==================== INITIALIZATION ====================
    $(document).ready(function() {
        $('#statusSelect').select2({
            theme: 'default',
            placeholder: 'All Status',
            allowClear: true,
            width: '100%'
        });

        animateStatCards();
        animateTableRows();
    });

    // ==================== ANIMATIONS ====================
    function animateStatCards() {
        const cards = document.querySelectorAll('.stat-card-modern');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    function animateTableRows() {
        const rows = document.querySelectorAll('.supplier-row');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, index * 50);
        });
    }

    // ==================== VALIDATION ====================
    function validatePerPage(select) {
        const validValues = [15, 25, 50, 100];
        const value = parseInt(select.value);
        
        if (!validValues.includes(value)) {
            select.value = 15;
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Selection',
                text: 'Please select a valid number of suppliers per page.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        document.getElementById('perPageForm').submit();
        return true;
    }

    // ==================== SHOW SUPPLIER ====================
    window.showSupplier = async function(id) {
        const modal = new bootstrap.Modal(document.getElementById('supplierShowModal'));
        const content = document.getElementById('supplierShowContent');
        
        content.innerHTML = `
            <div class="p-5 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        modal.show();
        
        try {
            const response = await fetch(`/admin/suppliers/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Failed to fetch details');
            const supplier = await response.json();
            
            content.innerHTML = `
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-building fa-3x text-primary"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1">${supplier.supplier_name}</h4>
                            <span class="badge bg-secondary mb-3">${supplier.supplier_code}</span>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge ${supplier.status === 'active' ? 'bg-success' : 'bg-secondary'} px-3 py-2 rounded-pill">
                                    <i class="fas fa-circle fa-2xs me-1"></i> ${supplier.status.charAt(0).toUpperCase() + supplier.status.slice(1)}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Contact Person</label>
                                    <p class="mb-0 text-dark fw-medium">${supplier.contact_person || '—'}</p>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Email</label>
                                    <p class="mb-0 text-dark fw-medium">${supplier.email || '—'}</p>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Phone</label>
                                    <p class="mb-0 text-dark fw-medium">${supplier.phone || '—'}</p>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Mobile</label>
                                    <p class="mb-0 text-dark fw-medium">${supplier.mobile || '—'}</p>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Address</label>
                                    <p class="mb-0 text-dark fw-medium">${supplier.address || '—'}</p>
                                </div>
                                <div class="col-12 mt-3 p-3 bg-light rounded-3">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">Internal Notes</label>
                                    <p class="mb-0 text-dark small">${supplier.notes || 'No notes available.'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('btnEditFromShow').onclick = () => {
                modal.hide();
                editSupplier(id);
            };
            
        } catch (error) {
            content.innerHTML = `<div class="p-5 text-center text-danger"><i class="fas fa-exclamation-circle me-2"></i> Error loading details.</div>`;
        }
    };

    // ==================== EDIT SUPPLIER ====================
    window.editSupplier = async function(id) {
        const modalElement = document.getElementById('supplierEditModal');
        const modal = new bootstrap.Modal(modalElement);
        const form = document.getElementById('editSupplierForm');
        
        // Reset form
        form.reset();
        
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        try {
            const response = await fetch(`/admin/suppliers/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Failed to fetch data');
            const supplier = await response.json();
            
            document.getElementById('edit_supplier_id').value = supplier.id;
            document.getElementById('edit_supplier_code').value = supplier.supplier_code;
            document.getElementById('edit_supplier_name').value = supplier.supplier_name;
            document.getElementById('edit_contact_person').value = supplier.contact_person || '';
            document.getElementById('edit_email').value = supplier.email || '';
            document.getElementById('edit_phone').value = supplier.phone || '';
            document.getElementById('edit_mobile').value = supplier.mobile || '';
            document.getElementById('edit_address').value = supplier.address || '';
            document.getElementById('edit_notes').value = supplier.notes || '';
            
            Swal.close();
            modal.show();
            
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Could not load supplier data.' });
        }
    };

    // ==================== SUBMIT EDIT FORM ====================
    document.getElementById('editSupplierForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }
        
        const id = document.getElementById('edit_supplier_id').value;
        const submitBtn = document.getElementById('btnSaveSupplier');
        const originalContent = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        try {
            const response = await fetch(`/admin/suppliers/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('supplierEditModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload(); // Simple reload for now to see changes
                });
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: error.message });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    });

    // ==================== DELETE SUPPLIER ====================
    window.deleteSupplier = function(id, name) {
        Swal.fire({
            title: 'Delete Supplier?',
            html: `
                <p>Are you sure you want to delete <strong>${name}</strong>?</p>
                <p>Type <strong style="background:#f8f9fa;padding:2px 6px;">${name}</strong> to confirm:</p>
                <input type="text" id="confirm-name" class="swal2-input" placeholder="Enter supplier name">
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const input = Swal.getPopup().querySelector('#confirm-name');
                if (!input) return false;
                const enteredName = input.value.trim();
                if (enteredName !== name) {
                    Swal.showValidationMessage(`The name does not match. Please type exactly "${name}"`);
                    return false;
                }
                return true;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch(`/admin/suppliers/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.redirected) {
                         window.location.href = response.url;
                         return;
                    }

                    const data = await response.json();
                    if (data.success || response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Supplier deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Delete failed');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        });
    };

    // ==================== COPY TO CLIPBOARD ====================
    document.querySelectorAll('.btn-copy').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const text = this.getAttribute('data-clipboard-text');
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const originalTitle = this.getAttribute('title') || 'Copy';
                this.setAttribute('title', 'Copied!');
                setTimeout(() => this.setAttribute('title', originalTitle), 1500);
            }).catch(() => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                const originalTitle = this.getAttribute('title') || 'Copy';
                this.setAttribute('title', 'Copied!');
                setTimeout(() => this.setAttribute('title', originalTitle), 1500);
            });
        });
    });

    // ==================== FORM HANDLING ====================
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                // Optional validation
            });
        }

        const applyFilterBtn = document.getElementById('applyFilterBtn');
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span class="d-none d-sm-inline">Applying...</span>';
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-filter me-2"></i><span class="d-none d-sm-inline">Apply</span>';
                }, 1000);
            });
        }

        // Debounced search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length >= 2 || this.value.length === 0) {
                        document.getElementById('applyFilterBtn').click();
                    }
                }, 500);
            });
        }
    });

    // ==================== RESPONSIVE DATA LABELS ====================
    function setDataLabels() {
        if (window.innerWidth < 768) {
            const headers = ['Supplier', 'Code', 'Contact', 'Phone', 'Products', 'Status', 'Actions'];
            document.querySelectorAll('.modern-table tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach((cell, index) => {
                    if (headers[index]) cell.setAttribute('data-label', headers[index]);
                });
            });
        }
    }
    setDataLabels();
    window.addEventListener('resize', setDataLabels);
</script>
@endpush

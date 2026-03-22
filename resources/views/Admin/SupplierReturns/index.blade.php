@extends('layouts.admin')

@section('title', 'Supplier Returns - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Returns Page */
.returns-page {
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
}

.search-input-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid #e2e8f0;
}

.search-input-group input {
    background: transparent;
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.95rem;
}

.filter-select {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.95rem;
    outline: none;
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
}

.premium-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
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

.status-pending { background: #fffbeb; color: #b45309; }
.status-completed { background: #f0fdf4; color: #15803d; }
.status-cancelled { background: #fef2f2; color: #b91c1c; }

/* Action buttons use global styles in admin layout */
</style>
@endpush

@section('content')
<div class="returns-page">
    {{-- Premium Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                <i class="fas fa-undo"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Supplier Returns</h1>
                <p class="page-subtitle">Manage damaged or expired inventory returns</p>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="addReturn()" class="btn-header-action btn-header-danger">
                <i class="fas fa-plus-circle"></i>
                <span>Log New Return</span>
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($returns->total()) }}</span>
                <span class="stat-label">Total Logged</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $returns->where('status', 'pending')->count() }}</span>
                <span class="stat-label">Pending Processing</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $returns->where('status', 'completed')->count() }}</span>
                <span class="stat-label">Returns Resolved</span>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card-premium">
        <form method="GET" action="{{ route('admin.supplier-returns.index') }}" class="row g-3">
            <div class="col-lg-5 col-md-12">
                <div class="search-input-group">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" name="search" placeholder="Search product or reason..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <select name="supplier_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->supplier_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold" style="background: #ef4444; border: none;">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Content --}}
    <div class="content-card-premium">
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="ps-4">Log Date</th>
                        <th>Product Details</th>
                        <th>Supplier</th>
                        <th>Qty</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($returns) ? count($returns) > 0 : !empty($returns))
@foreach($returns as $ret)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted fw-bold small text-uppercase">{{ \Carbon\Carbon::parse($ret->return_date)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $ret->product->product_name ?? 'Undefined' }}</span>
                                <span class="text-muted small">{{ $ret->product->product_code ?? 'NO-CODE' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $ret->supplier->supplier_name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $ret->quantity }}</span>
                        </td>
                        <td>
                            <span class="text-muted small d-inline-block text-truncate" style="max-width: 150px;" title="{{ $ret->reason }}">
                                {{ $ret->reason }}
                            </span>
                        </td>
                        <td>
                            <div class="status-badge status-{{ $ret->status }}">
                                <i class="fas fa-{{ $ret->status == 'completed' ? 'check-circle' : ($ret->status == 'pending' ? 'clock' : 'times-circle') }}"></i>
                                <span>{{ ucfirst($ret->status) }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button onclick="editReturn({{ $ret->id }})" class="btn-edit" title="Modify Log">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteReturn({{ $ret->id }})" class="btn-del" title="Remove Entry">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="opacity-30">
                                <i class="fas fa-undo fa-4x mb-3"></i>
                                <h3 class="fw-bold">No Records</h3>
                                <p>No supplier returns have been logged yet</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($returns->hasPages())
        <div class="p-4 border-top">
            {{ $returns->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';

async function addReturn() {
    Swal.fire({
        title: 'Log Supplier Return',
        html: `
            <div class="text-start p-1">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="small fw-bold text-muted">SUPPLIER</label>
                        <select id="swal_supplier" class="form-select rounded-3">
                            <option value="">Choose Supplier</option>
                            @foreach ($suppliers as $s) <option value="{{ $s->id }}">{{ $s->supplier_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted">PRODUCT</label>
                        <select id="swal_product" class="form-select rounded-3">
                            <option value="">Choose Product</option>
                            @foreach ($products as $p) <option value="{{ $p->id }}">{{ $p->product_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted">QUANTITY</label>
                        <input type="number" id="swal_qty" class="form-control rounded-3" value="1" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted">RETURN DATE</label>
                        <input type="date" id="swal_date" class="form-control rounded-3" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted">PRIMARY REASON</label>
                        <input type="text" id="swal_reason" class="form-control rounded-3" placeholder="e.g. Broken packaging">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted">STATUS</label>
                        <select id="swal_status" class="form-select rounded-3">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Record Return',
        confirmButtonColor: '#ef4444',
        preConfirm: () => {
            const sid = document.getElementById('swal_supplier').value;
            const pid = document.getElementById('swal_product').value;
            const qty = document.getElementById('swal_qty').value;
            const reason = document.getElementById('swal_reason').value;
            if (!sid || !pid || !qty || !reason) return Swal.showValidationMessage('Basic info required');
            return {
                supplier_id: sid,
                product_id: pid,
                quantity: qty,
                return_date: document.getElementById('swal_date').value,
                reason: reason,
                status: document.getElementById('swal_status').value
            };
        }
    }).then(result => {
        if (result.isConfirmed) saveReturn(null, result.value);
    });
}

async function editReturn(id) {
    Swal.fire({ title: 'Fetching...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`/admin/supplier-returns/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await res.json();
        Swal.close();

        Swal.fire({
            title: 'Modify Return Log',
            html: `
                <div class="text-start p-1">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small fw-bold text-muted">SUPPLIER</label>
                            <select id="swal_supplier" class="form-select rounded-3">
                                @foreach ($suppliers as $s) <option value="{{ $s->id }}" \${d.supplier_id == {{ $s->id }} ? 'selected' : ''}>{{ $s->supplier_name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted">PRODUCT</label>
                            <select id="swal_product" class="form-select rounded-3">
                                @foreach ($products as $p) <option value="{{ $p->id }}" \${d.product_id == {{ $p->id }} ? 'selected' : ''}>{{ $p->product_name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">QUANTITY</label>
                            <input type="number" id="swal_qty" class="form-control rounded-3" value="\${d.quantity}">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">RETURN DATE</label>
                            <input type="date" id="swal_date" class="form-control rounded-3" value="\${d.return_date}">
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted">REASON</label>
                            <input type="text" id="swal_reason" class="form-control rounded-3" value="\${d.reason}">
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted">STATUS</label>
                            <select id="swal_status" class="form-select rounded-3">
                                <option value="pending" \${d.status == 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="completed" \${d.status == 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" \${d.status == 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Entry',
            confirmButtonColor: '#3b82f6',
            preConfirm: () => ({
                supplier_id: document.getElementById('swal_supplier').value,
                product_id: document.getElementById('swal_product').value,
                quantity: document.getElementById('swal_qty').value,
                return_date: document.getElementById('swal_date').value,
                reason: document.getElementById('swal_reason').value,
                status: document.getElementById('swal_status').value
            })
        }).then(result => {
            if (result.isConfirmed) saveReturn(id, result.value);
        });
    } catch (e) {
        Swal.fire('Error', 'Failed to load details', 'error');
    }
}

async function saveReturn(id, data) {
    Swal.fire({ title: 'Saving...', didOpen: () => Swal.showLoading() });
    const url = id ? `/admin/supplier-returns/${id}` : '{{ route("admin.supplier-returns.store") }}';
    try {
        const response = await fetch(url, {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        });
        const res = await response.json();
        if (res.success) {
            Swal.fire('Success!', res.message, 'success').then(() => window.location.reload());
        } else {
            Swal.fire('Error', res.message || 'Operation failed', 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'Network error', 'error');
    }
}

function deleteReturn(id) {
    Swal.fire({
        title: 'Delete Return Log?',
        text: 'This will permanently remove the record from history.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/supplier-returns/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d = await res.json();
                if (d.success) Swal.fire('Deleted!', d.message, 'success').then(() => window.location.reload());
            } catch (e) {
                Swal.fire('Error', 'Network error', 'error');
            }
        }
    });
}
</script>
@endpush

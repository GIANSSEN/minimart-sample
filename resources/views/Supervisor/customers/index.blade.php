@extends('layouts.admin')

@section('title', 'Customers')

@push('styles')
<style>
.page-header-premium { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.header-left { display: flex; align-items: center; gap: 1rem; }
.header-icon-box { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #8B5CF6, #6D28D9); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem; box-shadow: 0 6px 16px rgba(139,92,246,0.3); }
.page-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
.page-subtitle { font-size: 0.85rem; color: #94a3b8; margin: 0; }
.filter-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; margin-bottom: 1.5rem; }
.table-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; overflow: hidden; }
.table th { font-weight: 600; font-size: 0.78rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; background: #f8fafc; padding: 1rem 1.25rem; border-bottom: 2px solid #f1f5f9; }
.table td { padding: 0.9rem 1.25rem; vertical-align: middle; color: #374151; font-size: 0.9rem; border-bottom: 1px solid #f8fafc; }
.table tbody tr:hover { background: #fafbfd; }
.customer-type-badge { padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
.type-regular { background: rgba(100,116,139,0.1); color: #475569; }
.type-senior { background: rgba(59,130,246,0.1); color: #2563eb; }
.type-pwd { background: rgba(139,92,246,0.1); color: #6d28d9; }
.type-pregnant { background: rgba(236,72,153,0.1); color: #be185d; }
.type-employee { background: rgba(16,185,129,0.1); color: #059669; }
/* Action buttons use global styles in admin layout */
.btn-add { background: linear-gradient(135deg, #8b5cf6, #6d28d9); border: none; color: white; border-radius: 12px; padding: 0.6rem 1.25rem; font-weight: 600; transition: all 0.3s; }
.btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(139,92,246,0.3); color: white; }
.form-control, .form-select { border-radius: 10px; border: 2px solid #f1f5f9; background: #f8fafc; height: 44px; }
.form-control:focus, .form-select:focus { border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1); }
.search-input-group { position: relative; }
.search-input-group .si { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 5; }
.search-input-group input { padding-left: 42px; }
.btn-filter { height: 44px; border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #8b5cf6, #6d28d9); border: none; }
.avatar-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: white; background: linear-gradient(135deg, #8b5cf6, #6d28d9); flex-shrink: 0; }
.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-state i { font-size: 3.5rem; color: #e2e8f0; margin-bottom: 1rem; }
.modal-content { border-radius: 20px; border: none; }
.modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.5rem; }
.modal-footer { border-top: 1px solid #f1f5f9; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <strong>Validation error:</strong> {{ $errors->first() }}
    </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-plus me-2"></i>Add Customer
        </button>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div><p class="text-muted small mb-1">Total</p><h3 class="fw-bold mb-0">{{ number_format($totalCount) }}</h3></div>
                    <div style="background:rgba(52,152,219,0.12);color:#3498db;width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-start">
                    <div><p class="text-muted small mb-1">Regular</p><h3 class="fw-bold mb-0 text-success">{{ number_format($regularCount) }}</h3></div>
                    <div style="background:rgba(46,204,113,0.12);color:#2ecc71;width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;"><i class="fas fa-user"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-start">
                    <div><p class="text-muted small mb-1">Senior/PWD</p><h3 class="fw-bold mb-0" style="color:#00c0ef;">{{ number_format($seniorCount) }}</h3></div>
                    <div style="background:rgba(0,192,239,0.12);color:#00c0ef;width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;"><i class="fas fa-wheelchair"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div><p class="text-muted small mb-1">Active</p><h3 class="fw-bold mb-0 text-warning">{{ number_format($activeCount) }}</h3></div>
                    <div style="background:rgba(243,156,18,0.12);color:#f39c12;width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;"><i class="fas fa-circle-check"></i></div>
                </div>
            </div>
        </div>
    </div>

    @php
        $otherTypesCount = max($totalCount - ($regularCount + $seniorCount), 0);
        $inactiveCount = max($totalCount - $activeCount, 0);
    @endphp
    <div class="chart-card mb-4 p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0"><i class="fas fa-chart-pie me-2" style="color:#8b5cf6;"></i>Customer Mix Overview</h6>
            <small class="text-muted">Customer type and status composition</small>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-lg-4">
                <div class="mx-auto" id="customerMixChartWrap" style="max-width: 240px;">
                    <canvas id="customerMixChart" height="220"></canvas>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">Active: {{ number_format($activeCount) }}</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">Inactive: {{ number_format($inactiveCount) }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">Regular: {{ number_format($regularCount) }}</span>
                    <span class="badge" style="background:rgba(139,92,246,0.12);color:#6d28d9;padding:0.5rem 0.8rem;">Senior/PWD: {{ number_format($seniorCount) }}</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Other Types: {{ number_format($otherTypesCount) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="fw-semibold mb-1"><i class="fas fa-filter me-2" style="color:#8b5cf6;"></i>Filter Logs</h6>
                <small class="text-muted">Find customers by profile, type, and status</small>
            </div>
        </div>
        <form method="GET" action="{{ route('supervisor.customers.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="search-input-group">
                        <i class="fas fa-search si"></i>
                        <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="customer_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="regular" {{ request('customer_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="senior" {{ request('customer_type') == 'senior' ? 'selected' : '' }}>Senior</option>
                        <option value="pwd" {{ request('customer_type') == 'pwd' ? 'selected' : '' }}>PWD</option>
                        <option value="pregnant" {{ request('customer_type') == 'pregnant' ? 'selected' : '' }}>Pregnant</option>
                        <option value="employee" {{ request('customer_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-filter text-white flex-fill"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('supervisor.customers.index') }}" class="btn btn-outline-secondary" style="height:44px;border-radius:10px;"><i class="fas fa-rotate-left"></i></a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold mb-0"><i class="fas fa-users me-2" style="color:#8b5cf6;"></i>Customer List</h6>
            <span class="badge bg-light text-muted">{{ $customers->total() }} customers</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Customer</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($customers) ? count($customers) > 0 : !empty($customers))
@foreach($customers as $customer)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">{{ substr($customer->name, 0, 1) }}</div>
                                <div>
                                    <span class="fw-semibold d-block">{{ $customer->name }}</span>
                                    <small class="text-muted">{{ $customer->email ?? 'No email' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            <span class="customer-type-badge type-{{ $customer->customer_type }}">
                                {{ ucfirst($customer->customer_type) }}
                            </span>
                        </td>
                        <td>
                            @if ($customer->status === 'active')
                                <span class="badge" style="background:rgba(16,185,129,0.12);color:#059669;padding:0.35rem 0.75rem;border-radius:20px;font-size:0.78rem;font-weight:600;">Active</span>
                            @else
                                <span class="badge" style="background:rgba(239,68,68,0.12);color:#dc2626;padding:0.35rem 0.75rem;border-radius:20px;font-size:0.78rem;font-weight:600;">Inactive</span>
                            @endif
                        </td>
                        <td>{{ optional($customer->created_at)->format('M d, Y') }}</td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn-edit" onclick="editCustomer({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->email }}', '{{ $customer->phone }}', '{{ $customer->address }}', '{{ $customer->customer_type }}', '{{ $customer->status }}')" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-del" onclick="deleteCustomer({{ $customer->id }}, '{{ addslashes($customer->name) }}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="deleteForm{{ $customer->id }}" method="POST" action="{{ route('supervisor.customers.destroy', $customer) }}" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h6 class="text-muted">No customers found</h6>
                                <p class="text-muted small">Add your first customer to get started</p>
                                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCustomerModal"><i class="fas fa-plus me-1"></i>Add Customer</button>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Showing {{ $customers->firstItem() }}-{{ $customers->lastItem() }} of {{ $customers->total() }}</small>
            {{ $customers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2 text-primary"></i>Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('supervisor.customers.store') }}" id="addCustomerForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer Type <span class="text-danger">*</span></label>
                            <select name="customer_type" class="form-select" required>
                                <option value="regular">Regular</option>
                                <option value="senior">Senior Citizen</option>
                                <option value="pwd">PWD</option>
                                <option value="pregnant">Pregnant</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" style="height:auto;border-radius:10px;" rows="2" placeholder="Enter address (optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-add"><i class="fas fa-save me-1"></i>Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2 text-success"></i>Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editCustomerForm">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" id="editPhone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer Type <span class="text-danger">*</span></label>
                            <select name="customer_type" id="editType" class="form-select" required>
                                <option value="regular">Regular</option>
                                <option value="senior">Senior Citizen</option>
                                <option value="pwd">PWD</option>
                                <option value="pregnant">Pregnant</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" id="editAddress" class="form-control" style="height:auto;border-radius:10px;" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;border-radius:12px;font-weight:600;"><i class="fas fa-save me-1"></i>Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const cmCtx = document.getElementById('customerMixChart');
const cmWrap = document.getElementById('customerMixChartWrap');
const cmData = [{{ $regularCount }}, {{ $seniorCount }}, {{ $otherTypesCount }}];
const cmTotal = cmData.reduce((a, b) => a + b, 0);
if (cmCtx) {
    if (cmTotal === 0 && cmWrap) {
        cmWrap.innerHTML = '<div class="text-center text-muted py-4 small">No customer mix data yet.</div>';
    } else {
        new Chart(cmCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Regular', 'Senior/PWD', 'Other Types'],
                datasets: [{
                    data: cmData,
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } },
                cutout: '68%'
            }
        });
    }
}

function editCustomer(id, name, email, phone, address, type, status) {
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email || '';
    document.getElementById('editPhone').value = phone || '';
    document.getElementById('editAddress').value = address || '';
    document.getElementById('editType').value = type;
    document.getElementById('editStatus').value = status;
    document.getElementById('editCustomerForm').action = '/supervisor/customers/' + id;
    new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
}

function deleteCustomer(id, name) {
    Swal.fire({
        title: 'Delete Customer?',
        html: `Are you sure you want to delete <strong>${name}</strong>? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}

@if (session('success'))
Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
@endif
@if (session('error'))
Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
@endif
</script>
@endpush

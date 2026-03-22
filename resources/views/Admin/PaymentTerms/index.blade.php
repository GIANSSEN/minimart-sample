@extends('layouts.admin')

@section('title', 'Payment Credit Terms - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Payment Terms Page */
.terms-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Stats Grid for Context */
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

.premium-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.term-identity {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.term-icon-box {
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

.term-name {
    display: block;
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}

.days-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    background: #f0f9ff;
    color: #0369a1;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

/* Action buttons use global styles in admin layout */

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
</style>
@endpush

@section('content')
<div class="terms-page">
    {{-- Premium Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Payment Terms</h1>
                <p class="page-subtitle">Configure accounts payable credit cycles</p>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="addPaymentTerm()" class="btn-header-action btn-header-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Create Term</span>
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(3, 105, 161, 0.1); color: #0369a1;">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $paymentTerms->total() }}</span>
                <span class="stat-label">Active Terms</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $paymentTerms->where('days_due', 0)->count() }}</span>
                <span class="stat-label">Cash Terms</span>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card-premium">
        <form method="GET" action="{{ route('admin.payment-terms.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="search-input-group">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" name="search" placeholder="Search by term name or description..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold" style="background: #3b82f6; border: none;">
                    <i class="fas fa-filter me-2"></i>Filter
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
                        <th class="ps-4">Term Identity</th>
                        <th>Credit Duration</th>
                        <th>Description / Usage</th>
                        <th>Last Modified</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($paymentTerms) ? count($paymentTerms) > 0 : !empty($paymentTerms))
@foreach($paymentTerms as $term)
                    <tr>
                        <td class="ps-4">
                            <div class="term-identity">
                                <div class="term-icon-box">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span class="term-name">{{ $term->term_name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="days-badge">
                                <i class="fas fa-hourglass-half"></i>
                                <span>{{ $term->days_due }} Days</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $term->description ?? 'No description provided' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $term->updated_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button onclick="editPaymentTerm({{ $term->id }})" class="btn-edit" title="Edit Configuration">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePaymentTerm({{ $term->id }}, '{{ addslashes($term->term_name) }}')" class="btn-del" title="Delete Term">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-50">
                                <i class="fas fa-file-invoice-dollar fa-4x mb-3 text-muted"></i>
                                <h3 class="text-muted fw-bold">No Configured Terms</h3>
                                <p>Define your first credit term to start tracking payloads</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($paymentTerms->hasPages())
        <div class="p-4 border-top">
            {{ $paymentTerms->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';

async function addPaymentTerm() {
    Swal.fire({
        title: 'New Credit Term',
        html: `
            <div class="text-start p-1">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">TERM IDENTIFIER</label>
                    <input type="text" id="swal_name" class="form-control rounded-3" placeholder="e.g. NET 30">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">DAYS UNTIL DUE</label>
                    <input type="number" id="swal_days" class="form-control rounded-3" value="0">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold text-muted">DESCRIPTION (OPTIONAL)</label>
                    <textarea id="swal_desc" class="form-control rounded-3" rows="3"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create Term',
        confirmButtonColor: '#3b82f6',
        preConfirm: () => {
            const name = document.getElementById('swal_name').value;
            const days = document.getElementById('swal_days').value;
            if (!name) return Swal.showValidationMessage('Identifier is required');
            return { term_name: name, days_due: days, description: document.getElementById('swal_desc').value };
        }
    }).then(async (result) => {
        if (result.isConfirmed) saveTerm(null, 'POST', result.value);
    });
}

async function editPaymentTerm(id) {
    Swal.fire({ title: 'Loading...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`/admin/payment-terms/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        Swal.close();

        Swal.fire({
            title: 'Update Credit Term',
            html: `
                <div class="text-start p-1">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">TERM IDENTIFIER</label>
                        <input type="text" id="swal_name" class="form-control rounded-3" value="${data.term_name}">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">DAYS UNTIL DUE</label>
                        <input type="number" id="swal_days" class="form-control rounded-3" value="${data.days_due}">
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold text-muted">DESCRIPTION (OPTIONAL)</label>
                        <textarea id="swal_desc" class="form-control rounded-3" rows="3">${data.description || ''}</textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Changes',
            confirmButtonColor: '#3b82f6',
            preConfirm: () => {
                const name = document.getElementById('swal_name').value;
                if (!name) return Swal.showValidationMessage('Identifier is required');
                return { term_name: name, days_due: document.getElementById('swal_days').value, description: document.getElementById('swal_desc').value };
            }
        }).then(async (result) => {
            if (result.isConfirmed) saveTerm(id, 'PUT', result.value);
        });
    } catch (e) {
        Swal.fire('Error', 'Failed to load data', 'error');
    }
}

async function saveTerm(id, method, data) {
    Swal.fire({ title: 'Saving...', didOpen: () => Swal.showLoading() });
    const url = id ? `/admin/payment-terms/${id}` : '{{ route("admin.payment-terms.store") }}';
    
    try {
        const response = await fetch(url, {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        });
        const resData = await response.json();
        if (resData.success) {
            Swal.fire('Success!', resData.message, 'success').then(() => window.location.reload());
        } else {
            Swal.fire('Error', resData.message || 'Operation failed', 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'Network error', 'error');
    }
}

function deletePaymentTerm(id, name) {
    Swal.fire({
        title: 'Delete Credit Term?',
        text: `Are you sure you want to remove ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/payment-terms/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire('Deleted!', '', 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Failed', 'Could not delete term', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Network error', 'error');
            }
        }
    });
}
</script>
@endpush

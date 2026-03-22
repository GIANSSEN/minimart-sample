@extends('layouts.admin')

@section('title', 'Pending Approvals - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Pending Approvals */
.pending-approvals-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Stats Summary */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.stat-card-glass {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.total .stat-icon { background: #EEF2FF; color: #4F46E5; }
.recent .stat-icon { background: #ECFDF5; color: #10B981; }
.page .stat-icon { background: #EFF6FF; color: #3B82F6; }

.stat-content .label { font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; }
.stat-content .value { font-size: 1.25rem; font-weight: 800; color: #1E293B; margin: 0; }

/* Filter Section */
.filter-section-premium {
    background: #fff;
    border-radius: 16px;
    padding: 1rem;
    margin-bottom: 1.25rem;
    border: 1px solid #edf2f7;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.filter-flex { display: flex; gap: 1rem; align-items: center; }

.search-box-premium {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}
.search-box-premium i { position: absolute; left: 1rem; color: #94A3B8; font-size: 0.9rem; }
.search-box-premium input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.8rem;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.search-box-premium input:focus { background: #fff; border-color: #3B82F6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); outline: none; }

.dropdown-premium { position: relative; width: 180px; }
.dropdown-premium select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #475569;
    appearance: none;
    cursor: pointer;
}
.dropdown-premium i { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8; pointer-events: none; }

.btn-filter-premium {
    padding: 0.75rem 1.5rem;
    background: #1E293B;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-filter-premium:hover { background: #0F172A; transform: translateY(-2px); }

/* Table */
.content-table-wrapper {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #edf2f7;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
}
.premium-table { width: 100%; border-collapse: collapse; }
.premium-table thead th {
    background: #F8FAFC;
    padding: 1.25rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #F1F5F9;
}
.premium-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: all 0.2s; }
.premium-table tbody tr:hover { background: #F8FAFC; }
.premium-table td { padding: 1.25rem 1.5rem; vertical-align: middle; }

/* Cells */
.profile-cell { display: flex; align-items: center; gap: 1rem; }
.avatar-wrapper { position: relative; width: 44px; height: 44px; }
.avatar-wrapper img { width: 100%; height: 100%; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
.status-dot-pending {
    position: absolute; bottom: -2px; right: -2px;
    width: 12px; height: 12px;
    background: #F59E0B;
    border: 2px solid #fff;
    border-radius: 50%;
}
.profile-name { display: block; font-weight: 700; color: #1E293B; font-size: 0.95rem; }
.profile-email { display: block; font-size: 0.8rem; color: #64748B; }

.id-cell { display: flex; flex-direction: column; gap: 4px; }
.user-role-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
}
.role-admin { background: #FEE2E2; color: #991B1B; }
.role-staff { background: #F3F4F6; color: #4B5563; }
.employee-id { font-weight: 700; color: #94A3B8; font-size: 0.8rem; margin-top: 2px; }

.date-full { display: block; font-weight: 600; color: #1E293B; font-size: 0.9rem; }
.date-ago { display: block; font-size: 0.75rem; color: #94A3B8; }

/* Actions */
.actions-flex { display: flex; justify-content: flex-end; gap: 0.6rem; }
.btn-action-approve {
    background: #10B981;
    color: #fff;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.85rem;
}
.btn-action-approve:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }

.btn-action-reject {
    background: #FEF2F2;
    color: #EF4444;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.85rem;
}
.btn-action-reject:hover { background: #EF4444; color: #fff; }

.btn-action-view {
    width: 38px; height: 38px;
    background: #F8FAFC;
    color: #64748B;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    transition: all 0.2s;
}
.btn-action-view:hover { background: #3B82F6; color: #fff; border-color: #3B82F6; }

/* Empty State */
.empty-state-illustrative { padding: 4rem 1rem; text-align: center; }
.illustrative-icon {
    width: 72px; height: 72px;
    background: #ECFDF5;
    color: #10B981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1.5rem;
}
.empty-state-illustrative h3 { font-size: 1.4rem; font-weight: 800; color: #1E293B; margin-bottom: 0.5rem; }
.empty-state-illustrative p { color: #64748B; max-width: 400px; margin: 0 auto; }

/* Pagination */
.pagination-footer-premium {
    padding: 1.25rem 1.5rem;
    background: #F8FAFC;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #F1F5F9;
}
.pagination-info { font-size: 0.85rem; color: #64748B; }

@media (max-width: 992px) {
    .stats-row { grid-template-columns: 1fr; }
    .page-header-premium { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .status-indicator-glass { width: 100%; justify-content: center; }
}

@media (max-width: 768px) {
    .filter-flex { flex-direction: column; align-items: stretch; }
    .dropdown-premium { width: 100%; }
    .premium-table thead { display: none; }
    .premium-table tbody tr { display: block; margin-bottom: 1rem; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; }
    .premium-table td { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px dotted #F1F5F9; }
    .premium-table td:last-child { border-bottom: none; flex-direction: column; gap: 0.75rem; padding-top: 1rem; }
    .premium-table td::before { content: attr(data-label); font-weight: 800; color: #94A3B8; font-size: 0.7rem; text-transform: uppercase; }
}
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let approvedCount = 0;

/**
 * Approve User Account
 */
function confirmApprove(id, name) {
    Swal.fire({
        title: 'Approve Account?',
        text: `Are you sure you want to activate the account for ${name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Approve',
        heightAuto: false
    }).then((result) => {
        if (result.isConfirmed) {
            triggerAction(id, 'approve');
        }
    });
}

/**
 * Reject User Account
 */
function confirmReject(id, name) {
    Swal.fire({
        title: 'Reject Account?',
        text: `Please provide a reason for rejecting ${name}'s request:`,
        input: 'textarea',
        inputPlaceholder: 'Reason for rejection...',
        inputAttributes: {
            'aria-label': 'Reason for rejection'
        },
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Confirm Rejection',
        heightAuto: false,
        inputValidator: (value) => {
            if (!value) {
                return 'You must provide a reason!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            triggerAction(id, 'reject', result.value);
        }
    });
}

/**
 * Trigger AJAX Action
 */
function triggerAction(id, action, reason = '') {
    const row = document.getElementById(`user-row-${id}`);
    
    // UI Progress State
    Swal.fire({
        title: 'Processing...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const bodyData = action === 'reject' ? { reason: reason } : {};

    fetch(`/admin/users/${id}/${action}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(bodyData)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Server error');
        return data;
    })
    .then(data => {
        if (data.success) {
            // Success visual feedback
            if (row) {
                row.style.transition = 'all 0.5s ease-in-out';
                row.style.opacity = '0';
                row.style.transform = 'translateX(50px)';
                setTimeout(() => {
                    row.remove();
                    updateUIStats(action);
                    checkQueueStatus();
                }, 500);
            }

            Swal.fire({
                title: action === 'approve' ? 'Approved!' : 'Rejected!',
                text: data.message || 'Action completed successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Error', data.message || 'Unable to update status.', 'error');
        }
    })
    .catch(error => {
        console.error('Action Failed:', error);
        Swal.fire('Failed', error.message || 'Connectivity issue. Please try again.', 'error');
    });
}

/**
 * Dynamic UI Updates
 */
function updateUIStats(action) {
    if (action === 'approve') {
        approvedCount++;
        const statLabel = document.getElementById('session-approved');
        if (statLabel) statLabel.innerText = approvedCount;
    }
    
    // Update total label
    const totalLabel = document.getElementById('pending-count-label');
    if (totalLabel) {
        let currentString = totalLabel.innerText;
        let currentMatch = currentString.match(/\d+/);
        if (currentMatch) {
            let current = parseInt(currentMatch[0]);
            totalLabel.innerText = (current - 1) + ' Requests Active';
        }
    }
}

function checkQueueStatus() {
    const tbody = document.getElementById('pending-users-list');
    if (tbody && tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4">
                    <div class="empty-state-illustrative">
                        <div class="illustrative-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h3>Queue is Empty!</h3>
                        <p>All staff account requests have been processed. Great job!</p>
                    </div>
                </td>
            </tr>
        `;
        // Optionally refresh page after a delay to get new pagination state
        setTimeout(() => location.reload(), 3000);
    }
}
</script>
@endpush

@section('content')
<div class="pending-approvals-page">
    
    {{-- Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box pending-header-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Pending Approvals</h1>
                <p class="page-subtitle">Review and authorize new system user registrations</p>
            </div>
        </div>
        
        <div class="header-actions">
            <div class="status-indicator-glass pending-indicator">
                <div class="pulse-dot"></div>
                <span>{{ $pendingUsers->total() }} PENDING REQUESTS</span>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="stats-row">
        <div class="stat-card-glass total">
            <div class="stat-icon"><i class="fas fa-users-cog"></i></div>
            <div class="stat-content">
                <span class="label">Total Pending</span>
                <h3 class="value">{{ $pendingUsers->total() }}</h3>
            </div>
        </div>
        <div class="stat-card-glass recent">
            <div class="stat-icon"><i class="fas fa-history"></i></div>
            <div class="stat-content">
                <span class="label">This Session</span>
                <h3 class="value" id="session-approved">0</h3>
            </div>
        </div>
        <div class="stat-card-glass page">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <span class="label">Current View</span>
                <h3 class="value">Page {{ $pendingUsers->currentPage() }}</h3>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="filter-section-premium">
        <form method="GET" action="{{ route('admin.users.pending') }}" class="filter-flex">
            <div class="search-box-premium">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by name, email, ID..." value="{{ request('search') }}">
            </div>
            
            <div class="dropdown-premium">
                <select name="role">
                    <option value="">All Roles</option>
                    @foreach (\App\Models\Role::active()->get() as $role)
                        <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>
            
            <button type="submit" class="btn-filter-premium">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            
            @if (request()->anyFilled(['search', 'role']))
            <a href="{{ route('admin.users.pending') }}" class="btn-reset-premium">
                <i class="fas fa-undo-alt"></i>
            </a>
            @endif
        </form>
    </div>

    {{-- Pending Users Table / Cards --}}
    <div class="content-table-wrapper">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Staff Profile</th>
                    <th>Role & ID</th>
                    <th>Registration Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="pending-users-list">
                @if(is_countable($pendingUsers) ? count($pendingUsers) > 0 : !empty($pendingUsers))
@foreach($pendingUsers as $user)
                <tr class="approval-row" id="user-row-{{ $user->id }}">
                    <td data-label="Staff Profile">
                        <div class="profile-cell">
                            <div class="avatar-wrapper">
                                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=6366f1&color=fff&size=40' }}" alt="">
                                <span class="status-dot-pending"></span>
                            </div>
                            <div class="profile-info">
                                <span class="profile-name">{{ $user->full_name }}</span>
                                <span class="profile-email">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td data-label="Role & ID">
                        <div class="id-cell">
                            <span class="user-role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            <span class="employee-id">#{{ $user->employee_id ?? 'No ID' }}</span>
                        </div>
                    </td>
                    <td data-label="Registration Date">
                        <div class="date-cell">
                            <span class="date-full">{{ $user->created_at->format('M d, Y') }}</span>
                            <span class="date-ago">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    <td data-label="Actions" class="text-end">
                        <div class="actions-flex">
                            <button class="btn-action-approve" onclick="confirmApprove({{ $user->id }}, '{{ $user->full_name }}')" title="Approve Account">
                                <i class="fas fa-check"></i>
                                <span>Approve</span>
                            </button>
                            <button class="btn-action-reject" onclick="confirmReject({{ $user->id }}, '{{ $user->full_name }}')" title="Reject Account">
                                <i class="fas fa-times"></i>
                                <span>Reject</span>
                            </button>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-action-view" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
@else
                <tr>
                    <td colspan="4">
                        <div class="empty-state-illustrative">
                            <div class="illustrative-icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h3>Queue is Empty!</h3>
                            <p>All staff account requests have been processed. Great job!</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        
        {{-- Pagination --}}
        @if ($pendingUsers->hasPages())
        <div class="pagination-footer-premium">
            <div class="pagination-info">
                Showing {{ $pendingUsers->firstItem() }} to {{ $pendingUsers->lastItem() }} of {{ $pendingUsers->total() }} users
            </div>
            <div class="pagination-links">
                {{ $pendingUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODALS / EXTRA UI --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

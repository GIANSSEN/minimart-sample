<div class="col-12">
    <div class="card card-table-enhanced border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" role="table">
                <thead>
                    <tr>
                        <th width="40" class="ps-4">
                            <div class="form-check custom-check">
                                <input class="form-check-input" type="checkbox" id="selectAllDesktop">
                            </div>
                        </th>
                        <th>Member Details</th>
                        <th>User Role</th>
                        <th>Status</th>
                        <th>Access Log</th>
                        <th width="140" class="text-end pe-4">Manage</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @if(is_countable($users) ? count($users) > 0 : !empty($users))
@foreach($users as $user)
                    <tr class="user-row-{{ $user->id }}" role="row">
                        <td class="ps-4">
                            <div class="form-check custom-check">
                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                            </div>
                        </td>
                        <td data-label="Member">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-container">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="" class="avatar-modern">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="member-info">
                                    <div class="member-name">{{ $user->full_name }}</div>
                                    <div class="member-email"><i class="far fa-envelope me-1"></i> {{ $user->email }}</div>
                                    <div class="member-username"><i class="far fa-user me-1"></i> {{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Role">
                            <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-staff' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td data-label="Status">
                            @php
                                $statusClass = match($user->approval_status) {
                                    'approved' => 'status-success',
                                    'pending' => 'status-warning',
                                    'rejected' => 'status-danger',
                                    default => 'status-neutral'
                                };
                                $statusIcon = match($user->approval_status) {
                                    'approved' => 'fa-check-circle',
                                    'pending' => 'fa-clock',
                                    'rejected' => 'fa-times-circle',
                                    default => 'fa-question-circle'
                                };
                            @endphp
                            <div class="status-indicator {{ $statusClass }} approval-badge">
                                <i class="fas {{ $statusIcon }}"></i>
                                <span>{{ ucfirst($user->approval_status) }}</span>
                            </div>
                        </td>
                        <td data-label="Last Login">
                            @if ($user->last_login_at)
                                <div class="activity-info">
                                    <div class="time-relative">{{ $user->last_login_at->diffForHumans() }}</div>
                                    <div class="time-absolute">{{ $user->last_login_at->format('M d, H:i') }}</div>
                                </div>
                            @else
                                <span class="status-never">No activity logged</span>
                            @endif
                        </td>
                        <td class="text-end pe-4" data-label="Manage">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-view" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit" title="Edit Settings">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($user->approval_status === 'pending')
                                <button onclick="approveUser({{ $user->id }}, '{{ addslashes($user->full_name) }}')" 
                                        class="btn-process" title="Approve Access">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->full_name) }}')" 
                                        class="btn-del" title="Revoke Access">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="6">
                            <div class="modern-empty-state">
                                <div class="empty-icon-wrapper">
                                    <i class="fas fa-users-slash"></i>
                                </div>
                                <h4>No members found</h4>
                                <p>Refine your search parameters or register a new team member.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-create-modern">
                                    <i class="fas fa-plus"></i> Add New User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
        <div class="table-pagination-footer border-top px-4 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="pagination-info">
                    Showing <span>{{ $users->firstItem() }}</span> to <span>{{ $users->lastItem() }}</span> of <span>{{ $users->total() }}</span> members
                </div>
                <div class="pagination-modern">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Modern Table Styling */
.card-table-enhanced {
    background: #fff;
    border: 1px solid #edf2f7;
}

.table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.user-row-{{ $users[0]->id ?? 'dummy' }} { transition: all 0.2s ease; }
.table tbody tr:hover { background-color: #f8fafc; }

/* Member Info */
.avatar-placeholder {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    color: #475569;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}
.avatar-modern { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

.member-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; line-height: 1.2; margin-bottom: 0.2rem; }
.member-email, .member-username { color: #64748b; font-size: 0.8rem; display: flex; align-items: center; }

/* Role & Status Badges */
.role-badge {
    display: inline-flex;
    padding: 0.35rem 0.8rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
}
.role-admin { background: #eff6ff; color: #1d4ed8; }
.role-staff { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}
.status-success { color: #10b981; }
.status-warning { color: #f59e0b; }
.status-danger { color: #ef4444; }
.status-indicator i { font-size: 0.8rem; }

/* Activity Info */
.activity-info { line-height: 1.3; }
.time-relative { color: #334155; font-size: 0.85rem; font-weight: 600; }
.time-absolute { color: #94a3b8; font-size: 0.75rem; }
.status-never { color: #cbd5e1; font-size: 0.85rem; font-style: italic; }

/* Action Buttons Container */
.action-flex { display: flex; gap: 0.5rem; justify-content: flex-end; }

/* Empty State */
.modern-empty-state { padding: 4rem 1rem; text-align: center; }
.empty-icon-wrapper {
    width: 64px;
    height: 64px;
    background: #f8fafc;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: #cbd5e1;
    font-size: 1.5rem;
}
.modern-empty-state h4 { font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; }
.modern-empty-state p { color: #64748b; margin-bottom: 1.5rem; max-width: 300px; margin-left: auto; margin-right: auto; }
.btn-create-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: #fff;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
}

/* Pagination Footer */
.pagination-info { font-size: 0.85rem; color: #64748b; }
.pagination-info span { font-weight: 700; color: #334155; }
</style>

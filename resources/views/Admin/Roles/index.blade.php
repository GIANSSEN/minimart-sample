@extends('layouts.admin')

@push('styles')
<style>
/* Modern Minimalist Roles & Permissions */
.roles-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.page-header-premium {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.header-left { display: flex; align-items: center; gap: 1rem; }
.header-icon-box {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}

.page-title { font-size: 1.3rem; font-weight: 800; color: #1e293b; margin: 0; }
.page-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }

.btn-add-role {
    padding: 0.75rem 1.5rem;
    background: #3B82F6;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}
.btn-add-role:hover { background: #2563EB; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); color: #fff; }

/* Roles Table Card */
.content-card-enhanced {
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

/* Role Identity */
.role-identity { display: flex; align-items: center; gap: 1rem; }
.role-icon-box {
    width: 40px;
    height: 40px;
    background: #EFF6FF;
    color: #3B82F6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.role-name { display: block; font-weight: 700; color: #1E293B; font-size: 0.95rem; }
.role-slug { display: block; font-size: 0.75rem; color: #94A3B8; font-family: monospace; }

/* Badge Styles */
.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}
.badge-permissions { background: #ECFDF5; color: #059669; }
.badge-users { background: #F3F4F6; color: #4B5563; }

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 700;
}
.status-active { color: #10B981; }
.status-inactive { color: #94A3B8; }

/* Action Buttons */
.action-flex { display: flex; justify-content: flex-end; gap: 0.5rem; }
/* Action buttons use global styles in admin layout */

@media (max-width: 768px) {
    .page-header-premium { flex-direction: column; align-items: stretch; gap: 1rem; text-align: center; }
    .header-left { flex-direction: column; }
    .premium-table thead { display: none; }
    .premium-table tbody tr { display: block; margin-bottom: 1rem; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; }
    .premium-table td { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px dotted #F1F5F9; }
    .premium-table td::before { content: attr(data-label); font-weight: 800; color: #94A3B8; font-size: 0.7rem; text-transform: uppercase; }
}
</style>
@endpush

@section('content')
<div class="roles-page">
    {{-- Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box roles-header-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Roles & Permissions</h1>
                <p class="page-subtitle">Configure system access levels and staff responsibilities</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.roles.create') }}" class="btn-header-action btn-header-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Register New Role</span>
            </a>
        </div>
    </div>

    {{-- Content Card --}}
    <div class="content-card-enhanced">
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="ps-4">Role Designation</th>
                        <th>Brief Description</th>
                        <th>Capabilities</th>
                        <th>Member Count</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($roles) ? count($roles) > 0 : !empty($roles))
@foreach($roles as $role)
                    <tr>
                        <td class="ps-4" data-label="Role">
                            <div class="role-identity">
                                <div class="role-icon-box">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <span class="role-name">{{ $role->name }}</span>
                                    <span class="role-slug">{{ $role->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td data-label="Description">
                            <span class="text-muted small">{{ Str::limit($role->description, 60) ?: 'No description provided' }}</span>
                        </td>
                        <td data-label="Permissions">
                            <div class="stat-badge badge-permissions">
                                <i class="fas fa-key"></i>
                                <span>{{ $role->permissions->count() }} Set</span>
                            </div>
                        </td>
                        <td data-label="Users">
                            <div class="stat-badge badge-users">
                                <i class="fas fa-users"></i>
                                <span>{{ $role->users_count ?? 0 }} Staff</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            @if ($role->status == 'active')
                                <div class="status-badge status-active">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Active</span>
                                </div>
                            @else
                                <div class="status-badge status-inactive">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Inactive</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-end pe-4" data-label="Manage">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn-edit" title="Edit Permissions">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if (!in_array($role->slug, ['admin', 'supervisor', 'cashier']))
                                <button class="btn-del" onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" title="Remove Role">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="6">
                            <div class="empty-state-illustrative">
                                <div class="illustrative-icon" style="background: #F3F4F6; color: #94A3B8;">
                                    <i class="fas fa-shield-virus"></i>
                                </div>
                                <h3>No Roles Defined</h3>
                                <p>Start by creating a specialized role for your team members.</p>
                                <a href="{{ route('admin.roles.create') }}" class="btn-add-role mt-3" style="display:inline-flex;">
                                    <i class="fas fa-plus"></i> Add First Role
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

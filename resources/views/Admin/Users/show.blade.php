@extends('layouts.admin')

@section('title', 'User Details - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="row mx-0 mb-4">
        <div class="col-12 px-0">
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box user-header-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">User Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">{{ $user->full_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-header-action btn-header-primary" aria-label="Edit user">
                <i class="fas fa-edit"></i>
                <span class="d-none d-sm-inline">Edit User</span>
            </a>
            <button type="button" class="btn-header-action btn-header-danger" onclick="showDeleteModal()" aria-label="Delete user">
                <i class="fas fa-trash"></i>
                <span class="d-none d-sm-inline">Delete</span>
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to users">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>
        </div>
    </div>

    <!-- Status Badges -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="d-flex gap-2 flex-wrap">
                <!-- Account Status -->
                <div class="badge-container">
                    <span class="badge badge-lg" style="background-color: {{ getStatusColor($user->status) }};">
                        <i class="fas fa-circle me-2" style="font-size: 0.6em;"></i>{{ ucfirst($user->status) }}
                    </span>
                </div>

                <!-- Approval Status -->
                <div class="badge-container">
                    <span class="badge badge-lg" style="background-color: {{ getApprovalStatusColor($user->approval_status ?? 'approved') }};">
                        <i class="fas {{ getApprovalStatusIcon($user->approval_status ?? 'approved') }} me-2"></i>{{ ucfirst($user->approval_status ?? 'Approved') }}
                    </span>
                </div>

                <!-- Role Badge -->
                <div class="badge-container">
                    <span class="badge badge-lg" style="background-color: #667eea;">
                        <i class="fas fa-shield-alt me-2"></i>{{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-0 g-3 g-md-4 px-3 px-md-4">
        <!-- Main User Info Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <!-- Card Header with Icon -->
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <div class="d-flex align-items-center gap-4">
                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=667eea&color=fff&size=80' }}" 
                             class="rounded-circle border border-4" width="80" height="80" 
                             style="border-color: #f0f1f5 !important;" alt="{{ $user->full_name }}">
                        <div>
                            <h4 class="mb-1 fw-bold">{{ $user->full_name }}</h4>
                            <p class="text-muted mb-0"><i class="fas fa-envelope me-2"></i>{{ $user->email }}</p>
                            @if ($user->employee_id)
                                <p class="text-muted mb-0"><i class="fas fa-id-card me-2"></i>Employee ID: {{ $user->employee_id }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Details Section -->
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 pb-3 border-bottom">
                        <i class="fas fa-circle text-gradient-secondary me-2" style="font-size: 0.5em;"></i>Personal Information
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Full Name</label>
                                <p class="mb-0 fw-semibold">{{ $user->full_name }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Username</label>
                                <p class="mb-0 fw-semibold">{{ $user->username ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Email Address</label>
                                <p class="mb-0 fw-semibold">
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Employee ID</label>
                                <p class="mb-0 fw-semibold">{{ $user->employee_id ?? 'Not Assigned' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Phone Number</label>
                                <p class="mb-0 fw-semibold">
                                    {{ $user->phone ? '📞 ' . $user->phone : 'Not Provided' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Account Status</label>
                                <p class="mb-0">
                                    <span class="badge" style="background-color: {{ getStatusColor($user->status) }};">
                                        <i class="fas fa-circle me-1" style="font-size: 0.6em;"></i>{{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if ($user->address)
                        <div class="col-12">
                            <div class="detail-field">
                                <label class="text-muted small fw-semibold mb-1 d-block">Address</label>
                                <p class="mb-0 fw-semibold">{{ $user->address }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account History Card -->
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden mt-4">
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history text-gradient-secondary me-2"></i>Account Timeline
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-user-plus text-gradient-secondary"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-semibold mb-1">Account Created</h6>
                                <p class="text-muted small mb-0">
                                    {{ $user->created_at->format('M d, Y') }} at {{ $user->created_at->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        @if ($user->updated_at != $user->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-edit text-info"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-semibold mb-1">Last Updated</h6>
                                <p class="text-muted small mb-0">
                                    {{ $user->updated_at->format('M d, Y') }} at {{ $user->updated_at->format('h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if ($user->approval_status === 'approved' && $user->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-semibold mb-1">Account Approved</h6>
                                <p class="text-muted small mb-0">
                                    {{ $user->approved_at->format('M d, Y') }} at {{ $user->approved_at->format('h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Card -->
        <div class="col-lg-4">
            <!-- Quick Info Card -->
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden sticky-top" style="top: 1rem; z-index: 100;">
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-gradient-secondary me-2"></i>Quick Info
                    </h5>
                </div>

                <div class="card-body p-4">
                    <!-- Role Card -->
                    <div class="info-section mb-4 pb-4 border-bottom">
                        <p class="text-muted small fw-semibold mb-2">ROLE & PERMISSIONS</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="role-icon" style="background-color: #667eea15; border-left: 4px solid #667eea; padding: 12px 16px; border-radius: 8px; flex: 1;">
                                <p class="mb-0 fw-bold" style="color: #667eea;">{{ ucfirst($user->role) }}</p>
                                <small class="text-muted">Primary Role</small>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Info -->
                    <div class="info-section mb-4 pb-4 border-bottom">
                        <p class="text-muted small fw-semibold mb-2">APPROVAL STATUS</p>
                        <div style="background-color: {{ getApprovalStatusColor($user->approval_status ?? 'approved') }}20; border-left: 4px solid {{ getApprovalStatusColor($user->approval_status ?? 'approved') }}; padding: 12px 16px; border-radius: 8px;">
                            <p class="mb-0 fw-bold" style="color: {{ getApprovalStatusColor($user->approval_status ?? 'approved') }};">
                                {{ ucfirst($user->approval_status ?? 'Approved') }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="info-section mb-4 pb-4 border-bottom">
                        <p class="text-muted small fw-semibold mb-2">CONTACT</p>
                        <div class="d-flex flex-column gap-2">
                            <a href="mailto:{{ $user->email }}" class="contact-link text-decoration-none">
                                <i class="fas fa-envelope me-2" style="color: #667eea;"></i>{{ $user->email }}
                            </a>
                            @if ($user->phone)
                            <a href="tel:{{ $user->phone }}" class="contact-link text-decoration-none">
                                <i class="fas fa-phone me-2" style="color: #667eea;"></i>{{ $user->phone }}
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="info-section">
                        <p class="text-muted small fw-semibold mb-2">METADATA</p>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">User ID:</span>
                                <code class="text-dark">{{ $user->id }}</code>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Created:</span>
                                <span class="text-dark">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden mt-4">
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-lg rounded-3">
                            <i class="fas fa-edit me-2"></i>Edit User Details
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-lg rounded-3" onclick="showDeleteModal()">
                            <i class="fas fa-trash me-2"></i>Delete User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg rounded-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger bg-opacity-10 border-0 py-4">
                <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-3">Are you sure you want to delete <strong>{{ $user->full_name }}</strong>?</p>
                <p class="text-danger small mb-0">
                    <i class="fas fa-info-circle me-2"></i>This action cannot be undone. All associated data will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer border-0 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Status & Badge Colors */
    .badge-container {
        display: inline-block;
    }

    .badge-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Detail Field Styling */
    .detail-field {
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .detail-field:hover {
        background-color: #f0f1f5;
    }

    .detail-field label {
        letter-spacing: 0.5px;
        color: #6b7280 !important;
    }

    /* Contact Links */
    .contact-link {
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .contact-link:hover {
        background-color: #667eea15;
        color: #667eea !important;
    }

    /* Timeline Styling */
    .timeline {
        position: relative;
        padding: 1rem 0;
    }

    .timeline-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .timeline-content {
        flex: 1;
        padding-top: 0.5rem;
    }

    /* Info Section */
    .info-section {
        transition: all 0.3s ease;
    }

    /* Gradient Background */
    .bg-gradient-soft {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    }

    .text-gradient-secondary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Card Shadow */
    .shadow-soft {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid rgba(0, 0, 0, 0.04) !important;
    }

    .shadow-soft:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function showDeleteModal() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    function getStatusColor(status) {
        const colors = {
            'active': '#10b981',
            'inactive': '#ef4444',
            'pending': '#f59e0b'
        };
        return colors[status] || '#6b7280';
    }

    function getApprovalStatusColor(status) {
        const colors = {
            'approved': '#10b981',
            'pending': '#f59e0b',
            'rejected': '#ef4444'
        };
        return colors[status] || '#667eea';
    }

    function getApprovalStatusIcon(status) {
        const icons = {
            'approved': 'fa-check-circle',
            'pending': 'fa-clock',
            'rejected': 'fa-times-circle'
        };
        return icons[status] || 'fa-info-circle';
    }
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Activity Logs - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box activity-header-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Activity Logs</h1>
                <p class="page-subtitle">Track and monitor all system activities and user actions</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" onclick="refreshLogs()" title="Refresh logs">
                <i class="fas fa-sync-alt"></i>
                <span class="d-none d-sm-inline">Refresh</span>
            </button>
            <button class="btn-header-action btn-header-secondary" onclick="exportLogs()" title="Export logs">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
            <button type="button" class="btn-header-action btn-header-danger" onclick="confirmClearAll()" title="Clear all logs">
                <i class="fas fa-trash"></i>
                <span class="d-none d-sm-inline">Clear All</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards Enhanced -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4" id="statsContainer" role="region" aria-label="Activity statistics">
        <!-- Total Logs -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column" role="region" aria-label="Total logs">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-primary" title="Total logs">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Total Logs</span>
                        <span class="stat-value fw-bold h3" aria-label="Total logs: {{ number_format($totalLogs) }}">{{ number_format($totalLogs) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-history me-1"></i><span>{{ $logs->count() }} this page</span>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column" role="region" aria-label="Active users">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-success" title="Active users">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Active Users</span>
                        <span class="stat-value fw-bold h3" aria-label="Active users: {{ number_format($activeUsers) }}">{{ number_format($activeUsers) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-user-clock me-1"></i><span>Tracked users</span>
                </div>
            </div>
        </div>

        <!-- Today Logs -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column" role="region" aria-label="Today's logs">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-warning" title="Today's logs">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Today</span>
                        <span class="stat-value fw-bold h3" aria-label="Today's logs: {{ number_format($todayLogs) }}">{{ number_format($todayLogs) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-calendar-day me-1"></i><span>{{ now()->format('M d') }}</span>
                </div>
            </div>
        </div>

        <!-- This Week Logs -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column" role="region" aria-label="This week's logs">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-info" title="This week's logs">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">This Week</span>
                        <span class="stat-value fw-bold h3" aria-label="This week's logs: {{ number_format($weekLogs) }}">{{ number_format($weekLogs) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-calendar me-1"></i><span>Last 7 days</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card Enhanced -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden filter-card-enhanced">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center">
                            <i class="fas fa-sliders-h text-gradient-secondary me-2"></i>Filter Logs
                        </h5>
                        <button class="btn btn-sm btn-link text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="filterForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-secondary"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" 
                                               placeholder="Search by description, action, IP..." 
                                               value="{{ request('search') }}" id="searchInput" autocomplete="off" aria-label="Search activity logs">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <select name="user_id" class="form-select form-select-enhanced" id="userSelect" aria-label="Filter by user">
                                        <option value="">All Users</option>
                                        @foreach ($usersList as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-secondary"></i>
                                        </span>
                                        <input type="date" name="date_from" class="form-control border-start-0" 
                                               placeholder="From" value="{{ request('date_from') }}" id="dateFromInput" aria-label="Filter from date">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-secondary"></i>
                                        </span>
                                        <input type="date" name="date_to" class="form-control border-start-0" 
                                               placeholder="To" value="{{ request('date_to') }}" id="dateToInput" aria-label="Filter to date">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-gradient-secondary w-100" id="applyFilterBtn" aria-label="Apply filters">
                                        <i class="fas fa-filter me-2"></i><span class="d-none d-sm-inline">Apply</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Active Filters -->
                            @if (request()->anyFilled(['search', 'user_id', 'date_from', 'date_to']))
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark p-2">
                                        <i class="fas fa-sliders-h me-1"></i>Active Filters:
                                    </span>
                                    @if (request('search'))
                                        <span class="badge bg-primary">
                                            "{{ request('search') }}" 
                                            <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('search', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove search filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('user_id'))
                                        @php $filterUser = $usersList->firstWhere('id', request('user_id')); @endphp
                                        @if ($filterUser)
                                        <span class="badge bg-info">
                                            {{ $filterUser->full_name }}
                                            <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('user_id', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove user filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        @endif
                                    @endif
                                    @if (request('date_from'))
                                        <span class="badge bg-success">
                                            From {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                                            <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('date_from', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove from date filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('date_to'))
                                        <span class="badge bg-warning text-dark">
                                            To {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                            <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('date_to', 'page'))) }}" 
                                               class="text-dark ms-1" title="Remove to date filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div class="row mx-0 px-4" id="activityLogsTableContainer" role="region" aria-label="Activity logs table">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="fas fa-circle text-gradient-secondary me-2" style="font-size: 0.5em;"></i>
                            Activity Logs <span class="badge bg-secondary ms-2">{{ $logs->total() }} total</span>
                        </h5>
                        <div>
                            <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="perPageForm" class="d-inline">
                                @foreach (request()->except('per_page', 'page') as $key => $value)
                                    @if ($value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <select name="per_page" class="form-select form-select-sm form-select-enhanced" style="width: 130px;" onchange="validatePerPage(this)" aria-label="Logs per page">
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0" role="table">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Date & Time</th>
                                <th class="py-3">User</th>
                                <th class="py-3">Action</th>
                                <th class="py-3">Description</th>
                                <th class="py-3">IP Address</th>
                                <th class="text-end px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($logs) ? count($logs) > 0 : !empty($logs))
@foreach($logs as $log)
                            <tr class="log-row" role="row">
                                <td class="px-4" data-label="Date & Time">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-day opacity-50 me-2" style="color: #667eea;"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $log->created_at ? $log->created_at->format('M d, Y') : 'N/A' }}</div>
                                            <small class="text-muted">{{ $log->created_at ? $log->created_at->format('h:i:s A') : '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="User">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $log->user ? $log->user->avatar_url : 'https://ui-avatars.com/api/?name=System&background=6c757d&color=fff&size=32' }}" 
                                             class="rounded-circle me-2" width="32" height="32" alt="User avatar" loading="lazy">
                                        <div>
                                            <div class="fw-semibold">{{ $log->user ? $log->user->full_name : 'System' }}</div>
                                            @if ($log->user)
                                                <small class="text-muted">{{ $log->user->role }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Action">
                                    @php
                                        $action = $log->description;
                                        $color = 'primary';
                                        $iconClass = 'fa-circle';
                                        if (str_contains($action, 'create')) {
                                            $color = 'success';
                                            $iconClass = 'fa-plus-circle';
                                        } elseif (str_contains($action, 'update')) {
                                            $color = 'warning';
                                            $iconClass = 'fa-edit';
                                        } elseif (str_contains($action, 'delete')) {
                                            $color = 'danger';
                                            $iconClass = 'fa-trash';
                                        } elseif (str_contains($action, 'login')) {
                                            $color = 'info';
                                            $iconClass = 'fa-sign-in-alt';
                                        } elseif (str_contains($action, 'logout')) {
                                            $color = 'secondary';
                                            $iconClass = 'fa-sign-out-alt';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-2 rounded-pill" title="Action type: {{ $log->log_name ?? 'ACTION' }}">
                                        <i class="fas {{ $iconClass }} me-1"></i>
                                        {{ $log->log_name ?? str_replace('_', ' ', ucfirst(Str::before($action, ' '))) }}
                                    </span>
                                </td>
                                <td data-label="Description">
                                    <span class="log-description" title="{{ $log->description }}">
                                        {{ Str::limit($log->description, 60) }}
                                    </span>
                                </td>
                                <td data-label="IP Address">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill" title="IP Address: {{ $log->ip_address ?? 'N/A' }}">
                                        <i class="fas fa-network-wired me-1" style="color: #6c757d;"></i>
                                        {{ $log->ip_address ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end px-4" data-label="Actions">
                                    <div class="btn-group" role="group" aria-label="Log actions">
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="viewLogDetails('{{ $log->id }}')"
                                                title="View log details" aria-label="View details for log {{ $log->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteLog('{{ $log->id }}')"
                                                title="Delete log" aria-label="Delete log {{ $log->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
@else
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-history fa-4x text-muted opacity-25 mb-4"></i>
                                        <h5 class="text-muted mb-2">No Activity Logs Found</h5>
                                        <p class="text-muted mb-3">Activities will appear here as users interact with the system</p>
                                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-primary">
                                            <i class="fas fa-sync-alt me-2"></i>Refresh
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($logs->hasPages())
                <div class="px-4 py-4 border-top">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            <i class="fas fa-database me-1"></i>
                            Showing <strong>{{ $logs->firstItem() }}</strong> to <strong>{{ $logs->lastItem() }}</strong> of <strong>{{ $logs->total() }}</strong> logs
                        </div>
                        <div class="pagination-modern">
                            {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-hidden="true" aria-labelledby="logDetailsModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="logDetailsModalLabel">
                    <i class="fas fa-history me-2"></i>
                    Activity Log Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);
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
        background: var(--primary-gradient);
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

    .stat-icon.bg-primary { background: var(--primary-gradient); }
    .stat-icon.bg-success { background: var(--success-gradient); }
    .stat-icon.bg-warning { background: var(--warning-gradient); }
    .stat-icon.bg-info { background: var(--info-gradient); }

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
    .modern-table {
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .modern-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 2px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .modern-table tbody tr {
        transition: var(--transition-smooth);
        border-bottom: 1px solid #e5e7eb;
    }

    .modern-table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.03);
        box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.05);
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
        .modern-table thead {
            display: none;
        }

        .modern-table tbody tr {
            display: block;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            box-shadow: var(--shadow-soft);
            animation: fadeInUp 0.4s ease-out;
        }

        .modern-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem 0;
        }

        .modern-table tbody td:before {
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

        .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }

        .btn i {
            margin-right: 0;
        }

        .modern-table tbody td:before {
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
        .filter-card-enhanced {
            display: none;
        }

        .modern-table {
            box-shadow: none;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ==================== UTILITIES ====================
    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    // ==================== INITIALIZATION ====================
    $(document).ready(function() {
        $('#userSelect').select2({
            theme: 'default',
            placeholder: 'All Users',
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
        const rows = document.querySelectorAll('.log-row');
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

    // ==================== VALIDATION FUNCTIONS ====================
    function validateFilterForm() {
        const dateFromInput = document.getElementById('dateFromInput');
        const dateToInput = document.getElementById('dateToInput');

        if (dateFromInput.value && dateToInput.value) {
            const dateFrom = new Date(dateFromInput.value);
            const dateTo = new Date(dateToInput.value);
            
            if (dateFrom > dateTo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Date Range',
                    text: 'Start date must be before end date.',
                    confirmButtonColor: '#667eea'
                });
                return false;
            }
        }

        return true;
    }

    function validatePerPage(select) {
        const validValues = [15, 25, 50, 100];
        const value = parseInt(select.value);
        
        if (!validValues.includes(value)) {
            select.value = 15;
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Selection',
                text: 'Please select a valid number of logs per page.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        document.getElementById('perPageForm').submit();
        return true;
    }

    // ==================== BUTTON FUNCTIONS ====================
    function refreshLogs() {
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span class="d-none d-sm-inline">Refreshing...</span>';

        Swal.fire({
            title: 'Refreshing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
            window.location.reload();
        }, 800);
    }

    function exportLogs() {
        Swal.fire({
            title: 'Exporting...',
            text: 'Preparing your CSV file',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        window.location.href = '{{ route("admin.activity-logs.export", "csv") }}';
        setTimeout(() => Swal.close(), 1500);
    }

    function confirmClearAll() {
        Swal.fire({
            title: 'Clear All Logs?',
            html: '<p class="text-danger fw-bold">⚠️ This action cannot be undone.</p><p>All activity logs will be permanently deleted.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, clear all',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = event.target.closest('button');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span class="d-none d-sm-inline">Clearing...</span>';

                Swal.fire({
                    title: 'Clearing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route("admin.activity-logs.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cleared!',
                            text: 'All activity logs have been cleared.',
                            confirmButtonColor: '#667eea'
                        }).then(() => window.location.reload());
                    } else {
                        throw new Error('Failed to clear logs');
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-2"></i><span class="d-none d-sm-inline">Clear All</span>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to clear logs. Please try again.',
                        confirmButtonColor: '#667eea'
                    });
                });
            }
        });
    }

    // ==================== MODAL FUNCTIONS ====================
    function viewLogDetails(id) {
        if (!id || isNaN(id)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid log ID',
                confirmButtonColor: '#667eea'
            });
            return;
        }

        const modalEl = document.getElementById('logDetailsModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const container = document.getElementById('logDetailsContent');

        modal.show();
        container.innerHTML = `
            <div class="log-details-container">
                <div class="log-details-card">
                    <div class="details-header">
                        <div class="details-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="spinner-border text-white" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="details-title">Loading...</h3>
                            <p class="text-muted small mb-0">Fetching activity details</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        fetch(`/admin/activity-logs/${id}/details`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);
            const html = buildLogDetailsHtml(data);
            container.innerHTML = html;
            container.style.animation = 'fadeInUp 0.3s ease-in';
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-circle fa-4x text-danger mb-3"></i>
                    <h5 class="text-danger fw-bold">Error Loading Details</h5>
                    <p class="text-muted">Unable to fetch activity log details.</p>
                    <small class="text-muted d-block mt-2">${error.message}</small>
                </div>
            `;
        });
    }

    function buildLogDetailsHtml(log) {
        const userFullName = log.user ? log.user.full_name : 'System';
        const userRole = log.user ? log.user.role : '';
        const userEmail = log.user ? log.user.email : '';

        let color = 'primary';
        let icon = 'fa-circle';
        const action = log.description || '';
        
        if (action.toLowerCase().includes('create')) {
            color = 'success';
            icon = 'fa-plus-circle';
        } else if (action.toLowerCase().includes('update')) {
            color = 'warning';
            icon = 'fa-edit';
        } else if (action.toLowerCase().includes('delete')) {
            color = 'danger';
            icon = 'fa-trash';
        } else if (action.toLowerCase().includes('login')) {
            color = 'info';
            icon = 'fa-sign-in-alt';
        } else if (action.toLowerCase().includes('logout')) {
            color = 'secondary';
            icon = 'fa-sign-out-alt';
        }

        return `
            <div class="log-details-container">
                <div class="log-details-card">
                    <div class="details-header">
                        <div class="details-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div>
                            <h3 class="details-title">Activity #${log.id}</h3>
                            <p class="text-muted small mb-0">${log.description || 'No description'}</p>
                        </div>
                    </div>
                    <div class="meta-grid">
                        <div class="meta-item p-3 bg-light rounded">
                            <span class="meta-label d-block small text-muted">User</span>
                            <span class="meta-value fw-bold">${userFullName}</span>
                            ${userRole ? `<small class="text-muted d-block">${userRole}</small>` : ''}
                            ${userEmail ? `<small class="text-muted d-block">${userEmail}</small>` : ''}
                        </div>
                        <div class="meta-item p-3 bg-light rounded">
                            <span class="meta-label d-block small text-muted">Action Type</span>
                            <span class="badge bg-${color}">${log.log_name || 'ACTION'}</span>
                        </div>
                        <div class="meta-item p-3 bg-light rounded">
                            <span class="meta-label d-block small text-muted">Date & Time</span>
                            <span class="meta-value small">${log.created_at || 'N/A'}</span>
                        </div>
                        <div class="meta-item p-3 bg-light rounded">
                            <span class="meta-label d-block small text-muted">IP Address</span>
                            <span class="meta-value small">${log.ip_address || 'N/A'}</span>
                        </div>
                    </div>
                    ${log.user_agent ? `
                    <div class="properties-section mt-4">
                        <h6 class="fw-bold mb-2">User Agent</h6>
                        <small class="text-muted d-block text-break">${log.user_agent}</small>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    function deleteLog(id) {
        if (!id || isNaN(id)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid log ID',
                confirmButtonColor: '#667eea'
            });
            return;
        }

        Swal.fire({
            title: 'Delete Log?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/admin/activity-logs/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Log has been deleted successfully.',
                            confirmButtonColor: '#667eea',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            const row = document.querySelector(`tr:has(button[onclick*="${id}"])`);
                            if (row) {
                                row.style.animation = 'slideOut 0.3s ease-out';
                                setTimeout(() => window.location.reload(), 300);
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Could not delete log.');
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'Something went wrong.',
                        confirmButtonColor: '#667eea'
                    });
                });
            }
        });
    }

    // ==================== FORM VALIDATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                if (!validateFilterForm()) {
                    e.preventDefault();
                }
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
    });

    // Animate stats on load
    window.addEventListener('load', () => {
        const statCards = document.querySelectorAll('.stat-card-modern');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush

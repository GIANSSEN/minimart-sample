@extends('layouts.admin')

@section('title', 'Returns & Refunds')

@push('styles')
<style>
    /* ========== DESIGN SYSTEM ========== */
    :root {
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-secondary: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        --shadow-soft: 0 10px 30px -12px rgba(0, 0, 0, 0.15);
        --shadow-hover: 0 20px 40px -15px rgba(0, 0, 0, 0.25);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page Header */
    .page-header-premium {
        background: white;
        padding: 2rem 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eef2f7;
        margin-bottom: 2rem;
    }
    .header-left { display: flex; align-items: center; gap: 1.25rem; }
    .header-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .page-title { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
    .page-subtitle { color: #64748b; font-size: 0.95rem; margin-bottom: 0; }

    /* Stat Cards */
    .stat-card-modern {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(0,0,0,0.02);
        transition: var(--transition-smooth);
    }
    .stat-card-modern:hover { transform: translateY(-5px); box-shadow: var(--shadow-hover); }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    .stat-icon.bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.bg-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .stat-value { font-size: 1.5rem; color: #1e293b; }

    /* Filter & Table */
    .input-group-enhanced { border-radius: 12px; overflow: hidden; }
    .btn-gradient-secondary {
        background: var(--gradient-secondary);
        border: none;
        color: white;
        border-radius: 12px;
        transition: var(--transition-smooth);
    }
    .table-modern thead th {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 2px solid #f1f5f9;
    }
    .table-modern tbody td { padding: 1.1rem 1.25rem; border-bottom: 1px solid #f1f5f9; }

    /* Animations */
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 animate-fade-in-up">
    @if ($errors->any())
    <div class="px-4 mb-4">
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Validation error:</strong> {{ $errors->first() }}
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box">
                <i class="fas fa-undo"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Returns & Refunds</h1>
                <p class="page-subtitle">Track return requests, manage refunds, and monitor product quality</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-header-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span class="d-none d-sm-inline">Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mx-0 g-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-md-4">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Pending</span>
                        <h3 class="stat-value fw-bold mb-0 text-warning">{{ number_format($pendingCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Processed</span>
                        <h3 class="stat-value fw-bold mb-0 text-success">{{ number_format($processedCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger text-white">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Cancelled</span>
                        <h3 class="stat-value fw-bold mb-0 text-danger">{{ number_format($cancelledCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-0 g-4 mb-4 px-3 px-md-4">
        <!-- Distribution Chart -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Returns Status
                        </h5>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 small fw-bold">Live Stats</span>
                    </div>
                    <div class="position-relative">
                        <canvas id="returnsStatusChart" height="280"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -20%); text-align: center;">
                            <h2 class="fw-extrabold mb-0">{{ number_format($pendingCount + $processedCount + $cancelledCount) }}</h2>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-sliders-h me-2 text-primary"></i>Filter Returns
                        </h5>
                        <a href="{{ route('supervisor.returns.index') }}" class="btn btn-sm btn-link text-secondary text-decoration-none">
                            <i class="fas fa-rotate-left me-1"></i>Reset Filters
                        </a>
                    </div>
                    <form method="GET" action="{{ route('supervisor.returns.index') }}">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="small fw-bold text-muted mb-1">Search Receipt No</label>
                                <div class="input-group input-group-enhanced">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Search receipt no..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">From Date</label>
                                <input type="date" name="date_from" class="form-control border-0 bg-light" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">To Date</label>
                                <input type="date" name="date_to" class="form-control border-0 bg-light" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">Status</label>
                                <select name="status" class="form-select border-0 bg-light rounded-3">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gradient-secondary w-100 py-2 fw-bold">
                                    <i class="fas fa-filter me-2"></i>Apply Analytics Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden mx-3 mx-md-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-undo me-2 text-primary"></i>Return Records</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary rounded-pill px-3">{{ $returns->total() }} total</span>
                <button class="btn btn-sm btn-outline-secondary" title="Export CSV"><i class="fas fa-file-csv me-1"></i>Export</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#ID</th>
                        <th>Receipt No</th>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th>Refund Amount</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($returns) ? count($returns) > 0 : !empty($returns))
@foreach($returns as $return)
                    <tr>
                        <td class="ps-4 text-muted fw-bold">#{{ $return->id }}</td>
                        <td>
                            <a href="{{ route('supervisor.transactions.show', $return->sale_id) }}" class="badge bg-light text-primary border p-2 fw-bold" style="text-decoration:none; font-family: monospace;">
                                {{ $return->sale->receipt_no ?? 'N/A' }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $return->product->product_name ?? 'N/A' }}</div>
                        </td>
                        <td class="text-center"><span class="badge bg-light text-secondary border fw-bold" style="font-size: 0.7rem;">{{ $return->quantity }} units</span></td>
                        <td><span class="fw-bold text-primary">₱{{ number_format($return->refund_amount, 2) }}</span></td>
                        <td>
                            <span class="text-truncate d-inline-block text-muted small" style="max-width:150px;" title="{{ $return->reason }}">
                                <i class="fas fa-info-circle me-1 opacity-50"></i>{{ $return->reason }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark small">{{ optional($return->created_at)->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match($return->status) {
                                    'pending' => 'warning',
                                    'processed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                $statusIcon = match($return->status) {
                                    'pending' => 'fa-clock',
                                    'processed' => 'fa-check',
                                    'cancelled' => 'fa-times',
                                    default => 'fa-info-circle'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} py-1 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                <i class="fas {{ $statusIcon }} me-1"></i> {{ ucfirst($return->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('supervisor.returns.show', $return) }}" class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($return->status === 'pending')
                                    <button class="btn-process" onclick="processReturn({{ $return->id }})" title="Process">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-cancel-r" onclick="cancelReturn({{ $return->id }})" title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <!-- Hidden forms remain the same -->
                                    <form id="processForm{{ $return->id }}" method="POST" action="{{ route('supervisor.returns.process', $return) }}" style="display:none;">
                                        @csrf
                                    </form>
                                    <form id="cancelForm{{ $return->id }}" method="POST" action="{{ route('supervisor.returns.cancel', $return) }}" style="display:none;">
                                        @csrf
                                        <input type="hidden" name="reason" id="cancelReason{{ $return->id }}">
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="9">
                            <div class="empty-state py-5 text-center">
                                <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width:80px;height:80px;font-size:2.5rem;">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <h5 class="fw-bold">No returns found</h5>
                                <p class="text-muted small">Returns from POS will appear here.</p>
                                <a href="{{ route('supervisor.returns.index') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Clear Review Filters</a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($returns->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <small class="text-muted fw-bold">Showing <span class="text-primary">{{ $returns->firstItem() }}</span> to <span class="text-primary">{{ $returns->lastItem() }}</span> of {{ $returns->total() }} entries</small>
            <div class="pagination-modern">
                {{ $returns->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const rrCtx = document.getElementById('returnsStatusChart');
const rrWrap = document.getElementById('returnsStatusChartWrap');
const rrData = [{{ $pendingCount }}, {{ $processedCount }}, {{ $cancelledCount }}];
const rrTotal = rrData.reduce((a, b) => a + b, 0);
if (rrCtx) {
    if (rrTotal === 0 && rrWrap) {
        rrWrap.innerHTML = '<div class="text-center text-muted py-5 glass-card shadow-inner">No return data yet.</div>';
    } else {
        new Chart(rrCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processed', 'Cancelled'],
                datasets: [{
                    data: rrData,
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                    hoverOffset: 15,
                    borderWidth: 0,
                    borderRadius: 10,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 25,
                            font: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                            color: '#64748b'
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 12
                    }
                },
                cutout: '80%'
            }
        });
    }
}

function processReturn(id) {
    Swal.fire({
        title: 'Process Return?',
        text: 'This will mark the return as processed and confirm the refund.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-check me-1"></i>Yes, Process',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('processForm' + id).submit();
        }
    });
}

function cancelReturn(id) {
    Swal.fire({
        title: 'Cancel Return Request?',
        html: `
            <p class="text-muted mb-3">Please provide a reason for cancellation.</p>
            <textarea id="cancelReasonInput" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-times me-1"></i>Confirm Cancel',
        cancelButtonText: 'Go Back',
        preConfirm: () => {
            const reason = document.getElementById('cancelReasonInput').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Reason is required.');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancelReason' + id).value = result.value;
            document.getElementById('cancelForm' + id).submit();
        }
    });
}
</script>
@endpush

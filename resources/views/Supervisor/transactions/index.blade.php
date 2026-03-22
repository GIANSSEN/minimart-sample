@extends('layouts.admin')

@section('title', 'Transactions')

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
    }
    .page-title { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
    .page-subtitle { color: #64748b; font-size: 0.95rem; margin-bottom: 0; }

    .btn-header-action {
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition-smooth);
    }
    .btn-header-secondary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
    }
    .btn-header-secondary:hover { background: #f1f5f9; transform: translateY(-2px); }

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
        flex-shrink: 0;
    }
    .stat-icon.bg-primary { background: var(--gradient-primary); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.bg-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .stat-value { font-size: 1.5rem; color: #1e293b; }

    /* Filter Enhancements */
    .input-group-enhanced { border-radius: 12px; overflow: hidden; }
    .form-select-enhanced { border-radius: 12px !important; }
    .btn-gradient-secondary {
        background: var(--gradient-secondary);
        border: none;
        color: white;
        border-radius: 12px;
        transition: var(--transition-smooth);
    }
    .btn-gradient-secondary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

    /* Table */
    .table-modern thead th {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 2px solid #f1f5f9;
    }
    .table-modern tbody td { padding: 1.1rem 1.25rem; border-bottom: 1px solid #f1f5f9; }
    .table-modern tbody tr:hover { background-color: #fcfdfe; }

    /* Pagination */
    .pagination-modern .pagination { gap: 6px; margin-bottom: 0; }
    .pagination-modern .page-link {
        border-radius: 10px !important;
        border: 1px solid #eef2f7;
        padding: 0.5rem 0.9rem;
        font-weight: 600;
        color: #64748b;
    }
    .pagination-modern .page-item.active .page-link {
        background: var(--gradient-primary);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

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
            <div class="header-icon-box" style="background: linear-gradient(135deg, #667eea, #764ba2); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Transaction Management</h1>
                <p class="page-subtitle">Monitor sales, track payments, and analyze transaction status</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header-action btn-header-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                <span class="d-none d-sm-inline">Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mx-0 g-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Total</span>
                        <h3 class="stat-value fw-bold mb-0">{{ number_format($totalCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Completed</span>
                        <h3 class="stat-value fw-bold mb-0">{{ number_format($completedCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Voided</span>
                        <h3 class="stat-value fw-bold mb-0 text-danger">{{ number_format($voidedCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Refunded</span>
                        <h3 class="stat-value fw-bold mb-0 text-warning">{{ number_format($refundedCount) }}</h3>
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
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Status Distribution
                        </h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-bold">Live Data</span>
                    </div>
                    <div class="position-relative">
                        <canvas id="transactionsStatusChart" height="280"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -20%); text-align: center;">
                            <h2 class="fw-extrabold mb-0">{{ number_format($totalCount) }}</h2>
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
                            <i class="fas fa-sliders-h me-2 text-primary"></i>Filter Transactions
                        </h5>
                        <a href="{{ route('supervisor.transactions.index') }}" class="btn btn-sm btn-link text-secondary text-decoration-none">
                            <i class="fas fa-rotate-left me-1"></i>Reset Filters
                        </a>
                    </div>
                    <form method="GET" action="{{ route('supervisor.transactions.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Search Receipt</label>
                                <div class="input-group input-group-enhanced">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-0 bg-light" placeholder="TXN-XXXXXX" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Cashier</label>
                                <select name="cashier_id" class="form-select form-select-enhanced border-0 bg-light">
                                    <option value="">All Cashiers</option>
                                    @foreach ($cashiers as $cashier)
                                        <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>{{ $cashier->full_name }}</option>
                                    @endforeach
                                </select>
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
                                <select name="status" class="form-select form-select-enhanced border-0 bg-light">
                                    <option value="">All Statuses</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
                                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
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
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden mx-3 mx-md-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-list-ul me-2 text-primary"></i>Transaction Records</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary rounded-pill px-3">{{ $transactions->total() }} total</span>
                <button class="btn btn-sm btn-outline-secondary" title="Export CSV"><i class="fas fa-file-csv me-1"></i>Export</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Receipt No</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $txn)
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-dark border p-2 fw-bold" style="font-family: monospace;">{{ $txn->receipt_no }}</span></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark small">{{ optional($txn->created_at)->format('M d, Y') }}</span>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ optional($txn->created_at)->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold small" style="width: 28px; height: 28px;">
                                    {{ substr($txn->cashier->full_name ?? 'N', 0, 1) }}
                                </div>
                                <span class="fw-medium small">{{ $txn->cashier->full_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($txn->customer_name)
                                <span class="fw-medium small">{{ $txn->customer_name }}</span>
                                @if ($txn->customer_type && $txn->customer_type !== 'regular')
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold" style="font-size: 0.65rem;">{{ strtoupper($txn->customer_type) }}</span>
                                @endif
                            @else
                                <span class="text-muted-400 italic small">Walk-in</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-secondary border fw-bold" style="font-size: 0.7rem;">{{ $txn->items->count() }} items</span></td>
                        <td>
                            <span class="badge {{ $txn->payment_method === 'cash' ? 'bg-success' : 'bg-info' }} bg-opacity-10 {{ $txn->payment_method === 'cash' ? 'text-success' : 'text-info' }} fw-bold" style="font-size: 0.7rem;">
                                <i class="fas {{ $txn->payment_method === 'cash' ? 'fa-money-bill-wave' : 'fa-credit-card' }} me-1"></i>
                                {{ strtoupper($txn->payment_method ?? 'N/A') }}
                            </span>
                        </td>
                        <td><span class="fw-bold text-primary">₱{{ number_format($txn->total_amount, 2) }}</span></td>
                        <td>
                            @php
                                $statusClass = match($txn->status) {
                                    'completed' => 'success',
                                    'voided' => 'danger',
                                    'refunded' => 'warning',
                                    default => 'secondary'
                                };
                                $statusIcon = match($txn->status) {
                                    'completed' => 'fa-check-circle',
                                    'voided' => 'fa-ban',
                                    'refunded' => 'fa-undo',
                                    default => 'fa-info-circle'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} py-1 px-3 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                <i class="fas {{ $statusIcon }} me-1"></i> {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('supervisor.transactions.show', $txn) }}" class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="9">
                            <div class="empty-state py-5 text-center">
                                <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width:80px;height:80px;font-size:2.5rem;">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h5 class="fw-bold">No transactions found</h5>
                                <p class="text-muted small">We couldn't find any records matching your filters.</p>
                                <a href="{{ route('supervisor.transactions.index') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Clear All Filters</a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <small class="text-muted fw-bold">Showing <span class="text-primary">{{ $transactions->firstItem() }}</span> to <span class="text-primary">{{ $transactions->lastItem() }}</span> of {{ $transactions->total() }} entries</small>
            <div class="pagination-modern">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const txCtx = document.getElementById('transactionsStatusChart');
const txWrap = document.getElementById('transactionsStatusChartWrap');
const txData = [{{ $completedCount }}, {{ $voidedCount }}, {{ $refundedCount }}];
const txTotal = txData.reduce((a, b) => a + b, 0);
if (txCtx) {
    if (txTotal === 0 && txWrap) {
        txWrap.innerHTML = '<div class="text-center text-muted py-5 elite-glass shadow-inner rounded-4">No transaction status data yet.</div>';
    } else {
        new Chart(txCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Voided', 'Refunded'],
                datasets: [{
                    data: txData,
                    backgroundColor: [
                        '#10b981', // Success
                        '#ef4444', // Danger
                        '#f59e0b'  // Warning
                    ],
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
                            pointStyle: 'circle',
                            padding: 25,
                            font: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                            color: '#64748b'
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 12,
                        bodyFont: { size: 13, weight: '500' },
                        displayColors: true
                    }
                },
                cutout: '80%'
            }
        });
    }
}
</script>
@endpush

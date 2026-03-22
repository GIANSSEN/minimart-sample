@extends('layouts.admin')

@section('title', 'Sales Report')

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
    .stat-icon.bg-primary { background: var(--gradient-primary); }
    .stat-icon.bg-success { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.bg-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-value { font-size: 1.4rem; color: #1e293b; font-weight: 800; }

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
    .empty-chart { min-height: 220px; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:.9rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 animate-fade-in-up">
    @php $reportsRoutePrefix = request()->routeIs('admin.*') ? 'admin.reports' : 'supervisor.reports'; @endphp

    @if ($errors->any())
    <div class="px-4 mb-3">
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Validation error:</strong> {{ $errors->first() }}
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Sales Analytics</h1>
                <p class="page-subtitle">Range: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route($reportsRoutePrefix . '.sales') }}" class="btn btn-header-secondary">
                <i class="fas fa-rotate-left"></i>
                <span class="d-none d-sm-inline">Reset Filters</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mx-0 g-3 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route($reportsRoutePrefix . '.sales') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="small fw-bold text-muted mb-1">Date From</label>
                                <input type="date" name="date_from" class="form-control border-0 bg-light rounded-3" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-5">
                                <label class="small fw-bold text-muted mb-1">Date To</label>
                                <input type="date" name="date_to" class="form-control border-0 bg-light rounded-3" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-gradient-secondary w-100 py-2 fw-bold">
                                    <i class="fas fa-filter me-1"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mx-0 g-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Revenue</span>
                        <h3 class="stat-value mb-0">₱{{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Transactions</span>
                        <h3 class="stat-value mb-0">{{ number_format($totalTransactions) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Avg Sale</span>
                        <h3 class="stat-value mb-0">₱{{ number_format($avgSaleAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-purple text-white">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Top Cashier</span>
                        <h3 class="stat-value mb-0" style="font-size: 1.1rem;">{{ $topCashier?->cashier?->full_name ?? 'N/A' }}</h3>
                        @if ($topCashier)
                            <small class="text-muted fw-bold">₱{{ number_format($topCashier->total, 2) }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mx-0 g-4 mb-4 px-3 px-md-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Daily Revenue Trend
                    </h6>
                    <div id="salesTrendChartWrap" class="position-relative">
                        <canvas id="salesTrendChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Payment Methods
                    </h6>
                    <div class="mx-auto" id="paymentChartWrap" style="max-height: 280px;">
                        <canvas id="paymentChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Table -->
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden mx-3 mx-md-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Top 5 Products by Revenue</h5>
            <span class="badge bg-light text-secondary rounded-pill px-3 py-1 small fw-bold">Analytics</span>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Rank</th>
                        <th>Product</th>
                        <th class="text-center">Qty Sold</th>
                        <th class="text-end pe-4">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($topProducts) ? count($topProducts) > 0 : !empty($topProducts))
@foreach($topProducts as $i => $prod)
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-dark fw-bold rounded-circle" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;">{{ $i + 1 }}</span></td>
                        <td class="fw-bold">{{ $prod->product_name }}</td>
                        <td class="text-center"><span class="badge bg-light text-secondary border fw-bold">{{ number_format($prod->total_qty) }} units</span></td>
                        <td class="text-end pe-4 fw-bold text-primary">₱{{ number_format($prod->total_revenue, 2) }}</td>
                    </tr>
                    @endforeach
@else
                    <tr><td colspan="4" class="text-center text-muted py-5"><i class="fas fa-receipt mb-2 d-block opacity-25" style="font-size: 2rem;"></i>No sales data for this period.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const trendLabels = @json($salesTrend->pluck('date'));
const trendData = @json($salesTrend->pluck('total'));
const trendWrap = document.getElementById('salesTrendChartWrap');
const trendCanvas = document.getElementById('salesTrendChart');
if (trendCanvas) {
    const trendTotal = trendData.reduce((a, b) => a + b, 0);
    if (!trendLabels.length || trendTotal === 0) {
        trendWrap.innerHTML = '<div class="empty-chart glass-card w-100 h-100">No sales trend data for selected period.</div>';
    } else {
        const ctx = trendCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Revenue',
                    data: trendData,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#6366f1',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { size: 13, weight: '700' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: (context) => ' ₱ ' + context.parsed.y.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' },
                        ticks: { 
                            callback: v => '₱' + v.toLocaleString(),
                            font: { size: 11, weight: '500' },
                            color: '#64748b'
                        } 
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: '#64748b' }
                    }
                }
            }
        });
    }
}

const pmLabels = @json($paymentBreakdown->pluck('payment_method')->map(fn($m) => strtoupper($m ?? 'N/A')));
const pmData = @json($paymentBreakdown->pluck('count'));
const paymentWrap = document.getElementById('paymentChartWrap');
const paymentCanvas = document.getElementById('paymentChart');
if (paymentCanvas) {
    const pmTotal = pmData.reduce((a, b) => a + b, 0);
    if (!pmLabels.length || pmTotal === 0) {
        paymentWrap.innerHTML = '<div class="empty-chart glass-card w-100 h-100">No payment data.</div>';
    } else {
        new Chart(paymentCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: pmLabels,
                datasets: [{
                    data: pmData,
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    hoverOffset: 15,
                    borderWidth: 0,
                    borderRadius: 8,
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
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 11, weight: '600' },
                            color: '#64748b'
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 12
                    }
                },
                cutout: '75%'
            }
        });
    }
}
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Profit & Loss')

@push('styles')
<style>
    /* ========== DESIGN SYSTEM ========== */
    :root {
        --gradient-primary: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --gradient-purple: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
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
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
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
    .stat-icon.bg-success { background: var(--gradient-success); }
    .stat-icon.bg-warning { background: var(--gradient-warning); }
    .stat-icon.bg-purple { background: var(--gradient-purple); }
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
    .card-footer-elite { background: rgba(0,0,0,0.02); border-top: 1px solid #eef2f7; }
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
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Profit & Loss</h1>
                <p class="page-subtitle">Analyze revenue streams, cost of goods, and net profit margins</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route($reportsRoutePrefix . '.profit-loss') }}" class="btn btn-header-secondary">
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
                    <form method="GET" action="{{ route($reportsRoutePrefix . '.profit-loss') }}">
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
                        <span class="text-uppercase text-muted small fw-bold d-block">Total Revenue</span>
                        <h3 class="stat-value mb-0">₱{{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-dolly"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Total COGS</span>
                        <h3 class="stat-value mb-0">₱{{ number_format($totalCOGS, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Gross Profit</span>
                        <h3 class="stat-value mb-0">₱{{ number_format($grossProfit, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-purple text-white">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Net Margin</span>
                        <h3 class="stat-value mb-0">{{ number_format($netMargin, 2) }}%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Chart -->
    <div class="row mx-0 g-4 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fas fa-chart-column me-2 text-primary"></i>Revenue vs COGS (Comparative Analysis)
                    </h6>
                    <div id="plChartWrap" class="position-relative">
                        <canvas id="plChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden mx-3 mx-md-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-calendar me-2 text-primary"></i>Monthly Performance Breakdown</h5>
            <span class="badge bg-light text-secondary rounded-pill px-3 py-1 small fw-bold">Timeline</span>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Month</th>
                        <th class="text-end">Revenue</th>
                        <th class="text-end">COGS</th>
                        <th class="text-end">Gross Profit</th>
                        <th class="text-end pe-4">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($monthlyData) ? count($monthlyData) > 0 : !empty($monthlyData))
@foreach($monthlyData as $row)
                    @php
                        $cogs = $monthlyCOGS[$row->month] ?? 0;
                        $profit = $row->revenue - $cogs;
                        $margin = $row->revenue > 0 ? ($profit / $row->revenue) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('F Y') }}</td>
                        <td class="text-end text-primary fw-bold">₱{{ number_format($row->revenue, 2) }}</td>
                        <td class="text-end text-warning fw-bold">₱{{ number_format($cogs, 2) }}</td>
                        <td class="text-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                            {{ $profit >= 0 ? '+' : '-' }}₱{{ number_format(abs($profit), 2) }}
                        </td>
                        <td class="text-end pe-4 fw-bold text-dark">{{ number_format($margin, 2) }}%</td>
                    </tr>
                    @endforeach
@else
                    <tr><td colspan="5" class="text-center text-muted py-5"><i class="fas fa-calendar mb-2 d-block opacity-25" style="font-size: 2.5rem;"></i>No performance data found.</td></tr>
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
const labels = @json($monthlyData->pluck('month')->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y')));
const revenue = @json($monthlyData->pluck('revenue'));
const cogs = @json($monthlyData->map(fn($r) => $monthlyCOGS[$r->month] ?? 0));
const plWrap = document.getElementById('plChartWrap');
const plCanvas = document.getElementById('plChart');
if (plCanvas) {
    const totalRev = revenue.reduce((a, b) => a + b, 0);
    const totalCogs = cogs.reduce((a, b) => a + b, 0);
    if (!labels.length || (totalRev === 0 && totalCogs === 0)) {
        plWrap.innerHTML = '<div class="empty-chart glass-card w-100 h-100 align-items-center d-flex justify-content-center">No comparative data available.</div>';
    } else {
        new Chart(plCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { 
                        label: 'Revenue', 
                        data: revenue, 
                        backgroundColor: '#6366f1', 
                        borderRadius: 8,
                        categoryPercentage: 0.6,
                        barPercentage: 0.8
                    },
                    { 
                        label: 'COGS', 
                        data: cogs, 
                        backgroundColor: '#f59e0b', 
                        borderRadius: 8,
                        categoryPercentage: 0.6,
                        barPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { size: 13, weight: '700' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: (context) => ' ' + context.dataset.label + ': ₱ ' + context.parsed.y.toLocaleString()
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
</script>
@endpush

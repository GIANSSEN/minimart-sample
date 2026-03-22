@extends('layouts.admin')

@section('title', 'Inventory Report')

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
    .stat-icon.bg-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .stat-value { font-size: 1.5rem; color: #1e293b; font-weight: 800; }

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
    .search-input-group { position: relative; }
    .search-input-group .si { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-input-group input { padding-left: 35px !important; }
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
                <i class="fas fa-boxes"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Inventory Report</h1>
                <p class="page-subtitle">Monitor stock levels, track valuations, and manage reorder alerts</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route($reportsRoutePrefix . '.inventory') }}" class="btn btn-header-secondary">
                <i class="fas fa-rotate-left"></i>
                <span class="d-none d-sm-inline">Reset Filters</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mx-0 g-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Total Products</span>
                        <h3 class="stat-value mb-0 text-primary">{{ number_format($totalProducts) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">In Stock</span>
                        <h3 class="stat-value mb-0 text-success">{{ number_format($inStockCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Low Stock</span>
                        <h3 class="stat-value mb-0 text-warning">{{ number_format($lowStockCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger text-white">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="text-uppercase text-muted small fw-bold d-block">Out Of Stock</span>
                        <h3 class="stat-value mb-0 text-danger">{{ number_format($outOfStock) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Value -->
    <div class="row mx-0 g-4 mb-4 px-3 px-md-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Stock Distribution
                    </h6>
                    <div class="mx-auto" id="inventoryStatusChartWrap" style="max-height: 250px;">
                        <canvas id="inventoryStatusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-filter me-2 text-primary"></i>Refine View
                        </h6>
                        <span class="badge bg-light text-secondary border fw-bold">Valuation: ₱{{ number_format($totalValue, 2) }}</span>
                    </div>
                    <form method="GET" action="{{ route($reportsRoutePrefix . '.inventory') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Search Product</label>
                                <div class="search-input-group">
                                    <i class="fas fa-search si"></i>
                                    <input type="text" name="search" class="form-control border-0 bg-light rounded-3" placeholder="Name or code..." value="{{ $search }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Category</label>
                                <select name="category_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Stock Status</label>
                                <select name="stock_status" class="form-select border-0 bg-light rounded-3">
                                    <option value="">All Status</option>
                                    <option value="in_stock" {{ $stockStatus == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="low_stock" {{ $stockStatus == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out_of_stock" {{ $stockStatus == 'out_of_stock' ? 'selected' : '' }}>Out Of Stock</option>
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

    <!-- Product Table -->
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden mx-3 mx-md-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-boxes me-2 text-primary"></i>Detailed Inventory</h5>
            <span class="badge bg-light text-secondary rounded-pill px-3 py-1 small fw-bold">{{ count($products) }} items</span>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Category</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Min Level</th>
                        <th class="text-end">Unit Cost</th>
                        <th class="text-end">Total Value</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($products) ? count($products) > 0 : !empty($products))
@foreach($products as $product)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark small">{{ $product->product_name }}</span>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $product->product_code }}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-bold" style="font-size: 0.7rem;">{{ $product->category->name ?? 'Uncategorized' }}</span></td>
                        <td class="text-center fw-bold {{ $product->current_stock <= 0 ? 'text-danger' : ($product->current_stock <= $product->reorder_level ? 'text-warning' : 'text-success') }}">
                            {{ number_format($product->current_stock) }}
                        </td>
                        <td class="text-center text-muted small">{{ $product->reorder_level }}</td>
                        <td class="text-end small text-muted">₱{{ number_format($product->cost_price, 2) }}</td>
                        <td class="text-end fw-bold text-primary">₱{{ number_format($product->current_stock * $product->cost_price, 2) }}</td>
                        <td class="pe-4">
                            @php
                                $statusClass = match($product->inventory_status) {
                                    'in_stock' => 'success',
                                    'low_stock' => 'warning',
                                    'out_of_stock' => 'danger',
                                    default => 'secondary'
                                };
                                $statusIcon = match($product->inventory_status) {
                                    'in_stock' => 'fa-check-circle',
                                    'low_stock' => 'fa-exclamation-triangle',
                                    'out_of_stock' => 'fa-times-circle',
                                    default => 'fa-info-circle'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} py-1 px-3 rounded-pill fw-bold" style="font-size: 0.65rem;">
                                <i class="fas {{ $statusIcon }} me-1"></i> {{ ucwords(str_replace('_', ' ', $product->inventory_status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr><td colspan="7" class="text-center text-muted py-5"><i class="fas fa-boxes mb-2 d-block opacity-25" style="font-size: 2rem;"></i>No products found.</td></tr>
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
const invData = [{{ $inStockCount }}, {{ $lowStockCount }}, {{ $outOfStock }}];
const invTotal = invData.reduce((a, b) => a + b, 0);
const invWrap = document.getElementById('inventoryStatusChartWrap');
const invCanvas = document.getElementById('inventoryStatusChart');
if (invCanvas) {
    if (invTotal === 0) {
        invWrap.innerHTML = '<div class="empty-chart glass-card h-100 align-items-center d-flex justify-content-center">No inventory data available.</div>';
    } else {
        new Chart(invCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out Of Stock'],
                datasets: [{
                    data: invData,
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
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
                cutout: '82%'
            }
        });
    }
}
</script>
@endpush

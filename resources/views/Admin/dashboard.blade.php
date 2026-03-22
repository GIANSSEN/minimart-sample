{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard - CJ\'s Minimart')

@section('content')
<div class="dashboard-wrapper">
    
    {{-- Header Section - Modern Container UI --}}
    <div class="dashboard-header-container">
        <div class="header-content-wrapper">
            <div class="header-left-section">
                <div class="welcome-greeting">
                    <h1 class="welcome-text">Welcome back, {{ Auth::user()->full_name ?? 'Admin' }}! 👋</h1>
                    <p class="subtitle-text">Performance overview and active management for today.</p>
                </div>
            </div>
            
            <div class="header-right-section">
                <div class="header-utilities">
                    <div class="live-clock-premium" id="live-clock">
                        <i class="far fa-clock"></i>
                        <span id="clock-display">{{ now()->format('l, F j, Y | h:i A') }}</span>
                    </div>
                    
                    <button class="btn-refresh-data" id="refresh-dashboard" title="Refresh Stats">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    
                    <div class="header-divider"></div>
                    
                    <div class="quick-stats-mini">
                        <div class="stat-mini-item">
                            <span class="stat-label">Today</span>
                            <span class="stat-value" id="mini-today-sales">₱0</span>
                        </div>
                        <div class="stat-mini-item">
                            <span class="stat-label">Transactions</span>
                            <span class="stat-value" id="mini-transactions">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Metrics Grid --}}
    <div class="metrics-grid-premium">
        <!-- Revenue Card -->
        <div class="metric-card-glass revenue">
            <div class="card-icon"><i class="fas fa-coins"></i></div>
            <div class="card-info">
                <span class="label">Total Revenue</span>
                <h2 class="value" id="stat-total-revenue">₱{{ number_format($totalSales, 2) }}</h2>
                <div class="trend {{ $salesGrowth >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-arrow-{{ $salesGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ number_format(abs($salesGrowth), 1) }}% growth</span>
                </div>
            </div>
        </div>

        <!-- Today Sales Card -->
        <div class="metric-card-glass sales">
            <div class="card-icon"><i class="fas fa-shopping-basket"></i></div>
            <div class="card-info">
                <span class="label">Today's Sales</span>
                <h2 class="value" id="stat-today-sales">₱{{ number_format($todaySales, 2) }}</h2>
                <span class="footer-note">{{ $todayTransactions }} transactions today</span>
            </div>
        </div>

        <!-- Products Card -->
        <div class="metric-card-glass products">
            <div class="card-icon"><i class="fas fa-box-open"></i></div>
            <div class="card-info">
                <span class="label">Total Catalog</span>
                <h2 class="value" id="stat-total-products">{{ number_format($totalProducts) }}</h2>
                <span class="footer-note">{{ $activeProducts }} active items</span>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="metric-card-glass stock-alert {{ $lowStockCount > 0 ? 'pulse-alert' : '' }}">
            <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="card-info">
                <span class="label">Low Stock Alerts</span>
                <h2 class="value" id="stat-low-stock">{{ $lowStockCount }}</h2>
                <a href="{{ route('admin.inventory.index') }}" class="action-link">Restock Now <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </div>

    {{-- Analytics Section --}}
    <div class="analytics-row-premium">
        <!-- Sales Chart (Main) -->
        <div class="chart-container-glass main-sales">
            <div class="chart-header">
                <div class="title-box">
                    <i class="fas fa-chart-line"></i>
                    <h3>Sales Performance</h3>
                </div>
                <div class="period-switcher" id="sales-period-switcher">
                    <button class="period-btn active" data-period="week">
                        <i class="fas fa-calendar-day m-0 me-md-1"></i> <span class="d-none d-md-inline">Week</span>
                    </button>
                    <button class="period-btn" data-period="month">
                        <i class="fas fa-calendar-alt m-0 me-md-1"></i> <span class="d-none d-md-inline">Month</span>
                    </button>
                    <button class="period-btn" data-period="year">
                        <i class="fas fa-calendar-check m-0 me-md-1"></i> <span class="d-none d-md-inline">Year</span>
                    </button>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="salesChartMain"></canvas>
                <div class="chart-loader" id="sales-chart-loader">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>

        <!-- Top Products Section (NEW) -->
        <div class="dashboard-card-glass top-products">
            <div class="card-header-premium">
                <h3><i class="fas fa-crown"></i> Top Sellers</h3>
                <a href="{{ route('admin.reports.sales') }}" class="view-all">Full Report</a>
            </div>
            <div class="feed-body">
                @if(is_countable($topProducts) ? count($topProducts) > 0 : !empty($topProducts))
@foreach($topProducts as $item)
                <div class="top-product-item">
                    <div class="product-rank">#{{ $loop->iteration }}</div>
                    <div class="product-info-mini">
                        <strong>{{ $item->product->product_name ?? 'Unknown Product' }}</strong>
                        <small>{{ number_format($item->total_sold) }} units sold</small>
                    </div>
                    <div class="product-revenue">
                        ₱{{ number_format($item->total_revenue, 2) }}
                    </div>
                </div>
                @endforeach
@else
                <p class="text-center text-muted py-4">No sales data yet.</p>
                @endif
            </div>
        </div>

        <!-- Stock Distribution (Donut) -->
        <div class="chart-container-glass stock-dist">
            <div class="chart-header">
                <div class="title-box">
                    <i class="fas fa-dot-circle"></i>
                    <h3>Inventory Status</h3>
                </div>
            </div>
            <div class="chart-body donut-wrapper">
                <canvas id="inventoryStatusChart"></canvas>
                <div class="donut-center-info">
                    <span id="donut-total">{{ $totalProducts }}</span>
                    <small>Items</small>
                </div>
            </div>
            <div class="chart-legend-premium">
                <div class="legend-item"><span class="dot in-stock"></span> In Stock</div>
                <div class="legend-item"><span class="dot low-stock"></span> Low Stock</div>
                <div class="legend-item"><span class="dot out-stock"></span> Out</div>
            </div>
        </div>

        <!-- Category Distribution (Donut) NEW -->
        <div class="chart-container-glass category-dist">
            <div class="chart-header">
                <div class="title-box">
                    <i class="fas fa-tags"></i>
                    <h3>Category Breakdown</h3>
                </div>
            </div>
            <div class="chart-body donut-wrapper">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
            <div class="chart-legend-premium scrollable">
                @foreach ($categoryDistribution as $name => $count)
                <div class="legend-item"><span class="dot" style="background: hsl({{ ($loop->index * 40) % 360 }}, 70%, 50%)"></span> {{ $name }} ({{ $count }})</div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Insights & Actions --}}
    <div class="dashboard-secondary-grid mb-4">
        <!-- Business Health Card -->
        <div class="dashboard-card-glass health-card">
            <div class="card-header-premium">
                <h3><i class="fas fa-heartbeat"></i> Business Health</h3>
            </div>
            <div class="health-body">
                <div class="health-score-outer">
                    <div class="health-score-inner" style="width: {{ $businessHealth }}%; background: {{ $businessHealth > 80 ? 'var(--success-gradient)' : ($businessHealth > 50 ? 'var(--warning-gradient)' : 'var(--danger-gradient)') }};"></div>
                </div>
                <div class="health-info">
                    <span class="score-text">{{ $businessHealth }}%</span>
                    <p class="status-note">{{ $businessHealth > 80 ? 'Operating Excellent' : ($businessHealth > 50 ? 'Needs Attention' : 'Critical Action Required') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Reports Shortcuts -->
        <div class="dashboard-card-glass quick-reports">
            <div class="card-header-premium">
                <h3><i class="fas fa-file-invoice-dollar"></i> Quick Reports</h3>
            </div>
            <div class="reports-grid">
                <a href="{{ route('admin.reports.sales') }}" class="report-btn">
                    <i class="fas fa-chart-line"></i>
                    <span>Sales</span>
                </a>
                <a href="{{ route('admin.reports.inventory') }}" class="report-btn">
                    <i class="fas fa-boxes"></i>
                    <span>Stock</span>
                </a>
                <a href="{{ route('admin.reports.profit-loss') }}" class="report-btn">
                    <i class="fas fa-coins"></i>
                    <span>Profit</span>
                </a>
                <a href="{{ route('admin.inventory.alerts') }}" class="report-btn">
                    <i class="fas fa-bell"></i>
                    <span>Alerts</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom Content Grid --}}
    <div class="dashboard-secondary-grid">
        
        <!-- Left: Quick Approval Feed -->
        <div class="dashboard-card-glass approvals-feed">
            <div class="card-header-premium">
                <h3><i class="fas fa-user-check"></i> Recent Requests</h3>
                @if ($pendingCount > 0)
                    <span class="pending-badge-premium">{{ $pendingCount }} New</span>
                @endif
                <a href="{{ route('admin.users.pending') }}" class="view-all">See All</a>
            </div>
            <div class="feed-body" id="approvals-container">
                @if(is_countable($pendingUsers) ? count($pendingUsers) > 0 : !empty($pendingUsers))
@foreach($pendingUsers as $user)
                <div class="user-request-item" id="dash-user-{{ $user->id }}">
                    <div class="user-avatar-mini">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=4f46e5&color=fff&size=40" alt="">
                    </div>
                    <div class="user-details-mini">
                        <strong>{{ $user->full_name }}</strong>
                        <small>{{ $user->role }} | #{{ $user->employee_id }}</small>
                    </div>
                    <div class="user-actions-mini">
                        <button class="btn-process" onclick="quickApprove({{ $user->id }}, '{{ $user->full_name }}')" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-del" onclick="quickReject({{ $user->id }}, '{{ $user->full_name }}')" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endforeach
@else
                <div class="empty-feed">
                    <i class="fas fa-check-circle"></i>
                    <p>No pending approvals</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Recent Activity -->
        <div class="dashboard-card-glass activity-feed-premium">
            <div class="card-header-premium">
                <h3><i class="fas fa-stream"></i> Activity Feed</h3>
            </div>
            <div class="feed-body">
                @foreach ($recentActivities as $activity)
                <div class="activity-item-premium">
                    <div class="activity-icon-wrapper" style="background: {{ $activity->color }}15; color: {{ $activity->color }};">
                        <i class="fas {{ $activity->icon }}"></i>
                    </div>
                    <div class="activity-text-wrapper">
                        <p>{{ $activity->description }}</p>
                        <span class="time">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Transaction Table Full Width --}}
    <div class="dashboard-card-glass transactions-table-card">
        <div class="card-header-premium">
            <div class="title-group">
                <h3><i class="fas fa-receipt"></i> Recent Transactions</h3>
                <p>Real-time sales tracking</p>
            </div>
            <div class="header-actions">
                <button class="btn-export-premium" onclick="exportTransactions()">
                    <i class="fas fa-file-export"></i> <span>Export to CSV</span>
                </button>
                <a href="{{ route('admin.reports.sales') }}" class="btn-link">View Full Log</a>
            </div>
        </div>
        <div class="table-container-responsive">
            <table class="premium-dashboard-table">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Customer / Cashier</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th class="text-end">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentSales as $sale)
                    <tr>
                        <td><span class="receipt-id">#{{ $sale->receipt_no ?? 'N/A' }}</span></td>
                        <td>
                            <div class="user-combo-cell">
                                <span class="customer">{{ $sale->customer_name ?? 'Walk-in' }}</span>
                                <span class="cashier"><i class="fas fa-user-tag"></i> {{ $sale->user->full_name ?? 'Staff' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge paid"><i class="fas fa-check-circle"></i> Completed</span>
                        </td>
                        <td><span class="amount-text">₱{{ number_format($sale->total_amount, 2) }}</span></td>
                        <td><span class="payment-method-badge">{{ strtoupper($sale->payment_method ?? 'CASH') }}</span></td>
                        <td class="text-end"><span class="time-text">{{ $sale->created_at->format('h:i A') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Floating Action Menu --}}
<div class="fab-wrapper-premium">
    <button class="fab-main" id="fab-trigger" title="Quick Actions">
        <i class="fas fa-plus"></i>
    </button>
    <div class="fab-menu-premium" id="fab-menu">
        <a href="{{ route('cashier.pos.index') }}" class="fab-item pos" title="Open POS System">
            <i class="fas fa-cash-register"></i>
            <span>POS System</span>
        </a>
        <a href="{{ route('admin.products.create') }}" class="fab-item product" title="Add New Product">
            <i class="fas fa-box-open"></i>
            <span>Add Product</span>
        </a>
        <a href="{{ route('admin.users.create') }}" class="fab-item staff" title="Register Staff">
            <i class="fas fa-user-plus"></i>
            <span>New Staff</span>
        </a>
    </div>
</div>

<div id="toast-container-premium"></div>
@endsection

@push('styles')
<style>
/* =========================================================
   DASHBOARD — PREMIUM GLASSMORPHISM & RESPONSIVE STYLES
   ========================================================= */

:root {
    --glass-bg: rgba(255, 255, 255, 0.75);
    --glass-border: rgba(255, 255, 255, 0.4);
    --primary-gradient: linear-gradient(135deg, #4f46e5, #4338ca);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #f43f5e, #e11d48);
}

.dashboard-wrapper {
    padding: clamp(1rem, 3vw, 2rem);
    max-width: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: clamp(1rem, 3vw, 2rem);
    width: 100%;
}

/* --- Header Section - Enhanced Modern Container UI --- */
.dashboard-header-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.8) 100%);
    backdrop-filter: blur(30px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 24px;
    padding: 1.5rem 2rem;
    box-shadow: 
        0 10px 40px rgba(0, 0, 0, 0.05),
        inset 0 1px 1px rgba(255, 255, 255, 1);
    position: relative;
    overflow: hidden;
}


.dashboard-header-container::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -5%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    animation: float 6s ease-in-out infinite;
}

.dashboard-header-container::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(20px); }
}

.header-content-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2.5rem;
    position: relative;
    z-index: 1;
}

.header-left-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}

.welcome-greeting {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.welcome-text {
    font-size: 2rem;
    font-weight: 900;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.8px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #4f46e5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
}

.subtitle-text {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0;
    letter-spacing: 0.2px;
}

.header-right-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.header-utilities {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(248, 250, 252, 0.5) 100%);
    backdrop-filter: blur(15px);
    padding: 0.8rem 1.5rem;
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.7);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
}

.live-clock-premium {
    background: transparent;
    backdrop-filter: none;
    border: none;
    padding: 0;
    border-radius: 0;
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    box-shadow: none;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    white-space: nowrap;
    letter-spacing: 0.2px;
}

.live-clock-premium i {
    color: #4f46e5;
    font-size: 1rem;
    animation: pulse-icon 2s ease-in-out infinite;
}

@keyframes pulse-icon {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.btn-refresh-data {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
    border-radius: 12px;
    color: #fff;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-refresh-data::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.btn-refresh-data:hover {
    transform: scale(1.1) rotate(180deg);
    box-shadow: 0 12px 32px rgba(79, 70, 229, 0.4);
}

.btn-refresh-data:hover::before {
    left: 100%;
}

.btn-refresh-data:active {
    transform: scale(0.92);
}

.header-divider {
    width: 1.5px;
    height: 32px;
    background: linear-gradient(180deg, transparent 0%, rgba(79, 70, 229, 0.4) 50%, transparent 100%);
}

.quick-stats-mini {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.stat-mini-item {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    text-align: center;
    padding: 0.6rem 1rem;
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
}

.stat-mini-item:hover {
    background: rgba(255, 255, 255, 0.6);
    border-color: rgba(79, 70, 229, 0.3);
    transform: translateY(-2px);
}

.stat-label {
    font-size: 0.65rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.05rem;
    font-weight: 900;
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.4px;
}

/* --- Responsive Header --- */
@media (max-width: 1024px) {
    .dashboard-header-container {
        padding: 1.5rem;
    }

    .header-content-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .welcome-text {
        font-size: 1.8rem;
    }

    .header-right-section {
        width: 100%;
        justify-content: space-between;
    }

    .header-utilities {
        flex-wrap: wrap;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .dashboard-header-container {
        padding: 1.25rem;
        border-radius: 20px;
    }

    .welcome-text {
        font-size: 1.5rem;
    }

    .header-utilities {
        width: 100%;
        padding: 0.6rem 1rem;
        gap: 0.75rem;
    }

    .quick-stats-mini {
        gap: 1rem;
    }

    .stat-value {
        font-size: 0.95rem;
    }

    .header-divider {
        display: none;
    }
}

@media (max-width: 480px) {
    .dashboard-header-container {
        padding: 1rem;
    }

    .welcome-text {
        font-size: 1.25rem;
    }

    .subtitle-text {
        font-size: 0.85rem;
    }

    .header-utilities {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }

    .live-clock-premium {
        justify-content: center;
    }

    .quick-stats-mini {
        width: 100%;
        justify-content: space-around;
    }
}

/* --- Metrics Grid --- */
.metrics-grid-premium {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

@media (max-width: 1400px) {
    .metrics-grid-premium {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .metrics-grid-premium {
        grid-template-columns: 1fr;
    }
}

.metric-card-glass {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.metric-card-glass:hover { transform: translateY(-5px); }

.card-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    color: #fff;
    flex-shrink: 0;
}

.revenue .card-icon { background: var(--primary-gradient); box-shadow: 0 8px 15px rgba(79, 70, 229, 0.2); }
.sales .card-icon { background: var(--success-gradient); box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2); }
.products .card-icon { background: var(--warning-gradient); box-shadow: 0 8px 15px rgba(245, 158, 11, 0.2); }
.stock-alert .card-icon { background: var(--danger-gradient); box-shadow: 0 8px 15px rgba(244, 63, 94, 0.2); }

.card-info .label { font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.card-info .value { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 4px 0 6px; }
.trend { font-size: 0.8rem; font-weight: 700; display: flex; align-items: center; gap: 4px; }
.trend.up { color: #10b981; }
.trend.down { color: #ef4444; }
.footer-note { font-size: 0.8rem; color: #94a3b8; }
.action-link { font-size: 0.85rem; font-weight: 700; color: var(--error-rose); text-decoration: none; }

.pulse-alert { animation: pulseBg 2s infinite; }
@keyframes pulseBg { 0%, 100% { background: var(--glass-bg); } 50% { background: rgba(244, 63, 94, 0.05); } }

/* --- Analytics Section --- */
.analytics-row-premium {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(Min(100%, 350px), 1fr));
    gap: 1.5rem;
    width: 100%;
}
.chart-container-glass {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    min-width: 0; /* Important for grid items */
}


.chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.title-box { display: flex; align-items: center; gap: 1rem; }
.title-box i { font-size: 1.25rem; color: var(--primary-indigo); background: rgba(79,70,229,0.1); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
.title-box h3 { margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b; }

.period-switcher { 
    background: rgba(241, 245, 249, 0.8); 
    backdrop-filter: blur(8px);
    padding: 0.35rem; 
    border-radius: 14px; 
    display: flex; 
    gap: 0.35rem; 
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
}
.period-btn { 
    padding: 0.5rem 1.25rem; 
    border: none; 
    background: transparent; 
    border-radius: 10px; 
    font-size: 0.85rem; 
    font-weight: 700; 
    color: #64748b; 
    cursor: pointer; 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.period-btn i { font-size: 0.9rem; }
.period-btn:hover:not(.active) {
    background: rgba(255, 255, 255, 0.5);
    color: #4f46e5;
}
.period-btn.active { 
    background: #fff; 
    color: #4f46e5; 
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15); 
    transform: translateY(-1px);
}
@media (max-width: 768px) {
    .period-btn { padding: 0.5rem 0.8rem; }
}

.chart-body { 
    position: relative; 
    height: 320px; 
    min-height: 320px; /* Stabilize height during AJAX */
}
.donut-wrapper { 
    height: 260px; 
    min-height: 260px; /* Stabilize height */
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: center; 
}
.donut-center-info { position: absolute; text-align: center; }
.donut-center-info span { display: block; font-size: 2rem; font-weight: 800; color: #1e293b; line-height: 1; }
.donut-center-info small { color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; font-size: 0.7rem; }

.chart-legend-premium { display: flex; justify-content: center; gap: 1.5rem; margin-top: 1.5rem; }
.legend-item { display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; font-weight: 700; color: #64748b; }
.dot { width: 10px; height: 10px; border-radius: 50%; }
.dot.in-stock { background: #10b981; }
.dot.low-stock { background: #f59e0b; }
.dot.out-stock { background: #ef4444; }

.chart-legend-premium.scrollable {
    max-height: 100px;
    overflow-y: auto;
    flex-wrap: wrap;
    justify-content: flex-start;
    padding: 10px;
}
.chart-legend-premium.scrollable::-webkit-scrollbar { width: 4px; }
.chart-legend-premium.scrollable::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.chart-loader { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); display: none; align-items: center; justify-content: center; border-radius: 10px; z-index: 10; }
.spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-indigo); border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* --- Secondary Content Grid --- */
.dashboard-secondary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(Min(100%, 350px), 1fr));
    gap: 1.5rem;
    width: 100%;
}
.dashboard-card-glass {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    min-width: 0;
}


.card-header-premium { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.card-header-premium h3 { font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.75rem; }
.card-header-premium h3 i { color: var(--primary-indigo); }
.pending-badge-premium { background: var(--error-rose); color: #fff; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; }
.view-all { font-size: 0.85rem; font-weight: 700; color: var(--primary-indigo); text-decoration: none; }

.feed-body { display: flex; flex-direction: column; gap: 1rem; }

/* User Request Item */
.user-request-item {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.75rem; background: rgba(255,255,255,0.5);
    border-radius: 16px; border: 1px solid #f1f5f9;
}
.user-avatar-mini img { width: 40px; height: 40px; border-radius: 10px; }
.user-details-mini { flex: 1; }
.user-details-mini strong { display: block; font-size: 0.9rem; color: #1e293b; }
.user-details-mini small { color: #64748b; font-size: 0.75rem; }
.user-actions-mini { display: flex; gap: 0.5rem; }
/* Quick action buttons use global styles */

/* Top Product Item */
.top-product-item {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.85rem; background: rgba(255,255,255,0.4);
    border-radius: 16px; border: 1px solid #f1f5f9;
    transition: 0.2s;
}
.top-product-item:hover { background: #fff; transform: translateX(5px); }
.product-rank { 
    width: 32px; height: 32px; background: var(--primary-gradient); 
    color: #fff; border-radius: 10px; display: flex; align-items: center; 
    justify-content: center; font-weight: 800; font-size: 0.8rem;
}
.product-info-mini { flex: 1; }
.product-info-mini strong { display: block; font-size: 0.9rem; color: #1e293b; }
.product-info-mini small { color: #64748b; font-size: 0.75rem; }
.product-revenue { font-weight: 800; color: #10b981; font-size: 0.9rem; }

/* Health & Reports */
.health-score-outer {
    height: 12px; background: #f1f5f9; border-radius: 10px; overflow: hidden; margin-bottom: 1rem;
}
.health-score-inner { height: 100%; transition: width 1s ease-in-out; }
.health-info { display: flex; justify-content: space-between; align-items: center; }
.score-text { font-size: 1.5rem; font-weight: 800; color: #1e293b; }
.status-note { margin: 0; font-size: 0.9rem; font-weight: 700; color: #64748b; }

.reports-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.report-btn { 
    display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; 
    background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; 
    text-decoration: none; color: #475569; font-weight: 700; font-size: 0.85rem;
    transition: 0.2s;
}
.report-btn:hover { border-color: #4f46e5; color: #4f46e5; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.report-btn i { color: #4f46e5; font-size: 1rem; }

/* Activity Feed */
.activity-item-premium {
    display: flex; gap: 1rem;
}
.activity-icon-wrapper {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.activity-text-wrapper p { font-size: 0.9rem; color: #334155; margin: 0; line-height: 1.4; }
.activity-text-wrapper .time { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; display: block; }

/* --- Transactions Table Card --- */
.transactions-table-card { padding: 1.5rem 0; width: 100%; }
.transactions-table-card .card-header-premium { padding: 0 1.5rem 1.5rem; border-bottom: 1px solid #f1f5f9; align-items: flex-end; }
.title-group h3 { margin-bottom: 4px; }
.title-group p { font-size: 0.85rem; color: #94a3b8; margin: 0; }

.btn-export-premium {
    padding: 0.65rem 1.25rem; background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 10px; font-weight: 700; color: #475569;
    display: flex; align-items: center; gap: 0.6rem; cursor: pointer; transition: all 0.2s;
    font-size: 0.85rem;
}
.btn-export-premium:hover { background: #fff; border-color: #cbd5e1; color: #1e293b; }

.table-container-responsive { width: 100%; overflow-x: auto; }
.premium-dashboard-table { width: 100%; border-collapse: collapse; min-width: 900px; }
.premium-dashboard-table th { padding: 1rem 1.5rem; text-align: left; font-size: 0.8rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
.premium-dashboard-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
.receipt-id { font-family: 'Courier New', Courier, monospace; font-weight: 800; color: var(--primary-indigo); background: rgba(79,70,229,0.05); padding: 0.25rem 0.5rem; border-radius: 6px; }
.user-combo-cell .customer { display: block; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
.user-combo-cell .cashier { font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
.amount-text { font-weight: 800; color: #1e293b; font-size: 1rem; }
.payment-method-badge { font-size: 0.7rem; font-weight: 800; color: #64748b; border: 1.5px solid #e2e8f0; padding: 0.2rem 0.5rem; border-radius: 6px; }
.time-text { font-size: 0.85rem; color: #94a3b8; font-weight: 600; }

/* --- Floating Action Button --- */
.fab-wrapper-premium { position: fixed; bottom: 2rem; right: 2rem; z-index: 1000; }
.fab-main {
    width: 64px; height: 64px; background: var(--primary-gradient);
    color: #fff; border: none; border-radius: 50%; cursor: pointer;
    font-size: 1.5rem; box-shadow: 0 10px 25px rgba(79,70,229,0.4);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.fab-main.active { transform: rotate(45deg); background: var(--danger-gradient); }

.fab-menu-premium {
    position: absolute; bottom: 80px; right: 0;
    display: flex; flex-direction: column; gap: 1rem;
    pointer-events: none; opacity: 0; transform: translateY(20px);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.fab-menu-premium.active { pointer-events: auto; opacity: 1; transform: translateY(0); }

.fab-item {
    background: #fff; border-radius: 16px; padding: 0.75rem 1.25rem;
    display: flex; align-items: center; gap: 1rem; text-decoration: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #f1f5f9;
    white-space: nowrap; transition: all 0.2s;
}
.fab-item i { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; }
.fab-item span { font-weight: 700; color: #1e293b; font-size: 0.9rem; }
.fab-item:hover { transform: translateX(-10px); }

.fab-item.pos i { background: var(--success-gradient); }
.fab-item.product i { background: var(--warning-gradient); }
.fab-item.staff i { background: var(--primary-gradient); }

/* --- Responsive Adjustments --- */
@media (max-width: 1400px) {
    .metrics-grid-premium { gap: 1rem; }
}

@media (max-width: 1200px) {
    .metrics-grid-premium { grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
    .analytics-row-premium { grid-template-columns: 1fr; gap: 1.5rem; }
    .dashboard-secondary-grid { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .dashboard-header-premium { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .header-right { width: 100%; justify-content: space-between; flex-wrap: wrap; }
    .metrics-grid-premium { grid-template-columns: 1fr; }
    .dashboard-wrapper { padding: 1rem; }
    .welcome-text { font-size: 1.5rem; }
    .chart-container-glass { padding: 1.25rem; min-width: 0; }
    .premium-dashboard-table { min-width: 600px; }
    .analytics-row-premium, .dashboard-secondary-grid { grid-template-columns: 1fr; }
}

@media (max-width: 480px) {
    .metrics-grid-premium { gap: 1rem; }
    .period-switcher { width: 100%; display: flex; justify-content: space-between; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .period-btn { flex: 1; text-align: center; white-space: nowrap; padding: 0.5rem 0.5rem; font-size: 0.75rem; }
    .header-right { flex-direction: column; align-items: flex-start; }
    .live-clock-premium { width: 100%; justify-content: center; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Initialize Main Charts ---
    initSalesChart();
    initStockChart();
    initCategoryChart();
    
    // --- FAB Logic ---
    const fabTrigger = document.getElementById('fab-trigger');
    const fabMenu = document.getElementById('fab-menu');
    fabTrigger.addEventListener('click', () => {
        fabTrigger.classList.toggle('active');
        fabMenu.classList.toggle('active');
    });

    // --- Period Switcher Logic ---
    const periodButtons = document.querySelectorAll('.period-btn');
    periodButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const period = this.dataset.period;
            
            // UI Switch
            periodButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            updateSalesChart(period);
        });
    });

    // --- Refresh Dashboard ---
    document.getElementById('refresh-dashboard').addEventListener('click', function() {
        const btn = this;
        btn.querySelector('i').classList.add('fa-spin');
        
        fetch('/admin/dashboard/stats')
            .then(res => res.json())
            .then(data => {
                // Update stats
                document.getElementById('stat-total-revenue').innerText = '₱' + parseFloat(data.total_sales).toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('stat-today-sales').innerText = '₱' + parseFloat(data.today_sales).toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('stat-total-products').innerText = data.total_products.toLocaleString();
                document.getElementById('stat-low-stock').innerText = data.low_stock;
                
                // Refresh currently selected chart period
                const activePeriod = document.querySelector('.period-btn.active').dataset.period;
                updateSalesChart(activePeriod);
                
                showPremiumToast('Dashboard updated successfully', 'success');
            })
            .finally(() => {
                setTimeout(() => btn.querySelector('i').classList.remove('fa-spin'), 600);
            });
    });
});

let salesChart;
function initSalesChart() {
    const ctx = document.getElementById('salesChartMain').getContext('2d');
    const initialData = @json($salesChartData);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(initialData).map(d => d.substring(0, 3)),
            datasets: [{
                label: 'Revenue (₱)',
                data: Object.values(initialData),
                borderColor: '#4f46e5',
                borderWidth: 4,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 3,
                fill: true,
                backgroundColor: gradient
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { weight: 'bold' }, callback: v => '₱' + v.toLocaleString() } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });
}

function updateSalesChart(period) {
    const loader = document.getElementById('sales-chart-loader');
    loader.style.display = 'flex';

    fetch(`/admin/dashboard/chart-data?period=${period}`)
        .then(res => res.json())
        .then(data => {
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.data;
            salesChart.update();
            loader.style.display = 'none';
        })
        .catch(err => {
            console.error('Chart update failed:', err);
            loader.style.display = 'none';
            showPremiumToast('Failed to update chart data', 'error');
        });
}

function initStockChart() {
    const ctx = document.getElementById('inventoryStatusChart').getContext('2d');
    const stockData = @json($stockStatus);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(stockData),
            datasets: [{
                data: Object.values(stockData),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });
}

function initCategoryChart() {
    const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
    const categoryData = @json($categoryDistribution);
    const labels = Object.keys(categoryData);
    const data = Object.values(categoryData);
    
    const colors = labels.map((_, i) => `hsl(${(i * 40) % 360}, 70%, 50%)`);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
}

// --- Quick Actions Logic ---

function quickApprove(id, name) {
    Swal.fire({
        title: 'Approve User?',
        text: `Enable system access for ${name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Yes, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            handleDashUserAction(id, 'approve');
        }
    });
}

function quickReject(id, name) {
    Swal.fire({
        title: 'Reject User?',
        input: 'textarea',
        inputPlaceholder: 'Reason for rejection...',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        confirmButtonText: 'Reject'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            handleDashUserAction(id, 'reject', result.value);
        }
    });
}

function handleDashUserAction(id, action, reason = '') {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const item = document.getElementById(`dash-user-${id}`);

    fetch(`/admin/users/${id}/${action}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (item) {
                item.style.transform = 'translateX(100%)';
                item.style.opacity = '0';
                item.style.transition = '0.4s';
                setTimeout(() => item.remove(), 400);
            }
            showPremiumToast(data.message, 'success');
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => Swal.fire('Error', 'Connectivity issue.', 'error'));
}

function showPremiumToast(msg, type) {
    const container = document.getElementById('toast-container-premium');
    const toast = document.createElement('div');
    toast.style.cssText = `
        background: #fff; padding: 1rem 1.5rem; border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 5px solid ${type === 'success' ? '#10b981' : '#f43f5e'};
        margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;
        transform: translateX(100px); opacity: 0; transition: 0.4s;
        font-weight: 700; color: #1e293b; pointer-events: auto;
    `;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-times-circle'}" style="color: ${type === 'success' ? '#10b981' : '#f43f5e'}"></i> ${msg}`;
    
    container.style.cssText = 'position: fixed; bottom: 2rem; left: 2rem; z-index: 9999; pointer-events: none;';
    container.appendChild(toast);
    
    setTimeout(() => { toast.style.transform = 'translateX(0)'; toast.style.opacity = '1'; }, 100);
    setTimeout(() => { toast.style.transform = 'translateX(20px)'; toast.style.opacity = '0'; }, 3000);
    setTimeout(() => toast.remove(), 3500);
}

function exportTransactions() {
    window.location.href = "{{ route('admin.reports.export', ['type' => 'sales', 'format' => 'csv']) }}"; 
}
</script>
@endpush

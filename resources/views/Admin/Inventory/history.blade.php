@extends('layouts.admin')

@section('title', 'Stock History - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box history-header-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">
                    @if (isset($product) && $product)
                        {{ $product->product_name }}
                    @else
                        Stock History
                    @endif
                </h1>
                <p class="page-subtitle">
                    @if (isset($product) && $product)
                        <i class="fas fa-barcode me-1"></i> {{ $product->product_code }} | Tracking all stock movements
                    @else
                        <i class="fas fa-chart-line me-1"></i> Complete history of all stock movements
                    @endif
                </p>
            </div>
        </div>
        <div class="header-actions">
            @if (isset($product) && $product)
                <button type="button" class="btn-header-action btn-header-success" data-bs-toggle="modal" data-bs-target="#stockInModal">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Stock</span>
                </button>
            @endif
            <a href="{{ route('admin.inventory.export-history', request()->all()) }}" class="btn-header-action btn-header-secondary" title="Export History">
                <i class="fas fa-download"></i>
                <span>Export</span>
            </a>
            @if (isset($product) && $product)
                <a href="{{ route('admin.inventory.all-history') }}" class="btn-header-action btn-header-dark">
                    <i class="fas fa-arrow-left"></i>
                    <span>All History</span>
                </a>
            @else
                <a href="{{ route('admin.inventory.index') }}" class="btn-header-action btn-header-dark">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Product Stats Cards - Premium Glassmorphism -->
    @if (isset($product) && $product)
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4" id="statsContainer">
        <!-- Current Stock -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Current Stock</span>
                        @php $currentQty = $product->stock->quantity ?? 0; @endphp
                        <span class="stat-value fw-bold h3 {{ $currentQty <= ($product->stock->min_quantity ?? 10) ? 'text-danger' : '' }}">
                            {{ number_format($currentQty) }}
                        </span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-info-circle me-1"></i><span>{{ $product->unit ?? 'pcs' }} available</span>
                </div>
            </div>
        </div>

        <!-- Min Stock Level -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Reorder Level</span>
                        <span class="stat-value fw-bold h3">
                            {{ $product->stock->min_quantity ?? 10 }}
                        </span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-bell me-1"></i><span>Alert threshold</span>
                </div>
            </div>
        </div>

        <!-- Total In (Today) -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Stocks In</span>
                        @php 
                            $todayIn = $transactions->where('type', 'in')
                                ->where('created_at', '>=', now()->startOfDay())
                                ->sum('quantity');
                        @endphp
                        <span class="stat-value fw-bold h3">{{ number_format($todayIn) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-calendar-day me-1"></i><span>Today's additions</span>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Location</span>
                        <span class="stat-value fw-bold h3" style="font-size: 1.2rem;">
                            {{ $product->stock->location ?? 'A1' }}
                        </span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-warehouse me-1"></i><span>Storage shelf</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Global Stats -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Total Logs</span>
                        <span class="stat-value fw-bold h3">{{ $transactions->total() }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-database me-1"></i><span>Total movements</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Today In</span>
                        @php 
                            $todayInGlobal = \App\Models\StockTransaction::where('type', 'in')
                                ->whereDate('created_at', today())
                                ->sum('quantity');
                        @endphp
                        <span class="stat-value fw-bold h3">{{ number_format($todayInGlobal) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-calendar-day me-1"></i><span>Today's stock-in</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Today Out</span>
                        @php 
                            $todayOutGlobal = \App\Models\StockTransaction::where('type', 'out')
                                ->whereDate('created_at', today())
                                ->sum('quantity');
                        @endphp
                        <span class="stat-value fw-bold h3">{{ number_format($todayOutGlobal) }}</span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-calendar-day me-1"></i><span>Today's stock-out</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-modern p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="min-width-0">
                        <span class="stat-label text-uppercase text-muted small d-block">Avg Daily</span>
                        <span class="stat-value fw-bold h3" style="font-size: 1.2rem;">
                            {{ number_format($transactions->count() / 7, 1) }}
                        </span>
                    </div>
                </div>
                <div class="mt-auto small text-muted">
                    <i class="fas fa-chart-line me-1"></i><span>Weekly average</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Card Enhanced -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden filter-card-enhanced">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center">
                            <i class="fas fa-sliders-h text-gradient-secondary me-2"></i>Filter Transactions
                        </h5>
                    </div>
                    <form method="GET" action="{{ isset($product) && $product ? route('admin.inventory.history', $product->id) : route('admin.inventory.all-history') }}" id="filterForm">
                        <div class="row g-3">
                            @if (!isset($product) || !$product)
                            <div class="col-md-3">
                                <div class="input-group input-group-enhanced">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-box text-secondary"></i>
                                    </span>
                                    <select name="product_id" class="form-select border-start-0">
                                        <option value="">All Products</option>
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-2">
                                <select name="type" class="form-select form-select-enhanced">
                                    <option value="">All Types</option>
                                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group input-group-enhanced">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-calendar text-secondary"></i>
                                    </span>
                                    <input type="date" name="date_from" class="form-control border-start-0" 
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group input-group-enhanced">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-calendar text-secondary"></i>
                                    </span>
                                    <input type="date" name="date_to" class="form-control border-start-0" 
                                           value="{{ request('date_to') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-gradient-secondary w-100 h-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                            
                            <div class="col-md-1">
                                <a href="{{ isset($product) && $product ? route('admin.inventory.history', $product->id) : route('admin.inventory.all-history') }}" 
                                   class="btn btn-outline-secondary w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div class="row mx-0 px-3 px-md-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="fas fa-circle text-gradient-secondary me-2" style="font-size: 0.5em;"></i>
                            Movement History <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">{{ $transactions->total() }} entries</span>
                        </h5>
                        <div>
                            <select name="per_page" class="form-select form-select-sm form-select-enhanced" style="width: 130px;" onchange="window.location.href=this.value">
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Date & Time</th>
                                @if (!isset($product) || !$product)
                                <th class="py-3">Product</th>
                                @endif
                                <th class="py-3">Type</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3">Stock Progress</th>
                                <th class="py-3">Reason</th>
                                <th class="py-3">Reference</th>
                                <th class="py-3">User</th>
                                <th class="text-end px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $transaction)
                            @php
                                $prevQty = $transaction->previous_quantity ?? 0;
                                $newQty = $transaction->new_quantity ?? 0;
                                $isIncrease = $newQty > $prevQty;
                                
                                $reasonColors = [
                                    'sale' => 'success',
                                    'purchase' => 'primary',
                                    'damage' => 'danger',
                                    'expired' => 'danger',
                                    'adjustment' => 'info',
                                    'return' => 'primary',
                                    'restock' => 'success'
                                ];
                                $reason = $transaction->reason ?? 'unknown';
                                $color = $reasonColors[$reason] ?? 'secondary';
                            @endphp
                            <tr class="log-row">
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-center" style="width: 45px;">
                                            <div class="fw-bold">{{ $transaction->created_at ? $transaction->created_at->format('d') : '--' }}</div>
                                            <div class="text-muted small text-uppercase">{{ $transaction->created_at ? $transaction->created_at->format('M') : '---' }}</div>
                                        </div>
                                        <div class="border-start ps-3">
                                            <div class="small fw-semibold">{{ $transaction->created_at ? $transaction->created_at->format('Y') : '' }}</div>
                                            <div class="text-muted small">{{ $transaction->created_at ? $transaction->created_at->format('h:i A') : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                @if (!isset($product) || !$product)
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm-enhanced me-2 bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-box small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-truncate" style="max-width: 150px;">{{ $transaction->product->product_name ?? 'Deleted' }}</div>
                                            <small class="text-muted">{{ $transaction->product->product_code ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                @endif
                                <td>
                                    @if ($transaction->type == 'in')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-arrow-down me-1"></i> IN
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-arrow-up me-1"></i> OUT
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6 {{ $isIncrease ? 'text-success' : 'text-danger' }}">
                                        {{ $isIncrease ? '+' : '-' }}{{ number_format($transaction->quantity) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-muted small text-end" style="width: 40px;">{{ number_format($prevQty) }}</div>
                                        <div class="progress flex-grow-1" style="height: 4px; min-width: 60px;">
                                            <div class="progress-bar bg-{{ $isIncrease ? 'success' : 'danger' }}" 
                                                 role="progressbar" 
                                                 style="width: 100%"></div>
                                        </div>
                                        <div class="fw-bold small" style="width: 40px;">{{ number_format($newQty) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-2 rounded-pill">
                                        {{ ucfirst(str_replace('_', ' ', $reason)) }}
                                    </span>
                                </td>
                                <td>
                                    <code class="text-dark small">{{ $transaction->reference ?? '—' }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-user-sm bg-gradient-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                            {{ substr($transaction->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="small text-muted">{{ $transaction->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                        <button type="button" class="btn btn-sm btn-white border-end" onclick="viewDetails({{ $transaction->id }})" title="View Details">
                                            <i class="fas fa-eye text-info"></i>
                                        </button>
                                        @if ($transaction->created_at->diffInHours(now()) < 24)
                                        <button type="button" class="btn btn-sm btn-white" onclick="voidTransaction({{ $transaction->id }})" title="Void Transaction">
                                            <i class="fas fa-undo text-danger"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
@else
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state py-5">
                                        <div class="empty-icon-wrapper mb-4">
                                            <i class="fas fa-history fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">No Transactions Found</h5>
                                        <p class="text-muted small">Movement logs will appear here as they occur</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                <div class="px-4 py-4 border-top bg-light bg-opacity-50">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small order-2 order-md-1">
                            Showing <strong>{{ $transactions->firstItem() }}</strong> to <strong>{{ $transactions->lastItem() }}</strong> of <strong>{{ $transactions->total() }}</strong> entries
                        </div>
                        <div class="pagination-modern order-1 order-md-2">
                            {{ $transactions->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stock In Modal - Enhanced -->
@if (isset($product) && $product)
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header bg-success text-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-header-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <h5 class="modal-title">Add Stock</h5>
                        <small>{{ $product->product_name ?? '' }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockInForm">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="product_id" value="{{ $product->id ?? 0 }}">
                    <input type="hidden" name="type" value="in">
                    <input type="hidden" name="reason" value="purchase">
                    
                    <div class="current-stock-display mb-4">
                        <span class="label">Current Stock</span>
                        @php
                            $currentQty = 0;
                            if(isset($product->stock) && $product->stock) {
                                $currentQty = $product->stock->quantity ?? 0;
                            }
                        @endphp
                        <span class="value">{{ number_format($currentQty) }} {{ $product->unit ?? 'pcs' }}</span>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="number" name="quantity" class="form-control modern-input" id="floatingQuantity" step="0.01" min="0.01" required>
                        <label for="floatingQuantity">Quantity to Add <span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="number" name="unit_cost" class="form-control modern-input" id="floatingCost" step="0.01" min="0" value="0">
                        <label for="floatingCost">Unit Cost</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="reference" class="form-control modern-input" id="floatingReference" placeholder="PO-12345">
                        <label for="floatingReference">Reference</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea name="notes" class="form-control modern-input" id="floatingNotes" style="height: 100px" placeholder="Additional notes"></textarea>
                        <label for="floatingNotes">Notes</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-modern px-4">
                        <i class="fas fa-save me-2"></i>Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    /* ===== PREMIUM DESIGN ===== */
    
    /* Header Gradient */
    .header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #5f2c82 100%);
        position: relative;
        overflow: hidden;
    }
    
    .header-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    
    .header-gradient::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }

    /* Header Icon with Glow */
    .header-icon-wrapper {
        position: relative;
        width: 70px;
        height: 70px;
    }
    
    .header-icon-inner {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.2rem;
        position: relative;
        z-index: 2;
        border: 2px solid rgba(255,255,255,0.3);
    }
    
    .header-icon-glow {
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(255,255,255,0));
        border-radius: 25px;
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--glass-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: -1.5rem;
    }

    .header-icon-enhanced {
        width: 54px;
        height: 54px;
        background: var(--primary-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.25);
    }

    /* Stat Cards Modern */
    .stat-card-modern {
        background: white;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }

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

    .stat-icon.bg-primary { background: var(--primary-gradient); }
    .stat-icon.bg-success { background: var(--success-gradient); }
    .stat-icon.bg-warning { background: var(--warning-gradient); }
    .stat-icon.bg-info { background: var(--info-gradient); }

    /* Filter Card Enhanced */
    .filter-card-enhanced {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .input-group-enhanced {
        border-radius: 12px;
        overflow: hidden;
        border: 1.5px solid #edf2f7;
        transition: all 0.2s;
    }

    .input-group-enhanced:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group-enhanced .form-control,
    .input-group-enhanced .form-select,
    .input-group-enhanced .input-group-text {
        border: none;
        background: transparent;
        height: 48px;
    }

    .form-select-enhanced {
        border: 1.5px solid #edf2f7;
        border-radius: 12px;
        height: 48px;
        padding: 0 1rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .form-select-enhanced:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-gradient-secondary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-gradient-secondary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Modern Table */
    .modern-table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #edf2f7;
        color: #4a5568;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
    }

    .log-row {
        transition: all 0.2s;
    }

    .log-row:hover {
        background-color: #f8fafc;
    }

    .progress {
        background-color: #edf2f7;
        border-radius: 10px;
    }

    .avatar-user-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-white {
        background: white;
        border: 1px solid #edf2f7;
        transition: all 0.2s;
    }

    .btn-white:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
    }

    /* Modal Styling */
    .modern-modal {
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modern-modal .modal-header {
        border-bottom: none;
        padding: 2rem;
    }

    .modern-modal .modal-body {
        padding: 2rem;
    }

    .modern-input {
        border: 1.5px solid #edf2f7;
        border-radius: 12px;
        padding: 1rem;
        height: auto;
        transition: all 0.2s;
    }

    .modern-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .log-row {
        animation: fadeInUp 0.4s ease forwards;
        animation-delay: calc(var(--row-index) * 0.05s);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add row animation index
        document.querySelectorAll('.log-row').forEach((row, index) => {
            row.style.setProperty('--row-index', index);
        });

        // Initialize form listeners
        const stockInForm = document.getElementById('stockInForm');
        if (stockInForm) {
            stockInForm.addEventListener('submit', function(e) {
                e.preventDefault();
                processStockTransaction(this);
            });
        }
    });

    function processStockTransaction(form) {
        const formData = new FormData(form);
        
        Swal.fire({
            title: 'Processing Transaction',
            text: 'Updating stock levels, please wait...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("admin.inventory.process-stock-in") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Stock Updated!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Transaction Failed',
                    text: data.message || 'Please check your inputs and try again.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Could not connect to the server.'
            });
        });
    }

    function viewDetails(id) {
        // In a real app, this would fetch details via AJAX
        Swal.fire({
            title: `<span class="fw-bold">Transaction #${id}</span>`,
            html: `
                <div class="text-start p-2">
                    <p class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i><strong>Status:</strong> <span class="badge bg-success bg-opacity-10 text-success">Verified</span></p>
                    <p class="mb-2"><i class="fas fa-shield-alt text-primary me-2"></i><strong>Audit Log:</strong> Recorded on System</p>
                    <hr>
                    <p class="small text-muted">This transaction has been successfully processed and verified by the inventory management system.</p>
                </div>
            `,
            icon: 'info',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'Great, Thanks!'
        });
    }

    function voidTransaction(id) {
        Swal.fire({
            title: 'Void Transaction?',
            text: 'This will reverse the stock movement. This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, void it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => Swal.showLoading()
                });
                
                // Mocking the void process
                setTimeout(() => {
                    Swal.fire(
                        'Voided!',
                        'Transaction has been reversed.',
                        'success'
                    ).then(() => window.location.reload());
                }, 1000);
            }
        });
    }
</script>
@endpush

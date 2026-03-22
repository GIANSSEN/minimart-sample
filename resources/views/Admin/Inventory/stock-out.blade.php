@extends('layouts.admin')

@section('title', 'Stock Out - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0" style="max-width: 1400px; margin: 0 auto;" id="stock-out-page">
    <!-- Page Header (Aligned with Reference) -->
    <div class="header-card-white mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="header-icon-box-new bg-primary text-white me-3">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Stock Out</h4>
                    <p class="text-muted small mb-0">Record outgoing inventory, sales, and adjustments</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-new-action" data-bs-toggle="modal" data-bs-target="#newStockOutModal">
                    <i class="fas fa-minus-circle me-1"></i> New Stock Out
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards (Aligned with Reference) -->
    <div class="row mx-0 g-3 mb-4" id="statsContainer">
        <!-- Total Transactions -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-indigo-soft text-indigo">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">TOTAL</span>
                        <div class="stat-value-new">{{ number_format($transactions->total()) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-database"></i> {{ $transactions->count() }} this page
                </div>
            </div>
        </div>

        <!-- Total Quantity Out -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-orange-soft text-orange">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">QUANTITY OUT</span>
                        <div class="stat-value-new">{{ number_format($transactions->sum('quantity')) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-cubes"></i> units removed
                </div>
            </div>
        </div>

        <!-- Value Lost -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-yellow-soft text-yellow">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">VALUE LOST</span>
                        <div class="stat-value-new">₱{{ number_format($totalValueLost ?? 0, 0) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-coins"></i> cost of goods
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-purple-soft text-purple">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">THIS MONTH</span>
                        <div class="stat-value-new">{{ number_format($monthlyCount ?? 0) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-calendar"></i> {{ now()->format('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card Enhanced (collapsible, with active tags) -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden filter-card-enhanced">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 fw-semibold d-flex align-items-center">
                            <i class="fas fa-sliders-h text-gradient-secondary me-2"></i>Filter Stock Out
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.inventory.export-history', ['type' => 'out']) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-download me-1"></i>Export
                            </a>
                            <a href="{{ route('admin.inventory.stock-out') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-redo-alt me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.inventory.stock-out') }}" id="filterForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <!-- Search (col-md-3) -->
                                <div class="col-md-3">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-warning"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" 
                                               placeholder="Search product..." 
                                               value="{{ request('search') }}" id="searchInput" autocomplete="off" aria-label="Search products">
                                    </div>
                                </div>

                                <!-- Reason (col-md-2) -->
                                <div class="col-md-2">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-tag text-secondary"></i>
                                        </span>
                                        <select name="reason" class="form-select border-start-0" aria-label="Filter by reason">
                                            <option value="">All Reasons</option>
                                            <option value="sale" {{ request('reason') == 'sale' ? 'selected' : '' }}>Sale</option>
                                            <option value="damage" {{ request('reason') == 'damage' ? 'selected' : '' }}>Damage</option>
                                            <option value="expired" {{ request('reason') == 'expired' ? 'selected' : '' }}>Expired</option>
                                            <option value="adjustment" {{ request('reason') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                            <option value="return" {{ request('reason') == 'return' ? 'selected' : '' }}>Return</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Date From (col-md-2) -->
                                <div class="col-md-2">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-secondary"></i>
                                        </span>
                                        <input type="date" name="date_from" class="form-control border-start-0" 
                                               placeholder="From" value="{{ request('date_from') }}" id="dateFromInput" aria-label="From date">
                                    </div>
                                </div>

                                <!-- Date To (col-md-2) -->
                                <div class="col-md-2">
                                    <div class="input-group input-group-enhanced">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-secondary"></i>
                                        </span>
                                        <input type="date" name="date_to" class="form-control border-start-0" 
                                               placeholder="To" value="{{ request('date_to') }}" id="dateToInput" aria-label="To date">
                                    </div>
                                </div>

                                <!-- Apply Button (col-md-3) -->
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-gradient-warning w-100" id="applyFilterBtn" aria-label="Apply filters">
                                        <i class="fas fa-filter me-2"></i><span class="d-none d-sm-inline">Apply</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Active Filters -->
                            @if (request()->anyFilled(['search', 'reason', 'date_from', 'date_to']))
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark p-2">
                                        <i class="fas fa-sliders-h me-1"></i>Active Filters:
                                    </span>
                                    @if (request('search'))
                                        <span class="badge bg-primary">
                                            "{{ request('search') }}" 
                                            <a href="{{ route('admin.inventory.stock-out', array_merge(request()->except('search', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove search filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('reason'))
                                        <span class="badge bg-info">
                                            {{ ucfirst(request('reason')) }}
                                            <a href="{{ route('admin.inventory.stock-out', array_merge(request()->except('reason', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove reason filter">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('date_from'))
                                        <span class="badge bg-success">
                                            From {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                                            <a href="{{ route('admin.inventory.stock-out', array_merge(request()->except('date_from', 'page'))) }}" 
                                               class="text-white ms-1" title="Remove from date">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('date_to'))
                                        <span class="badge bg-warning text-dark">
                                            To {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                            <a href="{{ route('admin.inventory.stock-out', array_merge(request()->except('date_to', 'page'))) }}" 
                                               class="text-dark ms-1" title="Remove to date">
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

    <!-- Transactions Table (9 columns) -->
    <div class="row mx-0 px-3 px-md-4" id="transactionsTableContainer" role="region" aria-label="Stock out records table">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-soft border-0 py-4 px-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="fas fa-circle text-gradient-secondary me-2" style="font-size: 0.5em;"></i>
                            Stock Out Records <span class="badge bg-secondary ms-2">{{ $transactions->total() }} total</span>
                        </h5>
                        <div>
                            <form method="GET" action="{{ route('admin.inventory.stock-out') }}" id="perPageForm" class="d-inline">
                                @foreach (request()->except('per_page', 'page') as $key => $value)
                                    @if ($value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <select name="per_page" class="form-select form-select-sm form-select-enhanced" style="width: 130px;" onchange="validatePerPage(this)" aria-label="Records per page">
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
                                <th class="px-4 py-3">DATE & TIME</th>
                                <th class="py-3">PRODUCT</th>
                                <th class="py-3 text-center">QUANTITY</th>
                                <th class="py-3 text-end">UNIT PRICE</th>
                                <th class="py-3 text-end">TOTAL VALUE</th>
                                <th class="py-3">TRANSACTION NO.</th>
                                <th class="py-3">REASON</th>
                                <th class="py-3">USER</th>
                                <th class="text-end px-4 py-3">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $transaction)
                            @php
                                $unitPrice = $transaction->product->selling_price ?? 0;
                                $totalValue = $transaction->quantity * $unitPrice;
                                $reasonColors = [
                                    'sale' => 'success',
                                    'damage' => 'danger',
                                    'expired' => 'danger',
                                    'adjustment' => 'info',
                                    'return' => 'primary'
                                ];
                                $color = $reasonColors[$transaction->reason] ?? 'secondary';
                            @endphp
                            <tr class="transaction-row" role="row">
                                <td class="px-4" data-label="Date & Time">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-day opacity-50 me-2" style="color: #f59e0b;"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</div>
                                            <small class="text-muted">{{ $transaction->created_at ? $transaction->created_at->format('h:i A') : '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Product">
                                    <div class="d-flex align-items-center">
                                        <div class="product-icon-wrapper me-2">
                                            <div class="bg-warning bg-opacity-10 rounded-2 p-2">
                                                <i class="fas fa-box text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="min-width-0">
                                            <div class="fw-semibold text-truncate" style="max-width:200px;">{{ Str::limit($transaction->product->product_name ?? 'Deleted Product', 30) }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $transaction->product->product_code ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" data-label="Quantity">
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                        <i class="fas fa-arrow-down me-1"></i>-{{ number_format($transaction->quantity) }}
                                    </span>
                                </td>
                                <td class="text-end" data-label="Unit Price">
                                    <span class="fw-medium">₱{{ number_format($unitPrice, 2) }}</span>
                                </td>
                                <td class="text-end" data-label="Total Value">
                                    <span class="fw-bold text-primary">₱{{ number_format($totalValue, 2) }}</span>
                                </td>
                                <td data-label="Reason">
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-2 rounded-pill">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->reason)) }}
                                    </span>
                                </td>
                                <td data-label="Transaction No.">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                        {{ $transaction->reference ?? '—' }}
                                    </span>
                                </td>
                                <td data-label="User">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar-initials-xs rounded-circle bg-info bg-opacity-10 text-info me-2">
                                            {{ $transaction->user ? substr($transaction->user->name ?? 'S', 0, 1) : 'S' }}
                                        </span>
                                        <span class="small">{{ $transaction->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="text-end px-4" data-label="Actions">
                                    <div class="btn-group" role="group" aria-label="Transaction actions">
                                        <button class="btn btn-sm btn-outline-info" onclick="viewDetails({{ $transaction->id }})" title="View details" aria-label="View transaction {{ $transaction->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if ($transaction->created_at->diffInHours(now()) < 24)
                                        <button class="btn btn-sm btn-outline-danger" onclick="voidTransaction({{ $transaction->id }}, '{{ addslashes($transaction->product->product_name ?? '') }}', {{ $transaction->quantity }})" title="Void transaction" aria-label="Void transaction {{ $transaction->id }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
@else
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fas fa-arrow-up fa-4x text-muted opacity-25 mb-4"></i>
                                        <h5 class="text-muted mb-2">No Stock Out Records</h5>
                                        <p class="text-muted mb-3">Start recording outgoing inventory.</p>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#newStockOutModal">
                                            <i class="fas fa-minus-circle me-2"></i>New Stock Out
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                <div class="px-4 py-4 border-top">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            <i class="fas fa-database me-1"></i>
                            Showing <strong>{{ $transactions->firstItem() }}</strong> to <strong>{{ $transactions->lastItem() }}</strong> of <strong>{{ $transactions->total() }}</strong> entries
                        </div>
                        <div class="pagination-modern">
                            {{ $transactions->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- New Stock Out Modal -->
<div class="modal fade" id="newStockOutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
            <!-- Modal Header (Gradient - Warning/Danger for Stock Out) -->
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-pill p-2 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-up fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Record Stock Out</h5>
                        <p class="small mb-0 opacity-75">Deduct products from inventory (Sales, Damage, etc.)</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="stockOutForm" method="POST" action="{{ route('admin.inventory.process-stock-out') }}">
                @csrf
                <input type="hidden" name="type" value="out">
                
                <div class="modal-body p-4 bg-white">
                    <div class="row g-4">
                        <!-- SECTION: TRANSACTION INFO -->
                        <div class="col-12">
                            <div class="show-modal-section-label-out">
                                <i class="fas fa-info-circle me-1"></i> Transaction Info
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="dateOut">Date Out <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="date_out" class="form-control border-start-0" id="dateOut" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="releasedBy">Released By <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-user text-muted"></i></span>
                                <input type="text" name="released_by" class="form-control border-start-0" id="releasedBy" value="{{ auth()->user()->name }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="reference">Transaction No.</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                                <input type="text" name="reference" class="form-control border-start-0" id="reference" placeholder="e.g. INV-1001">
                            </div>
                        </div>

                        <!-- SECTION: PRODUCT SELECTION -->
                        <div class="col-12">
                            <div class="show-modal-section-label-out">
                                <i class="fas fa-search me-1"></i> Product Selection
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="productSelect">Select Product <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-box text-warning"></i></span>
                                    <select name="product_id" class="form-select select2 border-start-0" id="productSelect" required>
                                        <option value="">-- Search or Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-stock="{{ $product->stock->quantity ?? 0 }}"
                                                    data-unit="{{ $product->unit ?? 'units' }}"
                                                    data-price="{{ $product->selling_price ?? 0 }}"
                                                    data-reorder="{{ $product->stock->min_quantity ?? 10 }}">
                                                {{ $product->product_name }} ({{ $product->product_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: QUANTITY & PRICING -->
                        <div class="col-12">
                            <div class="show-modal-section-label-out">
                                <i class="fas fa-calculator me-1"></i> Quantity & Pricing
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-input-card-out border rounded-4 p-3 h-100 transition-hover">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1 d-block" for="quantity">Quantity</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                                        <i class="fas fa-cubes"></i>
                                    </div>
                                    <input type="number" name="quantity" class="form-control form-control-lg fw-bold border-0 bg-transparent fs-3 p-0" step="0.01" min="0.01" id="quantity" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-input-card-out border rounded-4 p-3 h-100 transition-hover">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1 d-block" for="unitPrice">Selling Price</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="fw-bold text-muted me-1 fs-5">₱</span>
                                        <input type="number" name="unit_price" class="form-control form-control-lg fw-bold border-0 bg-transparent fs-3 p-0" step="0.01" min="0" id="unitPrice" value="0.00" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-danger bg-opacity-10 rounded-4 border border-danger border-opacity-25 h-100">
                                <label class="form-label fw-bold small text-danger text-uppercase mb-1">Total Value</label>
                                <div class="fs-3 fw-bold text-danger" id="finalTotalValue">₱ 0.00</div>
                                <small class="text-danger opacity-75">(Auto-computed)</small>
                            </div>
                        </div>

                        <!-- SECTION: STOCK STATUS -->
                        <div class="col-md-6">
                            <div class="p-3 bg-secondary bg-opacity-10 rounded-4 border border-secondary border-opacity-25 shadow-sm">
                                <label class="form-label fw-bold small text-secondary text-uppercase mb-1">Available Stock</label>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-warehouse text-secondary opacity-50"></i>
                                    <div class="fs-4 fw-bold text-secondary" id="finalCurrentStockCount">0 units</div>
                                </div>
                                <small class="text-secondary opacity-75">(Before deduction)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1">Reason for Stock Out</label>
                            <select name="reason" class="form-select rounded-3 border-light bg-light shadow-sm py-2" id="reason" required>
                                <option value="sale">Sales Transaction</option>
                                <option value="damage">Damaged / Defective</option>
                                <option value="expired">Expired Products</option>
                                <option value="return">Return to Supplier</option>
                                <option value="adjustment">Inventory Adjustment</option>
                                <option value="staff_consumption">Staff Consumption</option>
                                <option value="others">Others / Miscellaneous</option>
                            </select>
                        </div>

                        <!-- SECTION: NOTES -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="notes">Internal Remarks</label>
                            <textarea name="notes" class="form-control rounded-3" id="notes" rows="2" placeholder="Explain the reason for stock out..."></textarea>
                        </div>

                        <!-- WARNING ALERTS -->
                        <div class="col-12" id="stockWarnings">
                            <div id="stockWarningAlert" class="alert alert-danger d-none align-items-center rounded-3 mb-2 py-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span class="small" id="warningMessageText">Warning: Quantity exceeds current stock!</span>
                            </div>
                            <div id="reorderWarningAlert" class="alert alert-warning d-none align-items-center rounded-3 mb-0 py-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="small">Heads up: This will put stock below reorder level.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 p-4 bg-light">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-5 shadow-sm ms-auto" id="submitBtn">
                        <i class="fas fa-check-circle me-1"></i> Process Stock Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transaction Details Modal (same as stock‑in) -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-hidden="true" aria-labelledby="transactionDetailsModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="transactionDetailsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Transaction Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transactionDetailsContent">
                <div class="text-center py-3">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    /* Refined Layout Consistency (Reference Image Style) */
    .header-card-white {
        background: white;
        padding: 24px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.02);
        margin: 0 1.5rem;
    }
    .header-icon-box-new {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
    }
    .btn-new-action {
        border: 1.5px solid #212529;
        border-radius: 12px;
        padding: 8px 24px;
        font-weight: 600;
        background: white;
        color: #212529;
        transition: all 0.2s;
    }
    .btn-new-action:hover {
        background: #212529;
        color: white;
        transform: translateY(-1px);
    }
    .stat-card-white {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }
    .stat-card-white:hover {
        transform: translateY(-3px);
    }
    .stat-icon-new {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .bg-indigo-soft { background: #eef2ff; }
    .text-indigo { color: #6366f1; }
    .bg-orange-soft { background: #fff7ed; }
    .text-orange { color: #f97316; }
    .bg-yellow-soft { background: #fefce8; }
    .text-yellow { color: #eab308; }
    .bg-purple-soft { background: #faf5ff; }
    .text-purple { color: #a855f7; }

    .stat-label-new {
        font-size: 0.7rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.05em;
    }
    .stat-value-new {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
    }
    .stat-footer-new {
        margin-top: 15px;
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Modal Specific */
    .show-modal-section-label-out {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #ef4444;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .show-modal-section-label-out::after {
        content: '';
        flex: 1;
        height: 1.5px;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.25), transparent);
        border-radius: 2px;
    }
    .metric-input-card-out {
        background: #fffafa;
        transition: all 0.2s ease;
    }
    .metric-input-card-out:focus-within {
        background: #fff;
        border-color: #ef4444 !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#productSelect').select2({
        dropdownParent: $('#newStockOutModal'),
        width: '100%'
    });

    // Product selection change handler
    $('#productSelect').on('change', function() {
        const selected = $(this).find('option:selected');
        const stock = parseFloat(selected.data('stock')) || 0;
        const price = parseFloat(selected.data('price')) || 0;
        const unit = selected.data('unit') || 'units';
        const reorder = parseFloat(selected.data('reorder')) || 0;
        
        $('#finalCurrentStockCount').text(stock + ' ' + unit);
        $('#unitPrice').val(price.toFixed(2));
        
        validateStock();
        updateTotalValue();
    });

    // Quantity and unit price input handlers
    $('#quantity, #unitPrice').on('input', function() {
        validateStock();
        updateTotalValue();
    });

    function validateStock() {
        const selected = $('#productSelect option:selected');
        if (!selected.val()) return;

        const currentStock = parseFloat(selected.data('stock')) || 0;
        const qtyToRelease = parseFloat($('#quantity').val()) || 0;
        const reorderLevel = parseFloat(selected.data('reorder')) || 0;

        // Reset warnings
        $('#stockWarningAlert').addClass('d-none').removeClass('d-flex');
        $('#reorderWarningAlert').addClass('d-none').removeClass('d-flex');
        $('#submitBtn').prop('disabled', false);

        if (qtyToRelease > currentStock) {
            $('#stockWarningAlert').removeClass('d-none').addClass('d-flex');
            $('#warningMessageText').text(`Warning: Quantity (${qtyToRelease}) exceeds available stock (${currentStock})!`);
            $('#submitBtn').prop('disabled', true);
        } else if (qtyToRelease > 0 && (currentStock - qtyToRelease) <= reorderLevel) {
            $('#reorderWarningAlert').removeClass('d-none').addClass('d-flex');
        }
    }

    function updateTotalValue() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const unitPrice = parseFloat($('#unitPrice').val()) || 0;
        const total = quantity * unitPrice;
        
        $('#finalTotalValue').text('₱ ' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    // Form submission
    $('#stockOutForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#submitBtn');
        
        // Basic check
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Stock out processed successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong.'
                    });
                    submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Process Stock Out');
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred while processing the request.';
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Process Stock Out');
            }
        });
    });
});

function viewDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
    $('#transactionDetailsContent').html('<div class="text-center py-5"><div class="spinner-border text-info"></div><p class="mt-2">Loading details...</p></div>');
    modal.show();

    $.get(`/admin/inventory/transaction/${id}`, function(response) {
        $('#transactionDetailsContent').html(response);
    }).fail(function() {
        $('#transactionDetailsContent').html('<div class="alert alert-danger m-3">Could not load details.</div>');
    });
}

function voidTransaction(id, name, qty) {
    Swal.fire({
        title: 'Void Transaction?',
        text: `Are you sure you want to void the stock out of ${qty} ${name}? This will return the quantity to inventory.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, void it!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/inventory/transaction/${id}/void`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Voided!', response.message, 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>
@endpush

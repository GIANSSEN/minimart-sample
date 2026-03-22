@extends('layouts.admin')

@section('title', 'Stock In - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header (Aligned with Reference) -->
    <div class="header-card-white mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="header-icon-box-new bg-primary text-white me-3">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Stock In</h4>
                    <p class="text-muted small mb-0">Record incoming inventory and purchase receipts</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-new-action" data-bs-toggle="modal" data-bs-target="#newStockInModal">
                    <i class="fas fa-plus-circle me-1"></i> New Stock In
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

        <!-- Total Quantity -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-orange-soft text-orange">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">QUANTITY IN</span>
                        <div class="stat-value-new">{{ number_format($transactions->sum('quantity')) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-cubes"></i> units received
                </div>
            </div>
        </div>

        <!-- Value Added -->
        <div class="col-6 col-md-3">
            <div class="stat-card-white p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon-new bg-yellow-soft text-yellow">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <span class="stat-label-new">VALUE ADDED</span>
                        <div class="stat-value-new">₱{{ number_format($transactions->sum('total_cost') ?? 0, 0) }}</div>
                    </div>
                </div>
                <div class="stat-footer-new">
                    <i class="fas fa-coins"></i> inventory value
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

    <!-- The rest of your page (filter, table, modals) remains exactly as you had it -->
    <!-- ... (copy all the code from your original stock-in blade from the "Filters - Perfectly Aligned" section onward) ... -->
    
    <!-- Filters - Perfectly Aligned -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <!-- Filter Header with Export/Reset -->
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-filter text-primary me-2"></i>
                            Filter Stock In
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.inventory.export-history', ['type' => 'in']) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-download me-1"></i>Export
                            </a>
                            <a href="{{ route('admin.inventory.stock-in') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-redo-alt me-1"></i>Reset
                            </a>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.inventory.stock-in') }}" id="filterForm">
                        <div class="row g-2">
                            <!-- Search -->
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Search Product</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Search product..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            
                            <!-- Date From -->
                            <div class="col-6 col-md-2">
                                <label class="form-label small text-muted mb-1">From</label>
                                <input type="date" 
                                       name="date_from" 
                                       class="form-control" 
                                       value="{{ request('date_from') }}"
                                       placeholder="mm/dd/yyyy">
                            </div>
                            
                            <!-- Date To -->
                            <div class="col-6 col-md-2">
                                <label class="form-label small text-muted mb-1">To</label>
                                <input type="date" 
                                       name="date_to" 
                                       class="form-control" 
                                       value="{{ request('date_to') }}"
                                       placeholder="mm/dd/yyyy">
                            </div>
                            
                            <!-- Apply Button -->
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1 d-none d-md-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row mx-0 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 px-3 px-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-list-alt text-primary me-2"></i>
                        Stock In Records
                        <span class="badge bg-secondary ms-2">{{ $transactions->total() }}</span>
                    </h5>
                    <select name="per_page" class="form-select form-select-sm w-auto" onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ request('per_page',15)==15?'selected':'' }}>15 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page')==25?'selected':'' }}>25 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page')==50?'selected':'' }}>50 per page</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page')==100?'selected':'' }}>100 per page</option>
                    </select>
                </div>
                <div class="card-body p-0">
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">DATE & TIME</th>
                                    <th class="py-3">PRODUCT</th>
                                    <th class="py-3">SUPPLIER</th>
                                    <th class="py-3 text-center">QUANTITY</th>
                                    <th class="py-3 text-end">UNIT COST</th>
                                    <th class="py-3 text-end">TOTAL COST</th>
                                    <th class="py-3">TRANSACTION NO.</th>
                                    <th class="py-3">USER</th>
                                    <th class="text-end px-4 py-3">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $transaction)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-semibold">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</div>
                                        <small class="text-muted">{{ $transaction->created_at ? $transaction->created_at->format('h:i A') : '' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-icon me-2">
                                                <div class="bg-primary bg-opacity-10 rounded-2 p-2">
                                                    <i class="fas fa-box text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($transaction->product->product_name ?? 'Deleted Product', 30) }}</div>
                                                <small class="text-muted">{{ $transaction->product->product_code ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success px-3 py-2">
                                            +{{ number_format($transaction->quantity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-medium">{{ $transaction->supplier->supplier_name ?? '—' }}</div>
                                    </td>
                                    <td class="text-end fw-medium">
                                        ₱{{ number_format($transaction->unit_cost ?? 0, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-primary">
                                            ₱{{ number_format(($transaction->quantity * ($transaction->unit_cost ?? 0)), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($transaction->reference)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                {{ $transaction->reference }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-xs me-2">
                                                <span class="avatar-initials rounded-circle bg-info bg-opacity-10 text-info">
                                                    {{ $transaction->user ? substr($transaction->user->name ?? 'S', 0, 1) : 'S' }}
                                                </span>
                                            </div>
                                            <span class="small">{{ $transaction->user->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-info" onclick="viewDetails({{ $transaction->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if ($transaction->created_at->diffInHours(now()) < 24)
                                            <button class="btn btn-sm btn-outline-danger" onclick="voidTransaction({{ $transaction->id }}, '{{ $transaction->product->product_name ?? '' }}', {{ $transaction->quantity }})" title="Void Transaction">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-arrow-down fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Stock In Records</h5>
                                            <p class="text-muted mb-3">Start recording incoming inventory</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newStockInModal">
                                                <i class="fas fa-plus-circle me-2"></i>New Stock In
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-block d-md-none p-3">
                        @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $transaction)
                        <div class="mobile-card mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="product-icon">
                                        <div class="bg-primary bg-opacity-10 rounded-2 p-2">
                                            <i class="fas fa-box text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ Str::limit($transaction->product->product_name ?? 'Deleted Product', 20) }}</div>
                                        <small class="text-muted">{{ $transaction->product->product_code ?? '' }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-success">+{{ number_format($transaction->quantity) }}</span>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Date</span>
                                    <span class="info-value">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Time</span>
                                    <span class="info-value">{{ $transaction->created_at ? $transaction->created_at->format('h:i A') : '' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Unit Cost</span>
                                    <span class="info-value">₱{{ number_format($transaction->unit_cost ?? 0, 2) }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total Cost</span>
                                    <span class="info-value text-primary fw-bold">₱{{ number_format(($transaction->quantity * ($transaction->unit_cost ?? 0)), 2) }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Transaction No.</span>
                                    <span class="info-value">{{ $transaction->reference ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">User</span>
                                    <span class="info-value">{{ $transaction->user->name ?? 'System' }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                                <button class="btn btn-sm btn-outline-info" onclick="viewDetails({{ $transaction->id }})">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                                @if ($transaction->created_at->diffInHours(now()) < 24)
                                <button class="btn btn-sm btn-outline-danger" onclick="voidTransaction({{ $transaction->id }}, '{{ $transaction->product->product_name ?? '' }}', {{ $transaction->quantity }})">
                                    <i class="fas fa-ban me-1"></i>Void
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
@else
                        <div class="text-center py-5">
                            <i class="fas fa-arrow-down fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Stock In Records</h5>
                            <p class="text-muted mb-3">Start recording incoming inventory</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newStockInModal">
                                <i class="fas fa-plus-circle me-2"></i>New Stock In
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if ($transactions->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 px-3 px-md-4 py-3 border-top">
                        <div class="text-muted small text-center text-md-start">
                            Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                        </div>
                        <div class="pagination-responsive">
                            {{ $transactions->withQueryString()->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Stock In Modal -->
<div class="modal fade" id="newStockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
            <!-- Modal Header (Gradient) -->
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-pill p-2 me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-import fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Record New Stock In</h5>
                        <p class="small mb-0 opacity-75">Receive products from suppliers into inventory</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="stockInForm" method="POST" action="{{ route('admin.inventory.process-stock-in') }}">
                @csrf
                <input type="hidden" name="type" value="in">
                
                <div class="modal-body p-4 bg-white">
                    <div class="row g-4">
                        <!-- SECTION: TRANSACTION INFO -->
                        <div class="col-12">
                            <div class="show-modal-section-label">
                                <i class="fas fa-info-circle me-1"></i> Transaction Info
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="supplierSelect">Supplier <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-truck text-muted"></i></span>
                                <select name="supplier_id" class="form-select select2 border-start-0" id="supplierSelect" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="invalid-feedback" id="supplier_idError"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="receivedDate">Received Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="received_date" class="form-control border-start-0" id="receivedDate" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="invalid-feedback" id="received_dateError"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="receivedBy">Received By <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-user text-muted"></i></span>
                                <input type="text" name="received_by" class="form-control border-start-0" id="receivedBy" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="invalid-feedback" id="received_byError"></div>
                        </div>

                        <!-- SECTION: PRODUCT SELECTION -->
                        <div class="col-12">
                            <div class="show-modal-section-label">
                                <i class="fas fa-search me-1"></i> Product Selection
                            </div>
                        </div>
        
                        <div class="col-12">
                            <div class="p-4 rounded-4 border bg-light bg-opacity-50 shadow-sm">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="productSelect">Select Product <span class="text-danger">*</span></label>
                                        <div class="input-group shadow-sm">
                                            <span class="input-group-text bg-white border-end-0 px-3">
                                                <i class="fas fa-box text-primary"></i>
                                            </span>
                                            <select name="product_id" class="form-select select2 border-start-0" id="productSelect" required disabled>
                                                <option value="">-- Choose Supplier First --</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback" id="product_idError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: QUANTITY & COST (CARDS) -->
                        <div class="col-12">
                            <div class="show-modal-section-label">
                                <i class="fas fa-calculator me-1"></i> Quantity & Pricing
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-input-card border rounded-4 p-3 h-100 transition-hover">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1 d-block" for="quantity">Quantity</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                                        <i class="fas fa-cubes"></i>
                                    </div>
                                    <input type="number" name="quantity" class="form-control form-control-lg fw-bold border-0 bg-transparent fs-3 p-0" step="0.01" min="0.01" id="quantity" placeholder="0" required>
                                </div>
                                <div class="invalid-feedback" id="quantityError"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="metric-input-card border rounded-4 p-3 h-100 transition-hover">
                                <label class="form-label fw-bold small text-muted text-uppercase mb-1 d-block" for="unitCost">Unit Cost</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="fw-bold text-muted me-1 fs-5">₱</span>
                                        <input type="number" name="unit_cost" class="form-control form-control-lg fw-bold border-0 bg-transparent fs-3 p-0" step="0.01" min="0" id="unitCost" value="0.00" required>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="unit_costError"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 h-100">
                                <label class="form-label fw-bold small text-primary text-uppercase mb-1">Total Cost</label>
                                <div class="fs-3 fw-bold text-primary" id="finalTotalCost">₱ 0.00</div>
                                <small class="text-primary opacity-75">(Auto-computed)</small>
                            </div>
                        </div>

                        <!-- SECTION: STOCK STATUS -->
                        <div class="col-md-6">
                            <div class="p-3 bg-secondary bg-opacity-10 rounded-4 border border-secondary border-opacity-25 shadow-sm">
                                <label class="form-label fw-bold small text-secondary text-uppercase mb-1">Current Inventory Level</label>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-warehouse text-secondary opacity-50"></i>
                                    <div class="fs-4 fw-bold text-secondary" id="finalCurrentStock">0 units</div>
                                </div>
                                <small class="text-secondary opacity-75">(Before recording)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1">Stock Movement Type</label>
                            <div class="stock-type-group d-flex flex-wrap gap-2 mt-1">
                                <input type="radio" class="btn-check" name="reason" id="typeNew" value="purchase" checked autocomplete="off">
                                <label class="btn btn-outline-success btn-sm rounded-pill px-3" for="typeNew">
                                    <i class="fas fa-shopping-cart me-1"></i> New Purchase
                                </label>

                                <input type="radio" class="btn-check" name="reason" id="typeReturn" value="return" autocomplete="off">
                                <label class="btn btn-outline-warning btn-sm rounded-pill px-3" for="typeReturn">
                                    <i class="fas fa-undo me-1"></i> Return
                                </label>

                                <input type="radio" class="btn-check" name="reason" id="typeAdj" value="adjustment" autocomplete="off">
                                <label class="btn btn-outline-info btn-sm rounded-pill px-3" for="typeAdj">
                                    <i class="fas fa-tools me-1"></i> Adjustment
                                </label>
                            </div>
                            <div class="invalid-feedback" id="reasonError"></div>
                        </div>

                        <!-- SECTION: ADDITIONAL DETAILS -->
                        <div class="col-12">
                            <div class="show-modal-section-label">
                                <i class="fas fa-file-alt me-1"></i> Additional Details
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="reference">Transaction / PO Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                                <input type="text" name="reference" class="form-control border-start-0" id="reference" placeholder="e.g. PO-2024-001">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="location">Warehouse / Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" name="location" class="form-control border-start-0" id="location" placeholder="e.g. Warehouse A, Shelf 1">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" for="notes">Internal Notes</label>
                            <textarea name="notes" class="form-control rounded-3" id="notes" rows="2" placeholder="Describe the stock in reason or any specific details..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 p-4 bg-light">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none px-4" data-bs-dismiss="modal">Discard</button>
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-outline-success fw-bold rounded-pill px-4" id="saveAndNewBtn">
                            <i class="fas fa-plus-circle me-1"></i> Save & New
                        </button>
                        <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm" id="submitBtn">
                            <i class="fas fa-check-circle me-1"></i> Complete Stock In
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Transaction Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    .show-modal-section-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #10b981;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .show-modal-section-label::after {
        content: '';
        flex: 1;
        height: 1.5px;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.25), transparent);
        border-radius: 2px;
    }
    .metric-input-card {
        background: #f0fdf4;
        transition: all 0.2s ease;
    }
    .metric-input-card:focus-within {
        background: #fff;
        border-color: #10b981 !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    /* Select2 Bootstrap 5 Alignment Fixes */
    .input-group .select2-container--default {
        flex: 1 1 auto;
        width: 1% !important;
    }
    .input-group .select2-container--default .select2-selection--single {
        height: 100% !important;
        border: 1px solid #dee2e6 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        display: flex;
        align-items: center;
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }
    .input-group .select2-container--default .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Your original scripts – unchanged -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#productSelect, #supplierSelect').select2({
        dropdownParent: $('#newStockInModal'),
        width: '100%',
        placeholder: {
            id: "",
            placeholder: "-- Select --"
        }
    });

    // Supplier selection change handler
    $('#supplierSelect').on('change', function() {
        const supplierId = $(this).val();
        const productSelect = $('#productSelect');
        
        // Reset product select
        productSelect.empty().append('<option value="">Select Product</option>');
        productSelect.val('').trigger('change');
        
        if (!supplierId) {
            productSelect.prop('disabled', true);
            productSelect.append('<option value="">-- Choose Supplier First --</option>');
            return;
        }
        
        productSelect.prop('disabled', false);
        
        // Fetch products for this supplier
        // Fix: Use dynamic route helper to handle subdirectories correctly
        const url = "{{ route('api.suppliers.products', ['supplier' => ':id']) }}".replace(':id', supplierId);
        
        console.log('Fetching products from:', url);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    console.error('Fetch error response:', response);
                    throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Products data received:', data);
                if (data.success && data.products) {
                    if (data.products.length === 0) {
                        productSelect.append('<option value="">No products found for this supplier</option>');
                    } else {
                        data.products.forEach(product => {
                            const stock = product.stock ? product.stock.quantity : 0;
                            const option = new Option(
                                `${product.product_name} (${product.product_code})`, 
                                product.id, 
                                false, 
                                false
                            );
                            $(option).attr('data-stock', stock);
                            $(option).attr('data-unit', product.unit || 'units');
                            $(option).attr('data-price', product.cost_price || 0);
                            productSelect.append(option);
                        });
                    }
                    productSelect.trigger('change.select2'); // Specific for Select2 update
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Fetch Error',
                    text: 'Unable to load products. Please check connection.'
                });
            });
    });

    // Product selection change handler
    $('#productSelect').on('change', function() {
        const selected = $(this).find('option:selected');
        const stock = selected.data('stock') || 0;
        const price = selected.data('price') || 0;
        const unit = selected.data('unit') || 'units';
        
        $('#finalCurrentStock').text(stock + ' ' + unit);
        $('#unitCost').val(price.toFixed(2));
        
        updateTotalCost();
    });

    // Quantity and unit cost input handlers
    $('#quantity, #unitCost').on('input', function() {
        updateTotalCost();
    });

    // Calculate total cost
    function updateTotalCost() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const unitCost = parseFloat($('#unitCost').val()) || 0;
        const total = quantity * unitCost;
        
        $('#finalTotalCost').text('₱ ' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    // Save & New Button Click
    $('#saveAndNewBtn').on('click', function() {
        submitStockIn(true);
    });

    // Reset form when modal is closed
    $('#newStockInModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    function resetForm() {
        $('#stockInForm')[0].reset();
        $('#productSelect, #supplierSelect').val(null).trigger('change');
        $('#finalCurrentStock').text('0 units');
        $('#finalTotalCost').text('₱ 0.00');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
    }

    // Stock In Form Submission
    $('#stockInForm').on('submit', function(e) {
        e.preventDefault();
        submitStockIn(false);
    });

    function submitStockIn(saveAndNew = false) {
        const form = document.getElementById('stockInForm');
        
        // Basic validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Reset validation states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
        
        const formData = new FormData(form);
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Loading state
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
        submitBtn.prop('disabled', true);
        
        Swal.fire({
            title: 'Processing...',
            text: 'Saving stock in record',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch('{{ route("admin.inventory.process-stock-in") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    if (saveAndNew) {
                        resetForm();
                        $('#supplierSelect').focus();
                    } else {
                        bootstrap.Modal.getInstance(document.getElementById('newStockInModal')).hide();
                        window.location.reload();
                    }
                });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        if (input.length) {
                            input.addClass('is-invalid');
                            const errorDiv = $(`#${key}Error`);
                            if (errorDiv.length) {
                                errorDiv.html(data.errors[key].join('<br>'));
                            } else {
                                // Fallback
                                input.after(`<div class="invalid-feedback">${data.errors[key].join('<br>')}</div>`);
                            }
                        }
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check the form and try again.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Something went wrong.'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Network error occurred. Please try again.'
            });
        })
        .finally(() => {
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        });
    }
});

// View transaction details
function viewDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
    const contentDiv = document.getElementById('transactionDetailsContent');
    
    fetch(`/admin/inventory/transaction/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const t = data.transaction;
                contentDiv.innerHTML = `
                    <div class="p-2">
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Transaction ID</span>
                            <span class="fw-bold text-primary">#${t.id}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Date & Time</small>
                                <span>${new Date(t.created_at).toLocaleString()}</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">Recorded By</small>
                                <span>${t.user?.name || 'System'}</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">Product</small>
                                <span class="fw-bold">${t.product?.product_name || 'N/A'}</span>
                                <div class="small text-muted">${t.product?.product_code || ''}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Quantity</small>
                                <span class="badge bg-success px-2">+${t.quantity}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Unit Cost</small>
                                <span>₱${(t.unit_cost || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="col-4 text-end">
                                <small class="text-muted d-block">Total Cost</small>
                                <span class="fw-bold text-primary">₱${(t.quantity * (t.unit_cost || 0)).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Transaction No.</small>
                                <span>${t.reference || '—'}</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">Location</small>
                                <span>${t.location || '—'}</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                contentDiv.innerHTML = '<p class="text-danger text-center">Failed to load details.</p>';
            }
        })
        .catch(() => {
            contentDiv.innerHTML = '<p class="text-danger text-center">Error loading details.</p>';
        });
    
    modal.show();
}

// Void transaction
function voidTransaction(id, productName, quantity) {
    Swal.fire({
        title: 'Void Transaction?',
        html: `This will remove <strong>${quantity}</strong> units of <strong>${productName || 'this product'}</strong> from inventory.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, void it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            fetch(`/admin/inventory/transaction/${id}/void`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Voided!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error!', data.message || 'Failed to void transaction.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Network error occurred.', 'error');
            });
        }
    });
}
</script>
@endpush        

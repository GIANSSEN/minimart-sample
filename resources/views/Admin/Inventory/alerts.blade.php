@extends('layouts.admin')

@section('title', 'Inventory Alerts - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0"> <!-- FULL WIDTH -->
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box alerts-header-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Inventory Alerts</h1>
                <p class="page-subtitle">Monitor stock levels, expired items, and other inventory issues</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.inventory.export-history') }}" class="btn-header-action btn-header-success">
                <i class="fas fa-download"></i>
                <span>Export</span>
            </a>
        </div>
    </div>

    <!-- Compact Alert Summary -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4">
        <div class="col-6 col-md-3">
            <div class="alert-summary-card alert-summary-warning">
                <div class="alert-summary-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-summary-content">
                    <div class="alert-summary-label">Low Stock</div>
                    <div class="alert-summary-value">{{ number_format($stats['low_stock']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="alert-summary-card alert-summary-danger">
                <div class="alert-summary-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="alert-summary-content">
                    <div class="alert-summary-label">Out of Stock</div>
                    <div class="alert-summary-value">{{ number_format($stats['out_of_stock']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="alert-summary-card alert-summary-expired">
                <div class="alert-summary-icon">
                    <i class="fas fa-skull-crossbones"></i>
                </div>
                <div class="alert-summary-content">
                    <div class="alert-summary-label">Expired</div>
                    <div class="alert-summary-value">{{ number_format($stats['expired']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="alert-summary-card alert-summary-near">
                <div class="alert-summary-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="alert-summary-content">
                    <div class="alert-summary-label">Near Expiry</div>
                    <div class="alert-summary-value">{{ number_format($stats['near_expiry']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Tabs - CENTERED -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <ul class="nav nav-tabs border-0 justify-content-start" id="alertTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $type == 'all' || $type == 'low' ? 'active' : '' }}" 
                                    onclick="window.location.href='{{ route('admin.inventory.alerts', ['type' => 'low']) }}'">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Low Stock 
                                <span class="badge bg-warning ms-2">{{ $stats['low_stock'] }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $type == 'out' ? 'active' : '' }}" 
                                    onclick="window.location.href='{{ route('admin.inventory.alerts', ['type' => 'out']) }}'">
                                <i class="fas fa-times-circle me-2"></i>
                                Out of Stock 
                                <span class="badge bg-danger ms-2">{{ $stats['out_of_stock'] }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $type == 'expired' ? 'active' : '' }}" 
                                    onclick="window.location.href='{{ route('admin.inventory.alerts', ['type' => 'expired']) }}'">
                                <i class="fas fa-skull-crossbones me-2"></i>
                                Expired 
                                <span class="badge bg-secondary ms-2">{{ $stats['expired'] }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $type == 'near' ? 'active' : '' }}" 
                                    onclick="window.location.href='{{ route('admin.inventory.alerts', ['type' => 'near']) }}'">
                                <i class="fas fa-clock me-2"></i>
                                Near Expiry 
                                <span class="badge bg-warning ms-2">{{ $stats['near_expiry'] }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Tab - FULL WIDTH ALIGNED -->
    @if ($type == 'all' || $type == 'low')
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 px-3"> <!-- Reduced padding -->
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Low Stock Items
                        <span class="badge bg-secondary ms-2">{{ $lowStock->total() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <select name="per_page" class="form-select form-select-sm" style="width: 130px;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ (int) request('per_page', 15) === 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ (int) request('per_page') === 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ (int) request('per_page') === 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ (int) request('per_page') === 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3 text-start">Product</th> <!-- Reduced padding -->
                                    <th class="py-3 text-start">Code</th>
                                    <th class="py-3 text-center">Current</th>
                                    <th class="py-3 text-center">Minimum</th>
                                    <th class="py-3 text-start">Last Restocked</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="text-end px-3 py-3">Action</th> <!-- Reduced padding -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($lowStock) ? count($lowStock) > 0 : !empty($lowStock))
@foreach($lowStock as $item)
                                <tr>
                                    <td class="px-3 text-start"> <!-- Reduced padding -->
                                        <div class="d-flex align-items-center">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->product_name }}" 
                                                     class="rounded-3 me-3" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item->product->product_name ?? 'N/A' }}</div>
                                                @if ($item->product && $item->product->brand)
                                                    <small class="text-muted">{{ $item->product->brand }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            {{ $item->product->product_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-warning">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->min_quantity }}</td>
                                    <td class="text-start">
                                        @php
                                            $lastTransaction = $item->product ? $item->product->stockTransactions()->where('type', 'in')->latest()->first() : null;
                                        @endphp
                                        @if ($lastTransaction)
                                            <span title="{{ $lastTransaction->created_at->format('M d, Y h:i A') }}">
                                                {{ $lastTransaction->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
                                        </span>
                                    </td>
                                    <td class="text-end px-3"> <!-- Reduced padding -->
                                        <button class="btn btn-sm btn-primary" onclick="showStockInModal({{ $item->product_id }}, @js($item->product->product_name ?? 'Unknown Product'), {{ (int) ($item->product->supplier_id ?? 0) }})">
                                            <i class="fas fa-plus me-1"></i> Quick Restock
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                            <h5 class="text-muted">No Low Stock Items</h5>
                                            <p class="text-muted mb-0">All products are above minimum stock levels.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 py-3 border-top">
                        {{ $lowStock->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Out of Stock Tab - FULL WIDTH ALIGNED -->
    @if ($type == 'all' || $type == 'out')
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 px-3"> <!-- Reduced padding -->
                    <h5 class="mb-0">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        Out of Stock Items
                        <span class="badge bg-secondary ms-2">{{ $outOfStock->total() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <select name="per_page" class="form-select form-select-sm" style="width: 130px;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ (int) request('per_page', 15) === 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ (int) request('per_page') === 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ (int) request('per_page') === 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ (int) request('per_page') === 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                        <button class="btn btn-sm btn-primary" onclick="bulkRestockOutOfStock()">
                            <i class="fas fa-plus me-1"></i> Restock All
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3 text-start">Product</th> <!-- Reduced padding -->
                                    <th class="py-3 text-start">Code</th>
                                    <th class="py-3 text-center">Last Stock</th>
                                    <th class="py-3 text-start">Last Sold</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="text-end px-3 py-3">Action</th> <!-- Reduced padding -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($outOfStock) ? count($outOfStock) > 0 : !empty($outOfStock))
@foreach($outOfStock as $item)
                                <tr>
                                    <td class="px-3 text-start"> <!-- Reduced padding -->
                                        <div class="d-flex align-items-center">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->product_name }}" 
                                                     class="rounded-3 me-3" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item->product->product_name ?? 'N/A' }}</div>
                                                @if ($item->product && $item->product->brand)
                                                    <small class="text-muted">{{ $item->product->brand }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            {{ $item->product->product_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-danger">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-start">
                                        @php
                                            $lastSale = $item->product ? $item->product->stockTransactions()->where('type', 'out')->where('reason', 'sale')->latest()->first() : null;
                                        @endphp
                                        @if ($lastSale)
                                            <span title="{{ $lastSale->created_at->format('M d, Y h:i A') }}">
                                                {{ $lastSale->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i> Out of Stock
                                        </span>
                                    </td>
                                    <td class="text-end px-3"> <!-- Reduced padding -->
                                        <button class="btn btn-sm btn-primary js-restock-out-item" data-product-id="{{ $item->product_id }}" onclick="showStockInModal({{ $item->product_id }}, @js($item->product->product_name ?? 'Unknown Product'), {{ (int) ($item->product->supplier_id ?? 0) }})">
                                            <i class="fas fa-plus me-1"></i> Quick Restock
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                            <h5 class="text-muted">No Out of Stock Items</h5>
                                            <p class="text-muted mb-0">All products have stock available.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 py-3 border-top">
                        {{ $outOfStock->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Expired Products Tab - FULL WIDTH ALIGNED -->
    @if ($type == 'all' || $type == 'expired')
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 px-3"> <!-- Reduced padding -->
                    <h5 class="mb-0">
                        <i class="fas fa-skull-crossbones text-secondary me-2"></i>
                        Expired Products
                        <span class="badge bg-secondary ms-2">{{ $expiredProducts->total() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <select name="per_page" class="form-select form-select-sm" style="width: 130px;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ (int) request('per_page', 15) === 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ (int) request('per_page') === 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ (int) request('per_page') === 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ (int) request('per_page') === 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                        <button class="btn btn-sm btn-danger" onclick="bulkDispose()">
                            <i class="fas fa-trash me-1"></i> Bulk Dispose
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3 text-start">Product</th> <!-- Reduced padding -->
                                    <th class="py-3 text-start">Code</th>
                                    <th class="py-3 text-start">Expiry Date</th>
                                    <th class="py-3 text-center">Days Expired</th>
                                    <th class="py-3 text-center">Stock</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="text-end px-3 py-3">Action</th> <!-- Reduced padding -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($expiredProducts) ? count($expiredProducts) > 0 : !empty($expiredProducts))
@foreach($expiredProducts as $product)
                                <tr>
                                    <td class="px-3 text-start"> <!-- Reduced padding -->
                                        <div class="d-flex align-items-center">
                                            @if ($product->image)
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" 
                                                     class="rounded-3 me-3" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $product->product_name }}</div>
                                                @if ($product->brand)
                                                    <small class="text-muted">{{ $product->brand }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">{{ $product->product_code }}</td>
                                    <td class="text-start">
                                        <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $days = \Carbon\Carbon::parse($product->expiry_date)->diffInDays(now());
                                        @endphp
                                        <span class="badge bg-danger">{{ $days }} days</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $product->stock->quantity ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            <i class="fas fa-skull-crossbones me-1"></i> Expired
                                        </span>
                                    </td>
                                    <td class="text-end px-3"> <!-- Reduced padding -->
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-outline-danger" onclick="disposeProduct({{ $product->id }}, @js($product->product_name))">
                                                <i class="fas fa-trash me-1"></i> Dispose
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="extendExpiry({{ $product->id }})">
                                                <i class="fas fa-calendar-plus me-1"></i> Extend
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                            <h5 class="text-muted">No Expired Products</h5>
                                            <p class="text-muted mb-0">All products are within expiry date.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 py-3 border-top">
                        {{ $expiredProducts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Near Expiry Tab - FULL WIDTH ALIGNED (YOUR REFERENCE) -->
    @if ($type == 'all' || $type == 'near')
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 px-3"> <!-- Reduced padding -->
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Near Expiry Products
                        <span class="badge bg-secondary ms-2">{{ $nearExpiry->total() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <select name="per_page" class="form-select form-select-sm" style="width: 130px;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" {{ (int) request('per_page', 15) === 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ (int) request('per_page') === 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ (int) request('per_page') === 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ (int) request('per_page') === 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                        <button class="btn btn-sm btn-warning" onclick="bulkPromoteNearExpiry()">
                            <i class="fas fa-tag me-1"></i> Mark for Sale
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3 text-start">PRODUCT</th> <!-- Reduced padding -->
                                    <th class="py-3 text-start">CODE</th>
                                    <th class="py-3 text-start">EXPIRY DATE</th>
                                    <th class="py-3 text-center">DAYS LEFT</th>
                                    <th class="py-3 text-center">STOCK</th>
                                    <th class="py-3 text-center">STATUS</th>
                                    <th class="text-end px-3 py-3">ACTION</th> <!-- Reduced padding -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($nearExpiry) ? count($nearExpiry) > 0 : !empty($nearExpiry))
@foreach($nearExpiry as $product)
                                <tr>
                                    <td class="px-3 text-start"> <!-- Reduced padding -->
                                        <div class="d-flex align-items-center">
                                            @if ($product->image)
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" 
                                                     class="rounded-3 me-3" width="48" height="48" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $product->product_name }}</div>
                                                @if ($product->brand)
                                                    <small class="text-muted">{{ $product->brand }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">{{ $product->product_code }}</td>
                                    <td class="text-start">
                                        <span class="text-warning fw-bold">{{ \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->expiry_date), false);
                                            $days = max(0, $days);
                                        @endphp
                                        <span class="badge bg-warning">{{ $days }} days</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $product->stock->quantity ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                            <i class="fas fa-clock me-1"></i> Near Expiry
                                        </span>
                                    </td>
                                    <td class="text-end px-3"> <!-- Reduced padding -->
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-sm btn-outline-warning" onclick="promoteProduct({{ $product->id }})">
                                                <i class="fas fa-tag me-1"></i> Promote
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="disposeProduct({{ $product->id }}, @js($product->product_name))">
                                                <i class="fas fa-trash me-1"></i> Dispose
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                            <h5 class="fw-semibold">No Near Expiry Products</h5>
                                            <p class="text-muted mb-0">All products have sufficient shelf life.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 py-3 border-top">
                        {{ $nearExpiry->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-arrow-down me-2"></i>Quick Stock In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockInForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="stock_product_id">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Product</label>
                            <input type="text" class="form-control bg-light" id="stock_product_name" readonly>
                            <div class="small text-muted mt-1">Current Stock: <span id="stock_current_qty">0</span></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select" id="stock_supplier_id" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="supplier_idError"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Received Date <span class="text-danger">*</span></label>
                            <input type="date" name="received_date" class="form-control" id="stock_received_date" value="{{ now()->format('Y-m-d') }}" required>
                            <div class="invalid-feedback" id="received_dateError"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Received By <span class="text-danger">*</span></label>
                            <input type="text" name="received_by" class="form-control" id="stock_received_by" value="{{ auth()->user()->name ?? '' }}" required>
                            <div class="invalid-feedback" id="received_byError"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" id="stock_quantity" step="0.01" min="0.01" required>
                            <div class="invalid-feedback" id="quantityError"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Unit Cost <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">&#8369;</span>
                                <input type="number" name="unit_cost" class="form-control" id="stock_unit_cost" step="0.01" min="0" required>
                            </div>
                            <div class="invalid-feedback" id="unit_costError"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Reference</label>
                            <input type="text" name="reference" class="form-control" placeholder="PO-12345">
                            <div class="invalid-feedback" id="referenceError"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="stock_reason_purchase" value="purchase" checked>
                                    <label class="form-check-label" for="stock_reason_purchase">Purchase</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="stock_reason_return" value="return">
                                    <label class="form-check-label" for="stock_reason_return">Return</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="stock_reason_adjustment" value="adjustment">
                                    <label class="form-check-label" for="stock_reason_adjustment">Adjustment</label>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block" id="reasonError"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" name="location" class="form-control" id="stock_location" placeholder="Warehouse A">
                            <div class="invalid-feedback" id="locationError"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expiry Date (Optional)</label>
                            <input type="date" name="expiry_date" class="form-control" id="stock_expiry_date">
                            <div class="invalid-feedback" id="expiry_dateError"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            <div class="invalid-feedback" id="notesError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="stock_submit_btn">
                        <i class="fas fa-check-circle me-1"></i> Record Stock In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extend Expiry Modal -->
<div class="modal fade" id="extendExpiryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Extend Expiry Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="extendExpiryForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="extend_product_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product</label>
                        <input type="text" class="form-control bg-light" id="extend_product_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Expiry Date</label>
                        <input type="text" class="form-control bg-light" id="current_expiry_date" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="new_expiry_date" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason</label>
                        <textarea name="reason" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Extend Expiry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* FULL WIDTH FIXES */
    .container-fluid.px-0 {
        width: 100%;
        max-width: 100%;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .row.mx-0 {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .page-header-premium {
        margin: 0 1rem 1rem 1rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .page-header-premium .page-title {
        font-size: 1.45rem;
    }

    .page-header-premium .page-subtitle {
        font-size: 0.9rem;
    }
    
    /* Consistent padding for all columns */
    [class*="col-"].px-2 {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    /* Compact Summary Cards */
    .alert-summary-card {
        display: flex;
        align-items: center;
        gap: 0.95rem;
        min-height: 116px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1rem 1.1rem;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
        transition: all 0.22s ease;
    }

    .alert-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.09);
        border-color: #cbd5e1;
    }

    .alert-summary-icon {
        width: 66px;
        height: 66px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.55rem;
        color: #fff;
        flex-shrink: 0;
    }

    .alert-summary-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #475569;
        font-weight: 700;
        line-height: 1.2;
    }

    .alert-summary-value {
        font-size: 2.15rem;
        line-height: 1;
        font-weight: 800;
        margin-top: 0.25rem;
        color: #0f172a;
    }

    .alert-summary-warning .alert-summary-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #fb7185 100%);
    }
    .alert-summary-warning .alert-summary-value {
        color: #f59e0b;
    }
    .alert-summary-danger .alert-summary-icon {
        background: linear-gradient(135deg, #ef4444 0%, #fb7185 100%);
    }
    .alert-summary-danger .alert-summary-value {
        color: #dc2626;
    }
    .alert-summary-expired .alert-summary-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .alert-summary-expired .alert-summary-value {
        color: #6366f1;
    }
    .alert-summary-near .alert-summary-icon {
        background: linear-gradient(135deg, #22d3ee 0%, #3b82f6 100%);
    }
    .alert-summary-near .alert-summary-value {
        color: #0284c7;
    }

    /* MODERN HEADER */
    .modern-header {
        background: white;
        border-bottom: 2px solid #dc3545;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: min(300px, 30vw);
        height: min(300px, 30vw);
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }

    .header-icon {
        width: clamp(55px, 6vw, 65px);
        height: clamp(55px, 6vw, 65px);
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(1.8rem, 4vw, 2.2rem);
        box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
        flex-shrink: 0;
        z-index: 2;
    }

    /* STAT CARDS - SMALLER AND BETTER ALIGNED */
    .stat-card {
        border-radius: 16px;
        padding: 1.25rem 1rem;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 100px;
        width: 100%;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .stat-card .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
        height: 100%;
    }

    .stat-card .stat-icon {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(5px);
        flex-shrink: 0;
    }

    .stat-card .stat-details {
        flex: 1;
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        display: block;
        margin-bottom: 2px;
    }

    .stat-card .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        display: block;
        margin-bottom: 2px;
    }

    .stat-card .stat-trend {
        font-size: 0.7rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }

    /* TABS */
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        gap: 0.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }

    .nav-tabs .nav-link:hover {
        background: #f8f9fa;
        color: #dc3545;
    }

    .nav-tabs .nav-link.active {
        background: transparent;
        color: #dc3545;
        border-bottom: 2px solid #dc3545;
    }

    /* TABLE STYLES - ALIGNMENT FIXED */
    .card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        color: #495056;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ecef;
        white-space: nowrap;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1rem;
        border-bottom: 1px solid #f1f3f5;
        font-size: 0.95rem;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* TEXT ALIGNMENT CLASSES */
    .text-start {
        text-align: left !important;
    }
    
    .text-center {
        text-align: center !important;
    }
    
    .text-end {
        text-align: right !important;
    }

    /* BADGES */
    .badge {
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        font-size: 0.85rem;
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header-premium {
            margin-left: 0.75rem;
            margin-right: 0.75rem;
        }

        .alert-summary-card {
            min-height: 102px;
            padding: 0.85rem 0.9rem;
            border-radius: 15px;
        }

        .alert-summary-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            font-size: 1.35rem;
        }

        .alert-summary-value {
            font-size: 1.75rem;
        }

        .modern-header {
            padding: 15px !important;
        }
        
        .header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .stat-card {
            min-height: 90px;
            padding: 1rem;
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.3rem;
            border-radius: 10px;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
        
        .stat-card .stat-label {
            font-size: 0.75rem;
        }
        
        .stat-card .stat-trend {
            font-size: 0.65rem;
        }

        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 15px;
            padding: 15px;
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 8px 10px;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #495057;
            margin-right: 10px;
            width: 40%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const endpoints = {
        processStockIn: '{{ route("admin.inventory.process-stock-in") }}',
        restock: '{{ route("admin.inventory.alerts.restock") }}',
        disposeTemplate: '{{ route("admin.inventory.alerts.dispose", ["product" => "__PRODUCT__"]) }}',
        bulkDispose: '{{ route("admin.inventory.alerts.dispose-expired") }}',
        promoteTemplate: '{{ route("admin.inventory.alerts.promote", ["product" => "__PRODUCT__"]) }}',
        bulkPromote: '{{ route("admin.inventory.alerts.promote-near-expiry") }}',
        productStockTemplate: '{{ route("admin.inventory.product-stock", ["id" => "__PRODUCT__"]) }}',
        extendExpiryTemplate: '{{ route("admin.products.extend-expiry", ["product" => "__PRODUCT__"]) }}'
    };

    function endpoint(template, id) {
        return template.replace('__PRODUCT__', String(id));
    }

    function getErrorMessage(err) {
        if (err && err.data && err.data.errors) {
            const firstField = Object.keys(err.data.errors)[0];
            if (firstField && err.data.errors[firstField].length) {
                return err.data.errors[firstField][0];
            }
        }

        if (err && err.message) {
            return err.message;
        }

        return 'Something went wrong.';
    }

    async function requestJson(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                ...(options.headers || {})
            }
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok || data.success === false) {
            const error = new Error(data.message || 'Request failed.');
            error.data = data;
            throw error;
        }

        return data;
    }

    function showLoading(title = 'Processing...') {
        Swal.fire({
            title,
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    }

    function clearStockInValidation() {
        document.querySelectorAll('#stockInForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#stockInForm .invalid-feedback').forEach(el => {
            if (el.id) {
                el.textContent = '';
            }
        });
    }

    async function showStockInModal(id, name, supplierId = 0) {
        const form = document.getElementById('stockInForm');
        if (form) {
            form.reset();
        }
        clearStockInValidation();

        document.getElementById('stock_product_id').value = id;
        document.getElementById('stock_product_name').value = name;
        document.getElementById('stock_received_date').value = new Date().toISOString().split('T')[0];
        document.getElementById('stock_received_by').value = @js(auth()->user()->name ?? '');
        document.getElementById('stock_current_qty').textContent = '0';

        if (supplierId && Number(supplierId) > 0) {
            document.getElementById('stock_supplier_id').value = String(supplierId);
        }

        try {
            const data = await requestJson(endpoint(endpoints.productStockTemplate, id));
            document.getElementById('stock_current_qty').textContent = `${data.current_stock ?? 0} units`;
            document.getElementById('stock_unit_cost').value = data.cost_price ?? '';
            document.getElementById('stock_location').value = data.location ?? '';
        } catch (err) {
            Swal.fire('Warning', 'Unable to load product stock details. You can still proceed manually.', 'warning');
        }

        new bootstrap.Modal(document.getElementById('stockInModal')).show();
    }

    async function disposeProduct(id, name) {
        const result = await Swal.fire({
            title: 'Dispose Product?',
            text: `Remove ${name} from inventory?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, dispose'
        });

        if (!result.isConfirmed) return;

        try {
            showLoading('Disposing product...');
            await requestJson(endpoint(endpoints.disposeTemplate, id), {
                method: 'POST',
                body: new FormData()
            });

            await Swal.fire({
                icon: 'success',
                title: 'Disposed',
                text: `${name} has been disposed.`,
                timer: 1800,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    async function promoteProduct(id) {
        const result = await Swal.fire({
            title: 'Mark for Sale',
            text: 'Set discount percent for this near-expiry product.',
            input: 'number',
            inputValue: 10,
            inputAttributes: {
                min: 0,
                max: 100,
                step: 0.01
            },
            showCancelButton: true,
            confirmButtonText: 'Apply Discount',
            preConfirm: (value) => {
                if (value === '' || value === null) {
                    return 10;
                }
                const parsed = Number(value);
                if (Number.isNaN(parsed) || parsed < 0 || parsed > 100) {
                    Swal.showValidationMessage('Discount must be between 0 and 100.');
                    return false;
                }
                return parsed;
            }
        });

        if (!result.isConfirmed) return;

        try {
            const formData = new FormData();
            formData.append('discount_percent', result.value);

            showLoading('Applying discount...');
            await requestJson(endpoint(endpoints.promoteTemplate, id), {
                method: 'POST',
                body: formData
            });

            await Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: 'Product marked for sale.',
                timer: 1600,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    async function extendExpiry(id) {
        try {
            const productData = await requestJson(endpoint(endpoints.productStockTemplate, id));

            document.getElementById('extend_product_id').value = id;
            document.getElementById('extend_product_name').value = productData.product_name || 'N/A';
            document.getElementById('current_expiry_date').value = productData.expiry_date || 'No expiry set';

            const dateField = document.querySelector('#extendExpiryForm input[name="new_expiry_date"]');
            if (dateField) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                dateField.min = tomorrow.toISOString().split('T')[0];
            }

            new bootstrap.Modal(document.getElementById('extendExpiryModal')).show();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    async function bulkRestockOutOfStock() {
        const ids = Array.from(document.querySelectorAll('button.js-restock-out-item'))
            .map((btn) => Number(btn.dataset.productId))
            .filter((id) => Number.isInteger(id) && id > 0);

        if (!ids.length) {
            Swal.fire('Info', 'No out of stock items found on this page.', 'info');
            return;
        }

        const inputResult = await Swal.fire({
            title: 'Bulk Restock',
            html: `
                <div class="text-start">
                    <label class="form-label mb-1">Quantity per item</label>
                    <input id="bulkQty" type="number" class="swal2-input" min="1" step="1" value="10" placeholder="Quantity">
                    <label class="form-label mb-1 mt-2">Unit Cost (optional)</label>
                    <input id="bulkCost" type="number" class="swal2-input" min="0" step="0.01" placeholder="Unit cost">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Apply',
            preConfirm: () => {
                const qty = Number(document.getElementById('bulkQty').value);
                const costRaw = document.getElementById('bulkCost').value;
                const cost = costRaw === '' ? null : Number(costRaw);

                if (!Number.isInteger(qty) || qty < 1) {
                    Swal.showValidationMessage('Quantity must be at least 1.');
                    return false;
                }
                if (cost !== null && (Number.isNaN(cost) || cost < 0)) {
                    Swal.showValidationMessage('Unit cost must be 0 or greater.');
                    return false;
                }

                return { qty, cost };
            }
        });

        if (!inputResult.isConfirmed) return;

        try {
            showLoading('Restocking selected items...');
            const { qty, cost } = inputResult.value;
            let updated = 0;

            for (const id of ids) {
                const formData = new FormData();
                formData.append('product_id', String(id));
                formData.append('quantity', String(qty));
                if (cost !== null) {
                    formData.append('unit_cost', String(cost));
                }
                formData.append('reference', `ALERT-BULK-RESTOCK-${new Date().toISOString().slice(0, 10)}`);
                formData.append('notes', 'Bulk restock from inventory alerts');

                await requestJson(endpoints.restock, {
                    method: 'POST',
                    body: formData
                });
                updated++;
            }

            await Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `${updated} item(s) restocked successfully.`,
                timer: 1800,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    async function bulkDispose() {
        const result = await Swal.fire({
            title: 'Bulk Dispose',
            text: 'Dispose all expired products with available stock?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, dispose all'
        });

        if (!result.isConfirmed) return;

        try {
            showLoading('Disposing expired items...');
            const data = await requestJson(endpoints.bulkDispose, {
                method: 'POST',
                body: new FormData()
            });

            await Swal.fire({
                icon: 'success',
                title: 'Completed',
                text: data.message || 'Expired items disposed successfully.',
                timer: 1800,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    async function bulkPromoteNearExpiry() {
        const result = await Swal.fire({
            title: 'Mark Near Expiry For Sale',
            text: 'Apply discount to all near-expiry products.',
            input: 'number',
            inputValue: 10,
            inputAttributes: {
                min: 0,
                max: 100,
                step: 0.01
            },
            showCancelButton: true,
            confirmButtonText: 'Apply',
            preConfirm: (value) => {
                const parsed = Number(value);
                if (Number.isNaN(parsed) || parsed < 0 || parsed > 100) {
                    Swal.showValidationMessage('Discount must be between 0 and 100.');
                    return false;
                }
                return parsed;
            }
        });

        if (!result.isConfirmed) return;

        try {
            const formData = new FormData();
            formData.append('discount_percent', String(result.value));

            showLoading('Updating near-expiry items...');
            const data = await requestJson(endpoints.bulkPromote, {
                method: 'POST',
                body: formData
            });

            await Swal.fire({
                icon: 'success',
                title: 'Completed',
                text: data.message || 'Near-expiry items marked for sale.',
                timer: 1800,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    }

    document.getElementById('stockInForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        clearStockInValidation();

        const submitBtn = document.getElementById('stock_submit_btn');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : null;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        }

        const formData = new FormData(form);

        try {
            showLoading('Saving stock in...');
            await requestJson(endpoints.processStockIn, {
                method: 'POST',
                body: formData
            });

            await Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Stock in recorded successfully.',
                timer: 1700,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            if (err && err.data && err.data.errors) {
                Object.keys(err.data.errors).forEach((key) => {
                    const input = document.querySelector(`#stockInForm [name="${key}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                    }

                    const errorEl = document.getElementById(`${key}Error`);
                    if (errorEl) {
                        errorEl.innerHTML = err.data.errors[key].join('<br>');
                    }
                });

                Swal.fire('Validation Error', 'Please check the required fields and try again.', 'error');
            } else {
                Swal.fire('Error', getErrorMessage(err), 'error');
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml || 'Record Stock In';
            }
        }
    });

    document.getElementById('extendExpiryForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const productId = formData.get('product_id');
        const newExpiry = formData.get('new_expiry_date');

        if (!productId) {
            Swal.fire('Error', 'Missing product id.', 'error');
            return;
        }
        if (!newExpiry) {
            Swal.fire('Error', 'Please select a new expiry date.', 'error');
            return;
        }

        try {
            showLoading('Updating expiry date...');
            await requestJson(endpoint(endpoints.extendExpiryTemplate, productId), {
                method: 'POST',
                body: formData
            });

            await Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Expiry date updated successfully.',
                timer: 1700,
                showConfirmButton: false
            });
            window.location.reload();
        } catch (err) {
            Swal.fire('Error', getErrorMessage(err), 'error');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.table').forEach(table => {
            const headers = [];
            table.querySelectorAll('thead th').forEach(th => {
                headers.push(th.textContent.trim());
            });

            table.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach((td, index) => {
                    if (headers[index]) {
                        td.setAttribute('data-label', headers[index]);
                    }
                });
            });
        });
    });
</script>
@endpush

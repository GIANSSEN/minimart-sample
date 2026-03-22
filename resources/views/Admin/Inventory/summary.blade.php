@extends('layouts.admin')

@section('title', 'Inventory Summary - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header (same style as Activity Logs) -->
    <div class="row mx-0 mb-4">
        <div class="col-12 px-0">
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box summary-header-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Inventory Summary</h1>
                <p class="page-subtitle">Overview of your inventory value and distribution</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.reports.inventory') }}" class="btn-header-action btn-header-primary">
                <i class="fas fa-file-invoice"></i>
                <span class="d-none d-sm-inline">Detailed Report</span>
            </a>
        </div>
    </div>
        </div>
    </div>

    <!-- Stats Cards (Gradient style from Activity Logs) using inventory data -->
    <div class="row mx-0 g-2 g-md-3 mb-4 px-3 px-md-4">
        <!-- Total Products -->
        <div class="col-6 col-md-3 d-flex">
            <div class="stat-card flex-fill" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-label">Total Products</span>
                        <span class="stat-value">{{ number_format($summary['total_products']) }}</span>
                    </div>
                </div>
                <div class="stat-footer small">
                    <i class="fas fa-box me-1"></i>Unique products
                </div>
            </div>
        </div>

        <!-- Total Stock -->
        <div class="col-6 col-md-3 d-flex">
            <div class="stat-card flex-fill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-label">Total Stock</span>
                        <span class="stat-value">{{ formatNumber($summary['total_stock']) }}</span>
                    </div>
                </div>
                <div class="stat-footer small">
                    <i class="fas fa-cube me-1"></i>Units in stock
                </div>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="col-6 col-md-3 d-flex">
            <div class="stat-card flex-fill" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-label">Inventory Value</span>
                        <span class="stat-value">₱{{ formatNumber($summary['total_value']) }}</span>
                    </div>
                </div>
                <div class="stat-footer small">
                    <i class="fas fa-tag me-1"></i>Selling price
                </div>
            </div>
        </div>

        <!-- Total Cost -->
        <div class="col-6 col-md-3 d-flex">
            <div class="stat-card flex-fill" style="background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-label">Total Cost</span>
                        <span class="stat-value">₱{{ formatNumber($summary['total_cost']) }}</span>
                    </div>
                </div>
                <div class="stat-footer small">
                    <i class="fas fa-chart-line me-1"></i>Purchase cost
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Margin Card (using inventory data) -->
    <div class="row mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="row align-items-center gy-3">
                        <div class="col-md-6">
                            <h5 class="mb-2 fw-semibold">
                                <i class="fas fa-chart-pie text-primary me-2"></i>Inventory Profit Margin
                            </h5>
                            <p class="text-muted mb-0 small">Projected profit if all stock sells at current selling price</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-wrap justify-content-md-end align-items-center gap-4">
                                <div>
                                    <span class="text-muted d-block small">Potential Profit</span>
                                    @php
                                        $potentialProfit = $summary['total_value'] - $summary['total_cost'];
                                        $profitClass = $potentialProfit >= 0 ? 'text-success' : 'text-danger';
                                    @endphp
                                    <span class="h3 fw-bold {{ $profitClass }}">
                                        ₱{{ formatNumber($potentialProfit) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-muted d-block small">Margin</span>
                                    @php
                                        $margin = $summary['total_value'] > 0 
                                            ? ($potentialProfit / $summary['total_value']) * 100 
                                            : 0;
                                        $marginClass = $margin >= 30 ? 'success' : ($margin >= 20 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="h4 fw-bold text-{{ $marginClass }}">
                                        {{ number_format($margin, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 12px;">
                        <div class="progress-bar bg-{{ $marginClass }}" role="progressbar" 
                             style="width: {{ min($margin, 100) }}%;" 
                             aria-valuenow="{{ $margin }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mx-0 mb-4 px-3 px-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('admin.inventory.summary') }}">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <select name="category" class="form-select form-select-enhanced">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select name="supplier" class="form-select form-select-enhanced">
                                    <option value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-gradient-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category & Supplier Tables -->
    <div class="row g-4 mb-4 px-3 px-md-4">
        <!-- By Category -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-soft rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-folder text-primary me-2"></i>Inventory by Category
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="py-3 text-end">Products</th>
                                    <th class="py-3 text-end">Total Stock</th>
                                    <th class="py-3 text-end px-4">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($summary['by_category']) ? count($summary['by_category']) > 0 : !empty($summary['by_category']))
@foreach($summary['by_category'] as $item)
                                <tr>
                                    <td class="px-4 fw-medium">{{ $item->category->category_name ?? 'Uncategorized' }}</td>
                                    <td class="text-end">{{ number_format($item->count) }}</td>
                                    <td class="text-end">{{ number_format($item->total_stock) }}</td>
                                    <td class="text-end px-4 fw-semibold text-primary">
                                        @php
                                            $value = \App\Models\Stock::join('products', 'stocks.product_id', '=', 'products.id')
                                                ->where('products.category_id', $item->category_id)
                                                ->sum(\DB::raw('stocks.quantity * products.selling_price'));
                                        @endphp
                                        ₱{{ number_format($value, 2) }}
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="4" class="text-center py-4">No category data available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- By Supplier -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-soft rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-truck text-primary me-2"></i>Inventory by Supplier
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Supplier</th>
                                    <th class="py-3 text-end">Products</th>
                                    <th class="py-3 text-end">Total Stock</th>
                                    <th class="py-3 text-end px-4">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(is_countable($summary['by_supplier']) ? count($summary['by_supplier']) > 0 : !empty($summary['by_supplier']))
@foreach($summary['by_supplier'] as $item)
                                <tr>
                                    <td class="px-4 fw-medium">{{ $item->supplier->supplier_name ?? 'No Supplier' }}</td>
                                    <td class="text-end">{{ number_format($item->count) }}</td>
                                    <td class="text-end">{{ number_format($item->total_stock) }}</td>
                                    <td class="text-end px-4 fw-semibold text-primary">
                                        @php
                                            $value = \App\Models\Stock::join('products', 'stocks.product_id', '=', 'products.id')
                                                ->where('products.supplier_id', $item->supplier_id)
                                                ->sum(\DB::raw('stocks.quantity * products.selling_price'));
                                        @endphp
                                        ₱{{ number_format($value, 2) }}
                                    </td>
                                </tr>
                                @endforeach
@else
                                <tr>
                                    <td colspan="4" class="text-center py-4">No supplier data available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Product List -->
    <div class="card border-0 shadow-soft rounded-4 mx-3 mx-md-4 mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-clipboard-list text-primary me-2"></i>Product Inventory Details
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="py-3">Code</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Supplier</th>
                            <th class="py-3 text-end">Stock</th>
                            <th class="py-3 text-end">Cost</th>
                            <th class="py-3 text-end">Selling</th>
                            <th class="py-3 text-end px-4">Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_countable($products) ? count($products) > 0 : !empty($products))
@foreach($products as $product)
                        <tr>
                            <td class="px-4 fw-medium">{{ $product->product_name }}</td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                            <td>{{ $product->supplier->supplier_name ?? 'N/A' }}</td>
                            <td class="text-end">
                                <span class="fw-bold">{{ $product->stock->quantity ?? 0 }}</span>
                                <small class="text-muted ms-1">{{ $product->unit }}</small>
                            </td>
                            <td class="text-end">₱{{ number_format($product->cost_price, 2) }}</td>
                            <td class="text-end">₱{{ number_format($product->selling_price, 2) }}</td>
                            <td class="text-end px-4 fw-bold text-primary">
                                ₱{{ number_format(($product->stock->quantity ?? 0) * $product->selling_price, 2) }}
                            </td>
                        </tr>
                        @endforeach
@else
                        <tr>
                            <td colspan="8" class="text-center py-4">No products found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-top gap-2">
                <div class="text-muted small">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                </div>
                <div>
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- CSRF token (if needed for any AJAX) -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@php
    // Helper function to format large numbers with abbreviation
    function formatNumber($number) {
        $number = (float) $number;
        if ($number >= 1e9) {
            return number_format($number / 1e9, 1) . 'B';
        }
        if ($number >= 1e6) {
            return number_format($number / 1e6, 1) . 'M';
        }
        if ($number >= 1e3) {
            return number_format($number / 1e3, 1) . 'K';
        }
        return number_format($number);
    }
@endphp

@push('styles')
<style>
    /* ===== ACTIVITY LOGS STYLE ADAPTATION ===== */
    :root {
        --purple-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --green-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --pink-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --teal-gradient: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);
    }

    .modern-header {
        background: white;
        border-bottom: 2px solid #28a745;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.02);
    }
    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 250px;
        height: 250px;
        background: var(--green-gradient);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
    }
    .header-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 8px 15px rgba(17, 153, 142, 0.25);
        flex-shrink: 0;
    }

    .stat-card {
        border-radius: 20px;
        padding: 1.5rem 1.2rem;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }
    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    .stat-icon {
        width: 55px;
        height: 55px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        backdrop-filter: blur(5px);
    }
    .stat-details {
        flex: 1;
        min-width: 0; /* Prevent overflow */
    }
    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        display: block;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.2;
        word-break: break-word;
        white-space: normal; /* Allow wrapping if needed */
    }
    .stat-footer {
        margin-top: 0.5rem;
        font-size: 0.7rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .form-select-enhanced {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.5rem 1rem;
    }
    .form-select-enhanced:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .btn-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        color: white;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .btn-gradient-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 2px;
    }
    .table tbody tr:hover {
        background-color: rgba(102,126,234,0.03);
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 30px;
        overflow: hidden;
    }
    .progress-bar {
        border-radius: 30px;
        transition: width 0.6s ease;
    }

    .pagination {
        gap: 0.3rem;
    }
    .page-link {
        border: none;
        border-radius: 8px !important;
        padding: 0.4rem 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    .page-link:hover {
        background: var(--green-gradient);
        color: white;
    }
    .page-item.active .page-link {
        background: var(--green-gradient);
        color: white;
    }

    @media (max-width: 768px) {
        .modern-header {
            padding: 1.2rem;
        }
        .header-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }
        .stat-card {
            padding: 1.2rem;
        }
        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }
        .stat-value {
            font-size: 1.5rem;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 1rem;
            padding: 1rem;
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem 0;
        }
        .table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

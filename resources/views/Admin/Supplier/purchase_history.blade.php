@extends('layouts.admin')

@section('title', 'Purchase History - CJ\'s Minimart')

@push('styles')
<style>
/* Modern Minimalist Purchase History */
.purchase-history-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.stat-card-premium {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #edf2f7;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}

.stat-card-premium:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border-color: #e2e8f0;
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info .stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

.stat-info .stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Filter Section */
.filter-card-premium {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #edf2f7;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}

.search-input-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.search-input-group:focus-within {
    background: #fff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.search-input-group input {
    background: transparent;
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.95rem;
    color: #1e293b;
}

.filter-select {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.95rem;
    color: #1e293b;
    outline: none;
    transition: all 0.2s;
}

.filter-select:focus {
    border-color: #3b82f6;
    background: #fff;
}

/* Table Enhancements */
.content-card-premium {
    background: #fff;
    border-radius: 24px;
    border: 1px solid #edf2f7;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

.premium-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.premium-table thead th {
    background: #f8fafc;
    padding: 1.25rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #edf2f7;
}

.premium-table tbody tr {
    transition: all 0.2s;
}

.premium-table tbody tr:hover {
    background: #f8fafc;
}

.premium-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.product-info {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}

.product-code {
    font-size: 0.75rem;
    color: #94a3b8;
    font-family: monospace;
}

.supplier-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-badge {
    color: #10b981;
    font-weight: 800;
    font-size: 1rem;
}

.cost-text {
    font-weight: 600;
    color: #475569;
}

.total-text {
    font-weight: 800;
    color: #1e293b;
}

.processed-by {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.user-name {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
}

.user-id {
    font-size: 0.7rem;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .premium-table thead { display: none; }
    .premium-table tbody tr { display: block; padding: 1rem; border: 1px solid #edf2f7; border-radius: 16px; margin-bottom: 1rem; }
    .premium-table td { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border: none; }
    .premium-table td::before { content: attr(data-label); font-weight: 700; font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; }
    .processed-by { align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="purchase-history-page">
    {{-- Premium Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-history"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Purchase History</h1>
                <p class="page-subtitle">Detailed log of all inventory stock-in transactions</p>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.reload()" class="btn-header-action btn-header-light">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-header-action btn-header-secondary">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($totalPurchases) }}</span>
                <span class="stat-label">Total Transactions</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($totalItemsReceived) }}</span>
                <span class="stat-label">Items Received</span>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">₱{{ number_format($totalValue, 2) }}</span>
                <span class="stat-label">Total Investment</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-card-premium">
        <form method="GET" action="{{ route('admin.purchase-history.index') }}" class="row g-3">
            <div class="col-lg-5 col-md-12">
                <div class="search-input-group">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" name="search" placeholder="Search product or supplier name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <select name="supplier_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->supplier_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold" style="background: #3b82f6; border: none;">
                    <i class="fas fa-filter me-2 text-white"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="content-card-premium">
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Product Details</th>
                        <th>Supplier</th>
                        <th>Qty</th>
                        <th>Unit Cost</th>
                        <th>Total Amount</th>
                        <th class="text-end pe-4">Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($transactions) ? count($transactions) > 0 : !empty($transactions))
@foreach($transactions as $transaction)
                    <tr>
                        <td class="ps-4" data-label="Date">
                            <div class="fw-bold text-dark">{{ $transaction->created_at->format('M d, Y') }}</div>
                            <div class="small text-muted">{{ $transaction->created_at->format('h:i A') }}</div>
                        </td>
                        <td data-label="Product">
                            <div class="product-info">
                                <span class="product-name">{{ $transaction->product->product_name ?? 'N/A' }}</span>
                                <span class="product-code">{{ $transaction->product->product_code ?? '-' }}</span>
                            </div>
                        </td>
                        <td data-label="Supplier">
                            <div class="supplier-badge">
                                <i class="fas fa-building"></i>
                                {{ $transaction->product->supplier->supplier_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td data-label="Quantity">
                            <span class="quantity-badge">+{{ number_format($transaction->quantity) }}</span>
                        </td>
                        <td data-label="Cost">
                            <span class="cost-text">₱{{ number_format($transaction->unit_cost ?? 0, 2) }}</span>
                        </td>
                        <td data-label="Total">
                            <span class="total-text">₱{{ number_format($transaction->quantity * ($transaction->unit_cost ?? 0), 2) }}</span>
                        </td>
                        <td class="text-end pe-4" data-label="Processed By">
                            <div class="processed-by">
                                <span class="user-name">{{ $transaction->user->full_name ?? $transaction->user->username ?? 'System' }}</span>
                                <span class="user-id">#{{ $transaction->user_id }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="opacity-50">
                                <i class="fas fa-box-open fa-4x mb-3 text-muted"></i>
                                <h3 class="text-muted fw-bold">No Records Found</h3>
                                <p>Try adjusting your search filters</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
        <div class="p-4 border-top">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

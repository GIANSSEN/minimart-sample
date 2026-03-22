@extends('layouts.admin')

@section('title', 'Sales Management')

@section('content')
<div class="container-fluid">
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Sales Management</h1>
                <p class="page-subtitle">Monitor sales, voids, and refunds</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('supervisor.sales.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cashier</label>
                    <select name="cashier_id" class="form-select">
                        <option value="">All Cashiers</option>
                        @foreach ($cashiers as $cashier)
                            <option value="{{ $cashier->id }}" {{ (string) request('cashier_id') === (string) $cashier->id ? 'selected' : '' }}>
                                {{ $cashier->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="voided" {{ request('status') === 'voided' ? 'selected' : '' }}>Voided</option>
                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="pending_void" {{ request('status') === 'pending_void' ? 'selected' : '' }}>Pending Voids</option>
                        <option value="pending_refund" {{ request('status') === 'pending_refund' ? 'selected' : '' }}>Pending Refunds</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('supervisor.sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sales Transactions</h5>
            <span class="badge bg-light text-dark">{{ $sales->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Receipt</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($sales) ? count($sales) > 0 : !empty($sales))
@foreach($sales as $sale)
                        <tr>
                            <td class="fw-semibold">{{ $sale->receipt_no }}</td>
                            <td>{{ optional($sale->created_at)->format('M d, Y h:i A') }}</td>
                            <td>{{ $sale->cashier->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $sale->payment_method === 'cash' ? 'bg-success' : 'bg-info' }}">
                                    {{ strtoupper($sale->payment_method ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="fw-semibold">P{{ number_format((float) $sale->total_amount, 2) }}</td>
                            <td>
                                @if ($sale->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($sale->status === 'voided')
                                    <span class="badge bg-danger">Voided</span>
                                @elseif ($sale->status === 'refunded')
                                    <span class="badge bg-warning text-dark">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $sale->status)) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('supervisor.sales.show', $sale) }}" class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
@else
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No sales found for the selected filters.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if (method_exists($sales, 'links'))
            <div class="card-footer bg-white border-0">
                {{ $sales->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

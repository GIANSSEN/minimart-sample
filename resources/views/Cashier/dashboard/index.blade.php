@extends('layouts.app')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Cashier Dashboard</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Today's Sales</h6>
                            <h3 class="mb-0">₱{{ number_format($todaySales ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Transactions Today</h6>
                            <h3 class="mb-0">{{ $transactionCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-receipt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Average Sale</h6>
                            <h3 class="mb-0">₱{{ number_format($averageSale ?? 0, 2) }}</h3>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Transactions</h5>
                    <a href="{{ route('cashier.pos') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Sale
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Date/Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($recentSales) && $recentSales->count() > 0)
                                    @foreach ($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->receipt_no ?? 'N/A' }}</td>
                                        <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                                        <td>₱{{ number_format($sale->total_amount ?? 0, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($sale->payment_method ?? 'cash') == 'cash' ? 'success' : 'info' }}">
                                                {{ ucfirst($sale->payment_method ?? 'cash') }}
                                            </span>
                                        </td>
                                        <td>{{ isset($sale->created_at) ? $sale->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('cashier.sales.show', $sale->id ?? 0) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <!-- I-COMMENT OUT MUNA ANG PRINT BUTTON HANGGAT WALA PANG ROUTE -->
                                            <!-- 
                                            <a href="{{ route('cashier.pos.receipt', $sale->id ?? 0) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            -->
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (isset($recentSales) && $recentSales->count() > 0)
                <div class="card-footer">
                    <a href="{{ route('cashier.sales') }}" class="btn btn-link">View All Sales</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cashier.pos') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-shopping-cart"></i> New Sale
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cashier.sales') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-history"></i> Sales History
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning btn-lg w-100" onclick="window.location.reload()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .opacity-50 {
        opacity: 0.5;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .bg-primary .card-title, .bg-success .card-title, .bg-info .card-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .btn-lg {
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh every 5 minutes (300000 ms)
    setTimeout(function() {
        window.location.reload();
    }, 300000);
</script>
@endpush

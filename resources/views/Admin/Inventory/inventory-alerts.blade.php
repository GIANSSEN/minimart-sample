{{-- resources/views/admin/inventory/inventory-alerts.blade.php --}}
@extends('layouts.admin')

@section('title', 'CJ\'s Minimart - Inventory Alerts')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-1">
                        <i class="fas fa-bell text-warning me-2"></i>
                        Inventory Alerts
                    </h4>
                    <p class="text-muted mb-0">Products that need your attention</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Low Stock</h5>
                    <h2>{{ $lowStock->count() }}</h2>
                    <small>Products below reorder level</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Expired</h5>
                    <h2>{{ $expiredProducts->count() }}</h2>
                    <small>Products past expiry date</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Near Expiry</h5>
                    <h2>{{ $nearExpiry->count() }}</h2>
                    <small>Expiring within 30 days</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Section -->
    @if ($lowStock->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Low Stock Products ({{ $lowStock->count() }})</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Current Stock</th>
                        <th>Min Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lowStock as $stock)
                    <tr>
                        <td>{{ $stock->product->product_code ?? 'N/A' }}</td>
                        <td>{{ $stock->product->product_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-warning">{{ $stock->quantity }}</span></td>
                        <td>{{ $stock->min_quantity }}</td>
                        <td>
                            <a href="{{ route('admin.inventory.stock-in') }}?product={{ $stock->product_id }}" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Restock
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Expired Products Section -->
    @if ($expiredProducts->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Expired Products ({{ $expiredProducts->count() }})</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Expiry Date</th>
                        <th>Days Expired</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expiredProducts as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-danger">
                                {{ Carbon\Carbon::parse($product->expiry_date)->diffInDays(now()) }} days
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Near Expiry Section -->
    @if ($nearExpiry->count() > 0)
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Near Expiry Products ({{ $nearExpiry->count() }})</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Expiry Date</th>
                        <th>Days Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nearExpiry as $product)
                    @php
                        $daysLeft = Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($product->expiry_date), false);
                        $badgeClass = $daysLeft <= 7 ? 'danger' : 'warning';
                    @endphp
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ round($daysLeft) }} days
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if ($lowStock->count() == 0 && $expiredProducts->count() == 0 && $nearExpiry->count() == 0)
    <div class="text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h5>No Alerts</h5>
        <p class="text-muted">All inventory items are in good condition</p>
    </div>
    @endif
</div>
@endsection

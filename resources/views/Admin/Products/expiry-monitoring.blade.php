{{-- resources/views/admin/products/expiry-monitoring.blade.php --}}
@extends('layouts.admin')

@section('title', 'CJ\'s Minimart - Expiry Monitoring')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box expiry-header-icon">
                <i class="fas fa-clock text-warning"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Product Expiry Monitoring</h1>
                <p class="page-subtitle">Track and manage products approaching their expiry dates</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.products.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to products">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
            <button class="btn-header-action btn-header-primary" onclick="exportExpiryReport()" aria-label="Export report">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Expired</h6>
                            <h2 class="mb-0">{{ $expiredCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Near Expiry</h6>
                            <h2 class="mb-0">{{ $nearExpiryCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Expiring This Month</h6>
                            <h2 class="mb-0">{{ $expiringThisMonth ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Valid Products</h6>
                            <h2 class="mb-0">{{ $validProducts ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="expiryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" 
                                    id="expired-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#expired" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Expired Products
                                <span class="badge bg-danger ms-2">{{ $expiredProducts->total() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="near-expiry-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#near-expiry" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Near Expiry
                                <span class="badge bg-warning ms-2">{{ $nearExpiryProducts->total() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="expiring-month-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#expiring-month" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-calendar text-info me-2"></i>
                                Expiring This Month
                                <span class="badge bg-info ms-2">{{ $expiringThisMonthProducts->total() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <!-- Tab Contents -->
                    <div class="tab-content" id="expiryTabsContent">
                        <!-- Expired Tab -->
                        <div class="tab-pane fade show active" id="expired" role="tabpanel">
                            @include('admin.products.partials.expiry-table', [
                                'products' => $expiredProducts,
                                'type' => 'expired',
                                'title' => 'Expired Products',
                                'color' => 'danger'
                            ])
                        </div>

                        <!-- Near Expiry Tab -->
                        <div class="tab-pane fade" id="near-expiry" role="tabpanel">
                            @include('admin.products.partials.expiry-table', [
                                'products' => $nearExpiryProducts,
                                'type' => 'near-expiry',
                                'title' => 'Near Expiry Products',
                                'color' => 'warning'
                            ])
                        </div>

                        <!-- Expiring This Month Tab -->
                        <div class="tab-pane fade" id="expiring-month" role="tabpanel">
                            @include('admin.products.partials.expiry-table', [
                                'products' => $expiringThisMonthProducts,
                                'type' => 'expiring-month',
                                'title' => 'Expiring This Month',
                                'color' => 'info'
                            ])
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
    .page-header {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        margin-right: 5px;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        background: #f8f9fa;
        color: var(--primary-orange);
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #f39c12, #3498db);
        color: white;
        box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
    }

    .nav-tabs .nav-link .badge {
        background: rgba(255,255,255,0.2) !important;
        color: white;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .bg-danger .badge, .bg-warning .badge, .bg-info .badge, .bg-success .badge {
        background: rgba(255,255,255,0.2) !important;
        color: white;
    }

    .opacity-50 {
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    function exportExpiryReport() {
        Swal.fire({
            title: 'Export Expiry Report',
            text: 'Choose export format',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#3498db',
            confirmButtonText: 'Excel',
            cancelButtonText: 'PDF'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("admin.products.export-expiry", "excel") }}';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = '{{ route("admin.products.export-expiry", "pdf") }}';
            }
        });
    }
</script>
@endpush

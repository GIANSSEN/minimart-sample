@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        height: 100%;
    }

    [data-theme="dark"] .stat-card {
        background: #1e293b;
        border-color: #334155;
    }

    .stat-card.primary { border-bottom: 4px solid #3b82f6; }
    .stat-card.success { border-bottom: 4px solid #10b981; }
    .stat-card.warning { border-bottom: 4px solid #f59e0b; }
    .stat-card.danger { border-bottom: 4px solid #ef4444; }
</style>
@endpush

@section('content')
@php
    $showSupervisorCashActions = \Illuminate\Support\Facades\View::exists('supervisor.cash.drops')
        && \Illuminate\Support\Facades\View::exists('supervisor.cash.create-drop')
        && \Illuminate\Support\Facades\View::exists('supervisor.cash.shift-reports');
@endphp
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); border-radius: 20px; padding: 25px 30px; color: white;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2">
                            <i class="fas fa-user-tie me-2"></i>Supervisor Dashboard
                        </h2>
                        <p class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Welcome back, <strong>{{ Auth::user()->full_name ?? 'Supervisor' }}</strong>
                        </p>
                        <small class="text-white-50">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ now()->format('l, F j, Y') }}
                        </small>
                    </div>
                    <div class="text-end mt-3 mt-sm-0">
                        <div class="online-status" style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 30px;">
                            <span class="online-dot" style="width: 10px; height: 10px; background: #2ecc71; border-radius: 50%; display: inline-block;"></span>
                            <span class="ms-1">Online</span>
                        </div>
                        <div class="mt-2">
                            <span class="time-badge" style="background: rgba(0,0,0,0.2); padding: 5px 15px; border-radius: 20px;">
                                <i class="fas fa-clock me-1"></i>
                                {{ now()->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card primary" data-aos="fade-up" data-aos-delay="100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label text-muted mb-1">Today's Sales</div>
                        <div class="stat-value h3 mb-0 fw-bold">₱{{ number_format($todaySales ?? 0, 2) }}</div>
                    </div>
                    <div class="stat-icon" style="width: 50px; height: 50px; background: rgba(52, 152, 219, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3498db;">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
                <div class="stat-footer mt-3">
                    <small class="text-muted">{{ $todayTransactions ?? 0 }} transactions</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card success" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label text-muted mb-1">Active Cashiers</div>
                        <div class="stat-value h3 mb-0 fw-bold">{{ $todayCashiers ?? 0 }}</div>
                    </div>
                    <div class="stat-icon" style="width: 50px; height: 50px; background: rgba(46, 204, 113, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2ecc71;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="stat-footer mt-3">
                    <small class="text-muted">Currently on duty</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card warning" data-aos="fade-up" data-aos-delay="300">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label text-muted mb-1">Pending Voids</div>
                        <div class="stat-value h3 mb-0 fw-bold">{{ $pendingVoids ?? 0 }}</div>
                    </div>
                    <div class="stat-icon" style="width: 50px; height: 50px; background: rgba(243, 156, 18, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #f39c12;">
                        <i class="fas fa-ban fa-2x"></i>
                    </div>
                </div>
                <div class="stat-footer mt-3">
                    <small class="text-muted">Awaiting approval</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card danger" data-aos="fade-up" data-aos-delay="400">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label text-muted mb-1">Pending Refunds</div>
                        <div class="stat-value h3 mb-0 fw-bold">{{ $pendingRefunds ?? 0 }}</div>
                    </div>
                    <div class="stat-icon" style="width: 50px; height: 50px; background: rgba(231, 76, 60, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #e74c3c;">
                        <i class="fas fa-undo-alt fa-2x"></i>
                    </div>
                </div>
                <div class="stat-footer mt-3">
                    <small class="text-muted">Need attention</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('supervisor.sales.index', ['status' => 'pending_void']) }}" class="btn btn-warning btn-lg w-100 py-3">
                                <i class="fas fa-ban me-2"></i>Pending Voids
                                @if (($pendingVoids ?? 0) > 0)
                                    <span class="badge bg-dark ms-2">{{ $pendingVoids }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('supervisor.sales.index', ['status' => 'pending_refund']) }}" class="btn btn-info btn-lg w-100 py-3">
                                <i class="fas fa-undo-alt me-2"></i>Pending Refunds
                                @if (($pendingRefunds ?? 0) > 0)
                                    <span class="badge bg-dark ms-2">{{ $pendingRefunds }}</span>
                                @endif
                            </a>
                        </div>
                        @if ($showSupervisorCashActions)
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('supervisor.cash.create-drop') }}" class="btn btn-success btn-lg w-100 py-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>Cash Drop
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('supervisor.cash.shift-reports') }}" class="btn btn-primary btn-lg w-100 py-3">
                                    <i class="fas fa-chart-line me-2"></i>Shift Reports
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-ban me-2"></i>Recent Voids</h5>
                </div>
                <div class="card-body">
                    @if (isset($recentVoids) && $recentVoids->count() > 0)
                        <div class="list-group">
                            @foreach ($recentVoids as $void)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $void->receipt_no }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $void->cashier->full_name ?? 'Unknown' }} |
                                        <i class="fas fa-clock me-1"></i>{{ \Illuminate\Support\Carbon::parse($void->voided_at ?? $void->updated_at)->format('h:i A') }}
                                    </small>
                                </div>
                                <span class="badge bg-danger">₱{{ number_format($void->total_amount, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">No voided transactions</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-undo-alt me-2"></i>Recent Refunds</h5>
                </div>
                <div class="card-body">
                    @if (isset($recentRefunds) && $recentRefunds->count() > 0)
                        <div class="list-group">
                            @foreach ($recentRefunds as $refund)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $refund->receipt_no }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $refund->cashier->full_name ?? 'Unknown' }} |
                                        <i class="fas fa-clock me-1"></i>{{ \Illuminate\Support\Carbon::parse($refund->refunded_at ?? $refund->updated_at)->format('h:i A') }}
                                    </small>
                                </div>
                                <span class="badge bg-info">₱{{ number_format($refund->refund_amount ?? $refund->total_amount, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">No refunded transactions</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
@endpush

@php
    $stats = [
        ['label' => 'Total Users', 'value' => $totalUsers, 'icon' => 'users', 'icon_class' => 'bg-primary'],
        ['label' => 'Active', 'value' => $activeCount, 'icon' => 'user-check', 'icon_class' => 'bg-success'],
        ['label' => 'Pending', 'value' => $pendingCount, 'icon' => 'hourglass-start', 'icon_class' => 'bg-warning'],
        ['label' => 'Inactive', 'value' => $inactiveCount, 'icon' => 'user-times', 'icon_class' => 'bg-danger'],
    ];
@endphp

@foreach ($stats as $stat)
<!-- {{ $stat['label'] }} -->
<div class="col-6 col-md-3">
    <div class="stat-card-modern p-3 h-100 d-flex flex-column" role="region" aria-label="{{ $stat['label'] }}">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon {{ $stat['icon_class'] }}" title="{{ $stat['label'] }}">
                <i class="fas fa-{{ $stat['icon'] }}"></i>
            </div>
            <div class="min-width-0">
                <span class="stat-label text-uppercase text-muted small d-block">{{ $stat['label'] }}</span>
                <span class="stat-value fw-bold h3" aria-label="{{ $stat['label'] }}: {{ number_format($stat['value']) }}">{{ number_format($stat['value']) }}</span>
            </div>
        </div>
        <div class="mt-auto small text-muted">
            <i class="fas fa-sync-alt me-1"></i><span class="last-update-time">Just now</span>
        </div>
    </div>
</div>
@endforeach

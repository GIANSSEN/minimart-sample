@extends('layouts.admin')

@section('title', 'Profit & Loss Report')

@section('content')
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Profit & Loss Report</h1>
                <p class="page-subtitle">Overview of financial performance and margins</p>
            </div>
        </div>
        <div class="header-actions">
            {{-- Optional actions likePrint --}}
        </div>
    </div>

    <!-- Month Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.profit-loss') }}" class="row g-3">
                <div class="col-md-4">
                    <label>Month</label>
                    <select name="month" class="form-control">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <select name="year" class="form-control">
                        @foreach (range(date('Y') - 2, date('Y')) as $y)
                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card yellow">
                <div class="stat-value">₱{{ number_format($totalSales, 2) }}</div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-value">₱{{ number_format($cogs, 2) }}</div>
                <div class="stat-label">Cost of Goods Sold</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card yellow">
                <div class="stat-value">₱{{ number_format($grossProfit, 2) }}</div>
                <div class="stat-label">Gross Profit</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-value">{{ number_format($margin, 1) }}%</div>
                <div class="stat-label">Profit Margin</div>
            </div>
        </div>
    </div>

    <!-- Profit Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Monthly Profit Trend</h5>
        </div>
        <div class="card-body">
            <canvas id="profitChart" height="100"></canvas>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="card">
        <div class="card-header">
            <h5>Monthly Breakdown</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Sales</th>
                        <th>COGS</th>
                        <th>Profit</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyData as $data)
                    @php
                        $profit = $data->sales - $data->cogs;
                        $margin = $data->sales > 0 ? ($profit / $data->sales) * 100 : 0;
                    @endphp
                    <tr>
                        <td>{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</td>
                        <td>{{ $data->year }}</td>
                        <td>₱{{ number_format($data->sales, 2) }}</td>
                        <td>₱{{ number_format($data->cogs, 2) }}</td>
                        <td>₱{{ number_format($profit, 2) }}</td>
                        <td>{{ number_format($margin, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->map(function($d) { 
                return date('M', mktime(0, 0, 0, $d->month, 1)); 
            })) !!},
            datasets: [
                {
                    label: 'Sales',
                    data: {!! json_encode($monthlyData->pluck('sales')) !!},
                    backgroundColor: '#4e73df'
                },
                {
                    label: 'Profit',
                    data: {!! json_encode($monthlyData->map(function($d) { 
                        return $d->sales - $d->cogs; 
                    })) !!},
                    backgroundColor: '#ffd966'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush

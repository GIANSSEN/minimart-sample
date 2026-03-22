@extends('layouts.cashier')

@section('title', 'Sales History')

@push('styles')
<style>
    :root {
        --primary-blue: #2563eb;
        --secondary-slate: #64748b;
        --success-emerald: #10b981;
        --danger-rose: #f43f5e;
        --warning-amber: #f59e0b;
        --bg-glass: rgba(255, 255, 255, 0.9);
    }

    .history-container {
        padding: 1.5rem 0;
    }

    /* Summary Stats Banner */
    .history-card-premium {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .history-card-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .stat-box {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: transform 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.08);
    }

    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.7;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #fff;
    }

    /* List Design */
    .sale-item-card {
        background: white;
        border-radius: 15px;
        border: 1px solid #eef2f6;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .sale-item-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: var(--primary-blue);
        transform: scale(1.01);
    }

    .receipt-id {
        font-family: 'JetBrains Mono', 'Monaco', monospace;
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .status-badge-premium {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-completed {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #10b981;
    }

    .badge-voided {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #f43f5e;
    }

    /* Filters */
    .filter-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .search-input-premium {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s;
    }

    .search-input-premium:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .action-btn-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }

    .action-btn-circle.view { background: #eff6ff; color: #2563eb; }
    .action-btn-circle.void { background: #fff1f2; color: #e11d48; }

    .action-btn-circle:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid history-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-900 mb-0">Sales & Transactions</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('cashier.pos.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>New Sale
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="history-card-premium">
        <div class="row g-4 align-items-center">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-label">Daily Earnings</div>
                    <div class="stat-value">₱{{ number_format($todaySales, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-label">Daily Volume</div>
                    <div class="stat-value">{{ $todayCount }} <small class="fw-normal opacity-50">Sales</small></div>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <div class="pe-md-4">
                    <h5 class="fw-bold mb-1">Performance Track</h5>
                    <p class="small opacity-75 mb-0">Track and manage your daily grocery checkout velocity.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search/Filters -->
    <div class="filter-section shadow-sm">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" class="form-control search-input-premium ps-5" placeholder="Search by Receipt # or Customer...">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control search-input-premium">
            </div>
            <div class="col-md-3">
                <select class="form-select search-input-premium">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="voided">Voided</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Sales History List -->
    <div id="salesList">
        @forelse ($sales as $sale)
            <div class="sale-item-card animate__animated animate__fadeInUp">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="receipt-id">#{{ $sale->receipt_no }}</div>
                        <div class="small text-muted mt-1">
                            <i class="far fa-calendar-alt me-1"></i> {{ $sale->created_at->format('M d, Y') }} 
                            <span class="mx-1">•</span> 
                            <i class="far fa-clock me-1"></i> {{ $sale->created_at->format('h:i A') }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fw-bold text-dark">{{ $sale->customer_name ?? 'Walk-in Customer' }}</div>
                        <div class="small text-muted text-uppercase letter-spacing-1">{{ $sale->customer_type }} Classification</div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="badge-container">
                            @if ($sale->status == 'completed')
                                <span class="status-badge-premium badge-completed">
                                    <i class="fas fa-check-circle me-1"></i> Completed
                                </span>
                            @else
                                <span class="status-badge-premium badge-voided">
                                    <i class="fas fa-times-circle me-1"></i> Voided
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 text-end pe-md-4">
                        <div class="text-muted small mb-0">{{ $sale->items->count() }} items</div>
                        <div class="fw-900 fs-5 text-dark">₱{{ number_format($sale->total_amount, 2) }}</div>
                    </div>
                    <div class="col-md-2 text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('cashier.sales.show', $sale) }}" class="action-btn-circle view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if ($sale->status == 'completed')
                                <button class="action-btn-circle void void-btn" data-id="{{ $sale->id }}" title="Void Transaction">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                <h5>No sales history found</h5>
                <p class="text-muted">Once you complete a transaction, it will appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>

<!-- Void Modal -->
<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-ban me-2"></i>Void Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning border-0 small">
                    <i class="fas fa-exclamation-triangle me-2"></i> Warning: This action will restore stock quantities and mark the transaction as voided.
                </div>
                <p class="fw-bold mb-2">Reason for voiding:</p>
                <textarea class="form-control border-2" id="voidReason" rows="3" placeholder="Explain why this transaction is being voided..." style="border-radius: 12px;"></textarea>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" id="confirmVoid">Confirm Void</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentSaleId = null;

    $('.void-btn').click(function() {
        currentSaleId = $(this).data('id');
        $('#voidModal').modal('show');
    });

    $('#confirmVoid').click(function() {
        let reason = $('#voidReason').val();
        if (!reason) {
            Swal.fire({ icon: 'warning', title: 'Reason Required', text: 'Please explain why you are voiding this sale.' });
            return;
        }

        $.ajax({
            url: `/cashier/sales/${currentSaleId}/void`,
            method: 'POST',
            data: {
                reason: reason,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Transaction Voided', showConfirmButton: false, timer: 1500 });
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON.error });
            }
        });
    });
</script>
@endpush

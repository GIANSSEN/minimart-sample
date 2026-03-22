@extends('layouts.admin')

@section('title', 'Return Details')

@push('styles')
<style>
.details-card { background:#fff; border-radius:16px; border:1px solid #eef2f7; box-shadow:0 6px 24px rgba(0,0,0,.04); }
.detail-label { font-size:.75rem; color:#64748b; text-transform:uppercase; letter-spacing:.4px; font-weight:700; margin-bottom:.25rem; }
.detail-value { font-weight:600; color:#0f172a; }
.status-pill { padding:.35rem .85rem; border-radius:999px; font-size:.78rem; font-weight:700; }
.status-pending { background:rgba(245,158,11,.15); color:#b45309; }
.status-processed { background:rgba(16,185,129,.15); color:#047857; }
.status-cancelled { background:rgba(239,68,68,.15); color:#b91c1c; }
.line-table th { font-size:.78rem; text-transform:uppercase; color:#64748b; background:#f8fafc; }
@media (max-width: 768px) {
    .mobile-stack { flex-direction:column; align-items:flex-start !important; gap:.75rem; }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mobile-stack">
        <div>
            <h1 class="page-title mb-1">Return #{{ $return->id }}</h1>
            <p class="text-muted mb-0">Review return/refund information and process status.</p>
        </div>
        <a href="{{ route('supervisor.returns.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="details-card p-4 h-100">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="detail-label">Receipt No</div>
                        <div class="detail-value">{{ $return->sale->receipt_no ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Status</div>
                        @if ($return->status === 'pending')
                            <span class="status-pill status-pending">Pending</span>
                        @elseif ($return->status === 'processed')
                            <span class="status-pill status-processed">Processed</span>
                        @else
                            <span class="status-pill status-cancelled">Cancelled</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Created</div>
                        <div class="detail-value">{{ optional($return->created_at)->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Product</div>
                        <div class="detail-value">{{ $return->product->product_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Quantity</div>
                        <div class="detail-value">{{ number_format($return->quantity) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Refund Amount</div>
                        <div class="detail-value text-primary">P{{ number_format($return->refund_amount, 2) }}</div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Reason</div>
                        <div class="detail-value">{{ $return->reason }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Processed By</div>
                        <div class="detail-value">{{ $return->processor->full_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Sale Link</div>
                        <a href="{{ route('supervisor.transactions.show', $return->sale_id) }}" class="fw-semibold">
                            Open Transaction
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="details-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="fas fa-bolt me-2 text-warning"></i>Actions</h6>
                @if ($return->status === 'pending')
                    <form method="POST" action="{{ route('supervisor.returns.process', $return) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i> Process Return
                        </button>
                    </form>
                    <form method="POST" action="{{ route('supervisor.returns.cancel', $return) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small text-muted">Cancellation Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required maxlength="500" placeholder="Provide reason"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-1"></i> Cancel Return
                        </button>
                    </form>
                @else
                    <div class="alert alert-light border mb-0">
                        No further actions available for this return.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($return->sale && $return->sale->items && $return->sale->items->count())
    <div class="details-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Sale Line Items</h6>
        </div>
        <div class="table-responsive">
            <table class="table line-table mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($return->sale->items as $item)
                        <tr>
                            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($item->quantity) }}</td>
                            <td class="text-end">P{{ number_format($item->price, 2) }}</td>
                            <td class="text-end">P{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

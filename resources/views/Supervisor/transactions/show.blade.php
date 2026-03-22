@extends('layouts.admin')

@section('title', 'Transaction #' . $sale->receipt_no)

@push('styles')
<style>
.page-header-premium { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.header-left { display: flex; align-items: center; gap: 1rem; }
.header-icon-box { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem; box-shadow: 0 6px 16px rgba(59,130,246,0.3); }
.page-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
.page-subtitle { font-size: 0.85rem; color: #94a3b8; margin: 0; }
.detail-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; margin-bottom: 1.5rem; }
.detail-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; }
.detail-value { font-size: 1rem; font-weight: 600; color: #1e293b; }
.receipt-badge { font-family: monospace; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); padding: 0.4rem 1rem; border-radius: 8px; font-size: 1rem; font-weight: 700; color: #1e293b; letter-spacing: 1px; }
.table th { font-weight: 600; font-size: 0.78rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; background: #f8fafc; }
.table td { padding: 0.85rem 1rem; vertical-align: middle; }
.total-row { background: linear-gradient(135deg, #f8fafc, #f1f5f9); font-weight: 700; }
.status-badge { padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.82rem; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box"><i class="fas fa-receipt"></i></div>
            <div>
                <h1 class="page-title">Transaction Detail</h1>
                <p class="page-subtitle">Receipt #{{ $sale->receipt_no }}</p>
            </div>
        </div>
        <a href="{{ route('supervisor.transactions.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row g-4">
        <!-- Transaction Info -->
        <div class="col-lg-4">
            <div class="detail-card">
                <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Transaction Info</h6>
                <div class="mb-3 text-center">
                    <span class="receipt-badge">{{ $sale->receipt_no }}</span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ optional($sale->created_at)->format('M d, Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">{{ optional($sale->created_at)->format('h:i A') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Cashier</div>
                        <div class="detail-value">{{ $sale->cashier->full_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            @if ($sale->status === 'completed')
                                <span class="status-badge" style="background:rgba(16,185,129,0.12);color:#059669;">Completed</span>
                            @elseif ($sale->status === 'voided')
                                <span class="status-badge" style="background:rgba(239,68,68,0.12);color:#dc2626;">Voided</span>
                            @elseif ($sale->status === 'refunded')
                                <span class="status-badge" style="background:rgba(245,158,11,0.12);color:#d97706;">Refunded</span>
                            @else
                                <span class="status-badge" style="background:rgba(148,163,184,0.12);color:#64748b;">{{ ucfirst(str_replace('_', ' ', $sale->status)) }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($sale->customer_name)
                    <div class="col-6">
                        <div class="detail-label">Customer</div>
                        <div class="detail-value">{{ $sale->customer_name }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Customer Type</div>
                        <div class="detail-value">{{ ucfirst($sale->customer_type ?? 'Regular') }}</div>
                    </div>
                    @endif
                    @if ($sale->void_reason)
                    <div class="col-12">
                        <div class="detail-label">Void/Refund Reason</div>
                        <div class="detail-value text-danger">{{ $sale->void_reason }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="detail-card">
                <h6 class="fw-semibold mb-3"><i class="fas fa-money-bill-wave me-2 text-success"></i>Payment Summary</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value">{{ strtoupper($sale->payment_method ?? 'N/A') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Amount Paid</div>
                        <div class="detail-value text-primary">₱{{ number_format($sale->amount_paid, 2) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Subtotal</div>
                        <div class="detail-value">₱{{ number_format($sale->subtotal, 2) }}</div>
                    </div>
                    @if ($sale->discount_amount > 0)
                    <div class="col-6">
                        <div class="detail-label">Discount</div>
                        <div class="detail-value text-danger">-₱{{ number_format($sale->discount_amount, 2) }}</div>
                    </div>
                    @endif
                    @if ($sale->tax > 0)
                    <div class="col-6">
                        <div class="detail-label">Tax</div>
                        <div class="detail-value">₱{{ number_format($sale->tax, 2) }}</div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="detail-label">Change</div>
                        <div class="detail-value text-success">₱{{ number_format($sale->change, 2) }}</div>
                    </div>
                    <div class="col-12">
                        <div style="border-top: 2px solid #f1f5f9; padding-top: 0.75rem; margin-top: 0.5rem;">
                            <div class="detail-label">Total Amount</div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">₱{{ number_format($sale->total_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="col-lg-8">
            <div class="detail-card" style="padding: 0; overflow: hidden;">
                <div class="p-3 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-box me-2 text-primary"></i>Items Purchased ({{ $sale->items->count() }} items)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $index => $item)
                            <tr>
                                <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->product->product_name ?? $item->product_name ?? 'Unknown Product' }}</span>
                                    @if ($item->discount_amount > 0)
                                        <br><small class="text-danger">Discount: -₱{{ number_format($item->discount_amount, 2) }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">₱{{ number_format($item->unit_price ?? ($item->subtotal / max($item->quantity, 1)), 2) }}</td>
                                <td class="text-end pe-3 fw-semibold">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="4" class="text-end ps-3 fw-bold">Total:</td>
                                <td class="text-end pe-3 fw-bold text-primary">₱{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

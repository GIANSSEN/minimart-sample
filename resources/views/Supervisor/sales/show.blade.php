@extends('layouts.admin')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Sale Details</h2>
            <p class="text-muted mb-0">Receipt: {{ $sale->receipt_no }}</p>
        </div>
        <a href="{{ route('supervisor.sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Transaction Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm align-middle mb-0">
                        <tr>
                            <th width="40%">Date & Time</th>
                            <td>{{ optional($sale->created_at)->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Cashier</th>
                            <td>{{ $sale->cashier->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
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
                        </tr>
                        <tr>
                            <th>Payment</th>
                            <td>{{ strtoupper($sale->payment_method ?? 'N/A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Amount Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm align-middle mb-0">
                        <tr>
                            <th width="40%">Subtotal</th>
                            <td>P{{ number_format((float) ($sale->subtotal ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Tax</th>
                            <td>P{{ number_format((float) ($sale->tax ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td class="fw-bold">P{{ number_format((float) ($sale->total_amount ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td>P{{ number_format((float) ($sale->amount_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Change</th>
                            <td>P{{ number_format((float) ($sale->change ?? 0), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Items</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($sale->items) ? count($sale->items) > 0 : !empty($sale->items))
@foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>P{{ number_format((float) ($item->price ?? 0), 2) }}</td>
                            <td>P{{ number_format((float) ($item->subtotal ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
@else
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No line items available.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @if (!in_array($sale->status, ['voided', 'refunded']))
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Supervisor Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('supervisor.sales.void', $sale) }}">
                            @csrf
                            <label class="form-label">Void Reason</label>
                            <textarea name="reason" class="form-control mb-2" rows="2" required></textarea>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban me-1"></i> Void Sale
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('supervisor.sales.refund', $sale) }}">
                            @csrf
                            <label class="form-label">Refund Reason</label>
                            <textarea name="reason" class="form-control mb-2" rows="2" required></textarea>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-undo-alt me-1"></i> Refund Sale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection


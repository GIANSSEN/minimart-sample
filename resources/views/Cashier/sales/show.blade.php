@extends('layouts.cashier')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sale Details: {{ $sale->receipt_no }}</h2>
        <div>
            <a href="{{ route('cashier.sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('cashier.sales.receipt', $sale) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> Print Receipt
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Transaction Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Receipt Number:</th>
                            <td><strong>{{ $sale->receipt_no }}</strong></td>
                        </tr>
                        <tr>
                            <th>Date & Time:</th>
                            <td>{{ $sale->created_at->format('F d, Y h:i:s A') }}</td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                        </tr>
                        <tr>
                            <th>Cashier:</th>
                            <td>{{ $sale->cashier->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if ($sale->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($sale->status == 'voided')
                                    <span class="badge bg-danger">Voided</span>
                                @endif
                            </td>
                        </tr>
                        @if ($sale->status == 'voided')
                        <tr>
                            <th>Voided By:</th>
                            <td>{{ $sale->voided_by_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Voided At:</th>
                            <td>{{ $sale->voided_at ? $sale->voided_at->format('F d, Y h:i:s A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Void Reason:</th>
                            <td>{{ $sale->void_reason }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Payment Method:</th>
                            <td><span class="badge bg-info">{{ strtoupper($sale->payment_method) }}</span></td>
                        </tr>
                        <tr>
                            <th>Subtotal:</th>
                            <td>₱{{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Tax (12%):</th>
                            <td>₱{{ number_format($sale->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td>₱{{ number_format($sale->discount_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td><strong>₱{{ number_format($sale->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Amount Tendered:</th>
                            <td>₱{{ number_format($sale->amount_tendered, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Change:</th>
                            <td>₱{{ number_format($sale->change_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items Purchased</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱{{ number_format($item->unit_price, 2) }}</td>
                        <td>₱{{ number_format($item->discount, 2) }}</td>
                        <td>₱{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th>₱{{ number_format($sale->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

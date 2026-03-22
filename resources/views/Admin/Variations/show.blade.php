@extends('layouts.admin')

@section('title', 'Variation Details - CJ\'s Minimart')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Page Header -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="modern-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 py-3 px-3 px-md-4">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="header-icon">
                        <i class="fas fa-info-circle text-info"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-0 h4 h3-md">Variation Details</h1>
                        <p class="text-muted mb-0 small">{{ $variation->name }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.variations.edit', $variation->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.variations.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <!-- Image -->
                        <div class="col-12 text-center mb-3">
                            @if ($variation->image)
                                <img src="{{ asset($variation->image) }}" alt="{{ $variation->name }}" 
                                     class="img-fluid rounded-3" style="max-height:200px; object-fit:contain;">
                            @else
                                <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center p-4" style="width:200px;height:200px;">
                                    <i class="fas fa-tag fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Variation Name</label>
                                <p class="h6">{{ $variation->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Variation Value</label>
                                <p class="h6">{{ $variation->value ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Product</label>
                                <p class="h6">{{ $variation->product->product_name ?? '—' }}</p>
                                @if ($variation->product)
                                    <small class="text-muted">{{ $variation->product->product_code }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">SKU</label>
                                <p class="h6"><code>{{ $variation->sku ?? '—' }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Cost Price</label>
                                <p class="h6">₱{{ number_format($variation->cost_price, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Selling Price</label>
                                <p class="h6">₱{{ number_format($variation->selling_price, 2) }}</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Description</label>
                                <p class="bg-light p-3 rounded">{{ $variation->description ?? 'No description provided.' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Created At</label>
                                <p>{{ $variation->created_at ? $variation->created_at->format('F d, Y h:i A') : '—' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="fw-semibold text-muted small">Last Updated</label>
                                <p>{{ $variation->updated_at ? $variation->updated_at->format('F d, Y h:i A') : '—' }}</p>
                            </div>
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
    .modern-header {
        background: white;
        border-bottom: 2px solid #17a2b8;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }
    .header-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    @media (min-width: 768px) {
        .header-icon {
            width: 55px;
            height: 55px;
            font-size: 1.8rem;
        }
    }
    .detail-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 12px;
        height: 100%;
    }
</style>
@endpush

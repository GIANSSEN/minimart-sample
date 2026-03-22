@extends('layouts.cashier')

@section('title', 'Point of Sale')

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner (Matching Admin Theme - Resized) -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="welcome-card-compact shadow-sm">
                <div class="row align-items-center">
                    <div class="col-lg-9 d-flex align-items-center gap-3">
                        @if(in_array(Auth::user()->role, ['admin', 'supervisor']))
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('supervisor.dashboard') }}" 
                               class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        @endif
                        <div class="welcome-text-compact">
                            <h2 class="fw-800 mb-1">
                                <span class="gradient-text-compact">Point of Sale</span>
                            </h2>
                            <p class="mb-0 small opacity-90">
                                <i class="fas fa-user-circle me-1 text-warning"></i>
                                Cashier: <strong class="text-warning">{{ Auth::user()->full_name }}</strong>
                                <span class="ms-3 border-start ps-3">
                                    <i class="far fa-calendar-alt me-1 text-warning"></i>
                                    {{ now()->format('l, F j, Y') }}
                                </span>
                                <span class="ms-3 border-start ps-3">
                                    <i class="far fa-clock me-1 text-warning"></i>
                                    {{ now()->format('h:i A') }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 text-lg-end mt-2 mt-lg-0">
                        <div class="shift-stats-compact">
                            <div class="stat-pill">
                                <i class="fas fa-cash-register me-2"></i>
                                <span class="label me-2">Sales:</span>
                                <span class="value" id="shiftSales">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Products Grid (Left Side) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark-blue text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-0"><i class="fas fa-th-large me-2 text-primary"></i>Catalog</h5>
                            <div class="view-toggle d-flex bg-white rounded-pill p-1 shadow-sm border">
                                <button class="btn view-btn active rounded-pill px-3 py-1" id="gridViewBtn" onclick="setView('grid')">
                                    <i class="fas fa-th-large me-1"></i>Grid
                                </button>
                                <button class="btn view-btn rounded-pill px-3 py-1" id="listViewBtn" onclick="setView('list')">
                                    <i class="fas fa-list me-1"></i>List
                                </button>
                            </div>
                        </div>
                        <div class="cashier-info">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->full_name }}
                            <span class="badge bg-warning ms-2">Cashier</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" 
                                    class="form-control form-control-lg search-input" 
                                    id="searchProduct" 
                                    placeholder="Search product or scan barcode...">
                                <button class="btn btn-primary scan-btn" onclick="startBarcodeScanner()">
                                    <i class="fas fa-camera me-1"></i> Scan
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-lg category-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Quick Category Pills -->
                    <div class="category-pills mb-4">
                        <button class="category-pill active" onclick="filterCategory('all')">All</button>
                        @foreach ($categories->take(5) as $category)
                            <button class="category-pill" onclick="filterCategory({{ $category->id }})">{{ $category->category_name }}</button>
                        @endforeach
                    </div>

                    <!-- Products Grid -->
                    <div class="row g-3" id="productGrid">
                        @if(is_countable($products) ? count($products) > 0 : !empty($products))
                            @foreach($products as $product)
                                @php $stock = $product->stock->quantity ?? 0; @endphp
                                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 product-item" 
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->product_name }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-barcode="{{ $product->barcode }}"
                                    data-stock="{{ $stock }}"
                                    data-category="{{ $product->category_id }}">
                                    <div class="card product-card h-100 {{ $stock <= 0 ? 'out-of-stock' : '' }}">
                                        <div class="card-body p-3 d-flex flex-column">
                                            <div class="product-thumb-container mb-2 text-center">
                                                @if ($product->image)
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}"
                                                        class="product-img img-fluid rounded-3">
                                                @else
                                                    <div class="product-icon-placeholder py-3 bg-light rounded-3 text-primary">
                                                        <i class="fas fa-box fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="product-info-main flex-grow-1">
                                                <h6 class="product-title" title="{{ $product->product_name }}">{{ $product->product_name }}</h6>
                                                <div class="product-barcode small text-muted mb-1 d-none d-sm-block">
                                                    <i class="fas fa-barcode me-1"></i>{{ $product->barcode }}
                                                </div>
                                                <div class="product-stock mb-1 small">
                                                    <i class="fas fa-boxes me-1 text-primary"></i>
                                                    Stock: <span class="fw-bold {{ $stock <= 0 ? 'text-danger' : ($stock <= ($product->reorder_level ?? 5) ? 'text-warning' : 'text-success') }}">{{ $stock }}</span> <span class="text-muted">{{ $product->unit }}</span>
                                                </div>
                                            </div>

                                            <div class="product-price-section d-flex justify-content-between align-items-center mt-2">
                                                <div class="product-price fw-bold">₱{{ number_format($product->selling_price, 2) }}</div>
                                                <div>
                                                    @if ($stock <= 0)
                                                        <span class="badge bg-danger rounded-pill">Out</span>
                                                    @elseif ($stock <= ($product->reorder_level ?? 5))
                                                        <span class="badge bg-warning text-dark rounded-pill">Low</span>
                                                    @else
                                                        <span class="badge bg-success rounded-pill">In</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($stock > 0)
                                                <button class="btn btn-add-cart btn-sm mt-2 w-100" onclick="addToCartById({{ $product->id }})">
                                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-out-stock btn-sm mt-2 w-100" disabled>
                                                    <i class="fas fa-ban me-1"></i> Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">No products available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section (Right Side) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-lg border-0 sticky-cart">
                <div class="card-header bg-primary text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                        <span class="cart-count" id="cartCount">0</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Customer Type Selection - 2x2 Grid -->
                    <div class="customer-type-section mb-3">
                        <label class="fw-bold mb-2 d-block" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px;">
                            <i class="fas fa-id-card me-2 text-primary"></i>Customer Classification
                        </label>
                        <div class="customer-type-grid">
                            <input type="radio" class="btn-check" name="customerType" id="typeRegular" value="regular" checked>
                            <label class="customer-type-card" for="typeRegular">
                                <i class="fas fa-user"></i>
                                <span>Regular</span>
                            </label>

                            <input type="radio" class="btn-check" name="customerType" id="typeSenior" value="senior">
                            <label class="customer-type-card" for="typeSenior">
                                <i class="fas fa-user-tie"></i>
                                <span>Senior<br><small class="text-muted" style="font-size:0.65rem;">20% off</small></span>
                            </label>

                            <input type="radio" class="btn-check" name="customerType" id="typePWD" value="pwd">
                            <label class="customer-type-card" for="typePWD">
                                <i class="fas fa-wheelchair"></i>
                                <span>PWD<br><small class="text-muted" style="font-size:0.65rem;">20% off</small></span>
                            </label>

                            <input type="radio" class="btn-check" name="customerType" id="typePregnant" value="pregnant">
                            <label class="customer-type-card" for="typePregnant">
                                <i class="fas fa-baby"></i>
                                <span>Pregnant<br><small class="text-muted" style="font-size:0.65rem;">20% off</small></span>
                            </label>
                        </div>
                    </div>

                    <!-- Cart Items with Scrollbar -->
                    <div class="cart-items-wrapper mb-3 border rounded shadow-sm overflow-hidden">
                        <div class="cart-items-header d-flex justify-content-between p-2 bg-light border-bottom small fw-bold text-uppercase text-muted">
                            <div style="flex: 2;">Item</div>
                            <div style="flex: 1;" class="text-center">Qty</div>
                            <div style="flex: 1;" class="text-end">Total</div>
                            <div style="width: 30px;"></div>
                        </div>
                        <div class="cart-items-scroll-area" id="cartItems" style="height: 300px; overflow-y: auto; background: #fff;">
                            <!-- Items or Empty State will be injected here by JS -->
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary p-4 bg-light border-top" id="cartSummary" style="display: none;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small fw-bold summary-label">SUBTOTAL</span>
                            <span class="fw-bold text-dark summary-value" id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger" id="discountRow" style="display: none;">
                            <span class="small fw-bold summary-label" id="discountLabel">DISCOUNT (20%)</span>
                            <span class="fw-bold summary-value" id="discountAmount">-₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 opacity-75">
                            <span class="text-muted small fw-bold summary-label">VATABLE AMOUNT</span>
                            <span class="fw-bold summary-value" id="vatableSales">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 opacity-75">
                            <span class="text-muted small fw-bold summary-label">VAT (12%)</span>
                            <span class="fw-bold summary-value" id="vatAmount">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                            <h5 class="mb-0 fw-900 text-dark letter-spacing-1">GRAND TOTAL</h5>
                            <h2 class="mb-0 fw-900" style="color: #2563eb; font-size: 2.2rem;" id="total">₱0.00</h2>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="payment-section" id="paymentSection" style="display: none;">
                        <div class="mb-3">
                            <label class="fw-bold mb-2" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px;">
                                <i class="fas fa-money-bill-wave me-2 text-primary"></i>Payment Method
                            </label>
                            <div class="payment-method-grid">
                                <input type="radio" class="btn-check" name="paymentMethod" id="methodCash" value="cash" checked>
                                <label class="payment-method-card" for="methodCash" style="grid-column: span 3;">
                                    <i class="fas fa-coins"></i>
                                    <span>Cash Payment</span>
                                </label>
                            </div>
                        </div>

                        <div class="amount-section mb-4" id="cashInput">
                            <label class="fw-bold mb-2 text-dark">
                                <i class="fas fa-cash-register me-2 text-primary"></i>Amount Received
                            </label>
                            <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border">
                                <span class="input-group-text bg-light border-0 px-3 fw-bold text-dark">₱</span>
                                <input type="number" class="form-control border-0 fw-800" id="amountTendered" 
                                    step="0.01" min="0" placeholder="0.00" style="font-size: 1.5rem; color: #1e293b;">
                            </div>
                        </div>

                        <div class="change-section mt-4" id="changeRow" style="display: none;">
                            <div class="change-box-premium p-4 rounded-4 shadow-lg d-flex justify-content-between align-items-center w-100 mb-4">
                                <div class="text-start">
                                    <span class="change-label-new d-block small text-uppercase fw-bold opacity-90">CUSTOMER'S CHANGE</span>
                                    <h1 class="change-amount-new mb-0 fw-bold" id="change" style="font-size: 3.5rem;">₱0.00</h1>
                                </div>
                                <div class="change-icon-new d-none d-sm-block opacity-75">
                                    <i class="fas fa-hand-holding-usd fa-4x text-warning" id="changeIcon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn btn-primary btn-lg w-100 mb-3 shadow-lg border-0 py-3 fw-900" 
                                    id="checkoutBtn" onclick="checkout()" style="border-radius: 12px; font-size: 1.15rem; letter-spacing: 1px;">
                                <i class="fas fa-check-circle me-2"></i> COMPLETE CHECKOUT
                            </button>
                            <button class="btn btn-outline-secondary btn-sm w-100 opacity-75 border-0" id="clearCartBtn" onclick="clearCart()">
                                <i class="fas fa-trash me-2"></i> Clear Current Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content receipt-modal">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="receiptContent">
                <!-- Receipt will be inserted here -->
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Scanner Modal (Simulated) -->
<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Scan Barcode</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="scanner-container mb-3" id="reader" style="width: 100%; min-height: 250px; background: #eee; border-radius: 10px; overflow: hidden;">
                </div>
                <p class="mb-3 small text-muted">Position barcode in front of camera</p>
                <div class="input-group">
                    <input type="text" class="form-control" id="manualBarcode" placeholder="Enter barcode manually">
                    <button class="btn btn-primary" onclick="processManualBarcode()">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<!-- Extra Libraries for POS Upgrades -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<style>
    /* ==================== COMPACT WELCOME CARD ==================== */
    .welcome-card-compact {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        border-radius: 12px;
        padding: 15px 25px;
        color: white;
        border-left: 5px solid #fff;
        position: relative;
        overflow: hidden;
    }

    .gradient-text-compact {
        background: linear-gradient(135deg, #fff 0%, #dbeafe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .shift-stats-compact .stat-pill {
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 15px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .shift-stats-compact .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
    }

    .shift-stats-compact .value {
        font-size: 1.1rem;
        font-weight: 800;
        color: #3B82F6;
    }

    /* ==================== THEME OVERRIDES ==================== */
    .bg-dark-blue {
        background-color: #3b82f6 !important;
    }

    .text-primary {
        color: #2563eb !important;
    }

    .btn-primary {
        background-color: #3B82F6;
        border-color: #3B82F6;
    }

    .btn-primary:hover {
        background-color: #2563EB;
        border-color: #2563EB;
    }

    /* ==================== SEARCH SECTION ==================== */
    .search-wrapper {
        position: relative;
        display: flex;
        gap: 10px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 10;
    }

    .search-input {
        padding-left: 45px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .scan-btn {
        border-radius: 12px;
        padding: 0 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .scan-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .category-select {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        height: 100%;
    }

    /* ==================== CATEGORY PILLS ==================== */
    .category-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .category-pill {
        padding: 8px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 30px;
        background: white;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s;
        cursor: pointer;
    }

    .category-pill:hover {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .category-pill.active {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border-color: transparent;
        color: white;
    }

    /* ==================== PRODUCT CARDS ==================== */
    .product-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        border-color: #0d6efd;
        box-shadow: 0 10px 25px rgba(13, 110, 253, 0.2);
    }

    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .product-badges {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .product-badges .badge {
        font-size: 0.70rem;
        padding: 4px 8px;
        border-radius: 20px;
    }

    .product-title {
        font-weight: 600;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.9rem;
    }

    .product-price {
        font-weight: 700;
        color: #0d6efd;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .product-stock {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* ==================== STICKY CART ==================== */
    .sticky-cart {
        position: sticky;
        top: 90px;
        z-index: 100;
    }

    .cart-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 10px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* ==================== CART ITEMS ALIGNMENT ==================== */
    .cart-item-row {
        transition: background 0.2s;
        border-bottom: 1px solid #f1f5f9;
        min-height: 70px;
    }
    
    .cart-item-row:hover {
        background-color: #f8fafc;
    }

    .cart-item-info {
        flex: 1;
        min-width: 0;
        padding-right: 10px;
    }

    .cart-item-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .cart-item-price {
        font-size: 0.75rem;
        color: #64748b;
    }

    .cart-qty-controls {
        width: 110px;
        display: flex;
        justify-content: center;
        flex-shrink: 0;
    }

    .cart-item-total {
        width: 90px;
        text-align: right;
        font-weight: 700;
        color: var(--primary-blue);
        flex-shrink: 0;
    }

    .cart-item-action {
        width: 45px;
        text-align: right;
        flex-shrink: 0;
    }

    /* ==================== CART SUMMARY ==================== */
    .cart-summary {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .total-row {
        border-top: 2px dashed #e0e0e0;
        padding-top: 10px;
        margin-top: 10px;
        font-size: 1.1rem;
    }

    .summary-label {
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .summary-value {
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.5px;
    }

    /* ==================== PREMIUM CHANGE BOX ==================== */
    .change-box-premium {
        background: #eff6ff;
        border-right: 6px solid #3b82f6;
        color: #1e3a8a;
        box-shadow: 0 10px 25px rgba(59,130,246,0.1);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .change-label-new {
        font-size: 0.8rem;
        letter-spacing: 2px;
        color: #3b82f6;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .change-amount-new {
        font-size: 3.8rem;
        font-weight: 900;
        letter-spacing: -1.5px;
    }

    /* Hide number spinners */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    .text-yellow {
        color: #ffc107 !important;
    }

    /* ==================== UTILS ==================== */
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    .quantity-input {
        width: 45px !important;
        border: none !important;
        background: transparent !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        color: #1e293b;
        padding: 0 5px;
        text-align: center;
        margin: 0 2px;
    }

    /* Hide arrows/spinners */
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-input[type=number] {
        -moz-appearance: textfield;
    }

    .hover-grow {
        transition: transform 0.2s;
    }
    .hover-grow:hover {
        transform: scale(1.2);
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
    .fw-900 {
        font-weight: 900;
    }

    /* ==================== VIEW TOGGLE BUTTONS ==================== */
    .view-btn {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        border: none;
        background: transparent;
        transition: all 0.2s;
    }
    .view-btn:hover { color: #3B82F6; }
    .view-btn.active {
        background: #3B82F6 !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(59,130,246,0.4);
    }

    /* ==================== CUSTOMER TYPE GRID ==================== */
    .customer-type-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .customer-type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 10px 6px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.3;
    }
    .customer-type-card i {
        font-size: 1.2rem;
        color: #94a3b8;
        transition: color 0.2s;
    }
    .customer-type-card:hover {
        border-color: #3B82F6;
        background: #eff6ff;
        color: #3B82F6;
    }
    .customer-type-card:hover i { color: #3B82F6; }
    .btn-check:checked + .customer-type-card {
        border-color: #3B82F6;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        color: #1d4ed8;
        box-shadow: 0 2px 10px rgba(59,130,246,0.25);
    }
    .btn-check:checked + .customer-type-card i { color: #2563eb; }

    /* ==================== PAYMENT METHOD GRID ==================== */
    .payment-method-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    .payment-method-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 10px 6px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
    }
    .payment-method-card i {
        font-size: 1.1rem;
        color: #94a3b8;
        transition: color 0.2s;
    }
    .payment-method-card:hover {
        border-color: #3B82F6;
        background: #eff6ff;
        color: #3B82F6;
    }
    .payment-method-card:hover i { color: #3B82F6; }
    .btn-check:checked + .payment-method-card {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #047857;
        box-shadow: 0 2px 10px rgba(16,185,129,0.25);
    }
    .btn-check:checked + .payment-method-card i { color: #059669; }

    /* ==================== ADD TO CART BUTTON ==================== */
    .btn-add-cart {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 0.3px;
        transition: all 0.2s;
    }
    .btn-add-cart:hover {
        background: linear-gradient(135deg, #2563EB, #1d4ed8);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    }
    .btn-out-stock {
        background: #f1f5f9;
        color: #94a3b8;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        cursor: not-allowed;
    }

    /* ==================== PRODUCT IMAGE ==================== */
    .product-img {
        height: 100px;
        width: 100%;
        object-fit: contain;
        background: #f8f9fa;
    }

    /* ==================== LIST VIEW LAYOUT ==================== */
    #productGrid.list-view {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
    }
    #productGrid.list-view > .product-item {
        width: 100% !important;
        max-width: 100% !important;
        flex: none !important;
    }
    #productGrid.list-view .product-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0 !important;
    }
    #productGrid.list-view .card-body {
        display: grid !important;
        grid-template-columns: 80px 1fr 100px 150px;
        align-items: center !important;
        gap: 20px;
        padding: 12px 20px !important;
    }
    #productGrid.list-view .product-thumb-container {
        width: 80px;
        height: 80px;
        margin-bottom: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #productGrid.list-view .product-img {
        height: 70px;
        width: 70px;
        object-fit: contain;
    }
    #productGrid.list-view .product-icon-placeholder {
        height: 70px;
        width: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
    }
    #productGrid.list-view .product-info-main {
        text-align: left;
    }
    #productGrid.list-view .product-title {
        font-size: 1.05rem;
        margin-bottom: 4px;
        white-space: normal;
        overflow: visible;
    }
    #productGrid.list-view .product-price-section {
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
    }
    #productGrid.list-view .product-price {
        font-size: 1.25rem;
        margin-bottom: 2px;
    }
    #productGrid.list-view .btn-add-cart,
    #productGrid.list-view .btn-out-stock {
        width: auto !important;
        padding: 10px 20px;
        font-size: 0.85rem;
        margin-top: 0 !important;
    }
    #productGrid.list-view .product-barcode {
        display: block !important;
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .sticky-cart { position: static; }
        .change-amount-new { font-size: 2.5rem !important; }
        .product-price { font-size: 1rem !important; }
    }
    @media (max-width: 576px) {
        .welcome-card-compact { padding: 10px 15px; }
        .cashier-info { display: none; }
        .category-pills {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 4px;
        }
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }

    .scan-btn {
        animation: pulse-primary 2s infinite;
    }

    .category-pill {
        border: 1.5px solid #e2e8f0;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-pill:hover {
        transform: translateY(-2px);
        background: #f1f5f9;
        border-color: #3B82F6;
        color: #3B82F6;
    }

    .welcome-card-compact {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
    }

    .product-card {
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .product-card:hover {
        border-color: #3B82F6 !important;
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let cart = [];
    let products = @json($products);
    let currentCustomerType = 'regular';
    let currentPaymentMethod = 'cash';
    let g_cartTotal = 0;
    let shiftSales = 0;
    let html5QrCode = null;
    let scannerActive = false;
    let focusedInputIndex = -1; // To track which input is being typed in

    // Add to cart
    function addToCart(element) {
        const id = element.closest('.product-item').dataset.id;
        const name = element.closest('.product-item').dataset.name;
        const price = parseFloat(element.closest('.product-item').dataset.price);
        const stock = parseInt(element.closest('.product-item').dataset.stock);

        if (stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Out of Stock',
                text: 'This product is currently unavailable!',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        const existingItem = cart.find(item => item.id == id);
        if (existingItem) {
            if (existingItem.quantity < stock) {
                existingItem.quantity++;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Insufficient Stock',
                    text: `Only ${stock} items available!`,
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                stock: stock
            });
        }
        
        // Quick visual feedback
        const badge = document.getElementById('cartCount');
        badge.classList.add('animate__animated', 'animate__rubberBand');
        setTimeout(() => badge.classList.remove('animate__rubberBand'), 1000);
        
        updateCartDisplay();
    }

    // Update cart display
    function updateCartDisplay() {
        const cartBody = document.getElementById('cartItems');
        const cartSummary = document.getElementById('cartSummary');
        const paymentSection = document.getElementById('paymentSection');
        const cartCount = document.getElementById('cartCount');
        
        if (cart.length === 0) {
            cartBody.innerHTML = `
                <div class="text-center py-5" id="emptyCart">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Cart is empty</p>
                    <small class="text-muted">Click products to add</small>
                </div>
            `;
            cartSummary.style.display = 'none';
            paymentSection.style.display = 'none';
            cartCount.textContent = '0';
            g_cartTotal = 0;
            return;
        }

        cartSummary.style.display = 'block';
        paymentSection.style.display = 'block';
        cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);

        let html = '';
        let calcSubtotal = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            calcSubtotal += itemTotal;
            
            html += `
                <div class="cart-item-row d-flex align-items-center p-2 animate__animated animate__fadeIn">
                    <div style="flex: 2; min-width: 0;" class="pe-2">
                        <div class="cart-item-name text-truncate small fw-bold" title="${item.name}">${item.name}</div>
                        <div class="cart-item-price x-small text-muted">₱${item.price.toFixed(2)}</div>
                    </div>
                    <div style="flex: 1;" class="d-flex justify-content-center">
                        <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border shadow-sm">
                            <button class="btn btn-link btn-sm p-0 text-danger hover-grow" type="button"
                                    onclick="updateQuantity(${index}, -1)">
                                <i class="fas fa-minus-circle fa-lg"></i>
                            </button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.stock}"
                                onfocus="focusedInputIndex = ${index}; this.select();"
                                onkeydown="handleKeyDown(event, ${index})"
                                oninput="handleQuantityTyping(${index}, this.value)"
                                onblur="handleQuantityBlur(${index}, this.value)"
                                style="width: 30px; border: none; text-align: center; font-weight: 800; background: transparent; color: #0f172a; font-size: 0.85rem;"
                            >
                            <button class="btn btn-link btn-sm p-0 text-success hover-grow" type="button"
                                    onclick="updateQuantity(${index}, 1)">
                                <i class="fas fa-plus-circle fa-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div style="flex: 1;" class="text-end fw-bold text-dark summary-value">
                        ₱${itemTotal.toFixed(2)}
                    </div>
                    <div style="width: 35px;" class="text-end">
                        <button class="btn btn-sm btn-link text-danger p-0 opacity-50 hover-opacity-100" 
                                onclick="removeItem(${index})" title="Void Item">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        if (focusedInputIndex === -1) {
            cartBody.innerHTML = html;
        } else {
            updateTotalsOnly();
        }

        // Auto Calculations
        const calcDiscount = currentCustomerType !== 'regular' ? calcSubtotal * 0.20 : 0;
        g_cartTotal = calcSubtotal - calcDiscount;
        
        // VAT Calculations
        const vatableSales = g_cartTotal / 1.12;
        const vatAmount = g_cartTotal - vatableSales;

        // Update summary
        document.getElementById('subtotal').textContent = `₱${calcSubtotal.toFixed(2)}`;
        
        const discountRow = document.getElementById('discountRow');
        const discountAmountEl = document.getElementById('discountAmount');
        const discountLabelEl = document.getElementById('discountLabel');

        if (calcDiscount > 0) {
            discountRow.style.display = 'flex';
            discountAmountEl.textContent = `-₱${calcDiscount.toFixed(2)}`;
            const label = currentCustomerType === 'senior' ? 'Senior' : 
                        currentCustomerType === 'pwd' ? 'PWD' : 'Pregnant';
            discountLabelEl.textContent = `Discount (20% - ${label}):`;
        } else {
            discountRow.style.display = 'none';
        }
        
        document.getElementById('vatableSales').textContent = `₱${vatableSales.toFixed(2)}`;
        document.getElementById('vatAmount').textContent = `₱${vatAmount.toFixed(2)}`;
        document.getElementById('total').textContent = `₱${g_cartTotal.toFixed(2)}`;
        
        calculateChange();
    }

    // New helper functions for direct editing
    function handleQuantityTyping(index, value) {
        if (value === '') return;
        
        let qty = parseInt(value);
        const item = cart[index];
        
        // Prevent negative or zero during typing
        if (isNaN(qty) || qty < 1) {
            qty = 1;
        }
        
        // Enforce stock limit
        if (qty > item.stock) {
            qty = item.stock;
            // Visual feedback could be added here
        }
        
        item.quantity = qty;
        updateTotalsOnly();
    }

    function handleQuantityBlur(index, value) {
        focusedInputIndex = -1;
        const qty = parseInt(value);
        
        if (isNaN(qty) || qty < 1) {
            removeItem(index);
        } else {
            updateCartDisplay(); 
        }
    }

    function handleKeyDown(event, index) {
        if (event.key === 'Enter') {
            event.target.blur();
        } else if (event.key === 'Escape') {
            updateCartDisplay(); // Restore original value
        }
    }

    function updateTotalsOnly() {
        let calcSubtotal = 0;
        cart.forEach(item => {
            calcSubtotal += item.price * item.quantity;
        });

        const calcDiscount = currentCustomerType !== 'regular' ? calcSubtotal * 0.20 : 0;
        const currentTotal = calcSubtotal - calcDiscount;
        
        const discountRow = document.getElementById('discountRow');
        const discountAmountEl = document.getElementById('discountAmount');
        if (discountRow) {
            if (calcDiscount > 0) {
                discountRow.style.display = 'flex';
                if (discountAmountEl) discountAmountEl.textContent = `-₱${calcDiscount.toFixed(2)}`;
                const label = currentCustomerType === 'senior' ? 'Senior' : 
                            currentCustomerType === 'pwd' ? 'PWD' : 'Pregnant';
                const labelEl = document.getElementById('discountLabel');
                if (labelEl) labelEl.textContent = `Discount (20% - ${label}):`;
            } else {
                discountRow.style.display = 'none';
            }
        }
        document.getElementById('total').textContent = `₱${currentTotal.toFixed(2)}`;
        
        const vatableSales = currentTotal / 1.12;
        const vatAmount = currentTotal - vatableSales;
        document.getElementById('vatableSales').textContent = `₱${vatableSales.toFixed(2)}`;
        document.getElementById('vatAmount').textContent = `₱${vatAmount.toFixed(2)}`;
        
        g_cartTotal = currentTotal;
        calculateChange();
        
        const rows = document.querySelectorAll('.cart-item-row');
        cart.forEach((item, idx) => {
            const totalCol = rows[idx]?.querySelector('.text-primary');
            if (totalCol) totalCol.textContent = `₱${(item.price * item.quantity).toFixed(2)}`;
        });
        
        document.getElementById('cartCount').textContent = cart.reduce((t, i) => t + i.quantity, 0);
    }

    // Update quantity (step change)
    function updateQuantity(index, change) {
        const item = cart[index];
        const newQuantity = item.quantity + change;
        setQuantity(index, newQuantity);
    }

    // Set absolute quantity
    function setQuantity(index, val) {
        const item = cart[index];
        const newQuantity = parseInt(val) || 1;
        
        if (newQuantity < 1) {
            removeItem(index);
        } else if (newQuantity <= item.stock) {
            item.quantity = newQuantity;
            updateCartDisplay();
        } else {
            item.quantity = item.stock; 
            Swal.fire({
                icon: 'warning',
                title: 'Stock Limit Reached',
                text: `Only ${item.stock} items available.`,
                timer: 1500,
                showConfirmButton: false
            });
            updateCartDisplay();
        }
    }

    // Remove item
    function removeItem(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    }

    // Clear cart
    function clearCart() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Clear Cart?',
            text: 'Remove all items?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                updateCartDisplay();
            }
        });
    }

    // Customer type change
    document.querySelectorAll('input[name="customerType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentCustomerType = this.value;
            updateCartDisplay();
        });
    });

    // Payment method change (Simplified as only Cash is available)
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentPaymentMethod = this.value;
            document.getElementById('cashInput').style.display = 'block';
            document.getElementById('changeRow').style.display = 'block';
        });
    });

    // Calculate change
    function calculateChange() {
        const amountTenderedEl = document.getElementById('amountTendered');
        const changeRow = document.getElementById('changeRow');
        const amountTendered = parseFloat(amountTenderedEl.value) || 0;
        
        if (currentPaymentMethod === 'cash') {
            changeRow.style.display = 'block';
            const change = amountTendered - g_cartTotal;
            const changeEl = document.getElementById('change');
            const changeIcon = document.getElementById('changeIcon');
            
            if (change < 0) {
                changeEl.textContent = `₱${Math.abs(change).toFixed(2)}`;
                changeEl.classList.remove('text-white', 'text-warning');
                changeEl.classList.add('text-danger');
                if (changeIcon) changeIcon.classList.replace('text-warning', 'text-danger');
            } else {
                changeEl.textContent = `₱${change.toFixed(2)}`;
                changeEl.classList.remove('text-danger');
                changeEl.classList.add('text-primary');
                if (changeIcon) {
                    changeIcon.classList.remove('text-danger');
                    changeIcon.classList.add('text-primary');
                }
            }
        }
    }

    document.getElementById('amountTendered').addEventListener('input', calculateChange);

    // Checkout function
    function checkout() {
        if (cart.length === 0) return;

        if (currentPaymentMethod === 'cash') {
            const amountTendered = parseFloat(document.getElementById('amountTendered').value) || 0;
            if (amountTendered < g_cartTotal) {
                Swal.fire({ icon: 'error', title: 'Insufficient Payment' });
                return;
            }
        }

        let finalSubtotal = 0;
        const finalItems = cart.map(item => {
            finalSubtotal += item.price * item.quantity;
            return {
                product_id: parseInt(item.id),
                quantity: item.quantity,
                price: item.price
            };
        });
        const finalDiscount = currentCustomerType !== 'regular' ? finalSubtotal * 0.20 : 0;
        const finalTotal = finalSubtotal - finalDiscount;
        const finalTax = (finalTotal / 1.12) * 0.12;

        Swal.fire({
            title: 'Complete Transaction?',
            text: `Total: ₱${finalTotal.toFixed(2)}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Complete'
        }).then((result) => {
            if (result.isConfirmed) {
                processCheckout(finalItems, finalDiscount, finalTax, finalTotal);
            }
        });
    }

    function processCheckout(finalItems, finalDiscount, finalTax, finalTotal) {
        const amountTendered = parseFloat(document.getElementById('amountTendered').value) || finalTotal;

        fetch('{{ route("cashier.pos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: finalItems,
                customer_type: currentCustomerType,
                payment_method: currentPaymentMethod,
                amount_tendered: amountTendered,
                discount_amount: finalDiscount,
                tax_amount: finalTax,
                total_amount: finalTotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Transaction Successful!', timer: 2000 });
                cart = [];
                updateCartDisplay();
                if (data.receipt) showReceipt(data.receipt);
            }
        });
    }

    function showReceipt(receipt) {
        const receiptContent = document.getElementById('receiptContent');
        let itemsHtml = '';
        
        receipt.items.forEach(item => {
            // Find name from our local products array
            const product = products.find(p => p.id == item.product_id);
            const name = product ? product.product_name : 'Unknown Product';
            itemsHtml += `
                <div class="d-flex justify-content-between mb-1 small">
                    <span>${item.quantity} x ${name}</span>
                    <span>₱${(item.quantity * item.price).toFixed(2)}</span>
                </div>
            `;
        });

        const discountHtml = receipt.discount_amount > 0 ? `
            <div class="d-flex justify-content-between mb-1 small text-danger fw-bold">
                <span>Discount (${receipt.discount_type})</span>
                <span>-₱${parseFloat(receipt.discount_amount).toFixed(2)}</span>
            </div>
        ` : '';

        receiptContent.innerHTML = `
            <div class="text-center mb-4">
                <h5 class="fw-bold mb-0">${document.title.split('|')[0].trim()}</h5>
                <p class="small text-muted mb-0">Receipt: ${receipt.receipt_no}</p>
                <p class="small text-muted">${receipt.date}</p>
            </div>
            <div class="border-bottom border-top py-3 mb-3">
                ${itemsHtml}
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1 small">
                    <span>Subtotal</span>
                    <span>₱${parseFloat(receipt.subtotal).toFixed(2)}</span>
                </div>
                ${discountHtml}
                <div class="d-flex justify-content-between mb-1 small opacity-75">
                    <span>Vatable Amount</span>
                    <span>₱${parseFloat(receipt.tax_amount / 0.12).toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small opacity-75">
                    <span>VAT (12%)</span>
                    <span>₱${parseFloat(receipt.tax_amount).toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2 pt-2 border-top">
                    <span>TOTAL</span>
                    <span class="text-primary">₱${parseFloat(receipt.total).toFixed(2)}</span>
                </div>
            </div>
            <div class="mb-0 pt-2 border-top small text-muted">
                <div class="d-flex justify-content-between">
                    <span>Payment Method:</span>
                    <span>${receipt.payment_method.toUpperCase()}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Cash Received:</span>
                    <span>₱${parseFloat(receipt.amount_tendered).toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark">
                    <span>Change:</span>
                    <span>₱${parseFloat(receipt.change).toFixed(2)}</span>
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top">
                <p class="mb-0 small fw-bold">Thank you for shopping!</p>
                <p class="small text-muted">Please come again</p>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
    }

    // Barcode Scanner Logic
    function startBarcodeScanner() {
        const scannerModal = new bootstrap.Modal(document.getElementById('scannerModal'));
        scannerModal.show();
        
        // Use a timeout to ensure modal is visible before starting camera
        setTimeout(() => {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Handle the scanned code as you would with any other scan
                console.log(`Code matched = ${decodedText}`, decodedResult);
                
                // Add to cart based on barcode
                processScannedBarcode(decodedText);
                
                // Stop scanning and close modal
                stopBarcodeScanner();
                scannerModal.hide();
                
                // Success feedback
                Swal.fire({
                    icon: 'success',
                    title: 'Product Scanned!',
                    text: decodedText,
                    timer: 1000,
                    showConfirmButton: false
                });
            };
            
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch(err => {
                    console.error("Camera start failed", err);
                    document.getElementById('reader').innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Camera access denied or not available.
                        </div>`;
                });
                
            scannerActive = true;
        }, 500);
    }

    function stopBarcodeScanner() {
        if (html5QrCode && scannerActive) {
            html5QrCode.stop().then((ignore) => {
                // QR Code scanning is stopped.
                scannerActive = false;
            }).catch((err) => {
                // Stop failed, handle it.
                console.error("Stop failed", err);
            });
        }
    }

    // Stop camera when modal is closed
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function () {
        stopBarcodeScanner();
    });

    function processScannedBarcode(barcode) {
        // Find product in catalog
        const item = document.querySelector(`.product-item[data-barcode="${barcode}"]`);
        if (item) {
            addToCart(item);
        } else {
            // If not in catalog, search via API
            fetch(`{{ url('cashier/pos/product') }}/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create a temporary element to pass to addToCart
                        const tempEl = document.createElement('div');
                        tempEl.className = 'product-item';
                        tempEl.dataset.id = data.product.id;
                        tempEl.dataset.name = data.product.name;
                        tempEl.dataset.price = data.product.price;
                        tempEl.dataset.stock = data.product.stock;
                        addToCart(tempEl);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: `Product with barcode ${barcode} not found in system.`
                        });
                    }
                });
        }
    }

    function processManualBarcode() {
        const barcode = document.getElementById('manualBarcode').value;
        if (barcode) {
            processScannedBarcode(barcode);
            document.getElementById('manualBarcode').value = '';
            bootstrap.Modal.getInstance(document.getElementById('scannerModal')).hide();
        }
    }

    // View Toggle
    function setView(view) {
        const grid = document.getElementById('productGrid');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'list') {
            grid.classList.add('list-view');
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        } else {
            grid.classList.remove('list-view');
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        }
    }

    // Add to cart by product ID (for button click)
    function addToCartById(productId) {
        const item = document.querySelector(`.product-item[data-id="${productId}"]`);
        if (item) {
            const stock = parseInt(item.dataset.stock);
            if (stock <= 0) {
                Swal.fire({ icon: 'error', title: 'Out of Stock', text: 'This product is currently unavailable!', timer: 1500, showConfirmButton: false });
                return;
            }
            const id = item.dataset.id;
            const name = item.dataset.name;
            const price = parseFloat(item.dataset.price);
            const existingItem = cart.find(i => i.id == id);
            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    Swal.fire({ icon: 'warning', title: 'Stock Limit', text: `Only ${stock} items available!`, timer: 1500, showConfirmButton: false });
                    return;
                }
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
            }
            const badge = document.getElementById('cartCount');
            badge.classList.add('animate__animated', 'animate__rubberBand');
            setTimeout(() => badge.classList.remove('animate__rubberBand'), 1000);
            updateCartDisplay();
        }
    }

    // Search and Category logic
    document.getElementById('searchProduct').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const barcode = (item.dataset.barcode || '').toLowerCase(); // Use dataset barcode
            item.style.display = (name.includes(search) || barcode.includes(search)) ? '' : 'none';
        });
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        const category = this.value;
        document.querySelectorAll('.product-item').forEach(item => {
            item.style.display = (!category || item.dataset.category === category) ? '' : 'none';
        });
    });
</script>
@endpush

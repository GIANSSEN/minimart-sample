<div class="col-12">
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0" role="table">
                <thead class="bg-light">
                    <tr>
                        <th width="40" class="ps-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllDesktop" aria-label="Select all products">
                            </div>
                        </th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th width="130" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(is_countable($products) ? count($products) > 0 : !empty($products))
@foreach($products as $product)
                    <tr class="product-row-{{ $product->id }}" role="row">
                        <td class="ps-4">
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}" aria-label="Select {{ $product->product_name }}">
                            </div>
                        </td>
                        <td data-label="Product">
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-thumb-wrapper flex-shrink-0">
                                    @php
                                        $rawImage = str_replace('\\', '/', (string) ($product->image ?? ''));
                                        $imageUrl = '';
                                        if ($rawImage !== '') {
                                            if (\Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://', '/'])) {
                                                $imageUrl = $rawImage;
                                            } else {
                                                if (!str_contains($rawImage, '/')) {
                                                    $rawImage = 'uploads/products/' . $rawImage;
                                                }
                                                $imageUrl = asset($rawImage);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $imageUrl }}"
                                         alt=""
                                         class="product-thumb {{ $imageUrl ? '' : 'd-none' }}"
                                         loading="lazy"
                                         onerror="this.classList.add('d-none'); this.closest('.product-thumb-wrapper').querySelector('.product-thumb-placeholder').classList.remove('d-none');">
                                    <div class="product-thumb-placeholder {{ $imageUrl ? 'd-none' : '' }}">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $product->product_name }}</h6>
                                    <small class="text-muted">{{ $product->product_code }}</small>
                                    @if ($product->barcode)
                                        <br><small class="text-muted-500"><i class="fas fa-barcode me-1"></i>{{ $product->barcode }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td data-label="Category">
                            <span class="badge badge-modern bg-light text-dark">
                                <i class="fas fa-tag me-1 text-secondary"></i>
                                {{ $product->category->category_name ?? '—' }}
                            </span>
                        </td>
                        <td data-label="Brand">
                            <span class="text-muted small">{{ $product->brand ?? '—' }}</span>
                        </td>
                        <td data-label="Supplier">
                            <span class="text-muted small">{{ $product->supplier->supplier_name ?? '—' }}</span>
                        </td>
                        <td data-label="Price">
                            <div>
                                <span class="fw-semibold">₱{{ number_format($product->selling_price, 2) }}</span>
                                @if ($product->discount_percent > 0)
                                    <br><small class="text-success">{{ $product->discount_percent }}% off</small>
                                @endif
                            </div>
                        </td>
                        <td data-label="Stock">
                            @php
                                $qty = $product->current_stock;
                                $stockClass = $product->stock_badge_class;
                                $stockLabel = $product->stock_status_label;
                            @endphp
                            <div class="d-flex flex-column gap-1">
                                <span class="badge-modern text-{{ $stockClass }}">
                                    <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>
                                    {{ $qty }} {{ $product->unit }}
                                </span>
                                <small class="text-{{ $stockClass }} fw-semibold" style="font-size:0.7rem;">{{ $stockLabel }}</small>
                            </div>
                        </td>
                        <td class="text-end pe-4" data-label="Actions">
                            <div class="d-flex gap-2 justify-content-end">
                                <button onclick="showProduct({{ $product->id }})"
                                        class="btn-view" title="View product details"
                                        aria-label="View {{ $product->product_name }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editProduct({{ $product->id }})"
                                        class="btn-edit" title="Edit product"
                                        aria-label="Edit {{ $product->product_name }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->product_name) }}')"
                                        class="btn-del" title="Delete product"
                                        aria-label="Delete {{ $product->product_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
@else
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <h5>No products found</h5>
                                <p class="text-muted">Try adjusting your filters or add a new product.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-gradient-secondary mt-3">
                                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                <div class="pagination-modern">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .product-thumb {
        width: 48px !important;
        height: 48px !important;
        min-width: 48px;
        min-height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    .product-thumb:hover { transform: scale(1.1); }
    .product-thumb-placeholder {
        width: 48px !important;
        height: 48px !important;
        min-width: 48px;
        min-height: 48px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea22, #764ba222);
        border: 1.5px dashed #667eea55;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.2rem;
    }
    .product-thumb-wrapper { 
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .table-modern td[data-label="Product"] {
        min-width: 200px;
    }
    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

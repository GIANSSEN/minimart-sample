<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-{{ $type == 'expired' ? 'skull-crosswalk' : ($type == 'near_expiry' ? 'hourglass-half' : ($type == 'valid' ? 'check-circle' : 'box')) }} text-{{ $color }} me-2"></i>
            {{ $title }}
        </h5>
        <div>
            <span class="badge bg-{{ $color }} me-2">{{ $products->total() }} total</span>
            @if (in_array($type, ['expired', 'near_expiry']))
            <button class="btn btn-sm btn-outline-{{ $color }}" onclick="exportTable()">
                <i class="fas fa-download"></i>
            </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if ($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            @if (in_array($type, ['expired', 'near_expiry']))
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="selectAll-{{ $type }}">
                            </th>
                            @endif
                            <th>Product</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Stock</th>
                            <th>Manufacturing Date</th>
                            <th>Expiry Date</th>
                            <th>Days Left</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        @php
                            $daysUntilExpiry = $product->has_expiry && $product->expiry_date 
                                ? now()->diffInDays($product->expiry_date, false) 
                                : null;
                        @endphp
                        <tr>
                            @if (in_array($type, ['expired', 'near_expiry']))
                            <td>
                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                            </td>
                            @endif
                            <td>
                                <div>
                                    <strong>{{ $product->product_name }}</strong>
                                    @if ($product->product_type == 'perishable')
                                        <span class="badge bg-info ms-1">Perishable</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $product->product_code }}</small>
                                </div>
                            </td>
                            <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                            <td>{{ $product->supplier->supplier_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->stock->quantity > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock->quantity ?? 0 }} {{ $product->unit }}
                                </span>
                            </td>
                            <td>
                                @if ($product->manufacturing_date)
                                    {{ $product->manufacturing_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->expiry_date)
                                    <strong>{{ $product->expiry_date->format('M d, Y') }}</strong>
                                @else
                                    <span class="text-muted">No Expiry</span>
                                @endif
                            </td>
                            <td>
                                @if ($daysUntilExpiry !== null)
                                    @if ($daysUntilExpiry < 0)
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif ($daysUntilExpiry <= 7)
                                        <span class="badge bg-warning text-dark">{{ $daysUntilExpiry }} days</span>
                                    @elseif ($daysUntilExpiry <= 30)
                                        <span class="badge bg-info">{{ $daysUntilExpiry }} days</span>
                                    @else
                                        <span class="badge bg-success">{{ $daysUntilExpiry }} days</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                {!! $product->expiry_badge !!}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($product->has_expiry && $product->expiry_status != 'expired')
                                    <button class="btn btn-warning" onclick="extendExpiry({{ $product->id }})" title="Extend Expiry">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                    @endif
                                    @if ($product->has_expiry && $product->expiry_status != 'expired')
                                    <button class="btn btn-danger" onclick="markAsExpired({{ $product->id }})" title="Mark as Expired">
                                        <i class="fas fa-skull-crosswalk"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $products->links() }}
            </div>

            <!-- Select All Script for this tab -->
            @if (in_array($type, ['expired', 'near_expiry']))
            <script>
                document.getElementById('selectAll-{{ $type }}')?.addEventListener('change', function() {
                    document.querySelectorAll('#{{ $type }} .product-checkbox').forEach(cb => {
                        cb.checked = this.checked;
                    });
                });
            </script>
            @endif
        @else
            <div class="text-center py-5">
                <div class="empty-state-icon mx-auto mb-3" style="width: 80px; height: 80px; background: #f8f9fc; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <p class="text-muted">No {{ strtolower($title) }} found.</p>
            </div>
        @endif
    </div>
</div>

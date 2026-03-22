{{-- resources/views/admin/products/partials/expiry-table.blade.php --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-{{ $color }} text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-{{ $type == 'expired' ? 'exclamation-triangle' : 'clock' }} me-2"></i>
            {{ $title }}
        </h5>
        <span class="badge bg-light text-dark">{{ $products->total() }} products</span>
    </div>
    <div class="card-body">
        @if ($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Expiry Date</th>
                            <th>Days {{ $type == 'expired' ? 'Expired' : 'Remaining' }}</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $daysUntilExpiry = now()->diffInDays($product->expiry_date, false);
                                $expiryDate = \Carbon\Carbon::parse($product->expiry_date);
                                $today = now();
                                $daysDiff = $today->diffInDays($expiryDate, false);
                                
                                if ($type == 'expired') {
                                    $statusClass = 'danger';
                                    $statusText = 'Expired';
                                    $daysText = abs(round($daysDiff)) . ' days ago';
                                } else {
                                    if ($daysDiff <= 3) {
                                        $statusClass = 'danger';
                                        $statusText = 'Critical';
                                    } elseif ($daysDiff <= 7) {
                                        $statusClass = 'warning';
                                        $statusText = 'Warning';
                                    } else {
                                        $statusClass = 'info';
                                        $statusText = 'Monitoring';
                                    }
                                    $daysText = round($daysDiff) . ' days remaining';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->product_code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->product_name }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-box text-{{ $color }}"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $product->product_name }}</strong>
                                            @if ($product->brand)
                                                <br><small class="text-muted">{{ $product->brand }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($product->stock > 10)
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @elseif ($product->stock > 0)
                                        <span class="badge bg-warning">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">
                                        {{ $expiryDate->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $daysText }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.show', $product->id) }}" 
                                           class="btn btn-outline-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Edit Product">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($type == 'expired')
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    onclick="markAsDiscontinued({{ $product->id }})"
                                                    title="Mark as Discontinued">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-warning" 
                                                    onclick="sendExpiryAlert({{ $product->id }})"
                                                    title="Send Alert">
                                                <i class="fas fa-bell"></i>
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
            <div class="d-flex justify-content-end mt-3">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="empty-state-icon mx-auto mb-3" 
                     style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-{{ $type == 'expired' ? 'check-circle' : 'clock' }} fa-3x text-{{ $color }}"></i>
                </div>
                <h5 class="text-muted mb-2">No {{ $type == 'expired' ? 'Expired' : 'Near Expiry' }} Products</h5>
                <p class="text-muted mb-0">
                    @if ($type == 'expired')
                        All products are within their expiry dates. Great job!
                    @else
                        No products are nearing their expiry date.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function markAsDiscontinued(productId) {
        Swal.fire({
            title: 'Mark as Discontinued?',
            text: 'This product will be marked as discontinued and removed from active inventory.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, mark as discontinued',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your AJAX call here to mark as discontinued
                fetch(`/admin/products/${productId}/discontinue`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Product has been marked as discontinued.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                });
            }
        });
    }

    function sendExpiryAlert(productId) {
        Swal.fire({
            title: 'Send Expiry Alert?',
            text: 'This will send a notification to relevant staff about this product nearing expiry.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, send alert',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your AJAX call here to send alert
                fetch(`/admin/products/${productId}/send-expiry-alert`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Alert Sent!',
                            text: 'Expiry notification has been sent successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to send alert. Please try again.'
                    });
                });
            }
        });
    }
</script>
@endpush

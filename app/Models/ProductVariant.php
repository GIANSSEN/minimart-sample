<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $full_name
 * @property-read mixed $stock_status
 * @property-read mixed $stock_status_color
 * @property-read mixed $stock_status_label
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItems
 * @property-read int|null $sale_items_count
 * @property-read \App\Models\Stock|null $stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant byProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant withoutTrashed()
 * @mixin \Eloquent
 */
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'variant_code',
        'variant_name',
        'variant_value',
        'sku',
        'barcode',
        'cost_price',
        'selling_price',
        'wholesale_price',
        'quantity',
        'reorder_level',
        'image',
        'attributes',
        'status',
        'created_by'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'quantity' => 'integer',
        'reorder_level' => 'integer',
        'attributes' => 'array'
    ];

    /**
     * Get the parent product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the stock for this variant.
     */
    public function stock()
    {
        return $this->morphOne(Stock::class, 'stockable');
    }

    /**
     * Get the stock transactions for this variant.
     */
    public function stockTransactions()
    {
        return $this->morphMany(StockTransaction::class, 'transactionable');
    }

    /**
     * Get the user who created the variant.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the sale items for this variant.
     */
    public function saleItems()
    {
        return $this->morphMany(SaleItem::class, 'itemable');
    }

    /**
     * Get stock status attribute.
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->quantity <= $this->reorder_level) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Get stock status color attribute.
     */
    public function getStockStatusColorAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get stock status label attribute.
     */
    public function getStockStatusLabelAttribute()
    {
        return match($this->stock_status) {
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'in_stock' => 'In Stock',
            default => 'Unknown',
        };
    }

    /**
     * Get full variant name attribute (Product Name + Variant).
     */
    public function getFullNameAttribute()
    {
        return $this->product->product_name . ' - ' . $this->variant_name . ': ' . $this->variant_value;
    }

    /**
     * Scope a query to only include active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include low stock variants.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= reorder_level')
                     ->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include out of stock variants.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    /**
     * Scope a query to search variants.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('variant_name', 'LIKE', "%{$search}%")
              ->orWhere('variant_value', 'LIKE', "%{$search}%")
              ->orWhere('variant_code', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('barcode', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by product.
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Update stock quantity.
     */
    public function updateQuantity($newQuantity, $type = 'set')
    {
        $oldQuantity = $this->quantity;
        
        if ($type === 'add') {
            $this->quantity += $newQuantity;
        } elseif ($type === 'subtract') {
            $this->quantity -= $newQuantity;
        } else {
            $this->quantity = $newQuantity;
        }
        
        $this->save();
        
        return [
            'old' => $oldQuantity,
            'new' => $this->quantity,
            'difference' => $this->quantity - $oldQuantity
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $variation_code
 * @property string $variation_name
 * @property string $variation_type
 * @property string|null $value
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $full_name
 * @property-read mixed $stock_status
 * @property-read mixed $stock_status_color
 * @property-read mixed $stock_status_label
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItems
 * @property-read int|null $sale_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation byProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereVariationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereVariationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereVariationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation withoutTrashed()
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariation whereStatus($value)
 * @mixin \Eloquent
 */
class ProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variations';

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'sku',
        'cost_price',
        'selling_price',
        'quantity',
        'reorder_level',
        'image',
        'description'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'reorder_level' => 'integer'
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the sale items for this variation.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'variation_id');
    }

    /**
     * Get stock status attribute.
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->quantity <= ($this->reorder_level ?? 5)) {
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
     * Get full name attribute.
     */
    public function getFullNameAttribute()
    {
        return $this->name . ($this->value ? " - {$this->value}" : "");
    }


    /**
     * Scope low stock variations.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= reorder_level')
                     ->where('quantity', '>', 0);
    }

    /**
     * Scope out of stock variations.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    /**
     * Scope search.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('value', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Scope by product.
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
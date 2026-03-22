<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $product_code
 * @property string|null $barcode
 * @property string $product_name
 * @property string|null $description
 * @property int $category_id
 * @property int|null $supplier_id
 * @property string|null $brand
 * @property string $unit
 * @property bool $has_expiry
 * @property \Illuminate\Support\Carbon|null $manufacturing_date
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int|null $shelf_life_days
 * @property string $product_type
 * @property numeric $cost_price
 * @property numeric $selling_price
 * @property numeric|null $wholesale_price
 * @property numeric $discount_percent
 * @property numeric $tax_rate
 * @property int $reorder_level
 * @property int $reorder_quantity
 * @property int|null $max_level
 * @property int|null $min_level
 * @property string|null $shelf_location
 * @property string|null $image
 * @property bool $has_variants
 * @property string $inventory_status
 * @property bool $is_phase_out
 * @property \Illuminate\Support\Carbon|null $phase_out_date
 * @property string|null $phase_out_reason
 * @property bool $needs_reorder
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $current_stock
 * @property-read mixed $expiry_badge
 * @property-read mixed $expiry_status
 * @property-read mixed $in_stock
 * @property-read mixed $inventory_status_color
 * @property-read mixed $stock_badge_class
 * @property-read mixed $stock_display
 * @property-read mixed $stock_status
 * @property-read mixed $stock_status_color
 * @property-read mixed $stock_status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryLog> $inventoryLogs
 * @property-read int|null $inventory_logs_count
 * @property-read \App\Models\Stock|null $stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product inStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product nearExpiry($days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product phaseOut()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHasVariants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInventoryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsPhaseOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereManufacturingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMaxLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMinLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNeedsReorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePhaseOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePhaseOutReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereReorderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereReorderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShelfLifeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShelfLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWholesalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_code', 'barcode', 'product_name', 'description',
        'category_id', 'supplier_id', 'brand', 'unit',
        'has_expiry', 'manufacturing_date', 'expiry_date', 'shelf_life_days',
        'product_type', 'cost_price', 'selling_price', 'wholesale_price',
        'discount_percent', 'tax_rate', 'reorder_level', 'reorder_quantity',
        'max_level', 'min_level', 'shelf_location', 'image', 'has_variants',
        'inventory_status', 'is_phase_out', 'phase_out_date',
        'phase_out_reason', 'needs_reorder', 'created_by'
    ];

    protected $casts = [
        'has_expiry' => 'boolean',
        'is_phase_out' => 'boolean',
        'needs_reorder' => 'boolean',
        'has_variants' => 'boolean',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'phase_out_date' => 'date',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'reorder_level' => 'integer',
        'reorder_quantity' => 'integer',
        'max_level' => 'integer',
        'min_level' => 'integer'
    ];

    /**
     * The "booted" method of the model.
     * Automatically create stock record when product is created
     */
    protected static function booted()
    {
        static::created(function ($product) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => 0,
                'min_quantity' => $product->reorder_level ?? 10,
                'max_quantity' => $product->max_level ?? 1000,
                'location' => $product->shelf_location ?? 'A1',
            ]);
        });

        static::updating(function ($product) {
            // Update stock min/max when product reorder level changes
            if ($product->isDirty('reorder_level') || $product->isDirty('max_level')) {
                if ($product->stock) {
                    $product->stock->update([
                        'min_quantity' => $product->reorder_level,
                        'max_quantity' => $product->max_level,
                    ]);
                }
            }
        });
    }

    /**
     * =============================================
     * ACCESSOR METHODS - PARA SA EASY DISPLAY
     * =============================================
     */

    /**
     * Get the stock quantity from the stocks table
     * Ito ang gagamitin sa views para hindi na mag-check ng null
     */
    public function getCurrentStockAttribute()
    {
        return $this->stock->quantity ?? 0;
    }

    /**
     * Get the formatted stock display with unit
     */
    public function getStockDisplayAttribute()
    {
        $qty = $this->current_stock;
        return $qty . ' ' . $this->unit;
    }

    /**
     * Check if product is in stock
     */
    public function getInStockAttribute()
    {
        return ($this->stock->quantity ?? 0) > 0;
    }

    /**
     * Get stock badge class based on quantity
     */
    public function getStockBadgeClassAttribute()
    {
        $qty = $this->current_stock;
        
        if ($qty <= 0) {
            return 'danger';
        } elseif ($qty <= $this->reorder_level) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    /**
     * =============================================
     * RELATIONSHIP METHODS
     * =============================================
     */

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the stock record associated with the product.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get the user who created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the stock transactions for the product.
     */
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    /**
     * Get the inventory logs for the product.
     */
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * =============================================
     * STATUS ATTRIBUTES
     * =============================================
     */

    /**
     * Get expiry status attribute.
     */
    public function getExpiryStatusAttribute()
    {
        if (!$this->has_expiry || !$this->expiry_date) {
            return 'no_expiry';
        }

        $today = Carbon::today();
        $expiry = Carbon::parse($this->expiry_date);
        
        if ($expiry->lt($today)) {
            return 'expired';
        } elseif ($expiry->lte($today->copy()->addDays(7))) {
            return 'critical';
        } elseif ($expiry->lte($today->copy()->addDays(30))) {
            return 'near_expiry';
        } else {
            return 'valid';
        }
    }

    /**
     * Get expiry badge HTML attribute.
     */
    public function getExpiryBadgeAttribute()
    {
        return match($this->expiry_status) {
            'expired' => '<span class="badge bg-danger">Expired</span>',
            'critical' => '<span class="badge bg-danger">Critical</span>',
            'near_expiry' => '<span class="badge bg-warning text-dark">Near Expiry</span>',
            'valid' => '<span class="badge bg-success">Valid</span>',
            default => '<span class="badge bg-secondary">No Expiry</span>',
        };
    }

    /**
     * Get stock status attribute.
     */
    public function getStockStatusAttribute()
    {
        if (!$this->stock) {
            return 'no_stock';
        }

        $quantity = $this->stock->quantity;
        
        if ($quantity <= 0) {
            return 'out_of_stock';
        } elseif ($quantity <= $this->reorder_level) {
            return 'low_stock';
        } elseif ($this->max_level && $quantity >= $this->max_level) {
            return 'overstock';
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
            'overstock' => 'info',
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
            'overstock' => 'Overstock',
            'in_stock' => 'In Stock',
            default => 'No Stock',
        };
    }

    /**
     * Get inventory status color attribute.
     */
    public function getInventoryStatusColorAttribute()
    {
        return match($this->inventory_status) {
            'expired', 'out_of_stock' => 'danger',
            'near_expiry', 'low_stock', 'phase_out' => 'warning',
            'in_stock' => 'success',
            default => 'secondary',
        };
    }

    /**
     * =============================================
     * CUSTOM METHODS
     * =============================================
     */

    /**
     * Update inventory status based on stock and expiry.
     */
    public function updateInventoryStatus()
    {
        $stockQty = $this->stock ? $this->stock->quantity : 0;
        
        // Check phase out first
        if ($this->is_phase_out) {
            $this->inventory_status = 'phase_out';
        }
        // Check expiry for perishable items
        elseif ($this->has_expiry && $this->expiry_date) {
            $today = Carbon::today();
            $expiry = Carbon::parse($this->expiry_date);
            
            if ($expiry->lt($today)) {
                $this->inventory_status = 'expired';
            } elseif ($expiry->lte($today->copy()->addDays(7))) {
                $this->inventory_status = 'near_expiry';
            } elseif ($stockQty <= 0) {
                $this->inventory_status = 'out_of_stock';
            } elseif ($stockQty <= $this->reorder_level) {
                $this->inventory_status = 'low_stock';
            } else {
                $this->inventory_status = 'in_stock';
            }
        }
        // Non-perishable items
        else {
            if ($stockQty <= 0) {
                $this->inventory_status = 'out_of_stock';
            } elseif ($stockQty <= $this->reorder_level) {
                $this->inventory_status = 'low_stock';
            } else {
                $this->inventory_status = 'in_stock';
            }
        }

        // Update reorder flag
        $this->needs_reorder = ($stockQty <= $this->reorder_level && $stockQty > 0);
        
        $this->saveQuietly();
    }

    /**
     * =============================================
     * SCOPES
     * =============================================
     */

    /**
     * Scope a query to only include expired products.
     */
    public function scopeExpired($query)
    {
        return $query->where('has_expiry', true)
                     ->where('expiry_date', '<', Carbon::today());
    }

    /**
     * Scope a query to only include products near expiry.
     */
    public function scopeNearExpiry($query, $days = 30)
    {
        return $query->where('has_expiry', true)
                     ->where('expiry_date', '>=', Carbon::today())
                     ->where('expiry_date', '<=', Carbon::today()->copy()->addDays($days));
    }

    /**
     * Scope a query to only include low stock products.
     */
    public function scopeLowStock($query)
    {
        return $query->join('stocks', 'products.id', '=', 'stocks.product_id')
                     ->whereRaw('stocks.quantity <= products.reorder_level')
                     ->where('stocks.quantity', '>', 0)
                     ->select('products.*');
    }

    /**
     * Scope a query to only include out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where(function($q) {
            $q->whereHas('stock', function($sq) {
                $sq->where('quantity', '<=', 0);
            })->orWhereDoesntHave('stock');
        });
    }

    /**
     * Scope a query to only include in stock products.
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('stock', function($q) {
            $q->where('quantity', '>', 0);
        });
    }

    /**
     * Scope a query to only include phase out products.
     */
    public function scopePhaseOut($query)
    {
        return $query->where('is_phase_out', true);
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('product_name', 'LIKE', "%{$search}%")
              ->orWhere('product_code', 'LIKE', "%{$search}%")
              ->orWhere('barcode', 'LIKE', "%{$search}%")
              ->orWhere('brand', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Get inventory alerts summary.
     */
    public static function getInventoryAlerts()
    {
        return [
            'expired' => self::expired()->count(),
            'near_expiry' => self::nearExpiry(7)->count(),
            'low_stock' => self::lowStock()->count(),
            'out_of_stock' => self::outOfStock()->count(),
            'phase_out' => self::phaseOut()->count(),
        ];
    }
}
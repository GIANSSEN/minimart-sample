<?php
// app/Models/Stock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $min_quantity
 * @property int|null $max_quantity
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $status
 * @property-read mixed $status_color
 * @property-read mixed $status_label
 * @property-read \App\Models\User|null $lastCounter
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereMaxQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'product_id',
        'quantity',
        'min_quantity',
        'max_quantity',
        'location',
        'last_counted_at',
        'last_counted_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'last_counted_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function lastCounter()
    {
        return $this->belongsTo(User::class, 'last_counted_by');
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    public function isOverstock()
    {
        return $this->max_quantity && $this->quantity >= $this->max_quantity;
    }

    public function getStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } elseif ($this->isOverstock()) {
            return 'overstock';
        }
        return 'normal';
    }

    public function getStatusColorAttribute()
    {
        return [
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'overstock' => 'info',
            'normal' => 'success'
        ][$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'overstock' => 'Overstock',
            'normal' => 'Normal'
        ][$this->status] ?? 'Unknown';
    }

    /**
     * Increase stock quantity
     */
    public function increase($quantity, $reason, $userId = null)
    {
        $oldQuantity = $this->quantity;
        $this->quantity += $quantity;
        $this->save();

        $this->createTransaction('in', $quantity, $oldQuantity, $this->quantity, $reason, $userId);
        $this->product->updateInventoryStatus();

        return $this;
    }

    /**
     * Decrease stock quantity
     */
    public function decrease($quantity, $reason, $userId = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Insufficient stock');
        }

        $oldQuantity = $this->quantity;
        $this->quantity -= $quantity;
        $this->save();

        $this->createTransaction('out', $quantity, $oldQuantity, $this->quantity, $reason, $userId);
        $this->product->updateInventoryStatus();

        return $this;
    }

    /**
     * Set stock quantity (adjustment)
     */
    public function setQuantity($newQuantity, $reason, $userId = null)
    {
        $oldQuantity = $this->quantity;
        $this->quantity = $newQuantity;
        $this->save();

        $this->createTransaction('adjustment', abs($newQuantity - $oldQuantity), $oldQuantity, $newQuantity, $reason, $userId);
        $this->product->updateInventoryStatus();

        return $this;
    }

    /**
     * Create stock transaction record
     */
    private function createTransaction($type, $quantity, $oldQty, $newQty, $reason, $userId)
    {
        return StockTransaction::create([
            'stock_id' => $this->id,
            'product_id' => $this->product_id,
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'previous_quantity' => $oldQty,
            'new_quantity' => $newQty,
            'reason' => $reason,
        ]);
    }
}
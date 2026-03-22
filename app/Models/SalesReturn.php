<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property int $quantity
 * @property string $reason
 * @property numeric $refund_amount
 * @property string $status
 * @property int|null $processed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $processor
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Sale $sale
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereProcessedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesReturn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalesReturn extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'reason',
        'refund_amount',
        'status',
        'processed_by'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

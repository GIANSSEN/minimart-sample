<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $supplier_id
 * @property int $product_id
 * @property int $quantity
 * @property string $reason
 * @property string $return_date
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierReturn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SupplierReturn extends Model
{
    protected $fillable = [
        'supplier_id',
        'product_id',
        'quantity',
        'reason',
        'return_date',
        'status',
        'notes'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

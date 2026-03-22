<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $receipt_no
 * @property int $user_id
 * @property string|null $customer_name
 * @property string|null $customer_type
 * @property string|null $discount_type
 * @property numeric|null $discount_rate
 * @property numeric|null $discount_amount
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $total_amount
 * @property string $payment_method
 * @property numeric $amount_paid
 * @property numeric $change
 * @property string $status
 * @property int|null $voided_by
 * @property string|null $voided_at
 * @property string|null $void_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $items
 * @property-read int|null $items_count
 * @property int|null $cashier_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereReceiptNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereVoidReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereVoidedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereVoidedBy($value)
 * @mixin \Eloquent
 */
class Sale extends Model
{
    protected $table = 'sales';
    
    protected $fillable = [
        'receipt_no',
        'user_id',
        'customer_name',
        'customer_type',
        'discount_type',
        'discount_rate',
        'discount_amount',
        'subtotal',
        'tax',
        'total_amount',
        'payment_method',
        'amount_paid',
        'change',
        'status',
        'voided_by',
        'voided_at',
        'void_reason'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
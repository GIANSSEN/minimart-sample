<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $term_name
 * @property int $days_due
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereDaysDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereTermName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTerm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentTerm extends Model
{
    protected $fillable = [
        'term_name',
        'days_due',
        'description'
    ];
}

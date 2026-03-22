<?php
// app/Models/UnitOfMeasurement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $symbol
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement withoutTrashed()
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereStatus($value)
 * @mixin \Eloquent
 */
class UnitOfMeasurement extends Model
{
    use SoftDeletes;

    protected $table = 'unit_of_measurements';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'uom_id');
    }
}
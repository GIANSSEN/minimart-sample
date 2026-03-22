<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLog query()
 * @mixin \Eloquent
 */
class InventoryLog extends Model
{



    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'user_id',
        'old_quantity',
        'new_quantity',
        'adjustment',
        'reason',
        'type'
    ];
    
    protected $casts = [
        'old_quantity' => 'integer',
        'new_quantity' => 'integer',
        'adjustment' => 'integer'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
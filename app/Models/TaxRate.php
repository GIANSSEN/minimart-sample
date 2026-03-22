<?php
// app/Models/TaxRate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property-read int $days_since_expired
 * @property-read int $days_until_effective
 * @property-read string $effective_period
 * @property-read string $formatted_rate
 * @property-read int|null $products_count
 * @property-read string $status_badge
 * @property-read string $type_badge
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate default()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate withoutTrashed()
 * @property int $id
 * @property string $tax_code
 * @property string $name
 * @property numeric $rate
 * @property string $type
 * @property string|null $description
 * @property bool $is_default
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $effective_from
 * @property \Illuminate\Support\Carbon|null $effective_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereEffectiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereEffectiveTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaxRate extends Model
{
    use SoftDeletes;

    protected $table = 'tax_rates';

    protected $fillable = [
        'tax_code',
        'name',
        'rate',
        'type',
        'description',
        'is_default',
        'status',
        'effective_from',
        'effective_to'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_default' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the products that use this tax rate.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_taxes')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active tax rates.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include default tax rate.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get formatted rate with percentage symbol.
     */
    public function getFormattedRateAttribute(): string
    {
        return $this->rate . '%';
    }

    /**
     * Get type badge.
     */
    public function getTypeBadgeAttribute(): string
    {
        return $this->type === 'inclusive'
            ? '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2">Inclusive</span>'
            : '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Exclusive</span>';
    }

    /**
     * Get status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2">Active</span>'
            : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">Inactive</span>';
    }

    /**
     * Check if tax rate is currently effective.
     * FIXED: Added null checks and proper Carbon usage
     */
    public function isEffective(): bool
    {
        $today = Carbon::today();
        
        if ($this->effective_from instanceof Carbon) {
            if ($this->effective_from->gt($today)) {
                return false;
            }
        }
        
        if ($this->effective_to instanceof Carbon) {
            if ($this->effective_to->lt($today)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get product count attribute.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Get effective period display.
     * FIXED: Added null checks
     */
    public function getEffectivePeriodAttribute(): string
    {
        if (!$this->effective_from && !$this->effective_to) {
            return 'Always';
        }
        
        $from = 'Start';
        $to = 'End';
        
        if ($this->effective_from instanceof Carbon) {
            $from = $this->effective_from->format('M d, Y');
        }
        
        if ($this->effective_to instanceof Carbon) {
            $to = $this->effective_to->format('M d, Y');
        }
        
        return "{$from} - {$to}";
    }

    /**
     * Check if tax rate is currently active and effective.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->isEffective();
    }

    /**
     * Get days until effective.
     * FIXED: Added null check
     */
    public function getDaysUntilEffectiveAttribute(): int
    {
        if (!$this->effective_from instanceof Carbon) {
            return 0;
        }
        
        $today = Carbon::today();
        
        if ($this->effective_from->gt($today)) {
            return $today->diffInDays($this->effective_from);
        }
        
        return 0;
    }

    /**
     * Get days since expired.
     * FIXED: Added null check
     */
    public function getDaysSinceExpiredAttribute(): int
    {
        if (!$this->effective_to instanceof Carbon) {
            return 0;
        }
        
        $today = Carbon::today();
        
        if ($this->effective_to->lt($today)) {
            return $this->effective_to->diffInDays($today);
        }
        
        return 0;
    }
}
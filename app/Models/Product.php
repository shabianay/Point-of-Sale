<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'image',
        'selling_price',
        'discount_type',
        'discount_value',
        'unit',
        'stock',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getFinalPriceAttribute()
    {
        if (!$this->discount_type || $this->discount_value <= 0) {
            return (float) $this->selling_price;
        }
        if ($this->discount_type === 'percent') {
            return (float) $this->selling_price * (1 - $this->discount_value / 100);
        }
        return max(0, (float) $this->selling_price - $this->discount_value);
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_type && $this->discount_value > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function generateSku(): string
    {
        $prefix = strtoupper(substr($this->name, 0, 3));
        $random = Str::random(6);
        return $prefix . $random;
    }
}
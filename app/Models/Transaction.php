<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'table_number',
        'order_type',
        'user_id',
        'subtotal',
        'tax_amount',
        'service_charge_amount',
        'discount_amount',
        'total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'void_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_charge_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function generateCode(): string
    {
        return 'TRX-' . now()->format('Ymd') . '-' . str_pad(
            mt_rand(1, 9999),
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}
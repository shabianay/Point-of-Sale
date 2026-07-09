<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'store_address',
        'store_phone',
        'logo_path',
        'tax_rate',
        'service_charge',
        'active_payment_methods',
        'receipt_footer',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'active_payment_methods' => 'array',
    ];
}

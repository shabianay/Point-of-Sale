<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreSetting;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        StoreSetting::create([
            'store_name' => 'Toko Saya',
            'store_address' => 'Jl. Contoh No. 123',
            'store_phone' => '08123456789',
            'tax_rate' => 10.00,
            'service_charge' => 0,
            'active_payment_methods' => json_encode(['cash', 'qris', 'card', 'transfer']),
        ]);
    }
}

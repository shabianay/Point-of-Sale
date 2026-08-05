<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'code' => 'TRX-' . fake()->unique()->numerify('########'),
            'customer_name' => 'Walk-in Customer',
            'table_number' => '-',
            'order_type' => 'dine_in',
            'user_id' => User::factory(),
            'subtotal' => 15000,
            'tax_amount' => 1650,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 16650,
            'paid_amount' => 20000,
            'change_amount' => 3350,
            'payment_method' => 'cash',
            'status' => 'completed',
        ];
    }
}
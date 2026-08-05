<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransactionItem;
use App\Models\Transaction;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'product_id' => Product::factory(),
            'quantity' => 1,
            'unit_price' => 15000,
            'subtotal' => 15000,
        ];
    }
}
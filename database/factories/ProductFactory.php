<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->word(),
            'sku' => strtoupper(fake()->unique()->word()),
            'description' => fake()->sentence(),
            'selling_price' => 15000,
            'discount_type' => null,
            'discount_value' => 0,
            'unit' => 'pcs',
            'stock' => 50,
            'minimum_stock' => 5,
            'is_active' => true,
        ];
    }
}
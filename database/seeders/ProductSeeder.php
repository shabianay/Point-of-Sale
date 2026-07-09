<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Minuman
            ['category_id' => 2, 'name' => 'Caffe Latte', 'sku' => 'LAT001', 'selling_price' => 18000, 'stock' => 50, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Espresso', 'sku' => 'ESP001', 'selling_price' => 15000, 'stock' => 40, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Cappuccino', 'sku' => 'CAP001', 'selling_price' => 20000, 'stock' => 35, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Matcha Latte', 'sku' => 'MAT001', 'selling_price' => 22000, 'stock' => 30, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Teh Chai', 'sku' => 'TCH001', 'selling_price' => 12000, 'stock' => 45, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Kopi Susu', 'sku' => 'KOS001', 'selling_price' => 15000, 'stock' => 60, 'unit' => 'gelas'],
            // Makanan
            ['category_id' => 1, 'name' => 'Sandwich', 'sku' => 'SAN001', 'selling_price' => 25000, 'stock' => 30, 'unit' => 'pcs'],
            ['category_id' => 1, 'name' => 'Croissant', 'sku' => 'CRO001', 'selling_price' => 18000, 'stock' => 25, 'unit' => 'pcs'],
            ['category_id' => 1, 'name' => 'Pancake', 'sku' => 'PAN001', 'selling_price' => 22000, 'stock' => 20, 'unit' => 'porsi'],
            // Snack
            ['category_id' => 3, 'name' => 'Biscotti', 'sku' => 'BIS001', 'selling_price' => 8000, 'stock' => 80, 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Cookies', 'sku' => 'COO001', 'selling_price' => 10000, 'stock' => 100, 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Muffin', 'sku' => 'MUF001', 'selling_price' => 12000, 'stock' => 40, 'unit' => 'pcs'],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}

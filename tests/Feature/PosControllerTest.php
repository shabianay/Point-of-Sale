<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\StockMovement;

class PosControllerTest extends AuthTestCase
{
    public function test_kasir_can_view_pos(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeKasir();
        $category = Category::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/pos');

        $response->assertStatus(200);
    }

    public function test_add_item_returns_product_data(): void
    {
        $user = $this->makeKasir();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'selling_price' => 18000,
            'stock' => 5,
            'unit' => 'gelas',
        ]);

        $response = $this->actingAs($user)->post('/pos/add-item', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200)->assertJsonPath('product.id', $product->id);
    }

    public function test_calculate_change(): void
    {
        $user = $this->makeKasir();

        $response = $this->actingAs($user)->post('/pos/calculate-change', [
            'total' => 16650,
            'paid_amount' => 20000,
        ]);

        $response->assertStatus(200)->assertJsonPath('change', 3350);
    }

    public function test_checkout_creates_transaction_and_reduces_stock(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeKasir();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'selling_price' => 10000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)->post('/pos/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 10000, 'notes' => ''],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 30000,
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $tx = Transaction::first();
        $this->assertNotNull($tx);
        $this->assertEquals(20000, (float) $tx->subtotal);
        $this->assertEquals(8, $product->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'out',
        ]);
    }

    public function test_checkout_insufficient_stock_rejected(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeKasir();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'selling_price' => 10000,
            'stock' => 1,
        ]);

        $response = $this->actingAs($user)->post('/pos/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5, 'price' => 10000],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_checkout_auto_deactivates_out_of_stock_product(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeKasir();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'selling_price' => 10000,
            'stock' => 2,
        ]);

        $response = $this->actingAs($user)->post('/pos/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 10000],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 30000,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(0, $product->fresh()->stock);
        $this->assertFalse((bool) $product->fresh()->is_active);
    }
}
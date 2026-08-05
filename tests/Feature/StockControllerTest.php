<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;

class StockControllerTest extends AuthTestCase
{
    public function test_admin_can_view_stock_page(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->get('/stock');

        $response->assertStatus(200);
    }

    public function test_restock_adds_stock_and_creates_movement(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 5, 'is_active' => false]);

        $response = $this->actingAs($user)->post('/stock/restock', [
            'product_id' => $product->id,
            'quantity' => 20,
            'notes' => 'Dari supplier',
        ]);

        $response->assertRedirect(route('stock.index'));
        $this->assertEquals(25, $product->fresh()->stock);
        $this->assertTrue((bool) $product->fresh()->is_active);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 20,
            'reference_type' => 'restock',
        ]);
    }

    public function test_opname_updates_stock(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);

        $response = $this->actingAs($user)->post('/stock/opname', [
            'product_id' => $product->id,
            'physical_stock' => 7,
            'notes' => 'Opname bulanan',
        ]);

        $response->assertRedirect(route('stock.index'));
        $this->assertEquals(7, $product->fresh()->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 3,
            'reference_type' => 'opname',
        ]);
    }

    public function test_kasir_cannot_access_stock(): void
    {
        $user = $this->makeKasir();

        $response = $this->actingAs($user)->get('/stock');

        $response->assertStatus(403);
    }
}
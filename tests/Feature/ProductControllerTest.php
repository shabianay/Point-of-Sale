<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;

class ProductControllerTest extends AuthTestCase
{
    public function test_owner_can_list_products(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
    }

    public function test_owner_can_create_product(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/products', [
            'name' => 'Caffe Latte',
            'category_id' => $category->id,
            'selling_price' => 18000,
            'unit' => 'gelas',
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Caffe Latte']);
    }

    public function test_owner_can_update_product(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'name' => 'Lama']);

        $response = $this->actingAs($user)->put("/products/{$product->id}", [
            'name' => 'Update',
            'category_id' => $category->id,
            'sku' => $product->sku,
            'selling_price' => 20000,
            'unit' => 'pcs',
            'minimum_stock' => 3,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Update']);
    }

    public function test_owner_can_delete_product(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->delete("/products/{$product->id}");

        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_owner_can_toggle_product_status(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);

        $response = $this->actingAs($user)->post("/products/{$product->id}/toggle-status");

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => 0]);
    }

    public function test_kasir_cannot_access_products(): void
    {
        $user = $this->makeKasir();

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(403);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;

class DashboardTest extends AuthTestCase
{
    public function test_owner_can_view_dashboard(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeOwner();
        Category::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_payment_chart_data_returns_json(): void
    {
        $user = $this->makeOwner();
        Transaction::factory()->create(['payment_method' => 'cash']);

        $response = $this->actingAs($user)->get('/home/payment-chart');

        $response->assertStatus(200)
            ->assertJsonStructure(['labels', 'data']);
    }

    public function test_best_products_data_returns_json(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $tx = Transaction::factory()->create();
        \App\Models\TransactionItem::factory()->create([
            'transaction_id' => $tx->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'subtotal' => 45000,
        ]);

        $response = $this->actingAs($user)->get('/home/best-products');

        $response->assertStatus(200);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;

class ReportControllerTest extends AuthTestCase
{
    private function createSalesData(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $tx = Transaction::factory()->create(['user_id' => $user->id, 'status' => 'completed']);
        TransactionItem::factory()->create([
            'transaction_id' => $tx->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_price' => 10000,
            'subtotal' => 30000,
        ]);
    }

    public function test_owner_can_view_reports_index(): void
    {
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->get('/reports');

        $response->assertStatus(200);
    }

    public function test_owner_can_view_sales_report(): void
    {
        $user = $this->makeOwner();
        $this->createSalesData();

        $response = $this->actingAs($user)->get('/reports/sales');

        $response->assertStatus(200);
    }

    public function test_owner_can_view_best_products_report(): void
    {
        $user = $this->makeOwner();
        $this->createSalesData();

        $response = $this->actingAs($user)->get('/reports/best-products');

        $response->assertStatus(200);
    }

    public function test_owner_can_view_profit_report(): void
    {
        $user = $this->makeOwner();
        $this->createSalesData();

        $response = $this->actingAs($user)->get('/reports/profit');

        $response->assertStatus(200);
    }

    public function test_kasir_cannot_access_reports(): void
    {
        $user = $this->makeKasir();

        $response = $this->actingAs($user)->get('/reports');

        $response->assertStatus(403);
    }
}
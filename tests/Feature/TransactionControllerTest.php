<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StoreSetting;
use App\Models\ActivityLog;

class TransactionControllerTest extends AuthTestCase
{
    private function createTransactionWithItems(): Transaction
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);

        $tx = Transaction::factory()->create(['user_id' => $user->id]);
        TransactionItem::factory()->create([
            'transaction_id' => $tx->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 10000,
            'subtotal' => 20000,
        ]);
        return $tx;
    }

    public function test_user_can_list_transactions(): void
    {
        $user = $this->makeAdmin();
        Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/transactions');

        $response->assertStatus(200);
    }

    public function test_user_can_view_transaction_detail(): void
    {
        $user = $this->makeAdmin();
        $tx = $this->createTransactionWithItems();

        $response = $this->actingAs($user)->get("/transactions/{$tx->id}");

        $response->assertStatus(200);
    }

    public function test_void_transaction_restores_stock(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 5]);

        $tx = Transaction::factory()->create(['user_id' => $user->id]);
        TransactionItem::factory()->create([
            'transaction_id' => $tx->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post("/transactions/{$tx->id}/void", [
            'reason' => 'Salah input',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('voided', $tx->fresh()->status);
        $this->assertEquals(7, $product->fresh()->stock);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'void_transaction',
            'subject_type' => 'Transaction',
            'subject_id' => $tx->id,
        ]);
    }

    public function test_void_already_voided_transaction_rejected(): void
    {
        $user = $this->makeAdmin();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 5]);

        $tx = Transaction::factory()->create(['user_id' => $user->id, 'status' => 'voided']);

        $response = $this->actingAs($user)->post("/transactions/{$tx->id}/void", [
            'reason' => 'Coba lagi',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('voided', $tx->fresh()->status);
        $this->assertEquals(5, $product->fresh()->stock);
    }

    public function test_receipt_renders(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeAdmin();
        $tx = $this->createTransactionWithItems();

        $response = $this->actingAs($user)->get("/transactions/{$tx->id}/receipt");

        $response->assertStatus(200);
    }
}
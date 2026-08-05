<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;

class CategoryControllerTest extends AuthTestCase
{
    public function test_owner_can_list_categories(): void
    {
        $user = $this->makeOwner();
        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertStatus(200);
    }

    public function test_owner_can_create_category(): void
    {
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => 'Minuman',
            'description' => 'Kategori minuman',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Minuman']);
    }

    public function test_store_validates_required_name(): void
    {
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->post('/categories', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_owner_can_update_category(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create(['name' => 'Lama']);

        $response = $this->actingAs($user)->put("/categories/{$category->id}", [
            'name' => 'Baru',
            'description' => 'Desc',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Baru']);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_owner_can_delete_empty_category(): void
    {
        $user = $this->makeOwner();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete("/categories/{$category->id}");

        $response->assertRedirect(route('categories.index'));
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_kasir_cannot_access_categories(): void
    {
        $user = $this->makeKasir();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertStatus(403);
    }
}
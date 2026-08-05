<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserControllerTest extends AuthTestCase
{
    public function test_owner_can_list_users(): void
    {
        $user = $this->makeOwner();
        User::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_owner_can_create_user(): void
    {
        $user = $this->makeOwner();
        $role = Role::findByName('Kasir');

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@test.com',
            'password' => 'password123',
            'role' => $role->id,
        ]);

        $response->assertRedirect(route('users.index'));
        $created = User::where('email', 'kasirbaru@test.com')->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->hasRole('Kasir'));
    }

    public function test_owner_can_update_user(): void
    {
        $user = $this->makeOwner();
        $target = User::factory()->create();
        $role = Role::findByName('Admin');

        $response = $this->actingAs($user)->put("/users/{$target->id}", [
            'name' => 'Nama Update',
            'email' => $target->email,
            'role' => $role->id,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Nama Update']);
        $this->assertTrue($target->fresh()->hasRole('Admin'));
    }

    public function test_owner_can_deactivate_user(): void
    {
        $user = $this->makeOwner();
        $target = User::factory()->create();

        $response = $this->actingAs($user)->delete("/users/{$target->id}");

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_active' => 0]);
    }

    public function test_admin_cannot_access_user_management(): void
    {
        $user = $this->makeAdmin();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(403);
    }

    public function test_store_user_requires_unique_email(): void
    {
        $user = $this->makeOwner();
        $existing = User::factory()->create();
        $role = Role::findByName('Kasir');

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'Duplikat',
            'email' => $existing->email,
            'password' => 'password123',
            'role' => $role->id,
        ]);

        $response->assertSessionHasErrors('email');
    }
}
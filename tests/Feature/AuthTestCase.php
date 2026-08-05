<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StoreSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'manage users', 'manage products', 'manage categories',
            'manage stock', 'view reports', 'manage settings',
            'void transactions', 'do pos',
        ];
        foreach ($permissions as $name) {
            Permission::findOrCreate($name);
        }

        $owner = Role::findOrCreate('Owner');
        $owner->givePermissionTo($permissions);

        $admin = Role::findOrCreate('Admin');
        $admin->givePermissionTo([
            'manage products', 'manage categories', 'manage stock',
            'view reports', 'void transactions', 'do pos',
        ]);

        $kasir = Role::findOrCreate('Kasir');
        $kasir->givePermissionTo(['do pos']);

        $this->seedStoreSetting();
    }

    protected function makeUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);
        return $user;
    }

    protected function makeOwner(): User
    {
        return $this->makeUser('Owner');
    }

    protected function makeAdmin(): User
    {
        return $this->makeUser('Admin');
    }

    protected function makeKasir(): User
    {
        return $this->makeUser('Kasir');
    }

    protected function seedStoreSetting(): StoreSetting
    {
        return StoreSetting::firstOrCreate(
            ['store_name' => 'Test Store'],
            [
                'tax_rate' => 10,
                'service_charge' => 5,
                'receipt_footer' => 'Terima kasih',
            ]
        );
    }
}
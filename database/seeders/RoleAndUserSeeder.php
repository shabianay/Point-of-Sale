<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage stock']);
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'manage settings']);
        Permission::create(['name' => 'void transactions']);
        Permission::create(['name' => 'do pos']);

        $owner = Role::create(['name' => 'Owner']);
        $owner->givePermissionTo([
            'manage users', 'manage products', 'manage categories',
            'manage stock', 'view reports', 'manage settings',
            'void transactions', 'do pos',
        ]);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'manage products', 'manage categories',
            'manage stock', 'view reports', 'void transactions', 'do pos',
        ]);

        $kasir = Role::create(['name' => 'Kasir']);
        $kasir->givePermissionTo(['do pos']);

        $ownerUser = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@pos.test',
            'password' => bcrypt('password'),
        ]);
        $ownerUser->assignRole('Owner');

        $adminUser = User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@pos.test',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('Admin');

        $kasirUser = User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.test',
            'password' => bcrypt('password'),
        ]);
        $kasirUser->assignRole('Kasir');
    }
}

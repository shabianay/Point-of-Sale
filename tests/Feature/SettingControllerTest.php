<?php

namespace Tests\Feature;

class SettingControllerTest extends AuthTestCase
{
    public function test_owner_can_view_settings(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
    }

    public function test_owner_can_update_settings(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->put('/settings', [
            'store_name' => 'Toko Baru',
            'store_address' => 'Jl. Test 1',
            'store_phone' => '08123456789',
            'tax_rate' => 12,
            'service_charge' => 2,
            'receipt_footer' => 'Sampai jumpa',
            'active_payment_methods' => ['cash', 'qris'],
        ]);

        $response->assertRedirect(route('settings.index'));
        $this->assertDatabaseHas('store_settings', [
            'store_name' => 'Toko Baru',
            'tax_rate' => 12,
        ]);
    }

    public function test_settings_validates_store_name(): void
    {
        $this->seedStoreSetting();
        $user = $this->makeOwner();

        $response = $this->actingAs($user)->put('/settings', [
            'store_name' => '',
            'tax_rate' => 10,
            'service_charge' => 0,
        ]);

        $response->assertSessionHasErrors('store_name');
    }

    public function test_admin_cannot_access_settings(): void
    {
        $user = $this->makeAdmin();

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(403);
    }
}
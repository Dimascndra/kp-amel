<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/admin/profile');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_profile_and_password_page(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertSee('Ubah Kata Sandi');
        $response->assertSee('Profil');
        $response->assertSee($admin->email);
    }

    public function test_admin_can_update_profile_info(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->put('/admin/profile', [
            'name' => 'Admin Utama BPR',
            'email' => 'admin.baru@bankmajalengka.co.id',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHas('success');
        $admin->refresh();
        $this->assertEquals('Admin Utama BPR', $admin->name);
        $this->assertEquals('admin.baru@bankmajalengka.co.id', $admin->email);
    }

    public function test_admin_fails_password_update_with_wrong_current_password(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $admin->refresh();
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    public function test_admin_can_successfully_update_password(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'BprMajalengka2026!',
            'password_confirmation' => 'BprMajalengka2026!',
        ]);

        $response->assertSessionHas('success');
        $admin->refresh();
        $this->assertTrue(Hash::check('BprMajalengka2026!', $admin->password));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrangtuaDashboardNegativeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_tidak_bisa_mengakses_dashboard_orangtua()
    {
        // Tidak login

        $response = $this->get('/orangtua/dashboard');

        $response->assertRedirect('/login'); // redirect ke halaman login
    }

    #[Test]
    public function admin_tidak_boleh_mengakses_dashboard_orangtua()
    {
        $admin = User::create([
            'nik_anak' => '9999999999999999',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'nama_anak' => 'Admin Dummy', // walaupun bukan anak
        ]);

        $this->actingAs($admin);

        $response = $this->get('/orangtua/dashboard');

        $response->assertStatus(403); // karena diblok middleware role
    }
}

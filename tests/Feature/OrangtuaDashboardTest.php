<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrangtuaDashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function orangtua_dapat_mengakses_dashboard_dan_melihat_fitur_utama()
    {
        // Buat user orangtua (tanpa field 'name', tapi dengan 'nik_anak' dan 'nama_anak')
        $orangtua = User::create([
            'nik_anak' => '1234567890123456',
            'password' => bcrypt('password'),
            'role' => 'orangtua',
            'nama_anak' => 'Anak Uji Coba',
        ]);

        // Login sebagai orangtua
        $this->actingAs($orangtua);

        // Akses halaman dashboard orangtua
        $response = $this->get('/orangtua/dashboard');

        // Cek halaman berhasil dimuat
        $response->assertStatus(200);

        // Cek konten penting muncul di halaman
    $response->assertSeeText($orangtua->nama_anak);
    // imunisasi feature telah dihapus
    $response->assertSee('Deteksi');
    }
}

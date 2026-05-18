<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Immunization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImmunizationUpdateFailureUnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Immunization feature removed');
    }

    public function test_non_admin_user_cannot_update_immunization()
    {
        $orangtua = User::create([
            'nama_anak' => 'Tania',
            'nik_anak' => '3210987654325678',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        $immunization = Immunization::create([
            'name' => 'Campak',
            'age' => '9 bulan',
            'description' => 'Deskripsi awal',
        ]);

        $response = $this->actingAs($orangtua)->put("/admin/immunizations/{$immunization->id}", [
            'name' => 'Campak Update',
            'age' => '9 bulan',
            'description' => 'Deskripsi berubah',
        ]);

        $response->assertForbidden(); 
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImmunizationAccessFailureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Immunization feature removed');
    }

    public function test_non_admin_user_cannot_access_immunization_index()
    {
        $orangtua = User::create([
            'nama_anak' => 'Tania',
            'nik_anak' => '3210987654325678',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        $response = $this->actingAs($orangtua)->get('/admin/immunizations');

        $response->assertForbidden(); 
    }

    public function test_guest_user_cannot_access_immunization_index()
    {
        $response = $this->get('/admin/immunizations');

        $response->assertRedirect('/login'); 
    }
}

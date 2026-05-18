<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImmunizationSearchFailureUnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Immunization feature removed');
    }

    public function test_non_admin_cannot_access_immunization_search()
    {
        $user = User::create([
            'nama_anak' => 'Tania',
            'nik_anak' => '3210987654321234',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        $response = $this->actingAs($user)->get('/admin/immunizations?name=Polio');

        $response->assertForbidden(); 
    }
}

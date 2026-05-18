<?php

namespace Tests\Feature\Orangtua\ImmunizationRecord;

use App\Models\User;
use App\Models\Immunization;
use App\Models\ImmunizationRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateFailureMissingImmunizedAtTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Immunization feature removed');
    }

    public function test_update_fails_when_immunized_at_is_missing()
    {
        
        $user = User::create([
            'nama_anak' => 'Raisa',
            'nik_anak' => '3210987654322345',
            'password' => Hash::make('password123'),
            'role' => 'orangtua',
        ]);

        
        $imunisasi = Immunization::create([
            'name' => 'Polio',
            'age' => '0 bulan',
        ]);

        
        $record = ImmunizationRecord::create([
            'user_id' => $user->id,
            'immunization_id' => $imunisasi->id,
            'immunized_at' => now()->subDays(7)->toDateString(),
            'status' => 'Sudah',
        ]);


        $response = $this->actingAs($user)->put("/orangtua/immunization_records/{$record->id}", [
            'immunization_id' => $imunisasi->id,
            'status' => 'Belum',
           
        ]);

        $response->assertSessionHasErrors('immunized_at');
    }
}

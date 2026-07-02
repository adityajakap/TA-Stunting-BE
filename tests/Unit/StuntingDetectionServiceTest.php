<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\StuntingDetectionService;

class StuntingDetectionServiceTest extends TestCase
{
    /**
     * Test perhitungan Z-Score mengembalikan struktur array yang benar
     */
    public function test_calculate_z_score_and_status_structure()
    {
        // Menguji anak laki-laki umur 12 bulan dengan tinggi 75 cm
        $result = StuntingDetectionService::calculateZScoreAndStatus(12, 'L', 75.0);
        
        $this->assertArrayHasKey('z_score', $result);
        $this->assertArrayHasKey('status', $result);
        
        // Memastikan status hanya antara 'Normal' atau 'Stunting'
        $this->assertTrue(in_array($result['status'], ['Normal', 'Stunting']));
    }

    /**
     * Test jika memasukkan umur yang tidak ada di standar WHO (misal umur 999 bulan)
     * Harus melempar Exception (Error) yang tertangkap dengan baik
     */
    public function test_calculate_throws_exception_invalid_age()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data WHO tidak tersedia untuk umur ini.');

        // Menjalankan service dengan umur 999
        StuntingDetectionService::calculateZScoreAndStatus(999, 'P', 100.0);
    }
}

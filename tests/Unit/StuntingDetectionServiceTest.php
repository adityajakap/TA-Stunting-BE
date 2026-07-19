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

    /**
     * Test akurasi perhitungan Z-Score untuk anak laki-laki (Normal)
     * Data rujukan dari zscores_boys.json bulan ke-24:
     * Median (M) = 87.8161, Standar Deviasi (SD) = 3.0551
     */
    public function test_accuracy_boy_normal()
    {
        // Tinggi 87.8 cm (sangat dekat dengan median)
        $result = StuntingDetectionService::calculateZScoreAndStatus(24, 'L', 87.8);
        
        // Z-Score = (87.8 - 87.8161) / 3.0551 = -0.005 (dibulatkan jadi 0.0 atau -0.01)
        // Pastikan nilai Z-Score lebih besar dari -2
        $this->assertGreaterThanOrEqual(-2, $result['z_score']);
        $this->assertEquals('Normal', $result['status']);
    }

    /**
     * Test akurasi perhitungan Z-Score untuk anak laki-laki (Stunting)
     * Tinggi 78.7 cm adalah batas 3 SD ke bawah (Sangat Pendek/Stunting)
     */
    public function test_accuracy_boy_stunting()
    {
        $result = StuntingDetectionService::calculateZScoreAndStatus(24, 'L', 78.7);
        
        // Z-Score = (78.7 - 87.8161) / 3.0551 = -2.98
        $this->assertLessThan(-2, $result['z_score']);
        $this->assertEquals('Stunting', $result['status']);
    }

    /**
     * Test akurasi perhitungan Z-Score untuk anak perempuan (Normal)
     * Data rujukan dari zscores_girls.json bulan ke-24:
     * Median (M) = 86.4153, Standar Deviasi (SD) = 3.2267
     */
    public function test_accuracy_girl_normal()
    {
        // Tinggi 86.4 cm (dekat dengan median)
        $result = StuntingDetectionService::calculateZScoreAndStatus(24, 'P', 86.4);
        
        // Z-Score = (86.4 - 86.4153) / 3.2267 = -0.0047
        $this->assertGreaterThanOrEqual(-2, $result['z_score']);
        $this->assertEquals('Normal', $result['status']);
    }

    /**
     * Test akurasi perhitungan Z-Score untuk anak perempuan (Stunting)
     * Tinggi 76.7 cm adalah batas 3 SD ke bawah (Sangat Pendek/Stunting)
     */
    public function test_accuracy_girl_stunting()
    {
        $result = StuntingDetectionService::calculateZScoreAndStatus(24, 'P', 76.7);
        
        // Z-Score = (76.7 - 86.4153) / 3.2267 = -3.01
        $this->assertLessThan(-2, $result['z_score']);
        $this->assertEquals('Stunting', $result['status']);
    }
}

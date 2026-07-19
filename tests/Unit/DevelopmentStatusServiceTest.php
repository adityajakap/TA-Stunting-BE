<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Child;
use App\Models\TahapanPerkembangan;
use App\Services\DevelopmentStatusService;
use Carbon\Carbon;

class DevelopmentStatusServiceTest extends TestCase
{
    /**
     * Test saat anak mencapai milestone "Lebih Cepat" (umur < minimal_bulan)
     */
    public function test_evaluate_achieved_lebih_cepat()
    {
        // 1. Arrange (Siapkan Data)
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01'; // Anak lahir 1 Jan 2023
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Tanggal pencapaian: 1 November 2023 (Umur anak saat pencapaian = 10 bulan)
        // 10 bulan itu LEBIH CEPAT dari minimal (12 bulan)
        $tanggal_pencapaian = '2023-11-01';

        // 2. Act (Jalankan Fungsi)
        $result = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

        // 3. Assert (Verifikasi Hasil)
        $this->assertEquals('lebih_cepat', $result['status']);
        $this->assertEquals('Lebih Cepat', $result['label']);
    }

    /**
     * Test saat anak mencapai milestone "Tepat Waktu" (minimal <= umur <= maksimal)
     */
    public function test_evaluate_achieved_tepat_waktu()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Tanggal pencapaian: 1 April 2024 (Umur anak = 15 bulan, masuk rentang 12-18)
        $tanggal_pencapaian = '2024-04-01';

        $result = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

        $this->assertEquals('tepat_waktu', $result['status']);
        $this->assertEquals('Tepat Waktu', $result['label']);
    }

    /**
     * Test saat anak mencapai milestone "Lambat / Perlu dipantau" (maksimal < umur <= batas evaluasi)
     */
    public function test_evaluate_achieved_lambat_pantau()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Tanggal pencapaian: 1 November 2024 (Umur anak = 22 bulan, masuk rentang >18 dan <=24)
        $tanggal_pencapaian = '2024-11-01';

        $result = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

        $this->assertEquals('lambat_pantau', $result['status']);
        $this->assertEquals('Lambat / Perlu dipantau', $result['label']);
    }

    /**
     * Test saat anak mencapai milestone "Terlambat Evaluasi" (umur > batas evaluasi)
     */
    public function test_evaluate_achieved_terlambat_evaluasi()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Tanggal pencapaian: 1 Maret 2025 (Umur anak = 26 bulan, lebih dari 24)
        $tanggal_pencapaian = '2025-03-01';

        $result = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

        $this->assertEquals('terlambat_evaluasi', $result['status']);
        $this->assertEquals('Terlambat / Perlu evaluasi', $result['label']);
    }
    /**
     * Test saat anak belum mencapai milestone dan umur < minimal_bulan
     */
    public function test_evaluate_not_achieved_belum_waktunya()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Umur sekarang disimulasikan: 1 November 2023 (10 bulan, kurang dari 12)
        Carbon::setTestNow(Carbon::parse('2023-11-01'));

        // Parameter ketiga adalah null karena belum tercapai
        $result = DevelopmentStatusService::evaluate($child, $tahapan, null);

        $this->assertEquals('belum_waktunya', $result['status']);
        $this->assertEquals('Belum waktunya', $result['label']);
        
        Carbon::setTestNow(); // reset waktu kembali ke asli
    }

    /**
     * Test saat anak belum mencapai milestone dan umur pas di dalam rentang
     */
    public function test_evaluate_not_achieved_belum_rentang()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Umur sekarang disimulasikan: 1 April 2024 (15 bulan, pas di rentang 12-18)
        Carbon::setTestNow(Carbon::parse('2024-04-01'));

        $result = DevelopmentStatusService::evaluate($child, $tahapan, null);

        $this->assertEquals('belum_rentang', $result['status']);
        $this->assertEquals('Belum tercapai, masih dalam rentang', $result['label']);
        
        Carbon::setTestNow();
    }

    /**
     * Test saat anak belum mencapai milestone dan umur sudah melebihi maksimal tapi belum kritis
     */
    public function test_evaluate_not_achieved_belum_pantau()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Umur sekarang disimulasikan: 1 November 2024 (22 bulan, di atas 18 tapi belum 24)
        Carbon::setTestNow(Carbon::parse('2024-11-01'));

        $result = DevelopmentStatusService::evaluate($child, $tahapan, null);

        $this->assertEquals('belum_pantau', $result['status']);
        $this->assertEquals('Belum tercapai, perlu dipantau', $result['label']);
        
        Carbon::setTestNow();
    }

    /**
     * Test saat anak belum mencapai milestone dan umur sudah melewati batas evaluasi dokter
     */
    public function test_evaluate_not_achieved_belum_evaluasi()
    {
        $child = new Child();
        $child->tanggal_lahir = '2023-01-01';
        
        $tahapan = new TahapanPerkembangan();
        $tahapan->umur_minimal_bulan = 12;
        $tahapan->umur_maksimal_bulan = 18;
        $tahapan->batas_evaluasi_bulan = 24;

        // Umur sekarang disimulasikan: 1 Maret 2025 (26 bulan, sudah lewat batas 24)
        Carbon::setTestNow(Carbon::parse('2025-03-01'));

        $result = DevelopmentStatusService::evaluate($child, $tahapan, null);

        $this->assertEquals('belum_evaluasi', $result['status']);
        $this->assertEquals('Belum tercapai, perlu evaluasi', $result['label']);
        
        Carbon::setTestNow();
    }
}

<?php

namespace App\Services;

class StuntingDetectionService
{
    /**
     * Menghitung Z-Score berdasarkan tinggi badan, umur, dan jenis kelamin.
     * Menggunakan standar WHO (zscores_boys.json / zscores_girls.json).
     * 
     * @return array [z_score, status]
     * @throws \Exception
     */
    public static function calculateZScoreAndStatus($umur, $jenisKelamin, $tinggiBadan)
    {
        $filePath = $jenisKelamin === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            throw new \Exception('File WHO tidak ditemukan.');
        }

        $data = json_decode(file_get_contents($filePath), true);
        if (!$data || !is_array($data)) {
            throw new \Exception('Gagal membaca file WHO.');
        }

        $umur = (int) $umur;
        $who_data = collect($data)->first(fn($item) => (int) $item['Month'] === $umur);

        if (!$who_data) {
            throw new \Exception('Data WHO tidak tersedia untuk umur ini.');
        }

        $median = $who_data['M'] ?? 0;
        $sd = $who_data['SD'] ?? 1;
        
        // Rumus Z-Score WHO
        $z_score = ($tinggiBadan - $median) / $sd;

        // Hanya dua status yang diperlukan: 'Stunting' bila z-score < -2, sisanya 'Normal'
        $status = $z_score < -2 ? 'Stunting' : 'Normal';

        return [
            'z_score' => round($z_score, 2),
            'status' => $status
        ];
    }
}

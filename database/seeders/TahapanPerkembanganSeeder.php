<?php

namespace Database\Seeders;

use App\Models\TahapanPerkembangan;
use Illuminate\Database\Seeder;

class TahapanPerkembanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahapan = [
            [
                'nama_tahapan' => 'Mengeluarkan suara (cooing)',
                'deskripsi' => 'Bayi mulai mengeluarkan suara seperti "ooo", "aaa"',
                'umur_minimal_bulan' => 1,
                'umur_maksimal_bulan' => 3,
            ],
            [
                'nama_tahapan' => 'Babbling (ocehan)',
                'deskripsi' => 'Mulai mengucapkan "ba-ba", "ma-ma" (belum jelas arti)',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
            ],
            [
                'nama_tahapan' => 'Mengucapkan kata pertama',
                'deskripsi' => 'Contoh: "mama", "papa"',
                'umur_minimal_bulan' => 9,
                'umur_maksimal_bulan' => 12,
            ],
            [
                'nama_tahapan' => 'Mengucapkan 2 kata sederhana',
                'deskripsi' => 'Contoh: "mau susu", "ambil bola"',
                'umur_minimal_bulan' => 12,
                'umur_maksimal_bulan' => 18,
            ],
            [
                'nama_tahapan' => 'Mulai berbicara kalimat sederhana',
                'deskripsi' => 'Mulai bisa menyusun kalimat pendek',
                'umur_minimal_bulan' => 18,
                'umur_maksimal_bulan' => 24,
            ],
            [
                'nama_tahapan' => 'Bicara lebih jelas dan banyak kosakata',
                'deskripsi' => 'Bisa menyebut nama benda, warna, dll',
                'umur_minimal_bulan' => 24,
                'umur_maksimal_bulan' => 36,
            ],
        ];

        foreach ($tahapan as $item) {
            TahapanPerkembangan::create($item);
        }
    }
}

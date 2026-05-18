<?php

namespace Database\Seeders;

use App\Models\TahapanPerkembangan;
use Illuminate\Database\Seeder;

class TahapanPerkembanganGigiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gigi = [
            [
                'nama_tahapan' => 'Gigi pertama',
                'deskripsi' => 'Muncul gigi pertama pada bayi',
                'umur_minimal_bulan' => 6,
                'umur_maksimal_bulan' => 10,
            ],
            [
                'nama_tahapan' => 'Gigi seri lengkap',
                'deskripsi' => 'Gigi seri tumbuh lengkap',
                'umur_minimal_bulan' => 8,
                'umur_maksimal_bulan' => 12,
            ],
            [
                'nama_tahapan' => 'Gigi samping',
                'deskripsi' => 'Gigi samping (lateral) mulai muncul',
                'umur_minimal_bulan' => 9,
                'umur_maksimal_bulan' => 16,
            ],
            [
                'nama_tahapan' => 'Geraham pertama',
                'deskripsi' => 'Geraham pertama tumbuh',
                'umur_minimal_bulan' => 13,
                'umur_maksimal_bulan' => 19,
            ],
            [
                'nama_tahapan' => 'Gigi taring',
                'deskripsi' => 'Muncul gigi taring',
                'umur_minimal_bulan' => 16,
                'umur_maksimal_bulan' => 23,
            ],
            [
                'nama_tahapan' => 'Geraham kedua',
                'deskripsi' => 'Geraham kedua tumbuh',
                'umur_minimal_bulan' => 23,
                'umur_maksimal_bulan' => 33,
            ],
        ];

        foreach ($gigi as $item) {
            TahapanPerkembangan::firstOrCreate(
                ['nama_tahapan' => $item['nama_tahapan']],
                [
                    'deskripsi' => $item['deskripsi'],
                    'umur_minimal_bulan' => $item['umur_minimal_bulan'],
                    'umur_maksimal_bulan' => $item['umur_maksimal_bulan'],
                ]
            );
        }
    }
}

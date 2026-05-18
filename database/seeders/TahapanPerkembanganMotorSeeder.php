<?php

namespace Database\Seeders;

use App\Models\TahapanPerkembangan;
use Illuminate\Database\Seeder;

class TahapanPerkembanganMotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data tahapan perkembangan motorik (Versi Tabel Siap Masuk Laporan)
     */
    public function run(): void
    {
        $motor = [
            [
                'nama_tahapan' => 'Mengangkat kepala',
                'deskripsi' => 'Bayi dapat mengangkat kepala saat terlentang',
                'umur_minimal_bulan' => 0,
                'umur_maksimal_bulan' => 3,
            ],
            [
                'nama_tahapan' => 'Tengkurap',
                'deskripsi' => 'Bayi dapat tengkurap sendiri',
                'umur_minimal_bulan' => 2,
                'umur_maksimal_bulan' => 4,
            ],
            [
                'nama_tahapan' => 'Berguling',
                'deskripsi' => 'Bayi dapat berguling dari telungkup ke terlentang',
                'umur_minimal_bulan' => 3,
                'umur_maksimal_bulan' => 6,
            ],
            [
                'nama_tahapan' => 'Duduk (dibantu)',
                'deskripsi' => 'Bayi dapat duduk dengan bantuan orang tua',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
            ],
            [
                'nama_tahapan' => 'Duduk mandiri',
                'deskripsi' => 'Bayi dapat duduk sendiri tanpa bantuan',
                'umur_minimal_bulan' => 6,
                'umur_maksimal_bulan' => 8,
            ],
            [
                'nama_tahapan' => 'Merangkak',
                'deskripsi' => 'Bayi dapat merangkak dengan tangan dan kaki',
                'umur_minimal_bulan' => 7,
                'umur_maksimal_bulan' => 10,
            ],
            [
                'nama_tahapan' => 'Berdiri berpegangan',
                'deskripsi' => 'Bayi dapat berdiri dengan berpegangan pada benda',
                'umur_minimal_bulan' => 8,
                'umur_maksimal_bulan' => 11,
            ],
            [
                'nama_tahapan' => 'Berdiri sendiri',
                'deskripsi' => 'Bayi dapat berdiri sendiri tanpa pegangan',
                'umur_minimal_bulan' => 9,
                'umur_maksimal_bulan' => 12,
            ],
            [
                'nama_tahapan' => 'Berjalan',
                'deskripsi' => 'Bayi dapat berjalan sendiri dengan langkah yang stabil',
                'umur_minimal_bulan' => 10,
                'umur_maksimal_bulan' => 15,
            ],
            [
                'nama_tahapan' => 'Berlari',
                'deskripsi' => 'Bayi dapat berlari dengan koordinasi yang baik',
                'umur_minimal_bulan' => 18,
                'umur_maksimal_bulan' => 24,
            ],
        ];

        foreach ($motor as $item) {
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

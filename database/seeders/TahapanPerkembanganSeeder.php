<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use Illuminate\Support\Facades\DB;

class TahapanPerkembanganSeeder extends Seeder
{
    public function run()
    {
        // Truncate existing data to prevent conflicts with new schema
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TahapanPerkembanganData::truncate();
        TahapanPerkembangan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $milestones = [
            // MOTORIK
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Mengangkat kepala',
                'deskripsi' => 'Anak mulai bisa mengangkat kepalanya sendiri',
                'umur_minimal_bulan' => 0,
                'umur_maksimal_bulan' => 3,
                'batas_evaluasi_bulan' => 4,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Tengkurap',
                'deskripsi' => 'Anak bisa tengkurap sendiri',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
                'batas_evaluasi_bulan' => 7,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Berguling',
                'deskripsi' => 'Anak bisa berguling dari telentang ke tengkurap atau sebaliknya',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
                'batas_evaluasi_bulan' => 7,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Duduk dibantu',
                'deskripsi' => 'Anak bisa duduk dengan ditopang',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
                'batas_evaluasi_bulan' => 7,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Duduk mandiri',
                'deskripsi' => 'Anak bisa duduk sendiri tanpa bantuan',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 9,
                'batas_evaluasi_bulan' => 10,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Merangkak',
                'deskripsi' => 'Anak bisa bergerak merangkak',
                'umur_minimal_bulan' => 5,
                'umur_maksimal_bulan' => 14,
                'batas_evaluasi_bulan' => 15,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Berdiri berpegangan',
                'deskripsi' => 'Anak berdiri dengan berpegangan pada benda',
                'umur_minimal_bulan' => 5,
                'umur_maksimal_bulan' => 12,
                'batas_evaluasi_bulan' => 13,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Berdiri sendiri',
                'deskripsi' => 'Anak bisa berdiri mandiri tanpa pegangan',
                'umur_minimal_bulan' => 7,
                'umur_maksimal_bulan' => 17,
                'batas_evaluasi_bulan' => 18,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Berjalan',
                'deskripsi' => 'Anak mulai melangkah berjalan sendiri',
                'umur_minimal_bulan' => 8,
                'umur_maksimal_bulan' => 18,
                'batas_evaluasi_bulan' => 19,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Motorik',
                'nama_tahapan' => 'Berlari',
                'deskripsi' => 'Anak mulai bisa berlari',
                'umur_minimal_bulan' => 18,
                'umur_maksimal_bulan' => 24,
                'batas_evaluasi_bulan' => 25,
                'sumber_referensi' => 'CDC Developmental Milestones & WHO Motor Development Study',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],

            // BAHASA
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Mengeluarkan suara / cooing',
                'deskripsi' => 'Anak mengeluarkan suara-suara seperti "aah", "ooh"',
                'umur_minimal_bulan' => 1,
                'umur_maksimal_bulan' => 4,
                'batas_evaluasi_bulan' => 5,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Babbling / ocehan',
                'deskripsi' => 'Anak mulai mengoceh dengan pengulangan konsonan',
                'umur_minimal_bulan' => 4,
                'umur_maksimal_bulan' => 6,
                'batas_evaluasi_bulan' => 9,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Mengucapkan kata pertama',
                'deskripsi' => 'Anak bisa mengucapkan satu kata bermakna (misal "mama", "papa")',
                'umur_minimal_bulan' => 9,
                'umur_maksimal_bulan' => 12,
                'batas_evaluasi_bulan' => 15,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Mengucapkan 2 kata sederhana',
                'deskripsi' => 'Anak mulai merangkai dua kata (misal "mau minum")',
                'umur_minimal_bulan' => 18,
                'umur_maksimal_bulan' => 30,
                'batas_evaluasi_bulan' => 31,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Mulai berbicara kalimat sederhana',
                'deskripsi' => 'Mulai bisa menyusun kalimat pendek yang dapat dipahami',
                'umur_minimal_bulan' => 24,
                'umur_maksimal_bulan' => 30,
                'batas_evaluasi_bulan' => 36,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],
            [
                'kategori' => 'Bahasa',
                'nama_tahapan' => 'Bicara lebih jelas dan banyak kosakata',
                'deskripsi' => 'Bicara semakin lancar, kosakata bertambah secara signifikan',
                'umur_minimal_bulan' => 30,
                'umur_maksimal_bulan' => 36,
                'batas_evaluasi_bulan' => 37,
                'sumber_referensi' => 'CDC Developmental Milestones',
                'catatan' => 'Milestone digunakan sebagai alat surveillance, bukan diagnosis.'
            ],

            // GIGI
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Gigi pertama',
                'deskripsi' => 'Tumbuhnya gigi seri pertama (biasanya bawah)',
                'umur_minimal_bulan' => 6,
                'umur_maksimal_bulan' => 10,
                'batas_evaluasi_bulan' => 16,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Gigi seri lengkap',
                'deskripsi' => 'Keempat gigi seri atas dan bawah sudah tumbuh',
                'umur_minimal_bulan' => 8,
                'umur_maksimal_bulan' => 12,
                'batas_evaluasi_bulan' => 18,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Gigi samping / lateral',
                'deskripsi' => 'Gigi seri samping (lateral incisor) tumbuh',
                'umur_minimal_bulan' => 9,
                'umur_maksimal_bulan' => 16,
                'batas_evaluasi_bulan' => 22,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Geraham pertama',
                'deskripsi' => 'Gigi geraham pertama tumbuh',
                'umur_minimal_bulan' => 13,
                'umur_maksimal_bulan' => 19,
                'batas_evaluasi_bulan' => 25,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Gigi taring',
                'deskripsi' => 'Gigi taring (canine) tumbuh',
                'umur_minimal_bulan' => 16,
                'umur_maksimal_bulan' => 23,
                'batas_evaluasi_bulan' => 29,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
            [
                'kategori' => 'Gigi',
                'nama_tahapan' => 'Geraham kedua',
                'deskripsi' => 'Gigi geraham kedua tumbuh lengkap',
                'umur_minimal_bulan' => 23,
                'umur_maksimal_bulan' => 33,
                'batas_evaluasi_bulan' => 36,
                'sumber_referensi' => 'Literatur erupsi gigi sulung',
                'catatan' => 'Erupsi gigi sulung umumnya terjadi sekitar 4–36 bulan dengan urutan central incisor, lateral incisor, first molar, canine, second molar.'
            ],
        ];

        foreach ($milestones as $item) {
            TahapanPerkembangan::create($item);
        }
    }
}

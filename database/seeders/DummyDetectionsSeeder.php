<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Child;
use App\Models\Detection;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyDetectionsSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada satu user dummy untuk orang tua
        $parent = User::firstOrCreate(
            ['username' => 'dummy_parent'],
            [
                'nama_lengkap' => 'Dummy Parent',
                'nik_ibu' => '3212345678901234',
                'password' => bcrypt('password'),
                'role' => 'orangtua'
            ]
        );

        // Hapus SEMUA data anak dan deteksi sebelumnya agar seragam
        Detection::query()->delete();
        Child::query()->delete();

        $faker = \Faker\Factory::create('id_ID');

        // Load WHO data
        $boysData = json_decode(file_get_contents(storage_path('app/zscores_boys.json')), true);
        $girlsData = json_decode(file_get_contents(storage_path('app/zscores_girls.json')), true);

        // Tentukan tanggal pelaksanaan yang seragam per bulan, antara minggu 1 dan 2 (tanggal 1 - 14)
        $tanggalPelaksanaanPerBulan = [
            5 => Carbon::create(2026, 5, $faker->numberBetween(1, 14), 9, 0, 0),
            6 => Carbon::create(2026, 6, $faker->numberBetween(1, 14), 9, 0, 0),
            7 => Carbon::create(2026, 7, $faker->numberBetween(1, 14), 9, 0, 0),
        ];

        // Daftar nama anak yang sopan dan normal
        $namaLaki = ['Budi Santoso', 'Andi Pratama', 'Rizky Aditya', 'Fajar Nugraha', 'Dimas Saputra', 'Reza Pahlevi', 'Bayu Setiawan', 'Hendra Gunawan', 'Agus Setiawan', 'Tegar Putra', 'Arif Rahman', 'Dika Pratama', 'Eko Purnomo', 'Faisal Akbar', 'Gilang Ramadhan', 'Iqbal Maulana', 'Joko Susanto', 'Kevin Sanjaya', 'Lukman Hakim', 'Muhammad Rizky', 'Naufal Alif', 'Oki Setiawan', 'Putra Mahardika', 'Rama Aditya', 'Surya Saputra'];
        $namaPerempuan = ['Siti Aminah', 'Putri Ayu', 'Rina Marlina', 'Dewi Lestari', 'Ayu Wandira', 'Nina Sari', 'Sari Indah', 'Lestari Budiarti', 'Ratna Sari', 'Tari Mulyani', 'Anisa Rahmawati', 'Bella Safira', 'Citra Kirana', 'Dinda Permata', 'Eka Fitriani', 'Fitri Handayani', 'Gita Gutawa', 'Hana Saraswati', 'Indah Permatasari', 'Jihan Fahira', 'Kiki Fatmala', 'Laila Sari', 'Maya Septha', 'Nadia Vega', 'Olivia Jensen'];

        // Buat 50 anak
        for ($i = 0; $i < 50; $i++) {
            // Bagi 25 laki-laki dan 25 perempuan
            if ($i < 25) {
                $jk = 'L';
                $nama = $namaLaki[$i % count($namaLaki)] . ' ' . $faker->lastName;
            } else {
                $jk = 'P';
                $nama = $namaPerempuan[($i - 25) % count($namaPerempuan)] . ' ' . $faker->lastName;
            }

            $umurAwalBulan = $faker->numberBetween(12, 48); // Umur dalam bulan pada Mei 2026
            
            // Mei 2026 = bulan ke-$umurAwalBulan
            // Jadi tanggal lahir: Mei 2026 dikurangi $umurAwalBulan bulan
            $tglLahir = Carbon::create(2026, 5, 15)->subMonths($umurAwalBulan)->subDays($faker->numberBetween(0, 20));

            $child = Child::create([
                'user_id' => $parent->id,
                'nama_lengkap_anak' => $nama,
                'tanggal_lahir' => $tglLahir,
                'jenis_kelamin' => $jk,
                'nik_anak' => '32' . str_pad($i + 1, 14, '0', STR_PAD_LEFT)
            ]);

            $whoData = $jk === 'L' ? $boysData : $girlsData;

            $trend = $faker->randomElement([1, 1, 1, 2, 2, 3, 3, 4]);

            $currentBerat = null;
            $currentTinggi = null;

            // Loop untuk Mei (5), Juni (6), Juli (7)
            foreach ([5, 6, 7] as $month) {
                $umurSaatIni = (int) floor($tglLahir->diffInMonths(Carbon::create(2026, $month, 15)));
                $whoMonth = collect($whoData)->firstWhere('Month', $umurSaatIni);
                
                if (!$whoMonth) {
                    throw new \Exception("WHO data not found for month: $umurSaatIni, jk: $jk");
                }

                // Estimasi berat badan rata-rata (kg) berdasarkan umur bulan
                if ($umurSaatIni <= 12) {
                    $medianBerat = 3.3 + ($umurSaatIni * 0.5);
                } else {
                    $medianBerat = 9.3 + (($umurSaatIni - 12) * 0.2);
                }
                
                $medianTinggi = $whoMonth['M'];
                $sdTinggi = $whoMonth['SD'];

                if ($month == 5) {
                    $currentBerat = $medianBerat * $faker->randomFloat(2, 0.85, 1.15); 
                    $currentTinggi = $medianTinggi + ($faker->randomFloat(2, -2.5, 1.5) * $sdTinggi);
                } else {
                    $deltaBerat = $faker->randomFloat(2, 0.1, 0.4);
                    $deltaTinggi = $faker->randomFloat(2, 0.5, 1.5);

                    if ($month == 6) {
                        if ($trend == 3 || $trend == 4) {
                            $deltaBerat = $faker->randomElement([0, -0.1, -0.2]); // Tetap / Turun
                        }
                    } elseif ($month == 7) {
                        if ($trend == 2 || $trend == 4) {
                            $deltaBerat = $faker->randomElement([0, -0.1]); // Tetap / Turun
                        }
                    }

                    $currentBerat += $deltaBerat;
                    if ($currentBerat < 2) $currentBerat = 2;

                    $currentTinggi += $deltaTinggi;
                }

                // Simulasi anak tidak datang (hanya berlaku untuk bulan Juni dan Juli agar bulan pertama terdata semua)
                if ($month > 5) {
                    // 10% kemungkinan anak tidak datang bulan ini
                    if ($faker->boolean(10)) {
                        continue;
                    }
                }

                $z_score = ($currentTinggi - $medianTinggi) / $sdTinggi;
                $status = $z_score < -2 ? 'Stunting' : 'Normal';

                // Gunakan tanggal seragam yang sudah ditentukan per bulan
                $created_at = clone $tanggalPelaksanaanPerBulan[$month];

                Detection::create([
                    'child_id' => $child->id,
                    'nama' => $child->nama_lengkap_anak,
                    'umur' => $umurSaatIni,
                    'jenis_kelamin' => $jk,
                    'berat_badan' => round($currentBerat, 1),
                    'tinggi_badan' => round($currentTinggi, 1),
                    'z_score' => round($z_score, 2),
                    'status' => $status,
                    'added_by' => 'kader',
                    'kader_name' => 'Dedeh',
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ]);
            }
        }
    }
}

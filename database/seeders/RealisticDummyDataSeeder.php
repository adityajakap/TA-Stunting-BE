<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Child;
use App\Models\Detection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RealisticDummyDataSeeder extends Seeder
{
    public function run()
    {
        $boysJson = storage_path('app/zscores_boys.json');
        $girlsJson = storage_path('app/zscores_girls.json');
        
        if (!file_exists($boysJson) || !file_exists($girlsJson)) {
            $this->command->error("File WHO (zscores_boys.json / zscores_girls.json) tidak ditemukan di storage/app.");
            return;
        }

        $whoDataBoys = json_decode(file_get_contents($boysJson), true);
        $whoDataGirls = json_decode(file_get_contents($girlsJson), true);

        // Kumpulan nama-nama khas Indonesia
        $namaIbu = ['Siti Aminah', 'Dewi Lestari', 'Ratna Sari', 'Sri Wahyuni', 'Ayu Ningsih', 'Putri Diana', 'Indah Permatasari', 'Nur Aini', 'Rina Marlina', 'Eka Fitriani', 'Wati Susilawati', 'Yuni Astuti', 'Rini Puspita', 'Fitriani', 'Nia Ramadhani', 'Tini Surtini', 'Endang Sriwati', 'Ani Haryani', 'Dwi Yanti', 'Ningsih Sutarti', 'Reni Anggraeni', 'Ratu Felisha', 'Diana Safitri', 'Mega Wati', 'Wulan Guritno', 'Lina Marlina', 'Susi Susanti', 'Irma Suryani', 'Nina Herlina', 'Yanti Yulianti'];
        
        $namaAnakL = ['Rizky Pratama', 'Fajar Sidik', 'Dimas Anggara', 'Arya Wiguna', 'Bayu Setiawan', 'Dika Saputra', 'Putra Ramadhan', 'Gilang Dirga', 'Rian Hidayat', 'Irfan Hakim', 'Kevin Julio', 'Raka Putra', 'Alif Rahman', 'Bima Sakti', 'Candra Wijaya'];
        $namaAnakP = ['Aisyah Putri', 'Nabila Syakieb', 'Dinda Hauw', 'Tiara Andini', 'Zahra Larasati', 'Rara Sekar', 'Salsabila Ayu', 'Syifa Hadju', 'Nadia Saphira', 'Kiki Amalia', 'Mutiara Annisa', 'Kirana Larasati', 'Bunga Citra', 'Cinta Laura', 'Tasya Kamila'];

        $defaultPassword = Hash::make('password123');
        $now = Carbon::now();

        // 1. Generate 75 Users (Orang Tua)
        $users = [];
        for ($i = 0; $i < 75; $i++) {
            $nik = '320' . rand(1000000000000, 9999999999999);
            
            $users[] = User::create([
                'nik_ibu' => $nik,
                'nama_lengkap' => $namaIbu[$i % count($namaIbu)] . ' ' . rand(1, 999),
                'username' => 'ibu_' . strtolower(explode(' ', $namaIbu[$i % count($namaIbu)])[0]) . rand(100,999),
                'password' => $defaultPassword,
                'role' => 'orangtua',
            ]);
        }
        $this->command->info("Created 75 parents.");

        // 2. Generate 100 Children (Anak)
        $children = [];
        for ($i = 0; $i < 100; $i++) {
            $user = $users[array_rand($users)];
            $isBoy = rand(0, 1) === 1;
            
            $namaAnak = $isBoy ? $namaAnakL[array_rand($namaAnakL)] : $namaAnakP[array_rand($namaAnakP)];
            $namaAnak .= ' ' . rand(1, 99);
            $nikAnak = '320' . rand(1000000000000, 9999999999999);
            
            // Umur anak saat ini antara 6 bulan sampai 36 bulan
            $umurBulanSaatIni = rand(6, 36);
            $tglLahir = $now->copy()->subMonths($umurBulanSaatIni)->subDays(rand(1, 28));
            
            $children[] = Child::create([
                'user_id' => $user->id,
                'nama_lengkap_anak' => explode(' ', $namaAnak)[0] . ' ' . explode(' ', $namaAnak)[1],
                'tanggal_lahir' => $tglLahir->format('Y-m-d'),
                'nik_anak' => $nikAnak,
            ]);
            
            // Store gender for detection generation
            $children[$i]->jenis_kelamin = $isBoy ? 'L' : 'P';
            $children[$i]->umur_saat_ini = $umurBulanSaatIni;
        }
        $this->command->info("Created 40 children.");

        // 3. Generate Detections for the last 1-6 months for each child
        $totalDetections = 0;
        foreach ($children as $child) {
            $whoData = $child->jenis_kelamin === 'L' ? $whoDataBoys : $whoDataGirls;
            
            // Randomize if this child is stunting or normal overall
            $isStunting = rand(1, 100) <= 20; // 20% chance of stunting
            
            $numDetections = rand(3, 6); // each child has 3 to 6 months of data
            
            // Start from X months ago up to this month
            for ($j = $numDetections; $j >= 0; $j--) {
                $umurSaatDeteksi = $child->umur_saat_ini - $j;
                if ($umurSaatDeteksi < 0) continue;
                
                $whoRecord = collect($whoData)->first(function ($item) use ($umurSaatDeteksi) {
                    return (int)$item['Month'] === $umurSaatDeteksi;
                });
                
                if (!$whoRecord) continue;

                $medianTB = (float)$whoRecord['M'];
                $sdTB = (float)$whoRecord['SD'];
                
                // Tinggi Badan calculation
                if ($isStunting) {
                    // Stunting Z-Score < -2.0
                    $zScoreTarget = (rand(-35, -21) / 10.0); // e.g. -2.1 to -3.5
                } else {
                    // Normal Z-Score between -1.9 and 2.0
                    $zScoreTarget = (rand(-15, 15) / 10.0); // e.g. -1.5 to 1.5
                }
                
                $tinggiBadan = $medianTB + ($zScoreTarget * $sdTB);
                
                // Berat Badan (approximate real values)
                $beratBadan = ($umurSaatDeteksi / 2.5) + 3.5 + (rand(-10, 10) / 10.0);
                if ($beratBadan < 2.5) $beratBadan = 2.5 + rand(1, 5)/10;

                // Add slight randomness to created_at within the month
                $detectionDate = $now->copy()->subMonths($j)->subDays(rand(0, 5));
                
                Detection::create([
                    'child_id' => $child->id,
                    'nama' => $child->nama_lengkap_anak,
                    'umur' => $umurSaatDeteksi,
                    'jenis_kelamin' => $child->jenis_kelamin,
                    'berat_badan' => round($beratBadan, 1),
                    'tinggi_badan' => round($tinggiBadan, 1),
                    'z_score' => round($zScoreTarget, 2),
                    'status' => $zScoreTarget < -2 ? 'Stunting' : 'Normal',
                    'created_at' => $detectionDate,
                    'updated_at' => $detectionDate,
                ]);
                $totalDetections++;
            }
        }
        $this->command->info("Created $totalDetections detection records.");
    }
}

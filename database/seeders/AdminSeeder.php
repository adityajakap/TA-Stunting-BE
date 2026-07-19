<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'nama_lengkap' => 'Kader Posyandu',
            'username' => 'kader123',
            'password' => Hash::make('kader123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
 
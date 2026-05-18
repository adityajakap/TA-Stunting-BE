<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "No admin user found\n";
    exit(1);
}

$admin->nama_anak = 'admin123';
$admin->password = Hash::make('admin123');
$admin->save();

echo "Updated admin (id={$admin->id}) - nama_anak={$admin->nama_anak}\n";

<?php

// Bootstraps Laravel and prints users for debugging.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::select('id','nama_anak','nik_anak','role','created_at')->get();

echo json_encode($users->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

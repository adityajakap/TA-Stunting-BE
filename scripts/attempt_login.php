<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$credentials = ['nama_anak' => 'admin123', 'password' => 'admin123'];

$result = Auth::attempt($credentials);

if ($result) {
    $user = Auth::user();
    echo "Authenticated: id={$user->id} nama_anak={$user->nama_anak} role={$user->role}\n";
} else {
    echo "Authentication failed\n";
}

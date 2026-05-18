<?php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Artikel;
use Illuminate\Support\Str;

$now = now();

$artikel = Artikel::create([
    'title' => 'Test Artikel oleh Agent',
    'content' => '<p>Konten test untuk memverifikasi publish flow.</p>',
    'slug' => Str::slug('Test Artikel oleh Agent') . '-' . time(),
    'image' => null,
    'is_published' => true,
    'published_at' => $now,
]);

echo "Created article: " . $artikel->id . "\n";

<?php
$require = __DIR__ . '/../vendor/autoload.php';
require $require;
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Artikel;

$rows = Artikel::orderBy('id','desc')->take(20)->get(['id','title','is_published','published_at'])->toArray();
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;

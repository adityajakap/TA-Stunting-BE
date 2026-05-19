<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'TA-Stunting API',
        'status' => 'Running',
        'time' => now()->toDateTimeString()
    ]);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\DetectionController;
use App\Http\Controllers\Api\TahapanPerkembanganController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SkdnTargetController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ArtikelKategoriController;

/*
|--------------------------------------------------------------------------
| Public routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Public: Master data tahapan (needed for FE create form)
Route::get('/tahapan-master', function () {
    return response()->json(\App\Models\TahapanPerkembangan::orderBy('kategori')->orderBy('umur_minimal_bulan')->get());
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | Orangtua routes — scoped to a specific child
    |--------------------------------------------------------------------------
    */
    // Children management
    Route::apiResource('children', ChildController::class);

    // Detection (per child)
    Route::get('children/{child}/detections',     [DetectionController::class, 'index']);
    Route::get('children/{child}/kms-data',       [DetectionController::class, 'kmsData']);
    Route::post('children/{child}/detections',    [DetectionController::class, 'store']);
    Route::delete('children/{child}/detections/{id}', [DetectionController::class, 'destroy']);

    // Tahapan Perkembangan (per child)
    Route::get('children/{child}/perkembangan',           [TahapanPerkembanganController::class, 'index']);
    Route::post('children/{child}/perkembangan',          [TahapanPerkembanganController::class, 'store']);
    Route::put('children/{child}/perkembangan/{id}',      [TahapanPerkembanganController::class, 'update']);
    Route::delete('children/{child}/perkembangan/{id}',   [TahapanPerkembanganController::class, 'destroy']);

    // Skdn Targets
    Route::get('/skdn-target', [SkdnTargetController::class, 'show']);
    Route::post('/skdn-target', [SkdnTargetController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | Admin routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard',                          [AdminController::class, 'dashboard']);
        Route::get('/children',                           [AdminController::class, 'children']);
        Route::get('/detections',                         [DetectionController::class, 'adminIndex']);
        Route::post('/detections',                        [DetectionController::class, 'adminStore']);
        Route::get('/children/{child}/perkembangan',      [TahapanPerkembanganController::class, 'adminShow']);
        Route::post('/children/{child}/perkembangan',     [TahapanPerkembanganController::class, 'adminStore']);
        Route::apiResource('nutrition', NutritionController::class);
        Route::apiResource('artikel', ArtikelController::class);
        Route::apiResource('kategori', ArtikelKategoriController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Orangtua Dashboard routes
    |--------------------------------------------------------------------------
    */
    Route::get('/nutrition', [NutritionController::class, 'index']);
    Route::get('/nutrition/{id}', [NutritionController::class, 'show']);
    Route::get('/artikel', [ArtikelController::class, 'index']);
    Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
    Route::get('/dashboard/orangtua', function () {
        $today = \Carbon\Carbon::now();
        $hari = strtolower($today->isoFormat('dddd'));
        $tanggal = $today->dayOfYear;

        $menuByCategory = [
            'pagi' => \App\Models\NutritionRecommendation::where('category', 'pagi')->get(),
            'siang' => \App\Models\NutritionRecommendation::where('category', 'siang')->get(),
            'malam' => \App\Models\NutritionRecommendation::where('category', 'malam')->get(),
            'snack' => \App\Models\NutritionRecommendation::where('category', 'snack')->get(),
        ];

        $getMenuByDate = function ($menuList) use ($hari, $tanggal) {
            if ($menuList->isEmpty()) return null;
            $index = crc32($hari . $tanggal) % $menuList->count();
            return $menuList[$index];
        };

        $menus = collect();
        foreach ($menuByCategory as $kategori => $list) {
            $menus[$kategori] = $getMenuByDate($list);
        }
        
        // Ensure we always have at least 3 menus by adding fallback if needed
        $allMenus = \App\Models\NutritionRecommendation::get();
        $availableMenus = $menus->filter()->values();
        
        while ($availableMenus->count() < 3 && $availableMenus->count() < $allMenus->count()) {
            $randomMenu = $allMenus->random();
            if (!$availableMenus->contains('id', $randomMenu->id)) {
                $availableMenus->push($randomMenu);
            }
        }
        
        // Replace the original menus with the guaranteed 3 menus
        $categories = ['pagi', 'siang', 'malam', 'snack'];
        for ($i = 0; $i < min(4, $availableMenus->count()); $i++) {
            if ($i < count($categories)) {
                $menus[$categories[$i]] = $availableMenus[$i];
            }
        }

        $artikels = \App\Models\Artikel::latest()->get(); 

        return response()->json([
            'menus' => $menus,
            'artikels' => $artikels
        ]);
    });
});

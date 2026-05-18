<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    ArtikelController,
    ArtikelKategoriController,
    DetectionController,
    // AdminDetectionController removed (admin handled by DetectionController)
    AdminTahapanPerkembanganDataController,
    TahapanPerkembanganController,
    TahapanPerkembanganDataController,
    BMICalculatorController,
    NutritionController,
    UserArtikelController
};
use App\Models\NutritionRecommendation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect('/login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Dashboard Orangtua
    Route::get('/orangtua/dashboard', function () {
        if (Auth::user()->role !== 'orangtua') {
            abort(403);
        }

        $today = Carbon::now();
        $hari = strtolower($today->isoFormat('dddd'));
        $tanggal = $today->dayOfYear;

        $menuByCategory = [
            'pagi' => DB::table('nutrition_recommendations')->where('category', 'pagi')->get(),
            'siang' => DB::table('nutrition_recommendations')->where('category', 'siang')->get(),
            'malam' => DB::table('nutrition_recommendations')->where('category', 'malam')->get(),
            'snack' => DB::table('nutrition_recommendations')->where('category', 'snack')->get(),
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
        $allMenus = DB::table('nutrition_recommendations')->get();
        $availableMenus = $menus->filter()->values();
        
        // If we have less than 3 menus, add random ones from all menus
        while ($availableMenus->count() < 3 && $availableMenus->count() < $allMenus->count()) {
            $randomMenu = $allMenus->random();
            if (!$availableMenus->contains('id', $randomMenu->id)) {
                $availableMenus->push($randomMenu);
            }
        }
        
        // Replace the original menus with the guaranteed 3 menus
        $categories = ['pagi', 'siang', 'malam'];
        for ($i = 0; $i < min(3, $availableMenus->count()); $i++) {
            if ($i < count($categories)) {
                $menus[$categories[$i]] = $availableMenus[$i];
            }
        }

        $artikels = DB::table('artikels')->latest()->get(); // Ambil semua artikel untuk carousel

        return view('orangtua.dashboard', compact('menus', 'artikels'));
    })->name('orangtua.dashboard');



});

// Deteksi Stunting (Orangtua)
Route::middleware(['auth'])->group(function () {
    Route::get('/orangtua/deteksi-stunting', [DetectionController::class, 'create'])->name('orangtua.detections.create');
    Route::post('/orangtua/deteksi-stunting', [DetectionController::class, 'store'])->name('orangtua.detections.store');
    Route::delete('/orangtua/deteksi-stunting/{id}', [DetectionController::class, 'destroy'])->name('orangtua.detections.destroy');
});

// Deteksi Stunting (Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/deteksi-stunting', [DetectionController::class, 'index'])->name('admin.detections.index');
    Route::get('/admin/deteksi-stunting/export-pdf', [DetectionController::class, 'exportPdf'])->name('admin.detections.export-pdf');
    // Admin - tambah deteksi untuk anak lain
    Route::get('/admin/deteksi-stunting/create', [DetectionController::class, 'adminCreate'])->name('admin.detections.create');
    Route::post('/admin/deteksi-stunting', [DetectionController::class, 'adminStore'])->name('admin.detections.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // 🔹 Kategori Artikel
    Route::prefix('artikel/kategori')->name('artikel.kategori.')->group(function () {
        Route::get('/', [ArtikelKategoriController::class, 'index'])->name('index');
        Route::get('/create', [ArtikelKategoriController::class, 'create'])->name('create');
        Route::post('/', [ArtikelKategoriController::class, 'store'])->name('store');
        Route::get('/{kategori}/edit', [ArtikelKategoriController::class, 'edit'])->name('edit');
        Route::put('/{kategori}', [ArtikelKategoriController::class, 'update'])->name('update');
        Route::delete('/{kategori}', [ArtikelKategoriController::class, 'destroy'])->name('destroy');
    });

    // 🔹 Artikel CRUD lengkap
    Route::prefix('artikel')->name('artikel.')->group(function () {
        Route::get('/', [ArtikelController::class, 'index'])->name('index');
        Route::get('/create', [ArtikelController::class, 'create'])->name('create');
        Route::post('/', [ArtikelController::class, 'store'])->name('store');
        Route::get('/{id}', [ArtikelController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArtikelController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArtikelController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArtikelController::class, 'destroy'])->name('destroy');
    });

    // 🔹 Imunisasi (dihapus)
    // fitur imunisasi telah dihapus — routes dan controller dihapus

    // 🔹 Tahapan Perkembangan (Read-only - Data sudah di-seed dari database)
    Route::get('tahapan_perkembangan', [TahapanPerkembanganController::class, 'index'])->name('tahapan_perkembangan.index');
    Route::get('perkembangan/create', [TahapanPerkembanganController::class, 'create'])->name('perkembangan.create');

    // 🔹 Admin: Daftar Anak & Tambah Pencapaian untuk masing-masing anak
    Route::get('perkembangan/children', [AdminTahapanPerkembanganDataController::class, 'index'])->name('perkembangan.children.index');
    Route::get('perkembangan/children/{user}', [AdminTahapanPerkembanganDataController::class, 'show'])->name('perkembangan.children.show');
    Route::get('perkembangan/children/{user}/create', [AdminTahapanPerkembanganDataController::class, 'create'])->name('perkembangan.children.create');
    Route::post('perkembangan/children/{user}', [AdminTahapanPerkembanganDataController::class, 'store'])->name('perkembangan.children.store');

    // 🔹 Nutrition
    Route::resource('nutrition', NutritionController::class)->except(['show']);
});

// Artikel untuk Orangtua
Route::prefix('orangtua/artikel')->name('orangtua.artikel.')->middleware('auth')->group(function () {
    Route::get('/', [UserArtikelController::class, 'index'])->name('index');
    Route::get('/{id}', [UserArtikelController::class, 'show'])->name('show');
});

// Orangtua Tahapan Perkembangan (imunisasi dihapus)
Route::prefix('orangtua')->name('orangtua.')->middleware('auth')->group(function () {
    Route::resource('tahapan_perkembangan', TahapanPerkembanganDataController::class);
});


// Nutrition untuk Orangtua
Route::middleware(['auth'])->group(function () {
    Route::get('/orangtua/nutritionUs', [NutritionController::class, 'user'])
        ->name('orangtua.nutritionUs.index');

    Route::get('/orangtua/nutritionUs/{id}', function (string $id) {
        if (auth()->user()->role !== 'orangtua') abort(403, 'Unauthorized');
        $menu = NutritionRecommendation::findOrFail($id);
        return view('orangtua.nutritionus.show', compact('menu'));
    })->name('orangtua.nutritionUs.show');
});

//bmi
Route::middleware(['auth'])->group(function () {
    Route::get('/bmi', function() {
        // Check if user has orangtua role
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this page.');
        }
        
        // If role is correct, proceed to the controller
        return app()->make(BMICalculatorController::class)->showBmiData();
    })->name('bmi');
        
    // POST routes for BMI functionality
    Route::post('/hitung-bmi', function(Request $request) {
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this feature.');
        }
        return app()->make(BMICalculatorController::class)->calculate($request);
    })->name('hitung-bmi');
        
    Route::post('/simpan-bmi', function(Request $request) {
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this feature.');
        }
        return app()->make(BMICalculatorController::class)->save($request);
    })->name('simpan-bmi');
        
    Route::post('/reset-bmi', function() {
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this feature.');
        }
        return app()->make(BMICalculatorController::class)->reset();
    })->name('reset-bmi');
        
    Route::post('/hapus-bmi/{index}', function($index) {
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this feature.');
        }
        return app()->make(BMICalculatorController::class)->deleteRow($index);
    })->name('hapus-bmi-row');
        
    Route::post('/calculate-calories', function(Request $request) {
        if (Auth::user()->role !== 'orangtua') {
            abort(403, 'Unauthorized. Only orangtua can access this feature.');
        }
        return app()->make(BMICalculatorController::class)->calculateCalories($request);
    })->name('calculate.calories');
});

Route::get('/profile', function () {
    return view('profile'); // universal view
})->middleware('auth')->name('profile');

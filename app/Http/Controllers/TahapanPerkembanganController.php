<?php

namespace App\Http\Controllers;

use App\Models\TahapanPerkembangan;

class TahapanPerkembanganController extends Controller
{
    /**
     * Display a listing of tahapan perkembangan (master data dari database).
     * Data ini adalah read-only dan di-seed secara otomatis.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $tahapanPerkembangan = TahapanPerkembangan::all();
        return view('admin.tahapan_perkembangan.index', compact('tahapanPerkembangan'));
    }
}
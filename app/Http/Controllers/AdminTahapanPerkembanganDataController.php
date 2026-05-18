<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use Illuminate\Http\Request;

class AdminTahapanPerkembanganDataController extends Controller
{
    // Tampilkan daftar anak (user dengan role orangtua)
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $query = User::where('role', 'orangtua');

        if ($request->filled('search')) {
            $query->where('nama_anak', 'like', '%' . $request->search . '%');
        }

    $users = $query->get();

    return view('admin.tahapan_perkembangan.children_index', compact('users'));
    }

    // Tampilkan daftar pencapaian untuk satu anak
    public function show($userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($userId);
        $data = TahapanPerkembanganData::where('user_id', $userId)->with('tahapanPerkembangan')->orderBy('tanggal_pencapaian')->get();

        return view('admin.tahapan_perkembangan.children_show', compact('user', 'data'));
    }

    // Tampilkan form tambah pencapaian untuk anak tertentu
    public function create($userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($userId);
        $tahapanPerkembangan = TahapanPerkembangan::all();

        return view('admin.tahapan_perkembangan.children_create', compact('user', 'tahapanPerkembangan'));
    }

    // Simpan pencapaian yang ditambahkan oleh admin untuk anak tertentu
    public function store(Request $request, $userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tahapan_perkembangan_id' => 'required|exists:tahapan_perkembangan,id',
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        TahapanPerkembanganData::create([
            'user_id' => $userId,
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian' => $request->tanggal_pencapaian,
            // Status akan di-auto-calculate melalui model boot method
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.perkembangan.children.show', $userId)->with('success', 'Pencapaian berhasil ditambahkan.');
    }
}

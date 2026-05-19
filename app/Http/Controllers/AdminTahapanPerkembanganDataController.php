<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Child;
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

        $query = Child::with('user');

        if ($request->filled('search')) {
            $query->where('nama_lengkap_anak', 'like', '%' . $request->search . '%');
        }

    $children = $query->get();

    return view('admin.tahapan_perkembangan.children_index', compact('children'));
    }

    // Tampilkan daftar pencapaian untuk satu anak
    public function show($userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $child = Child::findOrFail($userId);

        $tahapanMaster = TahapanPerkembangan::orderBy('kategori')->orderBy('umur_minimal_bulan')->get();

        $achievedData = TahapanPerkembanganData::where('child_id', $userId)
            ->get()
            ->keyBy('tahapan_perkembangan_id');

        $data = $tahapanMaster->map(function ($tahapan) use ($child, $achievedData) {
            $achieved = $achievedData->get($tahapan->id);
            if (!$achieved) {
                return null;
            }

            $tanggal_pencapaian = $achieved->tanggal_pencapaian;

            $statusDetail = \App\Services\DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

            return (object) [
                'tahapan' => $tahapan,
                'achieved_data' => $achieved,
                'status_detail' => $statusDetail,
            ];
        })->filter();

        // Group by kategori
        $groupedData = $data->groupBy(function ($item) {
            return $item->tahapan->kategori;
        });

        return view('admin.tahapan_perkembangan.children_show', compact('child', 'groupedData'));
    }

    public function exportPdf($userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $child = Child::findOrFail($userId);

        $tahapanMaster = TahapanPerkembangan::orderBy('kategori')->orderBy('umur_minimal_bulan')->get();

        $achievedData = TahapanPerkembanganData::where('child_id', $userId)
            ->get()
            ->keyBy('tahapan_perkembangan_id');

        $data = $tahapanMaster->map(function ($tahapan) use ($child, $achievedData) {
            $achieved = $achievedData->get($tahapan->id);
            if (!$achieved) {
                return null;
            }

            $tanggal_pencapaian = $achieved->tanggal_pencapaian;

            $statusDetail = \App\Services\DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

            return (object) [
                'tahapan' => $tahapan,
                'achieved_data' => $achieved,
                'status_detail' => $statusDetail,
            ];
        })->filter();

        // Group by kategori
        $groupedData = $data->groupBy(function ($item) {
            return $item->tahapan->kategori;
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.tahapan_perkembangan.pdf', compact('child', 'groupedData'));
        return $pdf->download('Laporan_Perkembangan_' . str_replace(' ', '_', $child->nama_lengkap_anak) . '.pdf');
    }

    // Tampilkan form tambah pencapaian untuk anak tertentu
    public function create($userId)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $child = Child::findOrFail($userId);
        $tahapanPerkembangan = TahapanPerkembangan::all();

        return view('admin.tahapan_perkembangan.children_create', compact('child', 'tahapanPerkembangan'));
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
            'child_id' => $userId,
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian' => $request->tanggal_pencapaian,
            // Status akan di-auto-calculate melalui model boot method
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.perkembangan.children.show', $userId)->with('success', 'Pencapaian berhasil ditambahkan.');
    }
}

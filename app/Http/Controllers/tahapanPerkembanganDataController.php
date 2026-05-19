<?php

namespace App\Http\Controllers;

use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TahapanPerkembanganDataController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $childId = session('selected_child_id');
        $child = \App\Models\Child::findOrFail($childId);

        $kategoriOptions = collect([
            (object)['id' => 'Motorik', 'name' => 'Motorik'],
            (object)['id' => 'Bahasa', 'name' => 'Bahasa'],
            (object)['id' => 'Gigi', 'name' => 'Gigi'],
        ]);

        $selectedKategori = $request->input('kategori', []);

        $query = TahapanPerkembangan::query();
        if (!empty($selectedKategori)) {
            $query->whereIn('kategori', $selectedKategori);
        }

        $tahapanMaster = $query->orderBy('kategori')->orderBy('umur_minimal_bulan')->get();

        // Get achieved data
        $achievedData = TahapanPerkembanganData::where('child_id', $childId)
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

        return view('orangtua.tahapan_perkembangan.index', [
            'groupedData' => $groupedData,
            'kategoris' => $kategoriOptions,
            'kategoriIds' => $selectedKategori,
            'action' => route('orangtua.tahapan_perkembangan.index'),
            'child' => $child
        ]);
    }

    public function create()
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        // Menampilkan daftar tahapan perkembangan untuk dipilih oleh orang tua
        $tahapanPerkembangan = TahapanPerkembangan::all();
        return view('orangtua.tahapan_perkembangan.create', compact('tahapanPerkembangan'));
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        // Mengambil data tahapan perkembangan berdasarkan ID
        $tahapanPerkembanganData = TahapanPerkembanganData::findOrFail($id);
        $tahapanPerkembangan = TahapanPerkembangan::all();

        // Mengirim data ke view edit
        return view('orangtua.tahapan_perkembangan.edit', compact('tahapanPerkembanganData', 'tahapanPerkembangan'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        // Validasi data yang diterima
        $request->validate([
            'tahapan_perkembangan_id' => 'required|exists:tahapan_perkembangan,id',
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        // Menyimpan pencapaian tahapan perkembangan untuk user
        TahapanPerkembanganData::create([
            'child_id' => session('selected_child_id'),
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian' => $request->tanggal_pencapaian,
            // Status akan di-auto-calculate melalui model boot method
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        // Validasi data yang diterima
        $request->validate([
            'tahapan_perkembangan_id' => 'required|exists:tahapan_perkembangan,id',
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        // Menemukan data yang akan diupdate berdasarkan ID
        $tahapanPerkembanganData = TahapanPerkembanganData::findOrFail($id);

        // Update data pencapaian tahapan perkembangan
        $tahapanPerkembanganData->update([
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian' => $request->tanggal_pencapaian,
            // Status akan di-auto-calculate melalui model boot method
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil diupdate.');
    }
    public function destroy($id)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }
        
        $tahapanPerkembanganData = TahapanPerkembanganData::findOrFail($id);
        $tahapanPerkembanganData->delete();

        return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil dihapus.');
    }
}
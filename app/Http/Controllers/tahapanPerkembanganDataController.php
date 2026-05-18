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

        $statusOptions = collect([
            (object)['id' => 'tercapai', 'name' => 'Tercapai'],
            (object)['id' => 'belum_tercapai', 'name' => 'Belum Tercapai'],
        ]);

        $selectedStatus = $request->input('kategori', []); // gunakan nama param yg sama

        $query = TahapanPerkembanganData::query();

        if (!empty($selectedStatus)) {
            $query->whereIn('status', $selectedStatus);
        }

        $data = $query->where('user_id', Auth::id())->orderBy('tanggal_pencapaian')->paginate(10);

        return view('orangtua.tahapan_perkembangan.index', [
            'data' => $data,
            'kategoris' => $statusOptions,
            'kategoriIds' => $selectedStatus,
            'action' => route('orangtua.tahapan_perkembangan.index'),
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
            'user_id' => Auth::id(),
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
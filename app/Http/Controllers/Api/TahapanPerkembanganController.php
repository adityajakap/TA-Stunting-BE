<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use App\Services\DevelopmentStatusService;
use Illuminate\Http\Request;

class TahapanPerkembanganController extends Controller
{
    /**
     * List semua milestone dari master data beserta status yang sudah dicapai anak.
     */
    public function index(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);

        $kategori = $request->query('kategori');
        $query = TahapanPerkembangan::query();
        if ($kategori) {
            $query->whereIn('kategori', (array) $kategori);
        }

        $tahapanMaster = $query->orderBy('kategori')->orderBy('umur_minimal_bulan')->get();

        $achievedData = TahapanPerkembanganData::where('child_id', $childId)
            ->get()
            ->keyBy('tahapan_perkembangan_id');

        $result = $tahapanMaster->map(function ($tahapan) use ($child, $achievedData) {
            $achieved          = $achievedData->get($tahapan->id);
            $tanggal_pencapaian = $achieved ? $achieved->tanggal_pencapaian : null;
            $statusDetail      = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

            return [
                'tahapan'            => $tahapan,
                'pencapaian'         => $achieved,
                'status_detail'      => $statusDetail,
            ];
        });

        // Group by kategori
        $grouped = $result->groupBy(fn($item) => $item['tahapan']['kategori']);

        return response()->json([
            'child'        => $child,
            'milestones'   => $grouped,
        ]);
    }

    /**
     * Simpan pencapaian milestone baru.
     */
    public function store(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);

        $request->validate([
            'tahapan_perkembangan_id' => 'required|exists:tahapan_perkembangan,id',
            'tanggal_pencapaian'      => 'required|date|before_or_equal:today',
            'catatan'                 => 'nullable|string',
        ]);

        $data = TahapanPerkembanganData::create([
            'child_id'                => $child->id,
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian'      => $request->tanggal_pencapaian,
            'catatan'                 => $request->catatan,
        ]);

        return response()->json($data, 201);
    }

    /**
     * Update pencapaian milestone.
     */
    public function update(Request $request, $childId, $id)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $item  = TahapanPerkembanganData::where('child_id', $child->id)->findOrFail($id);

        $request->validate([
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan'            => 'nullable|string',
        ]);

        $item->update($request->only(['tanggal_pencapaian', 'catatan']));
        return response()->json($item);
    }

    /**
     * Hapus pencapaian.
     */
    public function destroy(Request $request, $childId, $id)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $item  = TahapanPerkembanganData::where('child_id', $child->id)->findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Pencapaian dihapus.']);
    }

    /**
     * Admin: lihat milestones anak tertentu (via child_id langsung, tidak perlu belongs to user).
     */
    public function adminShow(Request $request, $childId)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $child = \App\Models\Child::findOrFail($childId);
        $tahapanMaster = TahapanPerkembangan::orderBy('kategori')->orderBy('umur_minimal_bulan')->get();
        $achievedData  = TahapanPerkembanganData::where('child_id', $childId)->get()->keyBy('tahapan_perkembangan_id');

        $result = $tahapanMaster->map(function ($tahapan) use ($child, $achievedData) {
            $achieved          = $achievedData->get($tahapan->id);
            $tanggal_pencapaian = $achieved ? $achieved->tanggal_pencapaian : null;
            $statusDetail      = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

            return [
                'tahapan'       => $tahapan,
                'pencapaian'    => $achieved,
                'status_detail' => $statusDetail,
            ];
        });

        return response()->json([
            'child'      => $child->load('user'),
            'milestones' => $result->groupBy(fn($item) => $item['tahapan']['kategori']),
        ]);
    }

    public function adminStore(Request $request, $childId)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $child = \App\Models\Child::findOrFail($childId);

        $request->validate([
            'tahapan_perkembangan_id' => 'required|exists:tahapan_perkembangan,id',
            'tanggal_pencapaian'      => 'required|date|before_or_equal:today',
            'catatan'                 => 'nullable|string',
        ]);

        $data = TahapanPerkembanganData::create([
            'child_id'                => $child->id,
            'tahapan_perkembangan_id' => $request->tahapan_perkembangan_id,
            'tanggal_pencapaian'      => $request->tanggal_pencapaian,
            'catatan'                 => $request->catatan,
        ]);

        return response()->json($data, 201);
    }

    /**
     * Export PDF laporan perkembangan anak.
     */
    public function exportPdf(Request $request, $childId)
    {
        // Authorization check: strictly for admin/kader
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'kader'])) {
            return response()->json(['message' => 'Unauthorized. Fitur cetak hanya untuk admin/kader.'], 403);
        }

        $child = \App\Models\Child::findOrFail($childId);

        $tahapanMaster = TahapanPerkembangan::orderBy('kategori')->orderBy('umur_minimal_bulan')->get();
        $achievedData  = TahapanPerkembanganData::where('child_id', $childId)->get()->keyBy('tahapan_perkembangan_id');

        $data = $tahapanMaster->map(function ($tahapan) use ($child, $achievedData) {
            $achieved = $achievedData->get($tahapan->id);
            if (!$achieved) {
                return null;
            }

            $tanggal_pencapaian = $achieved->tanggal_pencapaian;
            $statusDetail = DevelopmentStatusService::evaluate($child, $tahapan, $tanggal_pencapaian);

            return (object) [
                'tahapan'       => $tahapan,
                'achieved_data' => $achieved,
                'status_detail' => $statusDetail,
            ];
        })->filter();

        // Group by kategori
        $groupedData = $data->groupBy(function ($item) {
            return $item->tahapan->kategori;
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.perkembangan', compact('child', 'groupedData'));
        return $pdf->download('Laporan_Perkembangan_' . str_replace(' ', '_', $child->nama_lengkap_anak) . '.pdf');
    }
}

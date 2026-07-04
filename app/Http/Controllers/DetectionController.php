<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Detection;
use App\Models\Child;
use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function index(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $data = Detection::where('child_id', $child->id)->latest()->get();
        return response()->json($data);
    }

    public function kmsData(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);
        
        $filePath = $child->jenis_kelamin === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File WHO tidak ditemukan.'], 422);
        }

        $who_data = json_decode(file_get_contents($filePath), true);

        $detections = Detection::where('child_id', $child->id)
            ->orderBy('umur', 'asc')
            ->get(['umur', 'tinggi_badan', 'created_at']);

        return response()->json([
            'gender' => $child->jenis_kelamin,
            'who' => $who_data,
            'history' => $detections
        ]);
    }

    public function store(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);

        $validated = $request->validate([
            'umur'         => 'required|integer',
            'jenis_kelamin'=> 'required|in:L,P',
            'berat_badan'  => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $filePath = $validated['jenis_kelamin'] === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File WHO tidak ditemukan.'], 422);
        }

        $data = json_decode(file_get_contents($filePath), true);
        $umur = (int) $validated['umur'];
        $who_data = collect($data)->first(fn($item) => (int) $item['Month'] === $umur);

        if (!$who_data) {
            return response()->json(['message' => 'Data WHO tidak tersedia untuk umur ini.'], 422);
        }

        $median  = $who_data['M'] ?? 0;
        $sd      = $who_data['SD'] ?? 1;
        $z_score = ($validated['tinggi_badan'] - $median) / $sd;
        $status  = $z_score < -2 ? 'Stunting' : 'Normal';

        $detection = Detection::create([
            'child_id'      => $child->id,
            'nama'          => $child->nama_lengkap_anak,
            'umur'          => $umur,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'berat_badan'   => $validated['berat_badan'],
            'tinggi_badan'  => $validated['tinggi_badan'],
            'z_score'       => round($z_score, 2),
            'status'        => $status,
            'added_by'      => 'orangtua',
        ]);

        return response()->json($detection, 201);
    }

    public function destroy(Request $request, $childId, $id)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $detection = Detection::where('child_id', $child->id)->findOrFail($id);
        $detection->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    // Admin: all detections
    public function adminIndex(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $data = Detection::with('child.user')->latest()->get();
        return response()->json($data);
    }

    // Admin: store detection
    public function adminStore(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'child_id'     => 'required|exists:children,id',
            'berat_badan'  => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $child = Child::findOrFail($validated['child_id']);
        
        $tanggal_lahir = \Carbon\Carbon::parse($child->tanggal_lahir);
        $umur = (int) $tanggal_lahir->diffInMonths(\Carbon\Carbon::now());
        $jenis_kelamin = $child->jenis_kelamin ?? 'L';

        $filePath = $jenis_kelamin === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File WHO tidak ditemukan.'], 422);
        }

        $data = json_decode(file_get_contents($filePath), true);
        $who_data = collect($data)->first(fn($item) => (int) $item['Month'] === $umur);

        if (!$who_data) {
            return response()->json(['message' => 'Data WHO tidak tersedia untuk umur ini.'], 422);
        }

        $median  = $who_data['M'] ?? 0;
        $sd      = $who_data['SD'] ?? 1;
        $z_score = ($validated['tinggi_badan'] - $median) / $sd;
        $status  = $z_score < -2 ? 'Stunting' : 'Normal';

        $detection = Detection::create([
            'child_id'      => $child->id,
            'nama'          => $child->nama_lengkap_anak,
            'umur'          => $umur,
            'jenis_kelamin' => $jenis_kelamin,
            'berat_badan'   => $validated['berat_badan'],
            'tinggi_badan'  => $validated['tinggi_badan'],
            'z_score'       => round($z_score, 2),
            'status'        => $status,
            'added_by'      => 'kader',
        ]);

        return response()->json($detection, 201);
    }
}
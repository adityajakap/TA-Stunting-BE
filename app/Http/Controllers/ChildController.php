<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function index(Request $request)
    {
        // Menyembunyikan anak yang sudah lewat 5 tahun (60 bulan)
        $limitDate = \Carbon\Carbon::now()->subMonths(60)->toDateString();
        
        $children = $request->user()->children()
            ->where('tanggal_lahir', '>=', $limitDate)
            ->get();
            
        return response()->json($children);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap_anak' => 'required|string|max:255',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'nik_anak'          => 'nullable|string|max:20',
        ]);

        $child = $request->user()->children()->create([
            'nama_lengkap_anak' => $request->nama_lengkap_anak,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'nik_anak'          => $request->nik_anak,
        ]);

        return response()->json($child, 201);
    }

    public function show(Request $request, $id)
    {
        $child = $request->user()->children()->findOrFail($id);
        return response()->json($child);
    }

    public function update(Request $request, $id)
    {
        $child = $request->user()->children()->findOrFail($id);

        $request->validate([
            'nama_lengkap_anak' => 'sometimes|string|max:255',
            'tanggal_lahir'     => 'sometimes|date',
            'jenis_kelamin'     => 'sometimes|in:L,P',
            'nik_anak'          => 'nullable|string|max:20',
        ]);

        $child->update($request->only(['nama_lengkap_anak', 'tanggal_lahir', 'jenis_kelamin', 'nik_anak']));
        return response()->json($child);
    }

    public function destroy(Request $request, $id)
    {
        $child = $request->user()->children()->findOrFail($id);
        $child->delete();
        return response()->json(['message' => 'Anak berhasil dihapus.']);
    }
}
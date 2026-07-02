<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;

class DetectionController extends Controller
{
    // Untuk admin: list semua data dengan fitur pencarian
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $semua = Detection::with('child.user')->latest()->get();

        return view('admin.detections.index', compact('semua'));
    }


    // Admin: tampilkan form untuk menambahkan deteksi untuk akun anak lain
    public function adminCreate()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Ambil daftar anak untuk dipilih
        $children = Child::with('user')->get();

        return view('admin.detections.create', compact('children'));
    }

    // Admin dapat menyimpan deteksi untuk anak yang dipilih
    public function adminStore(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'umur' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $child = Child::findOrFail($validated['child_id']);

        try {
            $result = \App\Services\StuntingDetectionService::calculateZScoreAndStatus(
                $validated['umur'],
                $validated['jenis_kelamin'],
                $validated['tinggi_badan']
            );
            $z_score = $result['z_score'];
            $status = $result['status'];
            $umur = (int) $validated['umur'];
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        Detection::create([
            'child_id' => $child->id,
            'nama' => $child->nama_lengkap_anak,
            'umur' => $umur,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'berat_badan' => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'z_score' => round($z_score, 2),
            'status' => $status,
        ]);

        return redirect()->route('admin.detections.index')->with('success', 'Deteksi berhasil disimpan untuk ' . $child->nama_lengkap_anak);
    }


    // Untuk orangtua: tampilkan form deteksi + riwayat
    public function create()
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $semua = Detection::where('child_id', session('selected_child_id'))->latest()->get();
        return view('orangtua.detections.deteksi', compact('semua'));
    }

    // Orangtua bisa simpan data deteksi
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'umur' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        try {
            $result = \App\Services\StuntingDetectionService::calculateZScoreAndStatus(
                $validated['umur'],
                $validated['jenis_kelamin'],
                $validated['tinggi_badan']
            );
            $z_score = $result['z_score'];
            $status = $result['status'];
            $umur = (int) $validated['umur'];
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $child = Child::findOrFail(session('selected_child_id'));

        Detection::create([
            'child_id' => $child->id,
            'nama' => $child->nama_lengkap_anak,
            'umur' => $umur,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'berat_badan' => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'z_score' => round($z_score, 2),
            'status' => $status,
        ]);

        // Redirect ke halaman form agar lebih baik UX nya
        return redirect()->route('orangtua.detections.create')->with('success', 'Deteksi berhasil disimpan!');
    }

    // Hapus data deteksi, hanya milik user yang bersangkutan
    public function destroy($id)
    {
        $detection = Detection::with('child')->findOrFail($id);

        if ($detection->child->user_id !== auth()->id()) {
            abort(403);
        }

        $detection->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // Export PDF semua data deteksi (untuk admin)
    public function exportPdf()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $semua = Detection::with('child.user')->latest()->get();

        $html = view('admin.detections.pdf', compact('semua'))->render();
        
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Data_Deteksi_Stunting_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}

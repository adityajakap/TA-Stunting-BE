<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use App\Models\User;

class DetectionController extends Controller
{
    // Untuk admin: list semua data dengan fitur pencarian
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $semua = Detection::with('user')->latest()->get();

        return view('admin.detections.index', compact('semua'));
    }


    // Admin: tampilkan form untuk menambahkan deteksi untuk akun anak lain
    public function adminCreate()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Ambil daftar orangtua/anak untuk dipilih
        $users = User::where('role', 'orangtua')->get();

        return view('admin.detections.create', compact('users'));
    }

    // Admin dapat menyimpan deteksi untuk anak yang dipilih
    public function adminStore(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'umur' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $filePath = $validated['jenis_kelamin'] === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            return back()->with('error', 'File WHO tidak ditemukan.');
        }

        $data = json_decode(file_get_contents($filePath), true);
        if (!$data || !is_array($data)) {
            return back()->with('error', 'Gagal membaca file WHO.');
        }

        $umur = (int) $validated['umur'];
        $who_data = collect($data)->first(fn($item) => (int) $item['Month'] === $umur);

        if (!$who_data) {
            return back()->with('error', 'Data WHO tidak tersedia untuk umur ini.');
        }

        $median = $who_data['M'] ?? 0;
        $sd = $who_data['SD'] ?? 1;
        $z_score = ($validated['tinggi_badan'] - $median) / $sd;

        // Hanya dua status yang diperlukan: 'Stunting' bila z-score < -2, sisanya 'Normal'
        $status = $z_score < -2 ? 'Stunting' : 'Normal';

        Detection::create([
            'user_id' => $user->id,
            'nama' => $user->nama_anak,
            'umur' => $umur,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'berat_badan' => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'z_score' => round($z_score, 2),
            'status' => $status,
        ]);

        return redirect()->route('admin.detections.index')->with('success', 'Deteksi berhasil disimpan untuk ' . $user->nama_anak);
    }


    // Untuk orangtua: tampilkan form deteksi + riwayat
    public function create()
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $semua = Detection::where('user_id', auth()->id())->latest()->get();
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

        $filePath = $validated['jenis_kelamin'] === 'L'
            ? storage_path('app/zscores_boys.json')
            : storage_path('app/zscores_girls.json');

        if (!file_exists($filePath)) {
            return back()->with('error', 'File WHO tidak ditemukan.');
        }

        $data = json_decode(file_get_contents($filePath), true);
        if (!$data || !is_array($data)) {
            return back()->with('error', 'Gagal membaca file WHO.');
        }

        $umur = (int) $validated['umur'];
        $who_data = collect($data)->first(fn($item) => (int) $item['Month'] === $umur);

        if (!$who_data) {
            return back()->with('error', 'Data WHO tidak tersedia untuk umur ini.');
        }

        $median = $who_data['M'] ?? 0;
        $sd = $who_data['SD'] ?? 1;
        $z_score = ($validated['tinggi_badan'] - $median) / $sd;

        // Hanya dua status yang diperlukan: 'Stunting' bila z-score < -2, sisanya 'Normal'
        $status = $z_score < -2 ? 'Stunting' : 'Normal';

        Detection::create([
            'user_id' => auth()->id(),
            'nama' => auth()->user()->nama_anak,
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
        $detection = Detection::findOrFail($id);

        if (auth()->user()->id !== $detection->user_id) {
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

        $semua = Detection::with('user')->latest()->get();

        $html = view('admin.detections.pdf', compact('semua'))->render();
        
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Data_Deteksi_Stunting_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}

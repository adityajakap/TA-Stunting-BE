<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap_anak' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'nik_anak' => 'nullable|string|max:16|unique:children,nik_anak',
        ]);

        $child = Auth::user()->children()->create([
            'nama_lengkap_anak' => $request->nama_lengkap_anak,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik_anak' => $request->nik_anak,
        ]);

        // Automatically select the newly added child
        session(['selected_child_id' => $child->id]);

        return redirect()->back()->with('success', 'Data anak berhasil ditambahkan dan dipilih.');
    }

    public function select(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        $child = Child::find($request->child_id);

        if ($child->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data anak ini.');
        }

        session(['selected_child_id' => $child->id]);

        return redirect()->back()->with('success', "Anak aktif diubah menjadi {$child->nama_lengkap_anak}.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;

class UserArtikelController extends Controller
{
    // Tampilkan daftar semua artikel untuk user
    public function index(Request $request)
    {
        $search = $request->input('search');

        $artikels = Artikel::query()->where('is_published', true)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        return view('orangtua.artikel.index', [
            'artikels' => $artikels->latest()->paginate(12)->appends($request->query()),
        ]);
    }



    // Tampilkan detail satu artikel
    public function show($id)
    {
        $artikel = Artikel::with('kategoris')->where('is_published', true)->findOrFail($id);

        // Tambah 1 view setiap kali artikel dibuka oleh user
        $artikel->increment('views');

        return view('orangtua.artikel.show', compact('artikel'));

}
}
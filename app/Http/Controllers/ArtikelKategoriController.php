<?php

namespace App\Http\Controllers;

use App\Models\ArtikelKategori;
use Illuminate\Http\Request;

class ArtikelKategoriController extends Controller
{
    public function index()
    {
        $kategoris = ArtikelKategori::all();
        return response()->json($kategoris);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kategori = ArtikelKategori::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Kategori berhasil ditambahkan.', 'data' => $kategori], 201);
    }

    public function show(ArtikelKategori $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, ArtikelKategori $kategori)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kategori->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Kategori berhasil diperbarui.', 'data' => $kategori]);
    }

    public function destroy(ArtikelKategori $kategori)
    {
        $kategori->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}

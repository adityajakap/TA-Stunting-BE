<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Artikel::with('kategoris')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest();

        if ($request->has('paginate')) {
            return response()->json($query->paginate($request->input('paginate')));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategoris' => 'nullable|array',
            'kategoris.*' => 'string',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (Artikel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
        ];

        // Publish handling
        if ($request->has('publish') && $request->publish) {
            $data['is_published'] = true;
            $data['published_at'] = now();
        } else {
            $data['is_published'] = false;
            $data['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            $path = $request->image->store('artikel-images', 'public');
            $data['image'] = $path;
        }

        $artikel = Artikel::create($data);

        if ($request->has('kategoris')) {
            $kategoriIds = [];
            foreach ($request->kategoris as $catName) {
                $kat = \App\Models\ArtikelKategori::firstOrCreate(['name' => $catName]);
                $kategoriIds[] = $kat->id;
            }
            $artikel->kategoris()->sync($kategoriIds);
        }

        return response()->json(['message' => 'Artikel berhasil ditambahkan!', 'data' => $artikel], 201);
    }

    public function show($id)
    {
        $artikel = Artikel::with('kategoris')->findOrFail($id);
        
        // Increment views if requested via API
        if (request()->has('increment_views')) {
            $artikel->increment('views');
        }

        return response()->json($artikel);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategoris' => 'nullable|array',
            'kategoris.*' => 'string',
        ]);

        $artikel = Artikel::findOrFail($id);
        $data = $request->only(['title', 'content']);

        if ($request->hasFile('image')) {
            if ($artikel->image && Storage::exists('public/' . $artikel->image)) {
                Storage::delete('public/' . $artikel->image);
            }
            $path = $request->image->store('artikel-images', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = $artikel->image;
        }

        if ($request->has('publish')) {
            $data['is_published'] = $request->publish ? true : false;
            $data['published_at'] = $request->publish ? now() : null;
        }

        $artikel->update($data);

        if ($request->has('kategoris')) {
            $kategoriIds = [];
            foreach ($request->kategoris as $catName) {
                $kat = \App\Models\ArtikelKategori::firstOrCreate(['name' => $catName]);
                $kategoriIds[] = $kat->id;
            }
            $artikel->kategoris()->sync($kategoriIds);
        } else {
            $artikel->kategoris()->detach();
        }

        return response()->json(['message' => 'Artikel berhasil diperbarui!', 'data' => $artikel]);
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->image && Storage::exists('public/' . $artikel->image)) {
            Storage::delete('public/' . $artikel->image);
        }

        $artikel->delete();

        return response()->json(['message' => 'Artikel berhasil dihapus!']);
    }
}

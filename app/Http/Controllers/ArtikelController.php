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

        $artikels = Artikel::with('kategoris')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.artikel.index', compact('artikels', 'search'));
    }


    public function create()
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
    // kategori feature removed: do not attach kategori

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function show($id)
    {
        $artikel = Artikel::with('kategoris')->findOrFail($id);
        return view('admin.artikel.show', compact('artikel'));
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

        // Update publish fields if provided
        if ($request->has('publish')) {
            $data['is_published'] = $request->publish ? true : false;
            $data['published_at'] = $request->publish ? now() : null;
        } else {
            $data['is_published'] = $artikel->is_published;
            $data['published_at'] = $artikel->published_at;
        }

    $artikel->update($data);
    // kategori feature removed: do not sync kategori

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->image && Storage::exists('public/' . $artikel->image)) {
            Storage::delete('public/' . $artikel->image);
        }

    // kategori feature removed: no relationship to detach
        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }
}

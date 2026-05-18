<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NutritionRecommendation;

class NutritionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Allow simple search from the admin search modal
        $search = request('search');

        $query = NutritionRecommendation::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nutrition', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
            });
        }

        // Paginate so the view can call ->links()
        $menus = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.nutrition.index', compact('menus'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.nutrition.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/nutrition', 'public');
        }

        NutritionRecommendation::create($data);

        return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $menu = NutritionRecommendation::findOrFail($id);
        return view('admin.nutrition.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $menu = NutritionRecommendation::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/nutrition', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $menu = NutritionRecommendation::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil dihapus');
    }

    public function user(Request $request)
    {
        if (auth()->user()->role !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $kategoriList = ['pagi', 'siang', 'malam', 'snack'];
        $kategoris = collect($kategoriList)->map(function($kategori) {
            return (object)[
                'id' => $kategori,
                'name' => ucfirst($kategori)
            ];
        });

        $kategoriIds = $request->input('kategori', []);

        $menus = NutritionRecommendation::when($kategoriIds, function ($query) use ($kategoriIds) {
            return $query->whereIn('category', $kategoriIds);
        })->get();

        return view('orangtua.nutritionUs.index', compact('menus', 'kategoris', 'kategoriIds'));
    }
}

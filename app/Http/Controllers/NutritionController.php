<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NutritionRecommendation;

class NutritionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = NutritionRecommendation::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nutrition', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori')) {
            $query->whereIn('category', (array)$request->input('kategori'));
        }

        if ($request->has('paginate')) {
            return response()->json($query->orderBy('created_at', 'desc')->paginate($request->input('paginate')));
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        return response()->json(NutritionRecommendation::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'kategori_stunting' => 'required|in:Stunting,Normal',
            'rentang_umur' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/nutrition', 'public');
        }

        $menu = NutritionRecommendation::create($data);

        return response()->json(['message' => 'Menu berhasil ditambahkan', 'data' => $menu], 201);
    }

    public function update(Request $request, $id)
    {
        $menu = NutritionRecommendation::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'kategori_stunting' => 'required|in:Stunting,Normal',
            'rentang_umur' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/nutrition', 'public');
        }

        $menu->update($data);

        return response()->json(['message' => 'Menu berhasil diperbarui', 'data' => $menu]);
    }

    public function destroy($id)
    {
        $menu = NutritionRecommendation::findOrFail($id);
        $menu->delete();

        return response()->json(['message' => 'Menu berhasil dihapus']);
    }
}
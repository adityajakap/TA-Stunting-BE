<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkdnTarget;

class SkdnTargetController extends Controller
{
    public function show(Request $request)
    {
        if ($request->has('month') && $request->has('year')) {
            $target = SkdnTarget::where('month', $request->month)
                ->where('year', $request->year)
                ->first();
            return response()->json($target);
        } else if ($request->has('year')) {
            $targets = SkdnTarget::where('year', $request->year)->get();
            return response()->json($targets);
        } else {
            $targets = SkdnTarget::all();
            return response()->json($targets);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|string',
            's_value' => 'required|integer|min:0'
        ]);

        $target = SkdnTarget::updateOrCreate(
            ['month' => $request->month, 'year' => $request->year, 'posyandu_name' => 'Nusa Indah 1'],
            ['s_value' => $request->s_value]
        );

        return response()->json($target, 201);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected function checkAdmin(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(response()->json(['message' => 'Unauthorized.'], 403));
        }
    }

    /**
     * Dashboard stats for admin
     */
    public function dashboard(Request $request)
    {
        $this->checkAdmin($request);

        $totalChildren = Child::count();
        $totalOrangtua = \App\Models\User::where('role', 'orangtua')->count();
        $totalDeteksi  = \App\Models\Detection::count();
        $stunting      = \App\Models\Detection::where('status', 'Stunting')->count();

        return response()->json([
            'total_anak'     => $totalChildren,
            'total_orangtua' => $totalOrangtua,
            'total_deteksi'  => $totalDeteksi,
            'kasus_stunting' => $stunting,
        ]);
    }

    /**
     * List all children (admin)
     */
    public function children(Request $request)
    {
        $this->checkAdmin($request);
        $children = Child::with('user')->get();
        return response()->json($children);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Detection;
use App\Models\Child;
use App\Models\SkdnTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SkdnController extends Controller
{
    public function getTarget(Request $request)
    {
        if ($request->has('month') && $request->has('year')) {
            $target = SkdnTarget::where('month', $request->month)
                ->where('year', $request->year)
                ->first();
            return response()->json($target ?: ['s_value' => 0]);
        }
        
        $targets = SkdnTarget::all();
        return response()->json($targets);
    }

    public function storeTarget(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            's_value' => 'required|numeric'
        ]);

        $target = SkdnTarget::updateOrCreate(
            ['month' => $request->month, 'year' => $request->year],
            ['s_value' => $request->s_value]
        );

        return response()->json($target);
    }

    public function exportPdf($month, $year)
    {
        // K is total registered children
        $kValue = Child::count();
        
        // Fetch Sasaran (S) from SkdnTarget model
        $target = SkdnTarget::where('month', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->where('year', (string)$year)
            ->first();
        $sValue = $target ? $target->s_value : 0;

        // Get all detections
        $allDetections = Detection::all()->filter(function($d) {
            return strtolower($d->added_by ?? 'orangtua') === 'kader';
        })->values();

        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d->created_at);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        // D is children weighed this month
        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        
        // N is children with weight gained compared to previous record
        foreach ($monthDetections->unique('child_id') as $current) {
            $previous = $allDetections->where('child_id', $current->child_id)
                                      ->where('created_at', '<', $current->created_at)
                                      ->sortByDesc('created_at')
                                      ->first();
            if ($previous && isset($current->berat_badan) && isset($previous->berat_badan) && $current->berat_badan > $previous->berat_badan) {
                $nValue++;
            }
        }
        
        $dateObj = Carbon::parse($year . '-' . $month . '-01');
        $bulanNama = $dateObj->translatedFormat('F');

        $pdf = Pdf::loadView('admin.skdn.pdf', compact('month', 'year', 'bulanNama', 'sValue', 'kValue', 'dValue', 'nValue', 'monthDetections'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->stream("Laporan_SKDN_{$bulanNama}_{$year}.pdf");
    }
}

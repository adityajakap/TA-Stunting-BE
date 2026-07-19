<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Detection;
use App\Models\Child;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class NtobController extends Controller
{
    public function exportPdf($month, $year)
    {
        $kValue = Child::count();

        // Get all detections
        $allDetections = Detection::whereHas('user', function($q) {
            $q->where('role', 'kader'); // Equivalent to added_by kader if we use user role, but wait, the FE filter was strtolower($d['added_by']) === 'kader'
        })->get();

        // Let's just fetch all detections and filter in memory like FE, or use DB query
        $allDetections = Detection::all()->filter(function($d) {
            return strtolower($d->added_by ?? 'orangtua') === 'kader';
        })->values();

        $monthDetections = $allDetections->filter(function($d) use ($month, $year) {
            $dDate = Carbon::parse($d->created_at);
            return $dDate->format('m') === str_pad($month, 2, '0', STR_PAD_LEFT) && $dDate->format('Y') === $year;
        });

        $dValue = $monthDetections->unique('child_id')->count();
        $nValue = 0;
        $tValue = 0;
        $bValue = 0;

        $uniqueWeighedChildren = $monthDetections->unique('child_id');
        foreach ($uniqueWeighedChildren as $current) {
            $previous = $allDetections->where('child_id', $current->child_id)
                                      ->where('created_at', '<', $current->created_at)
                                      ->sortByDesc('created_at')
                                      ->first();

            if (!$previous) {
                $bValue++;
            } else {
                if (isset($current->berat_badan) && isset($previous->berat_badan)) {
                    if ($current->berat_badan > $previous->berat_badan) {
                        $nValue++;
                    } else {
                        $tValue++;
                    }
                }
            }
        }

        $oValue = max(0, $kValue - $dValue);

        $dateObj = Carbon::parse($year . '-' . $month . '-01');
        $bulanNama = $dateObj->translatedFormat('F');

        $pdf = Pdf::loadView('admin.ntob.pdf', compact('month', 'year', 'bulanNama', 'kValue', 'dValue', 'nValue', 'tValue', 'bValue', 'oValue', 'monthDetections'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_NTOB_{$bulanNama}_{$year}.pdf");
    }
}

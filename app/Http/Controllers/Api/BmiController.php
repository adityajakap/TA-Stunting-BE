<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bmi;
use Illuminate\Http\Request;

class BmiController extends Controller
{
    public function index(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $bmiRecords = Bmi::where('child_id', $child->id)->latest()->get();

        $lastBmi = $bmiRecords->first();
        $estimatedCalories = null;
        $saranKalori = null;

        if ($lastBmi && $lastBmi->usia && $lastBmi->activity_level) {
            $weight = $lastBmi->berat;
            $height = $lastBmi->tinggi;
            $usia = $lastBmi->usia;
            $activityLevel = $lastBmi->activity_level;
            $gender = $lastBmi->gender ?? 'pria';

            $bmr = $gender === 'pria'
                ? 10 * $weight + 6.25 * $height - 5 * $usia + 5
                : 10 * $weight + 6.25 * $height - 5 * $usia - 161;

            $activityFactors = [
                'sedentary'        => 1.2,
                'lightly_active'   => 1.375,
                'moderately_active'=> 1.55,
                'very_active'      => 1.725,
                'extra_active'     => 1.9,
            ];

            $factor = $activityFactors[$activityLevel] ?? 1.2;
            $estimatedCalories = round($bmr * $factor);
            $saranKalori = match($lastBmi->status) {
                'Underweight' => $estimatedCalories + 500,
                'Overweight', 'Obese' => $estimatedCalories - 500,
                default => $estimatedCalories,
            };
        }

        return response()->json([
            'bmi_records'       => $bmiRecords,
            'last_bmi'          => $lastBmi,
            'estimated_calories'=> $estimatedCalories,
            'saran_kalori'      => $saranKalori,
        ]);
    }

    public function store(Request $request, $childId)
    {
        $child = $request->user()->children()->findOrFail($childId);

        $validated = $request->validate([
            'berat'          => 'required|numeric',
            'tinggi'         => 'required|numeric',
            'usia'           => 'required|numeric',
            'gender'         => 'required|in:pria,wanita',
            'activity_level' => 'required|string',
        ]);

        $bmi_value = $validated['berat'] / (($validated['tinggi'] / 100) ** 2);
        $status = match(true) {
            $bmi_value < 18.5 => 'Underweight',
            $bmi_value < 25   => 'Normal',
            $bmi_value < 30   => 'Overweight',
            default           => 'Obese',
        };

        $bmi = Bmi::create([
            'child_id'       => $child->id,
            'berat'          => $validated['berat'],
            'tinggi'         => $validated['tinggi'],
            'usia'           => $validated['usia'],
            'gender'         => $validated['gender'],
            'activity_level' => $validated['activity_level'],
            'bmi'            => round($bmi_value, 2),
            'status'         => $status,
        ]);

        return response()->json($bmi, 201);
    }

    public function destroy(Request $request, $childId, $id)
    {
        $child = $request->user()->children()->findOrFail($childId);
        $bmi = Bmi::where('child_id', $child->id)->findOrFail($id);
        $bmi->delete();
        return response()->json(['message' => 'Data BMI dihapus.']);
    }
}

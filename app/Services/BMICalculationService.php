<?php

namespace App\Services;

class BMICalculationService
{
    /**
     * Menghitung status BMI berdasarkan nilai BMI dan gender.
     */
    public static function getStatus($bmi, $gender)
    {
        $gender = strtolower($gender);
        
        if ($gender == 'pria' || $gender == 'laki-laki') {
            return self::statusBmiPria($bmi);
        } elseif ($gender == 'wanita' || $gender == 'perempuan') {
            return self::statusBmiWanita($bmi);
        }
        
        return "Gender tidak valid";
    }

    private static function statusBmiPria($bmi)
    {
        if ($bmi < 18.5) {
            return "Underweight";
        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
            return "Normal";
        } elseif ($bmi >= 25 && $bmi < 29.9) {
            return "Overweight";
        } else {
            return "Obese";
        }
    }

    private static function statusBmiWanita($bmi)
    {
        if ($bmi < 17.5) {
            return "Underweight";
        } elseif ($bmi >= 17.5 && $bmi < 23.9) {
            return "Normal";
        } elseif ($bmi >= 24 && $bmi < 28.9) {
            return "Overweight";
        } else {
            return "Obese";
        }
    }
}

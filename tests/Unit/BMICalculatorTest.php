<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BMICalculationService;

class BMICalculatorTest extends TestCase
{
    /**
     * Test perhitungan status BMI untuk laki-laki (Pria)
     */
    public function test_status_bmi_pria()
    {
        // Skenario 1: Kurang dari 18.5 -> Underweight
        $this->assertEquals("Underweight", BMICalculationService::getStatus(18.4, 'pria'));
        
        // Skenario 2: Antara 18.5 s/d 24.89 -> Normal
        $this->assertEquals("Normal", BMICalculationService::getStatus(22.0, 'pria'));
        
        // Skenario 3: Antara 25 s/d 29.89 -> Overweight
        $this->assertEquals("Overweight", BMICalculationService::getStatus(26.5, 'pria'));
        
        // Skenario 4: Lebih dari 29.9 -> Obese
        $this->assertEquals("Obese", BMICalculationService::getStatus(30.0, 'pria'));
    }

    /**
     * Test perhitungan status BMI untuk perempuan (Wanita)
     */
    public function test_status_bmi_wanita()
    {
        // Skenario 1: Kurang dari 17.5 -> Underweight
        $this->assertEquals("Underweight", BMICalculationService::getStatus(17.4, 'wanita'));
        
        // Skenario 2: Antara 17.5 s/d 23.89 -> Normal
        $this->assertEquals("Normal", BMICalculationService::getStatus(20.0, 'wanita'));
        
        // Skenario 3: Antara 24 s/d 28.89 -> Overweight
        $this->assertEquals("Overweight", BMICalculationService::getStatus(25.0, 'wanita'));
        
        // Skenario 4: Lebih dari 28.9 -> Obese
        $this->assertEquals("Obese", BMICalculationService::getStatus(29.0, 'wanita'));
    }
}

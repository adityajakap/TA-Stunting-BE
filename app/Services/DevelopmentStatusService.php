<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Child;
use App\Models\TahapanPerkembangan;

class DevelopmentStatusService
{
    /**
     * Evaluates the milestone status for a child.
     * 
     * @param Child $child
     * @param TahapanPerkembangan $tahapan
     * @param string|null $tanggal_pencapaian (Y-m-d) if achieved, null if not achieved yet
     * @return array Status details containing status key, label, badge class, and recommendation
     */
    public static function evaluate(Child $child, TahapanPerkembangan $tahapan, $tanggal_pencapaian = null)
    {
        $minAge = $tahapan->umur_minimal_bulan;
        $maxAge = $tahapan->umur_maksimal_bulan;
        $evalAge = $tahapan->batas_evaluasi_bulan;
        
        // Calculate age when achieved, or current age if not achieved
        if ($tanggal_pencapaian) {
            $ageInMonths = (int) round(Carbon::parse($child->tanggal_lahir)->floatDiffInMonths(Carbon::parse($tanggal_pencapaian)));
            return self::evaluateAchieved($ageInMonths, $minAge, $maxAge, $evalAge);
        } else {
            $currentAgeInMonths = (int) round(Carbon::parse($child->tanggal_lahir)->floatDiffInMonths(Carbon::now()));
            return self::evaluateNotAchieved($currentAgeInMonths, $minAge, $maxAge, $evalAge);
        }
    }

    private static function evaluateAchieved($age, $min, $max, $eval)
    {
        if ($age < $min) {
            return [
                'status' => 'lebih_cepat',
                'label' => 'Lebih Cepat',
                'badge' => 'bg-info text-dark', // Biru muda
                'rekomendasi' => 'Pencapaian sangat baik, anak mencapai milestone lebih awal dari rentang biasanya.'
            ];
        } elseif ($age >= $min && $age <= $max) {
            return [
                'status' => 'tepat_waktu',
                'label' => 'Tepat Waktu',
                'badge' => 'bg-success', // Hijau
                'rekomendasi' => 'Pencapaian sangat baik dan sesuai dengan rentang umur yang diharapkan.'
            ];
        } elseif ($age > $max && $age <= $eval) {
            return [
                'status' => 'lambat_pantau',
                'label' => 'Lambat / Perlu dipantau',
                'badge' => 'bg-warning text-dark', // Kuning
                'rekomendasi' => 'Pantau kembali perkembangan anak secara berkala. Pencapaian ini sedikit di atas rentang umur maksimal, tetapi masih dalam batas evaluasi.'
            ];
        } else { // $age > $eval
            return [
                'status' => 'terlambat_evaluasi',
                'label' => 'Terlambat / Perlu evaluasi',
                'badge' => 'bg-danger', // Merah
                'rekomendasi' => 'Pencapaian ini melewati batas evaluasi bulan. Konsultasikan dengan tenaga kesehatan sebagai langkah antisipasi yang baik.'
            ];
        }
    }

    private static function evaluateNotAchieved($age, $min, $max, $eval)
    {
        if ($age < $min) {
            return [
                'status' => 'belum_waktunya',
                'label' => 'Belum waktunya',
                'badge' => 'bg-secondary', // Abu-abu
                'rekomendasi' => 'Anak masih di bawah batas umur minimal untuk milestone ini. Ini sangat normal, terus stimulasi anak Anda.'
            ];
        } elseif ($age >= $min && $age <= $max) {
            return [
                'status' => 'belum_rentang',
                'label' => 'Belum tercapai, masih dalam rentang',
                'badge' => 'bg-primary', // Biru
                'rekomendasi' => 'Anak saat ini berada di rentang umur yang tepat untuk mencapai milestone ini. Berikan stimulasi yang sesuai dan terus pantau.'
            ];
        } elseif ($age > $max && $age <= $eval) {
            return [
                'status' => 'belum_pantau',
                'label' => 'Belum tercapai, perlu dipantau',
                'badge' => 'bg-warning text-dark', // Kuning
                'rekomendasi' => 'Pantau kembali perkembangan anak dan konsultasikan dengan tenaga kesehatan apabila ada kekhawatiran atau milestone belum tercapai setelah batas evaluasi.'
            ];
        } else { // $age > $eval
            return [
                'status' => 'belum_evaluasi',
                'label' => 'Belum tercapai, perlu evaluasi',
                'badge' => 'bg-danger', // Merah
                'rekomendasi' => 'Usia anak saat ini sudah melewati batas evaluasi untuk milestone ini. Segera konsultasikan dengan tenaga kesehatan sebagai indikator pemantauan perkembangan.'
            ];
        }
    }
}

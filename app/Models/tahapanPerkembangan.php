<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapanPerkembangan extends Model
{
    use HasFactory;

    protected $table = 'tahapan_perkembangan';

    protected $fillable = [
        'kategori',
        'nama_tahapan',
        'deskripsi',
        'umur_minimal_bulan',
        'umur_maksimal_bulan',
        'batas_evaluasi_bulan',
        'sumber_referensi',
        'catatan',
    ];

    public function dataPerkembangan()
    {
        return $this->hasMany(TahapanPerkembanganData::class);
    }
}

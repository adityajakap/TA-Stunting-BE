<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapanPerkembanganData extends Model
{
    use HasFactory;

    protected $table = 'tahapan_perkembangan_data';

    protected $fillable = [
        'user_id',
        'tahapan_perkembangan_id',
        'tanggal_pencapaian',
        'status',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tahapanPerkembangan()
    {
        return $this->belongsTo(TahapanPerkembangan::class);
    }

    /**
     * Boot method untuk auto-set status berdasarkan umur anak
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->status = $model->calculateStatus();
        });

        static::updating(function ($model) {
            $model->status = $model->calculateStatus();
        });
    }

    /**
     * Hitung status otomatis berdasarkan umur anak dan tahapan perkembangan
     */
    public function calculateStatus()
    {
        if (!$this->user || !$this->tahapanPerkembangan) {
            return 'belum_tercapai';
        }

        $umurBulan = $this->user->umur_bulan;
        $umurMinimal = $this->tahapanPerkembangan->umur_minimal_bulan;
        $umurMaksimal = $this->tahapanPerkembangan->umur_maksimal_bulan;

        // Jika umur anak sudah melewati umur maksimal tahapan, dianggap tercapai
        if ($umurBulan >= $umurMaksimal) {
            return 'tercapai';
        }

        // Jika umur anak dalam rentang tahapan, dianggap tercapai
        if ($umurBulan >= $umurMinimal && $umurBulan <= $umurMaksimal) {
            return 'tercapai';
        }

        return 'belum_tercapai';
    }

    /**
     * Accessor untuk menampilkan status yang user-friendly
     */
    public function getStatusDisplayAttribute()
    {
        return $this->status === 'tercapai' ? 'Tercapai ✓' : 'Belum Tercapai';
    }
}

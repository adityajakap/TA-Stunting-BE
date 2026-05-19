<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapanPerkembanganData extends Model
{
    use HasFactory;

    protected $table = 'tahapan_perkembangan_data';

    protected $fillable = [
        'child_id',
        'tahapan_perkembangan_id',
        'tanggal_pencapaian',
        'status',
        'catatan',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function tahapanPerkembangan()
    {
        return $this->belongsTo(TahapanPerkembangan::class);
    }

    // Boot method dan calculateStatus dihapus karena sekarang dihandle secara dinamis oleh DevelopmentStatusService

    /**
     * Accessor untuk mendapatkan detail status dari service secara dinamis
     * Akan me-return array: ['status' => ..., 'label' => ..., 'badge' => ..., 'rekomendasi' => ...]
     */
    public function getStatusDetailAttribute()
    {
        return \App\Services\DevelopmentStatusService::evaluate(
            $this->child,
            $this->tahapanPerkembangan,
            $this->tanggal_pencapaian
        );
    }
}

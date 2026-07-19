<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('age_limit', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $limitDate = \Carbon\Carbon::now()->subMonths(60)->toDateString();
            $builder->where('tanggal_lahir', '>=', $limitDate);
        });
    }

    protected $fillable = [
        'user_id',
        'nama_lengkap_anak',
        'tanggal_lahir',
        'jenis_kelamin',
        'nik_anak'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tahapanPerkembanganData()
    {
        return $this->hasMany(TahapanPerkembanganData::class);
    }

    public function detections()
    {
        return $this->hasMany(Detection::class);
    }

    public function bmis()
    {
        return $this->hasMany(Bmi::class);
    }

    /**
     * Hitung umur anak dalam bulan
     */
    public function getUmurBulanAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        
        $birthDate = Carbon::parse($this->tanggal_lahir);
        $now = Carbon::now();
        
        return (int) round($birthDate->floatDiffInMonths($now));
    }
}
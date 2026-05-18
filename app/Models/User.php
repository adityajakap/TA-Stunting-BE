<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nama_anak', 'nik_anak', 'tanggal_lahir', 'password', 'role'];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

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
        
        // Hitung bulan dari tanggal lahir
        return $birthDate->diffInMonths($now);
    }

    /**
     * Relasi ke tahapan perkembangan data
     */
    public function tahapanPerkembanganData()
    {
        return $this->hasMany(TahapanPerkembanganData::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bmi extends Model
{
    use HasFactory;

    protected $table = 'bmi';

    protected $fillable = [
        'child_id',
        'tanggal',
        'tinggi',
        'berat',
        'bmi',
        'status',
        'gender',
        'usia',
        'activity_level',
    ];

    /**
     * Relasi ke model Child
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}

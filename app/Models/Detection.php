<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id', 
        'nama',
        'umur',
        'jenis_kelamin',
        'berat_badan',
        'tinggi_badan',
        'z_score',
        'status',
        'added_by',
<<<<<<< HEAD
        'kader_name',
=======
>>>>>>> ccd89a1fced35046f85d2cb1c2c6c394b5cfafcf
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
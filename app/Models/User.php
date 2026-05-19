<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['nik_ibu', 'nama_lengkap', 'username', 'password', 'role'];

    protected $hidden = ['password'];

    /**
     * Relasi ke children
     */
    public function children()
    {
        return $this->hasMany(Child::class);
    }
}

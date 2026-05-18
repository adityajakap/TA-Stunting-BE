<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'views',
        'is_published',
        'published_at',
    ];

    public function kategoris()
    {
        return $this->belongsToMany(ArtikelKategori::class, 'artikel_artikel_kategori');
    }
}

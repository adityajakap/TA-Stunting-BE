<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nutrition', 'ingredients', 'instructions', 'category', 'kategori_stunting', 'rentang_umur', 'image'
    ];
}

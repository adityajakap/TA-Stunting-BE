<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// NOTE: ImmunizationRecord feature removed from UI — model kept for compatibility.
class ImmunizationRecord extends Model
{
    protected $fillable = ['user_id', 'immunization_id', 'immunized_at', 'status'];

    public function immunization()
    {
        return $this->belongsTo(Immunization::class);
    }
}


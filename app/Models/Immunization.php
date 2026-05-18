<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// NOTE: Immunization feature was removed from the UI; this model is kept
// as a lightweight compatibility shim to avoid breaking existing tests or
// code that may still reference the table. Consider removing the migration
// and model entirely if you want to fully purge the feature.
class Immunization extends Model
{
    protected $fillable = ['name', 'age', 'description'];

    public function records()
    {
        return $this->hasMany(ImmunizationRecord::class);
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Burn extends Model
{
    use HasFactory;
    protected $fillable=['result','patient_id', 'professional_id', 'resultados'];
     //Relación inversa de uno a muchos Patient-Burn
     public function patient() {
        return $this->belongsTo("App\Models\Patient");
    }
}

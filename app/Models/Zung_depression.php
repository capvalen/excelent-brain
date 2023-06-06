<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zung_depression extends Model
{
    use HasFactory;
    protected $fillable=['result','patient_id', 'professional_id'];
     //Relación inversa de uno a muchos Patient-Zung_depression
     public function patient() {
        return $this->belongsTo("App\Models\Patient");
    }
}

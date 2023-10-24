<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable=['attention_date','effective_date','patient_id','signature', 'professional_id'];
    //Relacion muchos a muchos Prescripttions-karios
    public function kairos()
    {
        return $this->belongsToMany("App\Models\Kairo")->withPivot('amount');
    }
    //Relacion INVERSA uno a uno Patient-Prescription
    public function patient()
    {
        return $this->belongsTo("App\Models\Patient");
    }
    public function professional()
    {
        return $this->belongsTo("App\Models\Professional");
    }

}

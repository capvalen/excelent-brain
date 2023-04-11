<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Initial_psychiatric_history extends Model
{
    use HasFactory;
    protected $fillable=['general_antecedent','main_signs_symptoms','illness','apc','languaje','thought','affect','percetion','superior_function','abstraction','conscience','insight','diagnostic_problems','diagnostic','plan','professional_id','patient_id'];
    //Relacion inversa de uno a uno Initial_psychiatric-Professional
    public function professional()
    {
        return $this->hasOne("App\Models\Professional");
    }
    //Relacion inversa de uno a uno Initial_psychiatric-Patient
    public function patient()
    {
        return $this->hasOne("App\Models\Patient");
    }
}

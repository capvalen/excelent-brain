<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Millon extends Model
{
    use HasFactory;
    protected $fillable=['schizoid','avoidant','depressant','dependent','histrionic','narcissistic','antisocial','agressive_sadistic','compulsive','naysayer','self_destructive','schizotypal','limit','paranoid','anxiety_disorder','somatoform_disorder','bipolar_disorder','dysthymic_disorder','alcohol_dependence','substance_dependence','post_traumatic_stress_disorder','thought_disorder','major_depression','delusional_disorder','sincerity','social_desirability','devaluation','validaity','patient_id', 'professional_id'];
     //Relación inversa de uno a muchos Patient-Millon
     public function patient() {
        return $this->belongsTo("App\Models\Patient");
    }
}

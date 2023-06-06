<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scr extends Model
{
    use HasFactory;
    protected $fillable=['somatization','obsession','interpersonal_sensitivity','depression','anxiety','hostility','phobic_anxiety','paranoid_ideation','psychoticism','igs','sp','psdi','patient_id', 'professional_id'];
    //Relación inversa de uno a muchos Patient-Scr
    public function patient() {
        return $this->belongsTo("App\Models\Patient");
    }
}

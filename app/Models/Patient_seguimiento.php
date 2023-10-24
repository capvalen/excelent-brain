<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient_seguimiento extends Model
{
    use HasFactory;
    protected $table = "pacient_seguimiento";
    protected $fillabe = ['registro', 'patient_id', 'user_id', 'idSeguimiento', 'observaciones', 'activo'];

    public function patient(){
        return $this->belongsTo("App\Models\Patient");
    }
    
    public function user(){
        return $this->belongsTo("App\Models\User");
    }
}

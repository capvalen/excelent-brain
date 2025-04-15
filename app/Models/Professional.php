<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;
    protected $fillable=['name','lastname','phone','profession', 'idProfesion','cv_description','photo','signing','user_id', 'idProfesion', 'activo'];
    //Relación de uno a muchos Professional-Schedule
    public function schedules() {
        return $this->hasMany("App\Models\Schedule");
    }
    //Relación de uno a muchos Proffesional-Appointment
    public function appointments() {
        return $this->hasMany("App\Models\Appointment");
    }
     //Relacion inversa de uno a uno Initial_psychiatric-Professional
     public function initial_psychiatric_history()
     {
         return $this->hasOne("App\Models\Initial_psychiatric_history");
     }
     //Relacion inversa de uno a uno Initial_psychiatric-Professional
     public function initial_psychological_history()
     {
         return $this->hasOne("App\Models\Initial_psychological_history");
     }
     //Relacion inversa de uno a uno User-Professional
     public function user()
     {
         return $this->hasOne("App\Models\User");
     }
    //Relación de uno a muchos Professiobnal-Medical_evolutions
    public function medical_evolutions() {
        return $this->hasMany("App\Models\Medical_evolution");
    }
     
}

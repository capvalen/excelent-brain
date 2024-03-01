<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=['date','type','patient_condition','link','mode','status','clasification','recomendation','professional_id','patient_id','schedule_id', 'formato_nuevo', 'byDoctor', 'idMembresia', 'recomendacion_comentario'];
    //Relación inversa de uno a muchos Patient-Appointment
    public function patient() {
        return $this->belongsTo("App\Models\Patient");
    }
    //Relación inversa de uno a muchos Patient-Appointment
    public function professional() {
        return $this->belongsTo("App\Models\Professional");
    }
		//Relacion uno a muchos inverso Schedule-Appoinment
    public function schedule()
    {
        return $this->belongsTo("App\Models\Schedule");
    }
    //Relación de muchos a muchos Appointment-medical_exams
    public function medical_exams() {
        return $this->belongsToMany("App\Models\Medical_exam");
    } 
    
    //Relacion uno a uno Appointment-reschedule
    public function reschedule()
    {
        return $this->hasOne("App\Models\Reschedule");
    }
    //Relacion uno a uno Appointment-Payment
    public function payment()
    {
        return $this->hasOne("App\Models\Payment");
    }
    public function precio(){
        return $this->hasOne('App\Models\Precio', 'id', 'type');
    }
    public function pagosExtras(){
        return $this->hasMany("App\Models\Extra_payment",'appointment_id', 'id');
    }
    public function faltas(){
        return $this->hasMany("App\Models\Faltas",'idCita', 'id');
    }
   
}

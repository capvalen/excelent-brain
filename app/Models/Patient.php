<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable=['name','nombres','email','birth_date','dni','phone','occupation','instruction_degree','birth_date','marital_status','discharge','faults', 'gender', 'type_dni', 'club', 'hobbies', 'activo', 'etiqueta', 'new_status', 'seguimiento', 'vivo'];
    //Relacion uno a uno Patient-Relaive
    public function relative()
    {
        return $this->hasOne("App\Models\Relative");
    }
    //Relacion uno a uno Patient-Address
    public function address()
    {
        return $this->hasOne("App\Models\Address");
    }
    //Relacion muchos a muchos Patients-Cies
    public function cies()
    {
        return $this->belongsToMany("App\Models\Cie");
        // ->withPivot('cie_patient');
    }
    //Relación de uno a muchos Patient-Appointment
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
    //Relación de uno a muchos Patient-Medical_evolutions
    public function medical_evolutions() {
        return $this->hasMany("App\Models\Medical_evolution");
    }

    //Relación de uno a muchos Patient-Prescription
    public function prescriptions() {
        return $this->hasMany("App\Models\Prescription");
    }
    public function membresias() {
        return $this->hasMany("App\Models\Membresia");
    }
    //Relación de uno a muchos Patient-Scr
    public function scrs() {
        return $this->hasMany("App\Models\Scr");
    }
    //Relación de uno a muchos Patient-Burn
    public function burns() {
        return $this->hasMany("App\Models\Burn");
    }
    //Relación de uno a muchos Patient-Zung_anxietys
    public function zung_anxiety() {
        return $this->hasMany("App\Models\Zung_anxiety");
    }
    //Relación de uno a muchos Patient-Zung_depression
    public function zung_depression() {
        return $this->hasMany("App\Models\Zung_depression");
    }
    //Relación de uno a muchos Patient-Millons
    public function millons() {
        return $this->hasMany("App\Models\Millon");
    }

    public function gads() {
        return $this->hasMany('App\Models\Gad');
    }

    public function phqs() {
        return $this->hasMany('App\Models\Phq');
    }

    public function Exams() {
        return $this->hasMany('App\Models\Exam');
    }
		public function Semaforo()
    {
        return $this->hasMany("App\Models\Semaforo");
    }
}

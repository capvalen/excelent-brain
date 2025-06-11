<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_exam extends Model
{
    use HasFactory;
    protected $fillable=['name', 'type'];
		//Indicamos que el valor es numérico
		protected $casts = [
        'type' => 'integer' // Esto convertirá automáticamente el campo a entero
    ];

    //Relación inversa de muchos a muchos Appointment-medical_exams
    public function appointments() {
        return $this->belongsToMany("App\Models\Appointment");
    }

}

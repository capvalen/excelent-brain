<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_evolution extends Model
{
    use HasFactory;
    protected $fillable=['type','date','schedule','content','auth','patient_id', 'professional_id', 'diagnostic', 'treatment'];
		protected $casts = [
        'diagnostic' => 'array',
    ];
    //Relacion INVERSA uno a uno Appoinment-Medical_evolution
    public function patient()
    {
        return $this->belongsTo("App\Models\Patient");
    }
    //Relacion INVERSA uno a uno Appoinment-Medical_evolution
    public function professional()
    {
        return $this->belongsTo("App\Models\Professional");
    }
    public function comentarios(){
        return $this->hasMany("App\Models\Medical_comment", 'idMedical', 'id');
    }

    //Obtener tipo de evolution en la tabla precios_clasificacion referenciando con type.
    public function typeEvolution(){
        return $this->belongsTo("App\Models\PrecioClasificacion", 'type', 'id');
    }

}

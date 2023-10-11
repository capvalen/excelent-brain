<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $fillable=['descripcion', 'nuevos', 'continuos', 'idClasificacion', 'sesiones', 'servicio'];
    use HasFactory;
    //Relacion uno a uno Appointment-Payment
    public function appointment()
    {
        return $this->hasOne("App\Models\Appointment");
    }
}

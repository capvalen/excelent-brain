<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable=['descripcion', 'nuevos', 'continuos', 'idClasificacion', 'sesiones', 'servicio'];
    //Relacion uno a uno Appointment-Payment
    public function appointment()
    {
        return $this->hasOne("App\Models\Appointment");
    }
}

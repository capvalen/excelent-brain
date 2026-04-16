<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioClasificacion extends Model
{
    protected $table = 'precios_clasificacion';
    protected $fillable = ['descripcion'];
    public $timestamps = false;

    //Relacion uno a muchos con Medical_evolution
    public function medicalEvolutions()
    {
        return $this->hasMany("App\Models\Medical_evolution");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acuerdo extends Model
{
    use HasFactory;
    protected $fillable=['patient_id','titulo', 'descripcion', 'archivo', 'ruta', 'user_id', 'activo'];
    protected $appends = ['nombre_usuario'];


    public function usuario()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }
    public function paciente()
    {
        return $this->hasOne(Patient::class,'id');
    }
    public function getNombreUsuarioAttribute()
    {
        return $this->usuario?->nombre;
    }
}

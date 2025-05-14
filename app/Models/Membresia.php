<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    public $timestamps = false;

    use HasFactory;
    //protected $table = "membresias";
    protected $fillable=['patient_id', 'inicio', 'fin', 'tipo', 'user_id', 'cuotas', 'monto', 'estado', 'activo' ];

    public function precio(){
        return $this->belongsTo( Precio::class, 'tipo' );
    }
}

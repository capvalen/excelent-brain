<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table="tipocomprobante";
    use HasFactory;
    protected $fillable=['descripcion'];
}

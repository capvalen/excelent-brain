<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triaje extends Model
{
    use HasFactory;
		protected $table='triaje';
		protected $fillable=['patient_id','motivo','sintomatologia','antecedentes','prioridad','especialista'];

		public function professional()
    {
        return $this->belongsTo("App\Models\Professional");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semaforo extends Model
{
    use HasFactory;
		protected $table = 'semaforo';

    protected $fillable=['patient_id', 'codigo', 'observaciones', 'activo', 'registro'];
    //Relacion uno a uno Patient-Relative

		protected $casts = [
			'codigo' => 'int'
    ];

    public function patient()
    {
        return $this->hasOne("App\Models\Patient");
    }
}

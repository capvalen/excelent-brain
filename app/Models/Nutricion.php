<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutricion extends Model
{
	public $timestamps = false;
	protected $table = 'nutriciones';

	use HasFactory;
	protected $fillable=['idPaciente', 'peso', 'talla', 
	'imc', 'perimetro', 'grasa', 'comidas', 'intolerancia', 'alergias',
	 'fuma', 'bebe', 'signos', 'diagnostico', 'dieta', 'suplemento'];
	public function patient() {
		return $this->belongsTo("App\Models\Patient", 'idPaciente');
	}
}

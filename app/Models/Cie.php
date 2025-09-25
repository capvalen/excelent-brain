<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cie extends Model
{
	use HasFactory;
	public $timestamps = false;
    protected $fillable=['code','description'];
		protected $visible = ['id', 'code', 'description'];
     //Relacion muchos a muchos Patients-Cies
     public function patients()
     {
         return $this->belongsToMany("App\Models\Patient");
        //  ->withPivot('cie_patient');
     }
     
}

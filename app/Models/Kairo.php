<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kairo extends Model
{
    use HasFactory;
    protected $fillable=['name','tradename','concentration','presentation'];
    //Relacion muchos a muchos Prescripttions-karios
    public function prescriptions()
    {
        return $this->belongsToMany("App\Models\Prescription")->withPivot('kairo_prescritpion');
    }
}

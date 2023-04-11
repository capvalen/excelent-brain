<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    use HasFactory;
    protected $fillable=['name','phone','kinship','patient_id'];
    //Relacion uno a uno Patient-Relative
    public function patient()
    {
        return $this->hasOne("App\Models\Patient");
    }
}

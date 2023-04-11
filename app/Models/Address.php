<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable=['address','district','province','department','patient_id'];
    //Relacion uno a uno Patient-Address
    public function patient()
    {
        return $this->hasOne("App\Models\Patient");
    }
}

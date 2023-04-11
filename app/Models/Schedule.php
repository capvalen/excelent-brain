<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable=['check_time','departure_date','day','professional_id'];
    //Relación inversa de uno a muchos Professional-Schedule
    public function professional() {
        return $this->belongsTo("App\Models\Professional");
    }
    //Relacion uno a uno Schedule-Appoinment
    public function appointments()
    {
        return $this->hasMany("App\Models\Appointment");
    }
}

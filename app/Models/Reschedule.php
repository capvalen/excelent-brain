<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reschedule extends Model
{
    use HasFactory;
    protected $fillable=['reason','appointment_id', 'fechaProxima'];
    //Relacion uno a uno Appointment_reschedule
    public function appointment()
    {
        return $this->belongsTo("App\Models\Appointment");
    }
}

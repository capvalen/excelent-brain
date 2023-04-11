<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reschedule extends Model
{
    use HasFactory;
    protected $fillable=['reason','appointment_id'];
    //Relacion uno a uno Appointment_reschedule
    public function appointment()
    {
        return $this->hasOne("App\Models\Appointmet");
    }
}

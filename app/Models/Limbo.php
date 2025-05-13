<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limbo extends Model
{
    use HasFactory;
    protected $fillable=['appointment_id','user_id','motivo'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function patient()
    {
        //1° modelo destino, 2° modelo intermedio
        return $this->hasOneThrough( Patient::class, Appointment::class);
    }
    public function professional(){
        return $this->hasOneThrough( Professional::class, Appointment::class );
    }
    public function precio(){
        return $this->hasOneThrough( Precio::class, Appointment::class );
    }
    public function schedule(){
        return $this->hasOneThrough( Schedule::class, Appointment::class );
    }
    public function payment(){
        return $this->hasOne( Payment::class, Appointment::class );
    }
    public function pagosExtras(){
        return $this->hasOne(Extra_payment::class,'appointment_id', 'id')->where('activo', 1)->first();
    }

}

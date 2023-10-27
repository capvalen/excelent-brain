<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra_payment extends Model
{
    protected $fillable=['customer','date','price','type','observation', 'voucher', 'voucher_issued', 'continuo', 'moneda', 'user_id', 'appointment_id', 'activo', 'razon', 'rebaja', 'motivoRebaja', 'descuento', 'motivoDescuento', 'razonAdelanto', 'idMembresia'];
    use HasFactory;

    public function appointment() {
        return $this->belongsTo("App\Models\Appointment");
    }

    public function precio(){
        return $this->hasOne("App\Models\Precio", 'id', 'type');
    }
}

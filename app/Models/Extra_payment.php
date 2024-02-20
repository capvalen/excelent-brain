<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra_payment extends Model
{
    protected $fillable=['customer','date','price','type','observation', 'voucher', 'voucher_issued', 'continuo', 'moneda', 'user_id', 'appointment_id', 'activo', 'razon', 'rebaja', 'motivoRebaja', 'descuento', 'motivoDescuento', 'razonAdelanto', 'idMembresia', 'tipo'];
    use HasFactory;

    public function appointment() {
        return $this->belongsTo("App\Models\Appointment");
    }

 /*    public function precio(){
        return $this->hasOne("App\Models\Precio", 'id', 'type');
    } */

    public function comprobante(){
        return $this->hasOne("App\Models\TipoComprobante", 'id', 'tipo');
    }

    public function method_payment(){
        return $this->hasOne("App\Models\Payment_method", 'id', 'moneda');
    }

    public function usuario(){
        return $this->hasOne("App\Models\User", 'id', 'user_id');
    }

    public function tipo_pagos(){
        return $this->hasOne("App\Models\TipoPago", 'id', 'type');
    }
}

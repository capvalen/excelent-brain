<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable=['bank','voucher','voucher_issued','pay_status','price','payment_method','observation','appointment_id'];
    use HasFactory;
    //Relacion uno a uno Appointment-Payment
    public function appointment()
    {
        return $this->hasOne("App\Models\Appointment");
    }
}

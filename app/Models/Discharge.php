<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'professional_id',
        'type', // 1: Psicológica, 2: Psiquiátrica
        'comments',
        'status', // 1: Activo, 2: Inactivo, 3: Reingreso
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id', 'id');
    }
}

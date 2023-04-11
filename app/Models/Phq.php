<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phq extends Model
{
    use HasFactory;
    protected $fillable = ['result', 'patient_id'];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }
}

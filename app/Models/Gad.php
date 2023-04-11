<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gad extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'result'];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }
}

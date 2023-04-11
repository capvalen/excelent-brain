<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['patient_id', 'exam'];
    use HasFactory;

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }
}

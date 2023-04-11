<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_detail extends Model
{
    use HasFactory;
    protected $fillable=['code','name','medical_exam_id'];
    //Relación inversa de uno a muchos Appointment-medical_exams
    public function medical_exam() {
        return $this->belongsTo("App\Models\Medical_exam");
    }
}

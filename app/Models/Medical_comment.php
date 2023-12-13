<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_comment extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable=['idMedical', 'comment', 'proffesional_id'];

}

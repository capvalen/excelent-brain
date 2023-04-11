<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adittional extends Model
{
    use HasFactory;

    protected $fillable=['name','number','professional_id','schedule'];

    public function professional() {
        return $this->belongsTo("App\Models\Professional");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra_payment extends Model
{
    protected $fillable=['customer','date','price','type','observation'];
    use HasFactory;
}

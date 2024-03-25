<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modify extends Model
{
    protected $fillable = [
        'pay_id',
        'payroll_id',
        'off_id',
        'column',
        'affected',
        'label',
        'action', 
        'amount',
    ];
    //use HasFactory;
}

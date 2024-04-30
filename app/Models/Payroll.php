<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'stat_id',
        'campus_id',
        'number_days',
        'jo_type',
        'fund',
        'payroll_dateStart',
        'payroll_dateEnd',
        'user_id',
        'pay_status',
    ];
    
    // use HasFactory;
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $table = 'employees';

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'position',
        'sg_step',
        'qualification',
        'camp_id',
        'emp_ID',
        'emp_status',
        'jo_type',
        'emp_dept',
        'emp_salary',
        'partime_rate',
    ];
    
    // use HasFactory;
}


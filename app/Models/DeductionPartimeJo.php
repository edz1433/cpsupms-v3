<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionPartimeJo extends Model
{
    protected $fillable = [
        'pay_id', 'payroll_id', 'emp_id', 'tax1', 'tax2', 'jo_sss', 'projects', 'nsca_mpc', 'add_less_abs', 
        'less_late', 'grad_guarantor'
    ];
    // use HasFactory;
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionJo extends Model
{
    protected $fillable = [
        'pay_id', 'payroll_id', 'emp_id', 'tax1', 'tax2', 'jo_sss', 'jo_smlf_loan', 'projects', 'nsca_mpc', 'add_less_abs', 
        'less_late', 'grad_guarantor'
    ];
    // use HasFactory;
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'pay_id', 'payroll_id', 'emp_id', 'tax2', 'add_sal_diff', 'add_nbc_diff',
        'add_step_incre', 'eml', 'pol_gfal', 'consol', 'ed_asst_mpl', 'loan',
        'rlip', 'gfal', 'computer', 'mpl', 'prem', 'calam_loan', 'mp2',
        'philhealth', 'holding_tax', 'lbp', 'cauyan', 'projects', 'nsca_mpc',
        'med_deduction', 'grad_guarantor', 'cfi', 'csb', 'fasfeed', 'dis_unliquidated',
    ];
    // use HasFactory;
}


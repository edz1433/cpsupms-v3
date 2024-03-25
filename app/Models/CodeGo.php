<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeGo extends Model
{
    protected $table = 'code_gos';

    protected $fillable = [
        'payroll_id', 'wages_code', 'gsis_code', 'pagibig_code', 'ph_code', 'bir_code', 'otherrec_code', 'otherprof_code', 
        'otherpayable_code', 'bank_code'
    ];

    // use HasFactory;
}


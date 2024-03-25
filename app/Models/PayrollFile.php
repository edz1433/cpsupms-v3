<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollFile extends Model
{
    protected $fillable = [
        'payroll_ID', 'camp_ID', 'stat_ID', 'emp_id', 'emp_pos', 'sg',
        'salary_rate', 'hr_day', 'number_hours', 'number_days', 'total_salary',
        'sal_type', 'voucher', 'status', 'startDate', 'endDate',
    ];
    // use HasFactory;
}

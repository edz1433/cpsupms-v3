<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;
use App\Models\PayrollFile;
use App\Models\Status;
use App\Models\Campus;
use App\Models\Modify;
use App\Models\ModifyJo;
use App\Models\ModifyPartimeJo;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\DeductionJo;
use App\Models\DeductionPartimeJo;
use App\Models\Setting;

class ImportController extends Controller
{
    public function Setting(){
        $settingrlipreg = Setting::where('code', 101)->first();
        $settingphreg = Setting::where('code', 102)->first();
        $settingphreg100 = Setting::where('code', 103)->first();
        $settingptimetax1 = Setting::where('code', 201)->first();
        $settingptimetax2 = Setting::where('code', 202)->first();
        $settingptimeptimetax1 = Setting::where('code', 301)->first();
        $settingptimeptimetax2 = Setting::where('code', 302)->first();
        $settingjotax1 = Setting::where('code', 401)->first();
        $settingjotax2 = Setting::where('code', 402)->first();

        return [
            'settingrlipreg' => $settingrlipreg,
            'settingphreg' => $settingphreg,
            'settingphreg100' => $settingphreg100,
            'settingptimetax1' => $settingptimetax1,
            'settingptimetax2' => $settingptimetax2,
            'settingptimeptimetax1' => $settingptimeptimetax1,
            'settingptimeptimetax2' => $settingptimeptimetax2,
            'settingjotax1' => $settingjotax1,
            'settingjotax2' => $settingjotax2,
        ];
    }

    public function importPayrollsTwo(Request $request, $payrollID, $statID){
        $settings = $this->Setting();

        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;
        $payroll = Payroll::find($payrollID);
        $pay_id = $payroll->id;
        $campID = $payroll->campus_id;
        $startDate = $payroll->payroll_dateStart;
        $endDate = $payroll->payroll_dateEnd;
        $number_hours=$request->number_hours;
        $empid = $request->emp_ID;

        $setrlip = $setph = $setph80 = $setph100 = $settax1 = $settax2 = null;

        if ($statID == 1) {
            $modeldeduct = 'Deduction';
            $modelmodify = 'Modify';
            $dedtable = 'deductions';
            $setrlip = $settings['settingrlipreg']->percent;
            $setph = $settings['settingphreg']->percent;
            $setph100 = $settings['settingphreg100']->percent;
        }elseif ($statID == 2) {
            $modeldeduct = 'DeductionFullPartime';
            $modelmodify = 'ModifyFullPartime';
            $dedtable = 'deduction_full_partimes';
            $settax1 = $settings['settingptimetax1']->percent;
            $settax2 = $settings['settingptimetax2']->percent;
        }elseif ($statID == 3) {
            $modeldeduct = 'DeductionPartimeJo';
            $modelmodify = 'ModifyPartimeJo';
            $dedtable = 'deduction_jos';
            $settax1 = $settings['settingptimeptimetax1']->percent;
            $settax2 = $settings['settingptimeptimetax2']->percent;
        }elseif ($statID == 4) {
            $modeldeduct = 'DeductionJo';
            $modelmodify = 'ModifyJo';
            $dedtable = 'deduction_jos';
            $settax1 = $settings['settingjotax1']->percent;
            $settax2 = $settings['settingjotax2']->percent;
        }

        $modelInstance = 'App\Models\\' . $modeldeduct;
        $modelInstance1 = 'App\Models\\' . $modelmodify;

        $employees = Employee::find($empid);
        $empOff=$employees->emp_dept;

            if($statID == 1){
                $salary = $employees->emp_salary;
                $tax1 = "0.00";
                $rlip = round(($employees->emp_salary * $setrlip), 2);
                
                if($employees->emp_salary >= 100000){
                    $ph = $setph100;
                }         
                else{
                    $ph = round($employees->emp_salary * $setph, 2);
                }
            }

            if($statID == 2){
                $salary = round($employees->emp_salary / $payroll->number_days, 2);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                $days = $number_hours;
                $earn = $salary * $number_hours;
                $tax1 = round($earn * $settax1, 2);
            }

            if($statID == 3){
                $salary = ($employees->emp_status == 3) ? $employees->emp_salary : $employees->partime_rate;
                $earn = $salary * $number_hours;
                $tax1 = round($earn * $settax1, 2);
            }
            
            if($statID == 4){
                $salary = $employees->emp_salary;
                $earn = $salary / 2;
                $tax1 = round($earn * $settax1, 2);
            }

            $existing_record = PayrollFile::where('payroll_ID', $payrollID)
            ->where('emp_id', $request->emp_ID)
            ->where('camp_ID', $campID)
            ->where('stat_ID', $statID)
            ->where('startDate', $startDate)
            ->where('endDate', $endDate)
            ->first();

            if ($existing_record) {
                return redirect()->back()->with('error', 'Already Exist');  
            }
            else {
            $payrollFile = PayrollFile::create([
                'payroll_ID' => $payrollID,
                'emp_id' => $request->emp_ID,
                'emp_pos' => $employees->position,
                'sg' => $employees->sg_step,
                'salary_rate' => $salary,
                'number_hours' => $number_hours,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'camp_ID' => $campID,
                'stat_ID' => $statID,
            ]);
            
            $payrollID = $payrollFile->id;
            
			if($statID == 1){
                
                $deduction = $modelInstance::where('emp_id', $empid)->latest()->first();

                if($deduction){
                    $modelInstance::create([
                        'pay_id' => $pay_id,
                        'payroll_id' => $payrollID,
                        'emp_id' => $empid,
                        'tax2' => '0.00',
                        // 'add_sal_diff' => $deduction->add_sal_diff,
                        // 'add_nbc_diff' => $deduction->add_nbc_diff,
                        // 'add_step_incre' => $deduction->add_step_incre,
                        'add_sal_diff' => '0.00',
                        'add_nbc_diff' => '0.00',
                        'add_step_incre' => '0.00',
                        'eml' => '0.00',
                        'pol_gfal' => $deduction->pol_gfal,
                        'consol' => '0.00',
                        'ed_asst_mpl' => '0.00',
                        'loan' => '0.00',
                        'rlip' => $rlip,
                        'gfal' => '0.00',
                        'computer' => '0.00',
                        'mpl' => '0.00',
                        'prem' => $deduction->prem,
                        'calam_loan' => '0.00',
                        'mp2' => $deduction->mp2,
                        'philhealth' => $ph,
                        'holding_tax' => $deduction->holding_tax,
                        'lbp' => '0.00',
                        'cauyan' => '0.00',
                        'projects' => '0.00',
                        'nsca_mpc' => '0.00',
                        'med_deduction' => '0.00',
                        'grad_guarantor' => '0.00',
                        'cfi' => '0.00',
                        'csb' => '0.00',
                        'fasfeed' => $deduction->fasfeed,
                        'dis_unliquidated' => '0.00',
                    ]);
                }
                else{
                    $modelInstance::create([
                        'pay_id' => $pay_id,
                        'payroll_id' => $payrollID,
                        'emp_id' => $empid,
                        'rlip' => $rlip ?? '0.00',
                        'philhealth' => $ph ?? '0.00',
                        'fasfeed' => '100',
                    ]);
                }

                $data = [
                    ['column' => 'Column1', 'label' => 'Philhealth', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column2', 'label' => 'Net MPC', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column3', 'label' => 'Graduate', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column4', 'label' => 'Project', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column5', 'label' => 'Pag ibig', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column6', 'label' => 'GSIS', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column7', 'label' => 'CSB', 'action' => 'Deduction', 'amount' => '0.00'],
                ];

                foreach ($data as $item) {
                    $label = isset($item['label']) ? $item['label'] : null;
                    $modelInstance1::create([
                        'pay_id' => $pay_id,
                        'payroll_id' => $payrollID,
                        'off_id' => $empOff,
                        'column' => $item['column'],
                        'label'  => $label,
                        'action' => $item['action'],
                        'amount' => $item['amount'],
                    ]);
                }   
            }

            else{

                $modelInstance::create([
                    'pay_id'=> $pay_id,
                    'payroll_id'=> $payrollID,
                    'emp_id'=>$empid,
                    'tax1' => $tax1,
                ]);

                $data = [
                    ['column' => 'Column1', 'label' => 'Column1', 'action' => 'Additionals', 'amount' => '0.00'],
                    ['column' => 'Column2', 'label' => 'Column2', 'action' => 'Additionals', 'amount' => '0.00'],
                    ['column' => 'Column3', 'label' => 'Column3', 'action' => 'Additionals', 'amount' => '0.00'],
                    ['column' => 'Column4', 'label' => 'Column4', 'action' => 'Additionals', 'amount' => '0.00'],
                    ['column' => 'Column5', 'label' => 'Column5', 'action' => 'Additionals', 'amount' => '0.00'],
                ];

                foreach ($data as $item) {
                    $label = isset($item['label']) ? $item['label'] : null;
                    $modelInstance1::create([
                        'pay_id' => $pay_id,
                        'payroll_id' => $payrollID,
                        'off_id' => $empOff,
                        'column' => $item['column'],
                        'label'  => $label,
                        'action' => $item['action'],
                        'amount' => $item['amount'],
                    ]);
                } 
            }                  

            return redirect()->back()->with('success', 'Additionals successfully');  
        }
    }

}


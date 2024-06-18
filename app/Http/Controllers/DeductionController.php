<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollFile; 
use App\Models\Payroll; 
use App\Models\Deduction;
use App\Models\DeductionJo;
use App\Models\DeductionPartimeJo;
use App\Models\Deductiontwo;
use App\Models\Campus;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Modify;
use App\Models\ModifyJo;
use App\Models\ModifyPartimeJo;
use App\Models\Setting;

class DeductionController extends Controller 
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

    public function deductionsEdit(Request $request){
        $id = $request->id;
        $cat = $request->cat;
        $modes = $request->modes;
        $statID = $request->stat;

        if ($statID == 1) {
            $modeldeduct = 'Deduction';
        }elseif ($statID == 2) {
            $modeldeduct = 'DeductionFullPartime';
        }elseif ($statID == 3) {
            $modeldeduct = 'DeductionPartimeJo';
        }elseif ($statID == 4) {
            $modeldeduct = 'DeductionJo';
        }

        $modelInstance = 'App\Models\\' . $modeldeduct;
     
        $deductions = $modelInstance::where('payroll_id', $id)->select('*')->first();
        
        $payroll = PayrollFile::find($id);
        $statID = $payroll->stat_ID;
        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;
    
        if($emp_statname == "Job Order" || $emp_statname == "Part-time"){
            return response()->json([
                'status'=>200,
                'types'=>'add',
                'empstat'=>'Job Order',
                'data'=>$deductions,
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'types'=>'deduct', 
                'empstat'=>'Not JO',
                'data'=>$deductions,
            ]);
        }
    }
    
    public function deductionsUpdate(Request $request){
        $settings = $this->Setting();
        $cat = $request->cat;
        $id = $request->payroll_id;
        $payroll = PayrollFile::find($id);
        $pay = Payroll::find($payroll->payroll_ID);
        $type = $pay->jo_type;
        $statID = $payroll->stat_ID;
        $emp_id = $payroll->emp_id;
        $employee = Employee::where('emp_ID', $emp_id)->first();
        $emp_salary = $payroll->salary_rate;
        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;
        $tax2 = floatval(sprintf("%.2f", $request->tax2 * $payroll->total_salary));
        $tax2 = number_format($tax2, 2);

        $setrlip = $setph = $setph100 = $settax1 = $settax2 = null;

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
        
        $deduction = $modelInstance::where('payroll_id', $id)->first();
        
        $additional = $deduction->add_sal_diff + $deduction->add_nbc_diff + $deduction->add_step_incre;
            
        $earn = $emp_salary + $additional - $request->add_less_abs;
        
        $rlip_left = round(($earn * $setrlip),2);

        if($earn >= 100000){
            $philhealth = $setph100;
        }else{
            $philhealth = round(($earn * $setph),2);
        }

        if($tax2 <= 0){ 
            $tax2 = '0.00';
        }

        if($statID  == 1){
           
            $modelInstance::where('payroll_id', $id)->update([
                'tax2' => '0.00',
                'eml' => $request->eml,
                'pol_gfal' => $request->pol_gfal,
                'consol' => $request->consol,
                'ed_asst_mpl' => $request->ed_asst_mpl,
                'loan' => $request->loan,
                'rlip' => $rlip_left,
                'gfal' => $request->gfal,
                'computer' => $request->computer,
                'health' => $request->health,
                'mpl' => $request->mpl,
                'prem' => $request->prem,
                'calam_loan' => $request->calam_loan,
                'mp2' => $request->mp2,
                'house_loan' => $request->house_loan,
                'philhealth' => $philhealth,
                'holding_tax' => $request->holding_tax,
                'lbp' => $request->lbp,
                'cauyan' => $request->cauyan,
                'projects' => $request->projects,
                'nsca_mpc' => $request->nsca_mpc,
                'med_deduction' => $request->med_deduction,
                'grad_guarantor' => $request->grad_guarantor,
                'cfi' => $request->cfi,
                'csb' => $request->csb,
                'fasfeed' => $request->fasfeed,
                'dis_unliquidated' => $request->dis_unliquidated,
                'add_less_abs' => $request->add_less_abs,
                'add_less_abs1' => $request->add_less_abs1,
                'less_late' => $request->less_late,
            ]);
          
        }

        else{
            $emp_salary = ($statID == 3) ? ($emp_salary * $payroll->number_hours) : (($statID == 2) ? ($emp_salary * $payroll->number_hours) : (($statID == 4 && $type == 2) ? $payroll->salary_rate : ($payroll->salary_rate / 2)));           
            $modify = $modelInstance1::where('payroll_id', $id)->where('action', 'Additionals');
            $totaladd = 0;
            if(isset($modify)){
                $totaladd = $modify->sum('amount');
            }
            $tax2 = $request->tax_two;
            if($tax2 != 0.00 || $request->twocheckbox == 1){
                $tax2 = ($emp_salary) + ($totaladd) - ($request->add_less_abs + $request->less_late); 
                $tax2 = round($tax2 * $settax2, 2);
            }
            if($tax2 != 0.00 && $request->twocheckbox != 1){
                $tax2 = '0.00';
            }

            $earn = ($emp_salary) + ($totaladd) - ($request->add_less_abs + $request->less_late);            
            $tax1 = round($earn * $settax1, 2);

            $modifyuodate =  $modelInstance::where('payroll_id', $id)->first();

            if($modifyuodate){
                $updateData = [
                    'tax1' => $tax1,
                    'tax2' => $tax2,
                    'projects' => $request->projects,
                    'nsca_mpc' => $request->nsca_mpc,
                    'grad_guarantor' => $request->grad_guarantor,
                    'jo_sss' => $request->jo_sss,
                    'add_less_abs' => $request->add_less_abs,
                    'less_late' => $request->less_late,
                ];
            
                if ($statID == 4){
                    $updateData['jo_smlf_loan'] = $request->jo_smlf_loan;
                }
            
                $modifyuodate->update($updateData);
            }
        }
        
        return redirect()->back()->with('success', 'Deductions updated successfully');    
    }

    public function additionalUpdate(Request $request){
        $settings = $this->Setting();
        $cat = $request->cat;
        $id = $request->payroll_id;
        $payroll = PayrollFile::find($id);
        $statID = $payroll->stat_ID;
        $emp_id = $payroll->emp_id;
        $employee = Employee::where('emp_ID', $emp_id)->first();
        $emp_salary = $payroll->salary_rate;
        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;

        $setrlip = $setph = $setph100 = $settax1 = $settax2 = null;

        if ($statID == 1) {
            $modeldeduct = 'Deduction';
            $setrlip = $settings['settingrlipreg']->percent;
            $setph = $settings['settingphreg']->percent;
            $setph100 = $settings['settingphreg100']->percent;
        }elseif ($statID == 3){
            $modeldeduct = 'DeductionPartimeJo';
            $settax1 = $settings['settingptimeptimetax1']->percent;
            $settax2 = $settings['settingptimeptimetax2']->percent;
        }elseif ($statID == 4){
            $modeldeduct = 'DeductionJo';
            $settax1 = $settings['settingjotax1']->percent;
            $settax2 = $settings['settingjotax2']->percent;
        }

        $modelInstance = 'App\Models\\' . $modeldeduct;
        
        $deduction = $modelInstance::where('payroll_id', $id)->first();

        $additional = $request->add_sal_diff + $request->add_nbc_diff + $request->add_step_incre;

        $earn = ($emp_salary + $additional) - $deduction->add_less_abs;
        $rlip_left = round(($earn * $setrlip),2);

        if($earn >= 100000){
            $philhealth = $setph100;
        }else{
            $philhealth = round(($earn * $setph),2);
        }
        
        $modelInstance::where('payroll_id', $id)->update([
            'add_sal_diff' => $request->add_sal_diff,
            'add_nbc_diff' => $request->add_nbc_diff,
            'add_step_incre' => $request->add_step_incre,
            'rlip' => $rlip_left,
            'philhealth' => $philhealth,
        ]);
        
        return redirect()->back()->with('success', 'Additionals updated successfully');  

    }
}


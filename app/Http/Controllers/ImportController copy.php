<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;
use App\Models\Status;
use App\Models\Campus;
use App\Models\Modify;
use App\Models\Employee;
use App\Models\Deduction;
class ImportController extends Controller
{
    public function importPayrolls(Request $request, $payrollID, $statID)
    {
        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;
        $payroll = Payroll::find($payrollID);
        $campID = $payroll->campus_id;
        $campus = Campus::find($campID);
        $startDate = $payroll->payroll_dateStart;
        $endDate = $payroll->payroll_dateEnd;

        // Get the uploaded file
        $file = $request->file('payroll');
        
        // Open the file
        $handle = fopen($file, 'r');
    
        // Loop through the file and insert or update each row into the database
        $missing_employees = [];
        $mismatched_employees = [];
        $mismatched_camp = [];
        $updated_count = 0;
        $inserted_count = 0;
        $all_employees_have_status = true;
    
        while (($row = fgetcsv($handle)) !== false) {
            $emp_id = $row[0];
            $hours = $row[1];
            $employee = DB::table('employees')->where('emp_id', $emp_id)->first();
            $employees = DB::table('employees')->where('emp_id', $emp_id)->where('camp_id', $campID)->first();
    
            if ($employees) {
                // Check if employee status matches
                if ($employee->camp_id != $campID){
                    $missing_employees[] = $emp_id;
                }
                else if ($employees->camp_id != $campID) {
                    $mismatched_camp[] = $emp_id;
                }
                else if ($employees->emp_status != $statID) {
                    $mismatched_employees[] = $emp_id;
                } 
                else {
                    $salary = $employees->emp_salary;
                    $days = $hours/8;
                    $total_sal = floatval(sprintf("%.2f",$salary * $days));
                    if($emp_statname == "Job Order" || $emp_statname == "Part-time"){
                        $tax1 = floatval(sprintf("%.2f", 0.01 * $total_sal));
                    }
                    if($emp_statname == "Regular"){
                        $tax1 = "0.00";
                    }
    
                    $existing_record = DB::table('payroll_files')
                        ->where('emp_id', $emp_id)
                        ->where('camp_ID', $campID)
                        ->where('stat_ID', $statID)
                        ->where('startDate', $startDate)
                        ->where('endDate', $endDate)
                        ->first();

                    if ($existing_record) {
                        // Update existing record
                        DB::table('payroll_files')
                            ->where('emp_id', $emp_id)
                            ->where('camp_ID', $campID)
                            ->where('stat_ID', $statID)
                            ->where('startDate', $startDate)
                            ->where('endDate', $endDate)
                            ->update([
                                'salary_rate' => $salary,
                                'number_hours' => $hours,
                                'number_days' => $days,
                                'total_salary' => sprintf("%.2f", $total_sal),
                                'tax1' => sprintf("%.2f", $tax1),
                                'stat_ID' => $statID
                        ]);

                        $updated_count++;
                    } 
                    else {
                        // Insert new record
                        $payrollID = DB::table('payroll_files')->insertGetId([
                            'payroll_ID' => $payrollID,
                            'emp_id' => $emp_id,
                            'salary_rate' => $salary,
                            'number_hours' => $hours,
                            'number_days' => $days,
                            'total_salary' => sprintf("%.2f", $total_sal),
                            'tax1' => $tax1,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'camp_ID' => $campID,
                            'stat_ID' => $statID
                        ]);

                        DB::table('deductions')->insert([
                            'payroll_id'=> $payrollID
                        ]);

                        $inserted_count++;
                    }
                }
            } else {
                // Employee ID does not exist
                $mismatched_camp[] = $emp_id;
                $missing_employees[] = $emp_id;
                $mismatched_employees[] = $emp_id;
            }
        }

        $message = '';
        if ($inserted_count > 0) {
            $message .= 'Payrolls imported successfully. ';
        }
        if ($updated_count > 0) {
            $message .= 'Payrolls updated successfully. ';
        }

        if (!empty($missing_employees) || !empty($mismatched_camp) || !empty($mismatched_employees)) {
            $error_message = '';
            if (!empty($missing_employees)) {
                $error_message .= 'The following employees IDs do not exist: ' . implode(', ', $missing_employees) . '\n\n';
            }
            if (!empty($mismatched_camp)) {
                $error_message .= 'The following employees belong to a different campus: '. implode(', ', $mismatched_camp) . '\n\n';
            }
            if (!empty($mismatched_employees)) {
                $error_message .= 'The following employees IDs are not '.$emp_statname.': '. implode(', ', $mismatched_employees) . '\n\n';
            }
            if(!empty($message)){
                return back()->with('import-success', $message)->with('import-error', $error_message)->with('status', "Uploading");   
            }   
            else{
                return back()->with('import-error', $error_message)->with('status', "Uploading");
            }
        }

        return back()->with('import-success', $message)->with('status', "Uploading");

        // Close the file
        fclose($handle); 

        // Redirect back to the form with a success message
        // return back()->with('import-success', 'Payrolls imported successfully.')->with('status', "Uploading");
    }

    public function importPayrollsTwo(Request $request, $payrollID, $statID){
        $stat = Status::find($statID);
        $emp_statname = $stat->status_name;
        $payroll = Payroll::find($payrollID);
        $pay_id = $payroll->id;
        $campID = $payroll->campus_id;
        $startDate = $payroll->payroll_dateStart;
        $endDate = $payroll->payroll_dateEnd;

        $empid = $request->emp_ID;

        
        // if($emp_statname == "Job Order" || $emp_statname == "Part-time"){
        //     $hr_day = $request->hr_day;
        //     if($request->hr_day == "Hours"){
        //         $number_hours=$request->number_hours;
        //     }
        //     if($request->hr_day == "Days"){
        //         $number_hours=$request->number_hours * 8;
        //     }
        //     $days = $number_hours/8;
        // }

        if($statID == 1 || $statID == 4){
            $hr_day = "Hours";
            $number_hours=$request->number_hours * 8;
            $days = $request->number_hours;
        }

        if($emp_statname == "Part-time/JO"){
            $hr_day = "Hours";
            $number_hours=$request->number_hours;
            $days = 0;
        }
        $employees = DB::table('employees')->where('id', $empid)->first();
        $empOff=$employees->emp_dept;
        if($employees->partime_rate == 0){
            $salary = $employees->emp_salary;
            $total_sal = floatval(sprintf("%.2f",$salary * $days));
        }
        if($employees->partime_rate != 0){
            $salary = $employees->partime_rate;
            $total_sal = floatval(sprintf("%.2f",$salary * $number_hours));
        }

        if($statID == 4 || $emp_statname == "Part-time" || $emp_statname == "Part-time/JO"){
            $half = round(($employees->emp_salary / 2), 2);
            $tax1 = floatval(sprintf("%.2f",$half * 0.03));
        }

        if($statID == 1){
            $tax1 = "0.00";
            $rlip = round(($employees->emp_salary * 0.09), 2);
            if($employees->emp_salary >= 80000){
                $ph = 1600.00;
            }
            else{
                $ph = $employees->emp_salary * 0.02;
            }
        }

            $existing_record = DB::table('payroll_files')->where('payroll_ID', $payrollID)->where('emp_id', $request->emp_ID)->where('camp_ID', $campID)
            ->where('stat_ID', $statID)->where('startDate', $startDate)->where('endDate', $endDate)
            ->first();

            if ($existing_record) {
                return redirect()->back()->with('error', 'Already Exist');  
            }
            else {
            // Insert new record
            $payrollID = DB::table('payroll_files')->insertGetId([
                'payroll_ID' => $payrollID,
                'emp_id' => $request->emp_ID,
                'emp_pos' => $employees->position,
                'sg' => $employees->sg_step,
                'salary_rate' => $salary,
                'number_hours' => $number_hours,
                'number_days' => $days,
                'hr_day' => $hr_day,
                'total_salary' => sprintf("%.2f", $total_sal),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'camp_ID' => $campID,
                'stat_ID' => $statID,
            ]);
            
			if($statID == 1){
                
                $deduction = Deduction::where('emp_id', $empid)->latest()->first();

                if($deduction){
                    DB::table('deductions')->insert([
                        'pay_id'=> $pay_id,
                        'payroll_id'=> $payrollID,
                        'emp_id'=>$empid,
                        'tax2' => '0.00',
                        'add_sal_diff' => $deduction->add_sal_diff,
                        'add_nbc_diff' => $deduction->add_nbc_diff,
                        'add_step_incre' => $deduction->add_step_incre,
                        'eml' => '0.00',
                        'pol_gfal' => $deduction->pol_gfal,
                        'consol' => '0.00',
                        'ed_asst_mpl' => '0.00',
                        'loan' => '0.00',
                        'rlip'=> $deduction->rlip,
                        'gfal' => '0.00',
                        'computer' => '0.00',
                        'mpl' => '0.00',
                        'prem' => $deduction->prem,
                        'calam_loan' => '0.00',
                        'mp2' => $deduction->mp2,
                        'philhealth' => $deduction->philhealth,
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
                    DB::table('deductions')->insert([
                        'pay_id'=> $pay_id,
                        'payroll_id'=> $payrollID,
                        'emp_id'=>$empid,
                        'rlip'=> $rlip ?? '0.00',
                        'philhealth' => $ph ?? '0.00',
                        'fasfeed' => '100',
                    ]);
                }

                $data = [
                    ['column' => 'Column1', 'label' => 'Project', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column2', 'label' => 'Net MPC', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column3', 'label' => 'Graduate', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column4', 'label' => 'Philhealth', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column5', 'label' => 'Pag ibig', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column6', 'label' => 'GSIS', 'action' => 'Deduction', 'amount' => '0.00'],
                    ['column' => 'Column7', 'label' => 'CSB', 'action' => 'Deduction', 'amount' => '0.00'],
                ];
            }

            if($statID == 4){

                DB::table('deductions')->insert([
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
            }
            
            foreach ($data as $item) {
                $label = isset($item['label']) ? $item['label'] : null;
                Modify::create([
                    'pay_id' => $pay_id,
                    'payroll_id' => $payrollID,
                    'off_id' => $empOff,
                    'column' => $item['column'],
                    'label'  => $label,
                    'action' => $item['action'],
                    'amount' => $item['amount'],
                ]);
            }                     

            return redirect()->back()->with('success', 'Additionals successfully');  
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deduction;
use App\Models\DeductionJo;
use App\Models\DeductionPartimeJo;
use App\Models\PayrollFile;
use App\Models\Modify;
use App\Models\ModifyJo;
use App\Models\ModifyPartimeJo;
use App\Models\Setting;

class ModifyController extends Controller
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

    public function modifyShow(Request $request)
    {
        $id = $request->id;
        $statID = $request->stat;

        if ($statID == 1) {
            $modeldmody = 'Modify';
        }elseif ($statID == 2) {
            $modeldmody = 'ModifyFullPartime';
        }elseif ($statID == 3) {
            $modeldmody = 'ModifyPartimeJo';
        }elseif ($statID == 4) {
            $modeldmody = 'ModifyJo';
        }
        $modelInstance = 'App\Models\\' . $modeldmody;
        $modifyRecords = $modelInstance::where('payroll_id', $id)->get(); 
    
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $modifyRecords,
        ]);
    }

    public function modifyUpdate(Request $request)
    {
        $route = $request->curr_route;
        $payrollID = $request->id;
        $payrollID1 = $request->idd;

        if($route == "storepayroll"){
            $columns = [
                'Column1' => 'Column1',
                'Column2' => 'Column2',
                'Column3' => 'Column3',
                'Column4' => 'Column4',
                'Column5' => 'Column5',
                'Column6' => 'Column6',
                'Column7' => 'Column7',
            ];
            
            foreach ($columns as $column => $fieldName) {
                $modify = Modify::firstOrNew(['payroll_id' => $payrollID, 'column' => $column]);
                $modify->action = $request->{$fieldName . '_action'};
                $modify->amount = $request->{$fieldName . '_amount'};
                $modify->save();
                
                Modify::where(['pay_id' => $payrollID1, 'column' => $column])
                ->update([
                    'label' => $request->{$fieldName . '_label'} ?? '',
                    'affected' => $request->{$fieldName . '_affected'} ?? '',
                ]);

            }            

        }
        if ($route != "storepayroll") {
            $columns = [
                'Column1' => 'Column1',
                'Column2' => 'Column2',
                'Column3' => 'Column3',
                'Column4' => 'Column4',
                'Column5' => 'Column5'
            ];

            $settings = $this->Setting();
            
            $setrlip = $setph = $setph100 = $settax1 = $settax2 = null;

            if ($route == "storepayroll-jo") {
                $modelded = "DeductionJo";
                $modeldmody = 'ModifyJo';
                $settax1 = $settings['settingjotax1']->percent;
                $settax2 = $settings['settingjotax2']->percent;
            }
            if ($route == "storepayroll-partime") {
                $modelded = "DeductionFullPartime";
                $modeldmody = 'ModifyFullPartime';
                $settax1 = $settings['settingptimetax1']->percent;
                $settax2 = $settings['settingptimetax2']->percent;
            }
            if ($route == "storepayroll-partime-jo") {
                $modelded = "DeductionPartimeJo";
                $modeldmody = 'ModifyPartimeJo';
                $settax1 = $settings['settingptimeptimetax1']->percent;
                $settax2 = $settings['settingptimeptimetax2']->percent;
            }

            $modeldedInstance = 'App\Models\\' . $modelded;
            $modelmodyInstance = 'App\Models\\' . $modeldmody;
        
            foreach ($columns as $column => $fieldName) {
                $modify = $modelmodyInstance::firstOrNew(['payroll_id' => $payrollID, 'column' => $column]);
                $modify->amount = $request->{$fieldName . '_amount'} ?? 0;
                $modify->save();

                $modelmodyInstance::where(['pay_id' => $payrollID1, 'column' => $column])
                ->update([
                    'action' => $request->{$fieldName . '_action'} ?? '',
                    'label' => $request->{$fieldName . '_label'} ?? '',
                ]);
            }            

            $deduction = $modeldedInstance::where('payroll_id', $payrollID)->first();
            $tax2 = $deduction->tax2;
  
                $pfile = PayrollFile::find($payrollID);
    
                $salary = ($pfile->stat_ID == 3) ? ($pfile->salary_rate * $pfile->number_hours) : ($pfile->salary_rate / 2);                
                $modify = $modelmodyInstance::where('payroll_id', $payrollID)->where('action', 'Additionals');

                $totaladd = $modify->sum('amount');
            
                $earn = ($salary) + ($totaladd) - ($deduction->add_less_abs + $deduction->less_late); 

                $tax1 = round($earn * $settax1, 2);
                $tax2 = round($earn * $settax2, 2);

                $modeldedInstance::where('payroll_id', $payrollID)->update([
                    'tax1' => $tax1,
                    'tax2' => $tax2,
                ]);
        }
        
        return redirect()->back()->with('success', 'Updated successfully');
        
    }
      
}


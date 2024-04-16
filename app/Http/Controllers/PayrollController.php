<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll; 
use App\Models\Voucher;
use App\Models\Campus; 
use App\Models\Office;
use App\Models\Status;
use App\Models\PayrollFile;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\DeductionJo;
use App\Models\CodeGo;
use App\Models\Modify; 
use App\Models\ModifyJo;
use PDF;

class PayrollController extends Controller
{
    private $modelDeduct;
    private $deductionTable;
    private $modelModify;
    private $modifyTable;
    private $deductionModelInstance;
    private $modifyModelInstance;
    private $action;

    public function tables($statID){
         if ($statID == 1) {
            $this->modelDeduct = 'Deduction';
            $this->deductionTable = 'deductions';
            $this->action = 'Refund';

            $this->modelModify = 'Modify';
            $this->modifyTable = 'modifies';
        } elseif ($statID == 2) {
            $this->modelDeduct = 'DeductionFullPartime';
            $this->deductionTable = 'deduction_full_partimes';
            $this->action = 'Additionals';

            $this->modelModify = 'ModifyFullPartime';
            $this->modifyTable = 'modify_full_partimes';
        } elseif ($statID == 3) {
            $this->modelDeduct = 'DeductionPartimeJo';
            $this->deductionTable = 'deduction_partime_jos';
            $this->action = 'Additionals';

            $this->modelModify = 'ModifyPartimeJo';
            $this->modifyTable = 'modify_partime_jos';
        } elseif ($statID == 4) {
            $this->modelDeduct = 'DeductionJo';
            $this->deductionTable = 'deduction_jos';
            $this->action = 'Additionals';

            $this->modelModify = 'ModifyJo';
            $this->modifyTable = 'modify_jos';
        }

        $this->deductionModelInstance = 'App\Models\\' . $this->modelDeduct;
        $this->modifyModelInstance = 'App\Models\\' . $this->modelModify;
    }
    public function viewPayroll(Request $request, $campId)
    {
        try {
            $role = auth()->user()->role;
            $userid = auth()->user()->id;
            $campId = Crypt::decrypt($campId);
            $sid = $request->input('sid');
            $statusall = Status::all();

            $cond = $sid == 'all' ? '!=' : '=';
    
            if ($role == "Administrator" || $role == "Payroll Administrator") {
                $status = Status::all();
                $camp = Campus::all();

            } elseif ($role == "Payroll Extension" && auth()->user()->campus_id != $campId) {
                throw new \Exception('You do not have permission to access this page');
            } else {
                $status = Status::where('status_name', '!=', 'Regular')->get();
                $camp = Campus::find(auth()->user()->campus_id)->get();
            }

            $pays = Payroll::join('campuses', 'payrolls.campus_id', '=', 'campuses.id')
            ->join('statuses', 'payrolls.stat_id', '=', 'statuses.id')
            ->join('users', 'payrolls.user_id', '=', 'users.id')
            ->select('payrolls.*', 'users.id as userid', 'users.lname', 'users.fname', 'campuses.campus_name', 'statuses.status_name')
            ->where('payrolls.campus_id', $campId)
            ->where('payrolls.stat_id', $cond, $sid)
            ->orderBy('payrolls.payroll_dateStart', 'desc') 
            ->get();        

            return view("payroll.viewPayroll", compact('camp', 'statusall', 'status', 'campId', 'pays', 'sid'));

        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'You do not have permission to access this page');  
        }
    } 

    public function createPayroll(Request $request){
        $campID = $request->input('campID');
        $userid = auth()->user()->id;
        //$id = $request->payrollID;
        $validator = Validator::make($request->all(), [
            'statName'=>'required',
            'PayrollDateStart'=>'required',
            'PayrollDateEnd'=>'required',
            'number_days'=>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // try {
            //Insert data into database 
            $payroll = Payroll::create([
                'stat_id' => $request->input('statName'),
                'campus_id' => $request->input('campID'),
                'number_days' => $request->input('number_days'),
                'fund' => $request->input('statName') == 4 ? $request->input('fund') : null,
                'jo_type' => $request->input('statName') == 4 ? $request->input('jo_type') : null,
                'payroll_dateStart' => $request->input('PayrollDateStart'),
                'payroll_dateEnd' => $request->input('PayrollDateEnd'),
                'user_id' => $userid,
            ]);

            $payrollID =$payroll->id;

            if ($request->input('statName') == 1) {

                CodeGo::create([
                    'payroll_id' => $payrollID,
                    'wages_code' => '50101010 01',
                    'gsis_code' => '20201020 00',
                    'pagibig_code' => '20201030 00',
                    'ph_code' => '20201040 00',
                    'bir_code' => '20201010 00',
                    'otherpayable_code' => '29999990 00',
                    'due_offemp' => '20101020 00',
                ]);

            }

            if ($request->input('statName') == 2 || $request->input('statName') == 3){
                
                CodeGo::create([
                    'payroll_id' => $payrollID,
                    'wages_code' => '50216010 01',
                    'bir_code' => '20201010 00',
                    'otherprof_code' => '50211990 00',
                    'otherpayable_code' => '29999990 00',
                    'bank_code' => '10102020 24',
                ]);
            }
            

            if ($request->input('statName') == 4) {
                $fund = $request->input('fund');
                if ($fund == "Income" || $fund == "Yearbook") {
                    $bankcode = "10102020 24";
                }else {
                    $bankcode = "10104040 00";
                }
                
                CodeGo::create([
                    'payroll_id' => $payrollID,
                    'wages_code' => '50216010 01',
                    'bir_code' => '20201010 00',
                    'otherpayable_code' => '29999990 00',
                    'bank_code' => $bankcode,
                ]);

            }
             
            return redirect()->back()->with('success', 'Payroll created successfully');  

        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'An error occurred: ' . 'You do not have permission to access this page'); 
        // }
    }

    public function deletePayroll($id){
        $payroll = Payroll::find($id);
        $PayrollFile = PayrollFile::where('payroll_ID', $id)->first();
    
        if ($PayrollFile) {
            $id1 = $PayrollFile->id;
    
            $statID = $PayrollFile->stat_ID;
    
            $this->tables($statID);
    
            $dedModelInstance = $this->deductionModelInstance;
            $modifyModelInstance = $this->modifyModelInstance;
    
            $dedModelInstance::where('pay_id', $id)->delete();
            $modifyModelInstance::where('pay_id', $id)->delete();
    
            PayrollFile::where('payroll_ID', $id)->delete();
        }
    
        CodeGo::where('payroll_id', $id)->delete();
        $payroll->delete();
    
        return response()->json([
            'status' => 200,
            'message' => 'Deleted Successfully',
        ]);
    }

    public function deletePayrollFiles($id){
        $PayrollFile = PayrollFile::find($id);
    
        $statID = $PayrollFile->stat_ID;
    
        if (!$PayrollFile) {
            return response()->json([
                'status' => 404,
                'message' => 'PayrollFile not found',
            ]);
        }
    
        $this->tables($statID);
    
        $dedModelInstance = $this->deductionModelInstance;
        $modifyModelInstance = $this->modifyModelInstance;
    
        $id1 = $PayrollFile->id;
        $dedModelInstance::where('payroll_id', $id1)->delete();
        $modifyModelInstance::where('payroll_id', $id1)->delete();
        $PayrollFile->delete();
    
        return response()->json([
            'status' => 200,
            'message' => 'Deleted Successfully',
        ]);
    }
    
    public function storepayroll($payrollID, $statID, $offID){
        $payroll = Payroll::find($payrollID);
        $office = Office::all()->where('office_name', '!=', 'UNKNOWN');
        $PayrollFile = PayrollFile::where('payroll_ID', $payroll->id)->first();    
        $codes = CodeGo::where('payroll_id', $payrollID)->first();
        $sta=request('s');
        
        $this->tables($statID);
    
        $dedModelInstance = $this->deductionModelInstance;
        $modifyModelInstance = $this->modifyModelInstance;
        $dedtable = $this->deductionTable;
        $modtable = $this->modifyTable;

        $finalCond = $offID == 'All' ? 'o.id != 0' : 'o.id = ' . $offID ;

        $deduction = $dedModelInstance::join('payroll_files as pf', $dedtable.'.payroll_id', '=', 'pf.id')
        ->join('employees as emp', 'pf.emp_id', '=', 'emp.id')
        ->join('offices as o', 'emp.emp_dept', '=', 'o.id')
        ->where('pf.payroll_ID', $payrollID)
        // ->where('pf.status', '!=', '3')
        // ->where('pf.status', 1)
        ->whereRaw($finalCond)
        ->get();
        
        if($PayrollFile){
            $modify1 = $modifyModelInstance::where('pay_id', $payroll->id)
            ->join('offices as of', $modtable.'.off_id', '=', 'of.id')
            ->get();

            $modifyRef = $modifyModelInstance::where('pay_id', $payrollID)
            ->join('offices AS o', $modtable.'.off_id', '=', 'o.id')
            ->where('action', 'Refund')
            ->whereRaw($finalCond)
            ->get();
        
            $modifyRef = $modifyRef->groupBy('column')
            ->map(function ($group) {
                return $group->sum('amount');
            });

            $modifyDed = $modifyModelInstance::where('pay_id', $payrollID)
            ->join('offices AS o', $modtable.'.off_id', '=', 'o.id')
            ->where('action', 'Deduction')
            ->whereRaw($finalCond)
            ->get();

            $modifyDed = $modifyDed->groupBy('column')
            ->map(function ($group) {
                return $group->sum('amount');
            });

        }else{
            $modify1 = null;
            $modifyRef = null;
            $modifyDed = null;
        }

        $campId = $payroll->campus_id;
        $startDate = $payroll->payroll_dateStart;
        $endDate = $payroll->payroll_dateEnd;
        $days = $payroll->number_days;
        
        if ($statID == 3) {
            $employee = Employee::where('camp_id', $campId)
                ->where(function ($query) {
                    $query->where('emp_status', 3)
                        ->orWhere(function ($query) {
                            $query->where('emp_status', 4)
                                ->where('partime_rate', '!=', 0);
                        });
                })
                ->get();
        }
        
        elseif($statID == 4){
            // dd($payroll->jo_type);
            if($payroll->jo_type != 0){
                $employee = Employee::all()->where('emp_status', $statID)->where('jo_type', $payroll->jo_type);
            }else{
                $employee = Employee::all()->where('emp_status', $statID);
            }
        }else{
            $employee = Employee::all()->where('emp_status', $statID);
        }
        
        try {
            $pfiles = PayrollFile::query()
            ->join($dedtable.' AS d', 'payroll_files.id', '=', 'd.payroll_id')
            ->join('employees AS e', 'payroll_files.emp_id', '=', 'e.id')
            ->join('offices AS o', 'e.emp_dept', '=', 'o.id')
            ->leftJoinSub(function ($query) {
                $query->from('modifies')
                    ->select('payroll_id', 
                        DB::raw('SUM(CASE WHEN action = "Refund" THEN amount ELSE 0 END) as sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" THEN amount ELSE 0 END) as sumDed')
                    )
                    ->groupBy('payroll_id');
            }, 'm', 'payroll_files.id', '=', 'm.payroll_id')
            ->select('payroll_files.id as pid', 'payroll_files.*', 'e.*', 'd.*', 'o.office_abbr', 'm.sumRef', 'm.sumDed')
            ->where('payroll_files.payroll_ID', '=', $payrollID)
            ->where('payroll_files.camp_ID', '=', $campId)
            ->where('payroll_files.stat_ID', '=', $statID)
            ->where('payroll_files.startDate', '=', $startDate)
            ->where('payroll_files.endDate', '=', $endDate)
            // ->where(function($query) {
            //     $query->where('o.id', '=', 64)
            //     ->orWhere('o.id', '=', 65);
            // })
            ->where(function($query) use ($statID, $sta) {
                if($statID != 1){
                    $query->where('payroll_files.status', $sta);
                }
            })
            ->whereRaw($finalCond)
            ->get();
            
            $empStat = Status::find($statID);
            $empStat = $empStat->status_name;
            $currentcamp = DB::select("SELECT * FROM campuses WHERE id ='$campId' ");

            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);

            $startFormatted = $start->format('F j');
            $endFormatted = $end->format('j, Y');

            if ($start->format('F Y') === $end->format('F Y')) {
                $daterange = $startFormatted . '-' . $endFormatted;
            } else {
                $daterange = $startFormatted . '-' . $endFormatted;
            }

            $middle = (clone $start)->setDate($start->format('Y'), $start->format('m'), 15);

            $month = $start->format('F');
            $firstHalf = $month.' '.$start->format('j') .'-'. $middle->format('j, Y');
            $secondHalf = $middle->modify('+1 day')->format('F j') .'-'. $end->format('j, Y');

            $firstHalfday = $start->format('j') .'-'. $middle->format('j, Y');
            $secondHalfday = $middle->modify('+1 day')->format('j') .'-'. $end->format('j, Y');

            

            $page = ($statID == 1) ? "storepayroll" : ($statID == 2 ? "storepayroll_full_partime" : ($statID == 3 ? "storepayroll_partime_jo" : ($statID == 4 ? ($payroll->jo_type == 1) ? "storepayroll_jo" : 'storepayroll_jo1' : '')));
    

            if(auth()->user()->role == "Administrator" || auth()->user()->role == "Payroll Administrator"){
                $status = Status::all();
                $camp = Campus::all();
            }
            else{
                if(auth()->user()->campus_id != $campId){
                    return redirect()->route('dashboard')->with('error1', 'You do not have permission to access this page');
                }    
                $status = Status::where('status_name', '!=', 'Regular')->get();
                $camp = Campus::find(auth()->user()->campus_id)->get();
            }

            if($PayrollFile){
                return view('payroll.'.$page, compact('camp', 'office', 'offID', 'status', 'currentcamp', 'empStat', 'pfiles', 'campId', 'statID', 'payrollID', 'employee', 'codes', 'days', 'firstHalf', 'secondHalf', 'daterange', 'deduction', 'modify1', 'modifyRef', 'modifyDed', 'firstHalfday', 'secondHalfday'));
            }
            else{
                return view('payroll.'.$page, compact('camp', 'office', 'offID', 'status', 'currentcamp', 'empStat', 'pfiles', 'campId', 'statID', 'payrollID', 'employee', 'codes', 'days', 'firstHalf', 'secondHalf', 'daterange', 'modify1', 'modifyRef', 'modifyDed'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . 'You do not have permission to access this page'); 
        }
    }

    public function updateCode(Request $request) {
        $id = $request->input('id');
        $code = CodeGo::find($id);
        $payroll = Payroll::find($code->payroll_id);
        $statID = $payroll->stat_id;

        if($statID == 1){
            CodeGo::where('id', $id)->update([
                'wages_code' => $request->input('wages_code'),
                'gsis_code' => $request->input('gsis_code'),
                'pagibig_code' => $request->input('pagibig_code'),
                'ph_code' => $request->input('ph_code'),
                'bir_code' => $request->input('bir_code'),
                'otherpayable_code' => $request->input('otherpayable_code'),
                'due_offemp' => $request->input('due_offemp'),
            ]);
        }

        if($statID == 2 || $statID == 3){
            CodeGo::where('id', $id)->update([
                'wages_code' => $request->input('wages_code'),
                'bir_code' => $request->input('bir_code'),
                'otherprof_code' => $request->input('otherprof_code'),
                'otherpayable_code' => $request->input('otherpayable_code'),
                'bank_code' => $request->input('bank_code'),
            ]);
        }

        if($statID == 4){
            CodeGo::where('id', $id)->update([
                    'wages_code' => $request->input('wages_code'),
                    'bir_code' => $request->input('bir_code'),
                    'otherrec_code' => $request->input('otherrec_code'),
                    'otherpayable_code' => $request->input('otherpayable_code'),
                    'bank_code' => $request->input('bank_code'),
            ]);
        }

        return redirect()->back()->with('success', 'Updated Successfully');

    }

    public function pdfRemittance($col, $payrollID){
        ini_set('memory_limit', '1024M');
        $deduct = "deductions";
        
        $payroll = Payroll::find($payrollID);
        
        $dateStart = date('F Y', strtotime($payroll->payroll_dateStart));

        $datas = Deduction::join('employees', 'deductions.emp_id', '=', 'employees.id')
        ->select('employees.*', 'deductions.' . $col)
        ->where('deductions.pay_id', $payrollID)
        ->orderBy('employees.lname')
        ->get();
    
        // $datas = $datas->all(); 

        $customPaper = array(0, 0, 792, 842);

        $viewTemplate = 'payroll.pdf_remittance';
        $pdf = \PDF::loadView($viewTemplate, compact('datas', 'col', 'dateStart'))->setPaper($customPaper);

        $pdf->setCallbacks([
            'before_render' => function ($domPdf) {
                $domPdf->getCanvas()->page_text(10, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
            },
        ]);

        $pdf->render();

        return $pdf->stream();
    }

    public function showPdf($payrollID, $statID, $pid, $offid)
    {
        $currRoute = Route::currentRouteName();

        $this->tables($statID);
    
        $dedModelInstance = $this->deductionModelInstance;
        $modifyModelInstance = $this->modifyModelInstance;
        $dedtable = $this->deductionTable;
        $modtable = $this->modifyTable;
        $action = $this->action;

        ini_set('memory_limit', '3072M');

        $office = Office::where('id', $offid)->first();

        $payroll = Payroll::find($payrollID);
        $campus = Campus::find($payroll->campus_id);
        $campusname = $campus->campus_name;

        $finalCond = $offid == 'All' ? 'of.id != 0' : 'of.id = ' . $offid ;

        $modify = $modifyModelInstance::where('pay_id', $payroll->id)
        ->join('offices as of', $modtable.'.off_id', '=', 'of.id')
        ->whereRaw($finalCond)
        ->get();

        $modify1 = $modifyModelInstance::where('pay_id', $payroll->id)
        ->join('offices as of', $modtable.'.off_id', '=', 'of.id')
        ->get();

        //table TH Refund
        $tablemodifyRef = $modifyModelInstance::where('pay_id', $payroll->id)
        ->where($modtable.'.action', $action)
        ->groupBy([$modtable.'.column', $modtable.'.action', $modtable.'.label'])
        ->havingRaw('SUM('.$modtable.'.amount) > 0')
        ->get(['column', 'label', 'action', DB::raw('SUM('.$modtable.'.amount) as totalAmount')]);
    
        //table TH Deduct
        $tablemodifyDed = $modifyModelInstance::where('pay_id', $payroll->id)
        ->where($modtable.'.action', 'Deduction')
        ->groupBy([$modtable.'.column', $modtable.'.action', $modtable.'.label'])
        ->havingRaw('SUM('.$modtable.'.amount) > 0')
        ->get(['column', 'label', 'action', DB::raw('SUM('.$modtable.'.amount) as totalAmount')]);
    

        $stat = request('stat');   

        $campID = $payroll->campus_id;
        $dateStart = $payroll->payroll_dateStart;
        $dateEnd = $payroll->payroll_dateEnd;
        $numdays = $payroll->number_days;

        $dateStartM = date('F Y', strtotime($payroll->payroll_dateStart));

        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);

        $startFormatted = $start->format('F j');
        $endFormatted = $end->format('F j, Y');
        $fulldate = "$startFormatted-" . $end->format('j, Y');

        $middle = (clone $start)->setDate($start->format('Y'), $start->format('m'), 15);

        $month = $start->format('F');
        $firstHalf = $month.' '.$start->format('j') .'-'. $middle->format('j, Y');
        $secondHalf = $middle->modify('+1 day')->format('F j') .'-'. $end->format('j, Y');

        $statuses = Status::find($statID);
        $office = Office::all();
        $code = CodeGo::where('payroll_id', $payrollID)->first();

        $cond = $offid == 'All' ? '!=' : '=';
        $office = $offid == 'All' ? '0' : $offid;
        
        if($pid == 1){
        $datas = DB::table('payroll_files')
            ->join($dedtable, 'payroll_files.id', '=', $dedtable . '.payroll_id')
            ->join('employees', 'payroll_files.emp_id', '=', 'employees.id')
            ->join('offices as of', function($join) {
                $join->on('employees.emp_dept', '=', 'of.id')
                     ->orderBy('of.orders'); 
            })
            ->leftJoinSub(function ($query) {
                $query->from('modifies')
                    ->select('payroll_id', 
                        DB::raw('SUM(CASE WHEN action = "Refund" THEN amount ELSE 0 END) as sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" THEN amount ELSE 0 END) as sumDed')
                    )
                    ->groupBy('payroll_id');
            }, 'm', 'payroll_files.id', '=', 'm.payroll_id')
            ->select('of.id as offid', 'payroll_files.id as pid', 'of.*', 'payroll_files.*', 'employees.*', $dedtable . '.*', 'm.sumRef', 'm.sumDed')
            ->where('payroll_files.payroll_ID', $payrollID)
            ->where('payroll_files.camp_ID', $campID)
            ->where('payroll_files.stat_ID', $statID)
            ->where('payroll_files.startDate', $dateStart)
            ->where('payroll_files.endDate', $dateEnd)
            ->where(function($query) use ($cond, $office, $statID){
                if($statID == 1){
                    // $query->orderBy('of.orders');
                }
                if($statID == 2){
                    $query->where('of.id', $cond, $office);
                }
            });

            if(Route::currentRouteName() === 'transmittal') {
                $datas->orderBy('employees.lname')
                      ->orderBy('employees.fname');
            }
            
            $datas = $datas->get();

        }elseif($pid == 2){
            $datas = DB::table('payroll_files')
            ->join($dedtable, 'payroll_files.id', '=', $dedtable . '.payroll_id')
            ->join('employees', 'payroll_files.emp_id', '=', 'employees.id')
            ->join('offices as of', function($join) {
                $join->on('employees.emp_dept', '=', 'of.id')
                     ->orderBy('of.orders'); 
            })
            ->leftJoinSub(function ($query) {
                $query->from('modifies')
                    ->select('payroll_id', 
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column1" THEN amount END) as rowcolamountref1'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column2" THEN amount END) as rowcolamountref2'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column3" THEN amount END) as rowcolamountref3'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column4" THEN amount END) as rowcolamountref4'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column5" THEN amount END) as rowcolamountref5'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column6" THEN amount END) as rowcolamountref6'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column7" THEN amount END) as rowcolamountref7'),

                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column1" THEN amount END) as rowcolamountded1'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column2" THEN amount END) as rowcolamountded2'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column3" THEN amount END) as rowcolamountded3'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column4" THEN amount END) as rowcolamountded4'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column5" THEN amount END) as rowcolamountded5'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column6" THEN amount END) as rowcolamountded6'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column7" THEN amount END) as rowcolamountded7'),
                        
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column1" THEN label ELSE 0 END) as column1labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column2" THEN label ELSE 0 END) as column2labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column3" THEN label ELSE 0 END) as column3labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column4" THEN label ELSE 0 END) as column4labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column5" THEN label ELSE 0 END) as column5labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column6" THEN label ELSE 0 END) as column6labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column7" THEN label ELSE 0 END) as column7labelRef'),

                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column1" THEN amount ELSE 0 END) as column1sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column2" THEN amount ELSE 0 END) as column2sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column3" THEN amount ELSE 0 END) as column3sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column4" THEN amount ELSE 0 END) as column4sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column5" THEN amount ELSE 0 END) as column5sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column6" THEN amount ELSE 0 END) as column6sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Refund" AND `column` = "column7" THEN amount ELSE 0 END) as column7sumRef'),
                        
                        // ... Repeat for other columns
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column1" THEN amount ELSE 0 END) as column1sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column2" THEN amount ELSE 0 END) as column2sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column3" THEN amount ELSE 0 END) as column3sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column4" THEN amount ELSE 0 END) as column4sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column5" THEN amount ELSE 0 END) as column5sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column6" THEN amount ELSE 0 END) as column6sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column7" THEN amount ELSE 0 END) as column7sumDed'),
                        // ... Repeat for other columns
                        DB::raw('SUM(CASE WHEN action = "Refund" AND amount > 0 THEN label ELSE 0 END) as labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND amount > 0 THEN label ELSE 0 END) as labelDed'),                                      

                        DB::raw('SUM(CASE WHEN action = "Refund" THEN amount ELSE 0 END) as sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" THEN amount ELSE 0 END) as sumDed')
                    )
                    ->groupBy('payroll_id');
            }, 'm', 'payroll_files.id', '=', 'm.payroll_id')
            ->select('of.id as offid', 'payroll_files.id as pid', 'of.*', 'payroll_files.*', 'employees.*', $dedtable . '.*', 'm.*')
            ->where('payroll_files.payroll_ID', $payrollID)
            ->where('payroll_files.camp_ID', $campID)
            ->where('payroll_files.stat_ID', $statID)
            ->where('payroll_files.startDate', $dateStart)
            ->where('payroll_files.endDate', $dateEnd)
            ->where('payroll_files.endDate', $dateEnd)
            ->where('payroll_files.voucher', null)
            ->whereNotIn('payroll_files.status', [3, 4])
            ->where(function($query) use ($cond, $office, $statID){
                if($statID == 1){
                    // $query->orderBy('of.orders');
                }
                if($statID == 2){
                    $query->where('of.id', $cond, $office);
                }
            });
            
            if(Route::currentRouteName() === 'transmittal') {
                $datas->orderBy('employees.lname')
                      ->orderBy('employees.fname');
            }
            
            $datas = $datas->get();
            
        }elseif($pid == 3){
            $datas = DB::table('payroll_files')
            ->join($dedtable, 'payroll_files.id', '=', $dedtable . '.payroll_id')
            ->join('employees', 'payroll_files.emp_id', '=', 'employees.id')
            ->join('offices as of', function($join) {
                $join->on('employees.emp_dept', '=', 'of.id')
                     ->orderBy('of.orders'); 
            })
            ->leftJoinSub(function ($query) {
                $query->from('modify_partime_jos')
                    ->select('payroll_id', 
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column1" THEN amount END) as rowcolamountref1'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column2" THEN amount END) as rowcolamountref2'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column3" THEN amount END) as rowcolamountref3'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column4" THEN amount END) as rowcolamountref4'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column5" THEN amount END) as rowcolamountref5'),

                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column1" THEN amount END) as rowcolamountded1'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column2" THEN amount END) as rowcolamountded2'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column3" THEN amount END) as rowcolamountded3'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column4" THEN amount END) as rowcolamountded4'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column5" THEN amount END) as rowcolamountded5'),

                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column1" THEN label ELSE 0 END) as column1labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column2" THEN label ELSE 0 END) as column2labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column3" THEN label ELSE 0 END) as column3labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column4" THEN label ELSE 0 END) as column4labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column5" THEN label ELSE 0 END) as column5labelRef'),

                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column1" THEN amount ELSE 0 END) as column1sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column2" THEN amount ELSE 0 END) as column2sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column3" THEN amount ELSE 0 END) as column3sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column4" THEN amount ELSE 0 END) as column4sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND `column` = "column5" THEN amount ELSE 0 END) as column5sumRef'),
                        // ... Repeat for other columns
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column1" THEN amount ELSE 0 END) as column1sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column2" THEN amount ELSE 0 END) as column2sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column3" THEN amount ELSE 0 END) as column3sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column4" THEN amount ELSE 0 END) as column4sumDed'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND `column` = "column5" THEN amount ELSE 0 END) as column5sumDed'),
                        // ... Repeat for other columns
                        DB::raw('SUM(CASE WHEN action = "Additionals" AND amount > 0 THEN label ELSE 0 END) as labelRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" AND amount > 0 THEN label ELSE 0 END) as labelDed'),                                      

                        DB::raw('SUM(CASE WHEN action = "Additionals" THEN amount ELSE 0 END) as sumRef'),
                        DB::raw('SUM(CASE WHEN action = "Deduction" THEN amount ELSE 0 END) as sumDed')
                    )
                    ->groupBy('payroll_id');
            }, 'm', 'payroll_files.id', '=', 'm.payroll_id')
            ->select('of.id as offid', 'payroll_files.id as pid', 'of.*', 'payroll_files.*', 'employees.*', $dedtable . '.*', 'm.*')
            ->where('payroll_files.payroll_ID', $payrollID)
            ->where('payroll_files.camp_ID', $campID)
            ->where('payroll_files.stat_ID', $statID)
            ->where('payroll_files.startDate', $dateStart)
            ->where('payroll_files.endDate', $dateEnd)
            ->where('payroll_files.endDate', $dateEnd)
            ->whereNotIn('payroll_files.status', [3, 4])
            ->where(function($query) use ($cond, $office, $statID){
                if($statID == 1){
                    // $query->orderBy('of.orders');
                }
                if($statID == 2){
                    $query->where('of.id', $cond, $office);
                }
            })
            ->get();
            
        }

            if ($datas->isEmpty()) {
                return back()->with('error', 'No data found.');
            }
        
            $datas = $datas->all(); 
            $customPaper = array(0, 0, 612, 1008); 
            if($statID == 1){
              
                $currRoute == "transmittal" ? $viewTemplate = 'payroll.pdf_transmittal' : $viewTemplate = $pid == 1 ? 'payroll.pdf_payrollform_reg' : 'payroll.pdf_payrollform_reg_2';
              
                $pdf = \PDF::loadView($viewTemplate, compact('datas', 'finalCond', 'fulldate', 'firstHalf', 'secondHalf', 'code', 'modify', 'modify1', 'pid', 'offid', 'office', 'dateStartM', 'tablemodifyRef', 'tablemodifyDed'))->setPaper($customPaper, 'landscape');
            
            }
            elseif($statID == 3){
              
                $viewTemplate = 'payroll.pdf_payrollform_partime_jo';
              
                $pdf = \PDF::loadView($viewTemplate, compact('datas', 'finalCond', 'fulldate', 'firstHalf', 'secondHalf', 'code', 'modify', 'modify1', 'pid', 'offid', 'office', 'campusname', 'numdays', 'dateStartM', 'tablemodifyRef', 'tablemodifyDed'))->setPaper($customPaper, 'landscape');
            
            }
            else{
                $viewTemplate = ($statID == 4) ? $viewTemplate = $payroll->jo_type == 1 ? 'payroll.pdf_payrollform_jo' : 'payroll.pdf_payrollform_jo1' : (($statID == 2) ? 'payroll.pdf_payrollform_partime' : '');

                if($statID == 2 &&  $offid == 'All'){
                    return redirect()->back()->with('error', 'Select Office/Department');
                }

                $pdf = \PDF::loadView($viewTemplate, compact('datas', 'firstHalf', 'secondHalf', 'code', 'modify1', 'pid', 'offid', 'campusname', 'numdays'))->setPaper($customPaper, 'landscape');
                
                $pdf->setOption('margin-top', 0);
                $pdf->setOption('margin-right', 0);
                $pdf->setOption('margin-bottom', 0);
                $pdf->setOption('margin-left', 0);
            }

            $pdf->setCallbacks([
                'before_render' => function ($domPdf) {
                    $domPdf->getCanvas()->page_text(10, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                },
            ]);

            $pdf->render();

            return $pdf->stream();
    }

    public function payslip($payrollID){
  
        $payslip = PayrollFile::join('payrolls', 'payroll_files.payroll_ID', '=', 'payrolls.id')
        ->join('employees', 'payroll_files.emp_id', '=', 'employees.id')
        ->select('payroll_files.*', 'employees.*', 'employees.id as empid')
        ->where('payroll_files.payroll_ID', $payrollID)
        ->get();
        
        $camp = Campus::all();
    
        if (auth()->user()->role == "Administrator" || auth()->user()->role == "Payroll Administrator") {
            return view("payroll.payslip", compact('payslip', 'camp', 'payrollID'));
        } else {
            return redirect()->route('dashboard')->with('error1', 'You do not have permission to access this page');
        }
    
    }

    public function payslipGen(Request $request)
    {
        $payslipsPerPage = 4;
        
        $payslip = PayrollFile::join('payrolls', 'payroll_files.payroll_ID', '=', 'payrolls.id')
        ->leftJoin('employees', 'payroll_files.emp_id', '=', 'employees.id')  
        ->select('payroll_files.*', 'payroll_files.id as pfile_id', 'employees.*')
        ->where('payroll_files.payroll_ID', $request->payroll_ID)
        ->whereIn('payroll_files.emp_id', $request->emp_ID)
        ->orderBy('employees.emp_dept')
        ->get();
    
        $deductions = [];
    
        foreach ($payslip as $p) {
            $deductions[$p->emp_id] = Deduction::where('pay_id', $request->payroll_ID)
            ->where('emp_id', $p->emp_id)
            ->get();
        }

        // dd($payslip);

        // dd($deductions);

        $middleIndex = floor($payslip->count() / 2);

        $payslip1 = $payslip->slice(0, $middleIndex);
        $payslip2 = $payslip->slice($middleIndex);

        $modify = Modify::all();
    
        $totalPayslips = $payslip->count();
        $totalPages = ceil($totalPayslips / $payslipsPerPage);
    
        $legalPaperLandscape = [0, 0, 612, 936];
        $pdf = PDF::loadView('payroll.payslip_generate', compact('payslip', 'deductions', 'payslip1', 'modify', 'totalPages'))->setPaper($legalPaperLandscape, 'portrait');
        
        $pdf->setOptions(['enable_php' => true]);
    
        $pdf->setCallbacks([
            'before_render' => function ($domPdf) {
                $domPdf->getCanvas()->page_text(10, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);
            },
        ]);
    
        return $pdf->stream();
    }

    public function voucherCreate(Request $request)
    {
        $validatedData = $request->validate([
            'pfile_id' => 'required',
            'amount' => 'required',
            'amount_txt' => 'required',
            'mode' => 'required',
            'certified' => 'required|array',
        ]);

        $voucher = new Voucher();
        
        $pfile = [
            'voucher' => 1,
        ];

        PayrollFile::where('id', $request->input('pfile_id'))->update($pfile);

        // Get the checked values from the request
        $checkedValues = $request->input('certified');

        if ($checkedValues === null) {
            $checkedValues = [];
        }

        $allValues = [1, 2, 3];

        $allCertified = [];

        foreach ($allValues as $value) {
            if (in_array($value, $checkedValues)) {
                $allCertified[] = $value;
            } else {
                $allCertified[] = 0;
            }
        }

        // Implode the final array into a comma-separated string
        $certified = implode(',', $allCertified);

        // Assign the imploded string to the certified property of the voucher
        $voucher->certified = $certified;


        $voucher->pfile_id = $request->input('pfile_id');
        $voucher->amount = str_replace(',', '', $request->input('amount'));
        $voucher->amount_txt = $request->input('amount_txt');
        $voucher->mode = $request->input('mode');
        $voucher->certified = $certified;

        $voucher->save();

        return redirect()->back()->with('success', 'Voucher created successfully!');
    }

    public function saltypepUp($id, $val){
        $pfile = [
            'sal_type' => $val
        ];

        PayrollFile::where('id', $id)->update($pfile);

        return redirect()->back()->with('success', 'Updated Successfully');
    }

    public function pdfVoucher($id){
        ini_set('memory_limit', '1024M');
        $voucher = Voucher::join('payroll_files as pf', 'vouchers.pfile_id', '=', 'pf.id')
        ->join('payrolls as pay', 'pf.payroll_ID', '=', 'pay.id')
        ->join('employees as emp', 'pf.emp_id', '=', 'emp.id')
        ->where('pfile_id', $id)
        ->first();

        $dateStart = $voucher->payroll_dateStart;
        $dateEnd = $voucher->payroll_dateEnd;

        $dateStartM = date('F Y', strtotime($voucher->payroll_dateStart));

        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);

        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);


        $middle = (clone $start)->setDate($start->format('Y'), $start->format('m'), 15);

        $month = $start->format('F');
        $firstHalf = $month.' '.$start->format('j') .'-'. $middle->format('j, Y');

        $customPaper = array(0, 0, 612, 1008);
    
        $viewTemplate = 'payroll.pdf_voucher';
        $pdf = \PDF::loadView($viewTemplate, compact('voucher', 'firstHalf'))->setPaper($customPaper);
    
        $pdf->setCallbacks([
            'before_render' => function ($domPdf) {
                $domPdf->getCanvas()->page_text(10, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
            },
        ]);
    
        $pdf->render();
    
        return $pdf->stream();
    }
    

    public function statUpdate(Request $request, $id, $val){
        $vocuher = isset($request->v) ? $request->v : null;
        $pfile = [
            'status' => $val,
            'sal_type' => ($val == 1) ? 1 : 3, 
            'voucher' => $vocuher,
        ];

        if($val !== 3){
            Voucher::where('pfile_id', $id)->delete();
        }

        PayrollFile::where('id', $id)->update($pfile);

        return redirect()->back()->with('success', 'Updated Successfully');
    }

}


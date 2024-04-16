<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Employee;
use App\Models\Status;
use App\Models\Campus;
use App\Models\Office;
use App\Models\Qualification;

class EmployeeController extends Controller
{
    public function emp_list()
    {
        $user = User::where('username', auth()->user()->username)->first();
        $offices = Office::where('office_name', '!=', 'UNKNOWN')->get();
        
        if (auth()->user()->role == "Payroll Extension") {
            $stat = Status::where('status_name', '!=', 'Regular')->get();
        }else{
            $stat = Status::all();
        }
        
        $employees = Employee::join('offices', 'employees.emp_dept', '=', 'offices.id')
            ->join('statuses', 'employees.emp_status', '=', 'statuses.id')
            ->join('campuses', 'employees.camp_id', '=', 'campuses.id')
            ->leftJoin('qualifications', 'employees.qualification', '=', 'qualifications.id')
            ->select('employees.*', 'offices.*', 'campuses.*', 'statuses.*', 'employees.id as empid');  
            
        if (auth()->user()->role == "Payroll Extension"){
            $employees->where('employees.camp_id', '=', auth()->user()->campus_id);
            $employees->where('employees.emp_status', '!=', 1);
        }
        
        $employees = $employees->get();
        $quali = Qualification::all();
        $camp = (auth()->user()->campus_id == 1) ? Campus::all() : Campus::where('id', auth()->user()->campus_id)->get();
    
        return view("emp.emplist", compact('employees', 'offices', 'stat', 'quali', 'camp'));
    }

    public function empCreate(Request $request){
        $validator = Validator::make($request->all(), [
            'LastName'=>'required',
            'FirstName'=>'required',
            'MiddleName'=>'nullable',
            'Position'=>'required',
            'sg_step'=>'nullable',
            'Campus'=>'required',
            'emp_ID'=>'required|unique:employees',
            'Status'=>'required',
            'Office'=>'required',
            'jo_type'=>'nullable',
            'SalaryRate'=>'required',
            'Qualification'=>'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        else{
            //Insert data into database
            $employee = Employee::insert([
                'lname'=>$request->input('LastName'),
                'fname'=>$request->input('FirstName'),
                'mname'=>$request->input('MiddleName'),
                'position'=>$request->input('Position'),
                'sg_step'=>$request->input('sg_step'),
                'camp_id'=>$request->input('Campus'),
                'emp_ID'=>$request->input('emp_ID'),
                'emp_status'=>$request->input('Status'),
                'emp_dept'=>$request->input('Office'),
                'jo_type'=>$request->input('jo_type'),
                'emp_salary'=>$request->input('SalaryRate'),
                'qualification'=>$request->input('Status') == '2' ? 'required' : '',
                'partime_rate'=>"0",
            ]);

            return redirect()->back()->with('success', 'Successfully Added');
        }
    }

    public function empEdit($id)
    {
        $emp = Employee::find($id);
        return response()->json([
            'status'=>200,
            'emp'=>$emp,
        ]);
    }

    public function empUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'LastName'=>'required',
            'FirstName'=>'required',
            'MiddleName'=>'nullable',
            'Position'=>'required',
            'sg_step'=>'nullable',
            'Campus'=>'required',
            'emp_ID'=>'required',
            'Status'=>'required',
            'Office'=>'required',
            'jo_type'=>'required',
            'SalaryRate'=>'required',
            'Qualification'=>'nullable',
       ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            $select = Employee::where('emp_ID', $request->emp_ID)->where('id', '!=', $request->id)->exists();
            if ($select) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Employee ID Already Exist!',
                ]);
            }
            else
            {
                $update = [
                    'lname' => $request->input('LastName'),
                    'fname' => $request->input('FirstName'),
                    'mname' => $request->input('MiddleName'),
                    'position' => $request->input('Position'),
                    'sg_step' => $request->input('sg_step'),
                    'camp_id' => $request->input('Campus'),
                    'emp_ID' => $request->input('emp_ID'),
                    'emp_status' => $request->input('Status'),
                    'emp_dept' => $request->input('Office'),
                    'jo_type' => $request->input('jo_type'),
                    'emp_salary' => $request->input('SalaryRate'),
                    'qualification' => ($request->input('Status') != 2) ? '' : $request->input('Qualification'),
                ];
                
                DB::table('employees')->where('id', $request->id)->update($update);

                return redirect()->back()->with('success', 'Updated Successfully');
            }
        }

        
    }

    public function empDelete($id){
        $emp = Employee::find($id);
        $emp->delete();

        return response()->json([
            'status'=>200,
            'message'=>"Deleted Successfully",
        ]);
    }

    public function empPartimeRate(Request $request){
        $validator = Validator::make($request->all(), [
            'PartimeRate'=>'',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'error'=>$validator->messages(),
            ]);
        }

        else{
            $update = [
                'partime_rate'=>round($request->input('PartimeRate'), 2)
            ];
            DB::table('employees')->where('id', $request->empid)->update($update);

            return response()->json([
                'status'=>200,
                'message'=>"Successfully Update",
            ]);
        }
    }
    
    public function empEditRate($id){
        $emp = Employee::find($id);
        return response()->json([
            'status'=>200,
            'emp'=>$emp,
        ]);
    }

}


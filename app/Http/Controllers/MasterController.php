<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Campus;
use App\Models\User;
use App\Models\Status;

class MasterController extends Controller
{
    //
     public function dashboard(){
        $userCount = User::all();
        $campCount = Campus::all();
        $chartEmployee = Employee::all();
        
        if(auth()->user()->campus_id == 1){
            $empCount = Employee::count();
        }
        else{
            $empCount = Employee::where('emp_ID', auth()->user()->campus_id)->count();
        }
        $offCount = Office::all();
        
        return view("home.dashboard", compact('campCount', 'empCount', 'offCount', 'userCount', 'chartEmployee'));
    }
    public function logout(){
        auth()->logout();
        return redirect()->route('getLogin',)->with('success','You have been Successfully Logged Out');
    }
}


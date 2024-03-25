<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; 
use App\Models\User;
use App\Models\Campus; 
use App\Models\Office;
use App\Models\Status;

class SettingsController extends Controller
{
    public function settingAccount(){
        $uid = auth()->user()->id;
        $user = User::find($uid);
        $offices = Office::all();
        
        if(auth()->user()->role !== "Administrator"){
            $campus = Campus::find($user->campus_id);
        }
        else{
            $campus = Campus::all();
        }

        $accounts = User::join('campuses', 'users.campus_id', '=', 'campuses.id')
        ->select('users.id as uid', 'users.*', 'campuses.*')
        ->where('users.id', $uid) 
        ->first();

        return view("setting-account.set-account", compact('user', 'campus', 'offices', 'accounts'));
    }

    public function settingPayroll(){
        $settings = Setting::join('statuses', 'settings.stat', '=', 'statuses.id')
        ->select('settings.id as setid', 'settings.*', 'statuses.*')
        ->get();
        return view('setting-payroll.set-payroll', compact('settings'));
    }

    public function updatePercent(Request $request){
        $value = ($request->code == 103) ? $request->val : ($request->val / 100);
        Setting::where('id', $request->id)->update([
            'percent' => $value,
        ]); 
    }
}

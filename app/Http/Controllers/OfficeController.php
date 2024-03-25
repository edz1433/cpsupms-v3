<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Office;

class OfficeController extends Controller
{
    //
    public function officeList() {
        $office = Office::all();
        return view("offdept.officelist", compact('office'));
    }

    public function officeCreate(Request $request){
        $validator = Validator::make($request->all(), [
            'OfficeName'=>'required',
            'OfficeAbbreviation'=>'required',
            'GroupBy'=>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        else{
            $select = Office::where('office_name', $request->OfficeName)->exists();
            if ($select) {
                return redirect()->back()->with('error', 'Office Already Exist!');
            }
            else
            {
                $query = Office::insert([
                    'office_name'=>$request->input('OfficeName'),
                    'office_abbr'=>$request->input('OfficeAbbreviation'),
                    'group_by'=>$request->input('GroupBy'),
                ]);
                
                return redirect()->back()->with('success', 'Office Added Successfully'); 
            }


            
        }
    }

    public function officeEdit($id)
    {
        $office = Office::all();
        $offEdit = Office::find($id);

        return view("offdept.officelist", compact('offEdit', 'office'));
    }

    public function officeUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'OfficeName'=>'required',
            'OfficeAbbreviation'=>'required',
            'GroupBy'=>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        else{
            $select = Office::where('office_name', $request->OfficeName)->where('id', '!=', $request->oid)->exists();
            if ($select) {
                return redirect()->back()->with('success', 'Office Already Exist!');
            }
            else
            {
                $update = [
                    'office_name'=>$request->input('OfficeName'),
                    'office_abbr'=>$request->input('OfficeAbbreviation'),
                    'group_by'=>$request->input('GroupBy')
                ];
                DB::table('offices')->where('id', $request->oid)->update($update);

                return redirect()->back()->with('success', 'Office Updated Successfully');
            }
        }
    }

    public function officeDelete($id){
        $office = Office::find($id);
        $office->delete();

        return response()->json([
            'status'=>200,
            'message'=>"Deleted Successfully",
        ]);
    }
}

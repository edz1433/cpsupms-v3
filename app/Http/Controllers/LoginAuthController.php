<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LoginAuthController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:5|max:12'
        ]);

        $validated = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->password);

        if ($validated) {
            $encryptedCampusId = Crypt::encrypt(auth()->user()->campus_id);
            return redirect()->route('dashboard')->with('success', 'Login Successfully')->with('encryptedCampusId', $encryptedCampusId);
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    function signin(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ],[
            'email.required' => '*Email Required',
            'password.required' => '*Password Required'
        ]);

        $creds = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($creds)) {
            return redirect()->route('index');
        } else {
            return back()->with('fail', '*Invalid Credentials');
        }
    }

    function logout(){
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}

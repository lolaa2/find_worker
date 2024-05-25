<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(){
        if(Auth::guard('admin')->check()){
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        
        if(Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])){
            return redirect()->route('dashboard.index');

        }else{
            return redirect()->back()->withErrors([
                'password' => 'Wrong Password'
            ])->withInput();
        }
    }
}

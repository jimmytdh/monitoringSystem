<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginCtrl extends Controller
{
    public function __construct()
    {
        //$this->middleware('isLogin');
    }

    public function index()
    {
        return view('login');
    }

    public function validateLogin(Request $req)
    {
        $user = User::where('username',$req->username)->first();
        if($user)
        {
            if(!Hash::check($req->password,$user->password))
            {
                return redirect('/login')->with('status','error');
            }
            Session::put('user',$user);
            Session::put('isLogin',true);
            return redirect('/');

        }else{
            return redirect('/login')->with('status','error');
        }
    }

    public function logoutUser()
    {
        Session::flush();
        return redirect('/login');
    }
}

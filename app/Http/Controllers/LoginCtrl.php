<?php

namespace App\Http\Controllers;

use App\Access;
use App\User;
use App\UserAccess;
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
            self::pageAccess($user->id);
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

    function pageAccess($id)
    {
        $access = Access::get();
        $data = array();
        foreach($access as $row)
        {
            $check = UserAccess::where('user_id',$id)
                ->where('page',$row->name)
                ->first();
            if($check)
                $data[$row->name] = '';
            else
                $data[$row->name] = 'hidden';
        }
        $pageAccess = (object)$data;
        Session::put('pageAccess',$pageAccess);
    }
}

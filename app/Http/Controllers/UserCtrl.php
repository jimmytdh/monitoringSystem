<?php

namespace App\Http\Controllers;

use App\Access;
use App\User;
use App\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserCtrl extends Controller
{
    function __construct()
    {

    }

    public function index($edit = false, $info = null)
    {
        $check = AccessCtrl::allowProcess('users');
        if(!$check)
            return redirect('/');

        $keyword = Session::get('userKeyword');
        $data = User::select('*');
        if($keyword){
            $data = $data->where('username','like',"%$keyword%");
        }
        $data = $data->orderBy('username','asc')
            ->paginate(20);

        $access = Access::orderBy('name','asc')->get();
        return view('users',[
            'menu' => 'settings',
            'sub' => 'users',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'access' => $access
        ]);
    }

    public function search(Request $req)
    {
        Session::put('userKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        $check = AccessCtrl::allowProcess('users');
        if(!$check)
            return redirect('/');

        $u = User::create([
            'name' => $req->name,
            'username' => $req->username,
            'level' => $req->level,
            'password' => bcrypt($req->password)
        ]);

        if(count($req->access)>0)
        {
            UserAccess::where('user_id',$u->id)->delete();
            foreach($req->access as $access)
            {
                $data = array(
                    'user_id' => $u->id,
                    'page' => $access
                );
                UserAccess::create($data);
            }
        }
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = User::find($id);
        if(!$info)
            return redirect('/users');

        return self::index(true,$info);
    }

    public function update(Request $req, $id)
    {
        $check = AccessCtrl::allowProcess('users');
        if(!$check)
            return redirect('/');

        if(count($req->access)>0)
        {
            UserAccess::where('user_id',$id)->delete();
            foreach($req->access as $access)
            {
                $data = array(
                    'user_id' => $id,
                    'page' => $access
                );
                UserAccess::create($data);
            }
        }

        $data = array(
            'name' => $req->name,
            'username' => $req->username,
            'level' => $req->level,
        );
        if($req->password)
        {
            $data['password'] = bcrypt($req->password);
        }
        User::find($id)
            ->update($data);
        return redirect()->back()->with('status','updated');
    }

    public function delete($id)
    {
        $check = AccessCtrl::allowProcess('users');
        if(!$check)
            return redirect('/');

        User::find($id)->delete();
        return redirect('/users')->with('status','deleted');
    }
}

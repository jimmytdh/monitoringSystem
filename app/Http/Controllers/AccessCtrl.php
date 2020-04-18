<?php

namespace App\Http\Controllers;

use App\Access;
use App\User;
use App\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccessCtrl extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }

    public function index($edit = false, $info = null)
    {
        $keyword = Session::get('accessKeyword');
        $data = Access::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->orderBy('name','asc')
            ->paginate(20);


        return view('access',[
            'menu' => 'settings',
            'sub' => 'access',
            'data' => $data,
            'edit' => $edit,
            'info' => $info
        ]);
    }

    public function search(Request $req)
    {
        Session::put('accessKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        Access::create([
            'name' => $req->name
        ]);
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = Access::find($id);
        if(!$info)
            return redirect('/settings/access');

        return self::index(true,$info);
    }

    public function update(Request $req, $id)
    {
        $data = array(
            'name' => $req->name
        );

        Access::find($id)
            ->update($data);
        return redirect()->back()->with('status','updated');
    }

    public function delete($id)
    {
        Access::find($id)->delete();
        return redirect('/settings/access')->with('status','deleted');
    }

    static function checkPageAccess($id,$page)
    {
        $check = UserAccess::where('user_id',$id)->where('page',$page)->first();
        if($check)
            return true;
        return false;
    }

    static function allowProcess($process)
    {
        $user = Session::get('user');
        $id = $user->id;
        $check = UserAccess::where('user_id',$id)->where('page',$process)->first();
        if($check)
            return true;
        return false;
    }
}

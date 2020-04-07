<?php

namespace App\Http\Controllers;

use App\CoMorbid;
use App\PatComorbid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ComorbidCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index($edit = false, $info = null)
    {
        $keyword = Session::get('morbidKeyword');

        $data = CoMorbid::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->orderBy('name','asc')
            ->paginate(15);

        return view('comorbid',[
            'menu' => 'lib',
            'sub' => 'comorbid',
            'data' => $data,
            'edit' => $edit,
            'info' => $info
        ]);
    }

    public function search(Request $req)
    {
        Session::put('morbidKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        CoMorbid::create(['name' => $req->name]);
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = CoMorbid::find($id);
        if(!$info)
            return redirect('/library/comorbid');

        return self::index(true,$info);
    }

    public function update(Request $req, $id)
    {
        CoMorbid::find($id)
            ->update([
                'name' => $req->name
            ]);
        return redirect()->back()->with('status','updated');
    }

    public function delete($id)
    {
        CoMorbid::find($id)->delete();
        return redirect('/library/comorbid')->with('status','deleted');
    }

    static function isPatMorbid($pat_id,$morbid_id,$date_consultation)
    {
        $check = PatComorbid::where('pat_id',$pat_id)
                    ->where('morbid_id',$morbid_id)
                    ->where('date_consultation',$date_consultation)
                    ->first();
        if($check)
            return true;
        return false;
    }
}

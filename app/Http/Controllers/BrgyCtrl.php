<?php

namespace App\Http\Controllers;

use App\Brgy;
use App\Muncity;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BrgyCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index($edit = false, $info = null, $muncity = null, $prov_code = null)
    {
        $keyword = Session::get('brgyKeyword');

        $data = Brgy::select('brgy.*');

        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->orderBy('name','asc')
            ->paginate(15);

        return view('brgy',[
            'menu' => 'lib',
            'sub' => 'brgy',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'province' => Province::orderBy('name','asc')->get(),
            'muncity' => $muncity,
            'prov_code' => $prov_code
        ]);
    }

    public function search(Request $req)
    {
        Session::put('brgyKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {

        $code = Brgy::where('mun_code',$req->muncity)
                    ->orderBy('code','desc')
                    ->first()
                    ->code;
        $code = str_pad((int)$code + 1,9,0,STR_PAD_LEFT);

        Brgy::create([
            'code' => $code,
            'mun_code' => $req->muncity,
            'name' => $req->name
        ]);
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = Brgy::find($id);
        if(!$info)
            return redirect('/library/brgy');

        $province = Muncity::where('code',$info->mun_code)->first()->prov_code;
        $muncity = Muncity::where('prov_code',$province)->get();

        return self::index(true,$info,$muncity,$province);
    }

    public function update(Request $req, $id)
    {
        Brgy::find($id)
            ->update([
                'mun_code' => $req->muncity,
                'name' => $req->name
            ]);
        return redirect()->back()->with('status','updated');
    }

    public function delete($id)
    {
        Brgy::find($id)->delete();
        return redirect('/library/brgy')->with('status','deleted');
    }

    static function getMuncityName($code)
    {
        return Muncity::where('code',$code)->first();
    }

    static function getProvinceName($code)
    {
        return Province::where('code',$code)->first();
    }

    public function fix()
    {
        $brgy = Brgy::get();
        foreach($brgy as $b)
        {
            $p = Muncity::where('code',$b->mun_code)->first()->prov_code;
            if(!$b->prov_code){
                Brgy::find($b->id)
                    ->update([
                        'prov_code' => $p
                    ]);
                echo "$b->name Done!<br>";
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Charges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChargeCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index($edit = false, $info = null)
    {
        $keyword = Session::get('chargeKeyword');

        $data = Charges::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->orderBy('section','asc')
            ->orderBy('name','asc')
            ->paginate(20);

        $section = Charges::orderBy('section','asc')
                        ->get()
                        ->unique('section');

        return view('charges',[
            'menu' => 'lib',
            'sub' => 'charges',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'section' => $section
        ]);
    }

    public function search(Request $req)
    {
        Session::put('chargeKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        Charges::create([
            'section' => $req->section,
            'name' => $req->name,
            'type' => $req->type,
            'amount' => $req->amount
        ]);
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = Charges::find($id);
        if(!$info)
            return redirect('/library/charges');

        return self::index(true,$info);
    }

    public function update(Request $req, $id)
    {
        Charges::find($id)
            ->update([
                'section' => $req->section,
                'name' => $req->name,
                'type' => $req->type,
                'amount' => $req->amount
            ]);
        return redirect()->back()->with('status','updated');
    }

    public function delete($id)
    {
        Charges::find($id)->delete();
        return redirect('/library/charges')->with('status','deleted');
    }
}

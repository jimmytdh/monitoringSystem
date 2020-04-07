<?php

namespace App\Http\Controllers;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServicesCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index($edit = false, $info = null)
    {
        $keyword = Session::get('serviceKeyword');

        $data = Services::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->orderBy('section','asc')
            ->orderBy('name','asc')
            ->paginate(20);

        $section = array(
            'Laboratory',
            'Pharmacy',
            'Radiology'
        );

        return view('services',[
            'menu' => 'lib',
            'sub' => 'services',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'section' => $section
        ]);
    }

    public function search(Request $req)
    {
        Session::put('serviceKeyword',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        Services::create([
            'section' => $req->section,
            'name' => $req->name,
            'type' => $req->type,
            'amount' => $req->amount
        ]);
        return redirect()->back()->with('status','saved');
    }

    public function edit($id)
    {
        $info = Services::find($id);
        if(!$info)
            return redirect('/library/services');

        return self::index(true,$info);
    }

    public function update(Request $req, $id)
    {
        Services::find($id)
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
        Services::find($id)->delete();
        return redirect('/library/services')->with('status','deleted');
    }
}

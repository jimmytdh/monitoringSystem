<?php

namespace App\Http\Controllers;

use App\Admit;
use App\Patient;
use App\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdmitCtrl extends Controller
{
    function __construct()
    {
        $this->middleware('login');
    }

    function index()
    {
        $data = Admit::select(
                        'patients.*',
                        'patadm.date_admitted'
                    )
                    ->leftJoin('patients','patients.id','=','patadm.pat_id');
        $keyword = Session::get('admitSearch');
        if($keyword){
            $data = $data->where(function($q) use ($keyword){
                $q->where('fname','like',"%$keyword%")
                    ->orwhere('mname','like',"%$keyword%")
                    ->orwhere('lname','like',"%$keyword%")
                    ->orwhere('pat_id','like',"%$keyword%");
            });
        }

        $data = $data->orderBy('lname','asc')
            ->paginate(30);

        return view('admitted',[
            'menu' => 'patients',
            'sub' => 'admit',
            'data' => $data
        ]);
    }

    function save(Request $req, $id)
    {
        $date = Carbon::now();
        $check = self::checkAdmittedPatient($id);
        if($check)
            return redirect()->back()->with('status','existed');

        $age = Patient::find($id)->dob;
        $age = LibraryCtrl::getAge($age);
        $data = array(
            'pat_id' => $id,
            'age' => $age,
            'status' => $req->status,
            'date_admitted' => Carbon::parse($req->dateTime),
            'date_discharge' => null,
            'disposition' => null,
        );
        $adm = Admit::create($data);
        Patient::find($id)->update(['status' => 'ADM']);
        HistoryCtrl::addHistory($id,$adm->id,'Admitted',Carbon::parse($req->dateTime));
        return redirect()->back()->with('status','admitted');
    }

    function checkAdmittedPatient($id)
    {
        $check = Admit::where('pat_id',$id)->first();
        if($check)
            return true;
        return false;
    }

    function services($id)
    {
        $patient = Patient::find($id);
        return view('patservices',[
            'name' => "$patient->lname, $patient->fname $patient->mname",
            'menu' => 'patients',
            'sub' => 'admit',
            'data' => array(),
            'id' => $id
        ]);
    }

    function showServices($pat_id,$service)
    {
        $data = Services::where('section',$service)
                    ->orderBy('name','asc')
                    ->get();
        return view('load.services',[
            'data' => $data,
            'id' => $pat_id
        ]);
    }

    function availServices($id)
    {
        dd($_POST);
    }
}

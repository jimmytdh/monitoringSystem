<?php

namespace App\Http\Controllers;

use App\Admit;
use App\Patient;
use App\PatServices;
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
        $check = AccessCtrl::allowProcess('in_patients');
        if(!$check)
            return redirect('/');

        $data = Admit::select(
                        'patients.*',
                        'patadm.date_admitted',
                        'patadm.id as adm_id'
                    )
                    ->leftJoin('patients','patients.id','=','patadm.pat_id');
        $keyword = Session::get('admitSearch');
        if($keyword){
            $data = $data->where(function($q) use ($keyword){
                $q->where('fname','like',"%$keyword%")
                    ->orwhere('mname','like',"%$keyword%")
                    ->orwhere('lname','like',"%$keyword%")
                    ->orwhere('patients.pat_id','like',"%$keyword%");
            });
        }

        $data = $data
            ->where('date_discharge',null)
            ->orderBy('date_admitted','desc')
            ->paginate(30);

        return view('admitted',[
            'menu' => 'patients',
            'sub' => 'admit',
            'data' => $data
        ]);
    }

    public function search(Request $req)
    {
        Session::put('admitSearch',$req->keyword);
        return self::index();
    }

    function save(Request $req, $id)
    {
        $check = AccessCtrl::allowProcess('patient_admit');
        if(!$check)
            return redirect('/patients')->with('status','denied');

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
        $check = AccessCtrl::allowProcess('patient_services');
        if(!$check)
            return redirect('/admitted')->with('status','denied');

        $patient = Patient::find($id);
        $adm = Admit::where('pat_id',$id)
                    ->orderBy('date_admitted','desc')
                    ->first();
        $start = Carbon::parse($adm->date_admitted)->format('Y-m-d');
        $end = Carbon::today()->format('Y-m-d');

        $data = PatServices::select('patservices.*','services.section','services.name')
                    ->leftJoin('services','services.id','=','patservices.service_id')
                    ->where('pat_id',$id)
                    ->whereBetween('date_given',[$start,$end])
                    ->get();

        return view('patservices',[
            'name' => "$patient->lname, $patient->fname $patient->mname",
            'menu' => 'patients',
            'sub' => 'admit',
            'data' => $data,
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

    function availServices(Request $req, $id)
    {
        $check = AccessCtrl::allowProcess('patient_services_add');
        if(!$check)
            return redirect()->back()->with('status','denied');

        foreach($req->services as $s)
        {
            $qty = $req->qty[$s];
            $amount = $req->amount[$s];
            $data = array(
                'pat_id' => $id,
                'service_id' => $s,
                'date_given' => Carbon::parse($req->date_given),
                'qty' => $qty,
                'amount' => $amount,
                'total' => $qty * $amount
            );
            PatServices::create($data);
        }

        return redirect()->back()->with('status','saved');
    }

    function removeService($id)
    {
        $check = AccessCtrl::allowProcess('patient_services_remove');
        if(!$check)
            return redirect()->back()->with('status','denied');

        PatServices::find($id)->delete();
        return redirect()->back()->with('status','deleted');
    }

    function discharge(Request $req,$id)
    {
        $check = AccessCtrl::allowProcess('patient_discharge');
        if(!$check)
            return redirect()->back()->with('status','denied');

        $adm = Admit::find($id);
        $adm_id = $adm->id;
        $tmp = $adm->update([
                'date_discharge' => Carbon::parse($req->dateTime),
                'disposition' => $req->status
            ]);
        Patient::find($adm->pat_id)->update(['status' => null]);
        HistoryCtrl::addHistory($adm->pat_id,$id,'Discharged',Carbon::parse($req->dateTime));
        return redirect()->back()->with('status','discharged');
    }
}

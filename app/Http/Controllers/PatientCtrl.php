<?php

namespace App\Http\Controllers;

use App\Brgy;
use App\CoMorbid;
use App\Consultation;
use App\Muncity;
use App\PatCharges;
use App\PatComorbid;
use App\PatHistory;
use App\Patient;
use App\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatientCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index()
    {
        $check = AccessCtrl::allowProcess('patient_list');
        if(!$check)
            return redirect('/');

        $data = Patient::select('*');
        $keyword = Session::get('patientSearch');
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

        return view('patients',[
            'menu' => 'patients',
            'sub' => 'patients',
            'data' => $data,
            'province' => Province::orderBy('name','asc')->get(),
            'comorbid' => CoMorbid::orderBy('name','asc')->get()
        ]);
    }

    public function search(Request $req)
    {
        Session::put('patientSearch',$req->keyword);
        return self::index();
    }

    public function save(Request $req)
    {
        $check = AccessCtrl::allowProcess('patient_save');
        if(!$check)
            return redirect('/');

        $info = array(
            'pat_id' => '',
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'dob' => self::convertDate($req->dob),
            'sex' => $req->sex,
            'purok' => $req->purok,
            'brgy' => $req->brgy,
            'muncity' => $req->muncity,
            'province' => $req->province,
            'parents' => $req->parents,
            'contact_num' => $req->contact_num,
            'no_household' => $req->no_household,
            'outcome' => $req->outcome,
            'died' => $req->died
        );
        if($req->died=='Y')
            $info['date_died'] = self::convertDate($req->date_died);

        $data = Patient::create($info);
        $pat_id = "CTDH" . str_pad($data->id,4,0, STR_PAD_LEFT);
        Patient::find($data->id)->update(['pat_id' => $pat_id]);

        if($req->comorbidities){
            $c = count($req->comorbidities);
            if($c>0)
            {
                for($i=0; $i<$c; $i++)
                {
                    PatComorbid::create([
                        'pat_id' => $data->id,
                        'morbid_id' => $req->comorbidities[$i],
                        'date_consultation' => $req->date_consultation
                    ]);
                }
            }
        }

        $consultation = array(
            'pat_id' => $data->id,
            'date_consultation' => self::convertDate($req->date_consultation),
            'comorbid' => $req->comorbid,
            'comorbid_details' => $req->comorbid_details,
            'home_isolation' => $req->home_isolation,
            'fever' => $req->fever,
            'cough' => $req->cough,
            'colds' => $req->colds,
            'sorethroat' => $req->sorethroat,
            'diarrhea' => $req->diarrhea,
            'dob' => $req->bd,
            'travel' => $req->travel,
            'travel_address' => $req->travel_address
        );

        if($req->fever=='Y')
            $consultation['date_fever'] = self::convertDate($req->date_fever);
        if($req->cough=='Y')
            $consultation['date_cough'] = self::convertDate($req->date_cough);
        if($req->colds=='Y')
            $consultation['date_colds'] = self::convertDate($req->date_colds);
        if($req->sorethroat=='Y')
            $consultation['date_sorethroat'] = self::convertDate($req->date_sorethroat);
        if($req->diarrhea=='Y')
            $consultation['date_diarrhea'] = self::convertDate($req->date_diarrhea);
        if($req->bd=='Y')
            $consultation['date_dob'] = self::convertDate($req->date_bd);
        if($req->travel=='Y')
            $consultation['date_travel'] = self::convertDate($req->date_travel);

        $con = Consultation::create($consultation);
        HistoryCtrl::addHistory($data->id,$con->id,'Consultation', self::convertDate($req->date_consultation));

        return redirect('/patients')->with('status','saved');
    }

    public function edit($id)
    {
        $info = Patient::find($id);
        $consultation = Consultation::where('pat_id',$id)->orderBy('date_consultation','desc')->first();
        $province = Province::orderBy('name','asc')->get();
        $muncity = Muncity::where('prov_code',$info->province)->orderBy('name','asc')->get();
        $brgy = Brgy::where('mun_code',$info->muncity)->orderBy('name','asc')->get();

        return view('load.patientInfo',[
            'info' => $info,
            'consultation' => $consultation,
            'province' => $province,
            'muncity' => $muncity,
            'brgy' => $brgy,
            'comorbid' => CoMorbid::orderBy('name','asc')->get()
        ]);
    }

    public function update(Request $req, $id)
    {
        $check = AccessCtrl::allowProcess('patient_update');
        if(!$check)
            return redirect('/');

        if(!$req->fname && !$req->lname)
        {
            return self::addConsultation($req, $id);
        }
        $info = array(
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'dob' => self::convertDate($req->dob),
            'sex' => $req->sex,
            'purok' => $req->purok,
            'brgy' => $req->brgy,
            'muncity' => $req->muncity,
            'province' => $req->province,
            'parents' => $req->parents,
            'contact_num' => $req->contact_num,
            'no_household' => $req->no_household,
            'died' => $req->died,
            'outcome' => $req->outcome
        );

        if($req->died=='Y')
            $info['date_died'] = self::convertDate($req->date_died);
        else
            $info['date_died'] = null;

        Patient::find($id)->update($info);

        if($req->comorbidities){
            $c = count($req->comorbidities);
            if($c>0)
            {
                PatComorbid::where('pat_id',$id)
                                ->where('date_consultation',self::convertDate($req->date_consultation))
                                ->delete();
                for($i=0; $i<$c; $i++)
                {
                    PatComorbid::create([
                        'pat_id' => $id,
                        'morbid_id' => $req->comorbidities[$i],
                        'date_consultation' => self::convertDate($req->date_consultation)
                    ]);
                }
            }
        }


        $consultation = array(
            'date_consultation' => self::convertDate($req->date_consultation),
            'comorbid' => $req->comorbid,
            'comorbid_details' => $req->comorbid_details,
            'home_isolation' => $req->home_isolation,
            'fever' => $req->fever,
            'date_fever' => null,
            'cough' => $req->cough,
            'date_cough' => null,
            'colds' => $req->colds,
            'date_colds' => null,
            'sorethroat' => $req->sorethroat,
            'date_sorethroat' => null,
            'diarrhea' => $req->diarrhea,
            'date_diarrhea' => null,
            'dob' => $req->bd,
            'date_dob' => null,
            'travel' => $req->travel,
            'travel_address' => $req->travel_address
        );

        if($req->fever=='Y')
            $consultation['date_fever'] = self::convertDate($req->date_fever);
        if($req->cough=='Y')
            $consultation['date_cough'] = self::convertDate($req->date_cough);
        if($req->colds=='Y')
            $consultation['date_colds'] = self::convertDate($req->date_colds);
        if($req->sorethroat=='Y')
            $consultation['date_sorethroat'] = self::convertDate($req->date_sorethroat);
        if($req->diarrhea=='Y')
            $consultation['date_diarrhea'] = self::convertDate($req->date_diarrhea);
        if($req->bd=='Y')
            $consultation['date_dob'] = self::convertDate($req->date_bd);
        if($req->travel=='Y')
            $consultation['date_travel'] = self::convertDate($req->date_travel);

        PatHistory::where('ref_id',$req->consultation_id)
                    ->where('transaction','Consultation')
                    ->update([
                        'date_transaction' => self::convertDate($req->date_consultation)
                    ]);
        Consultation::find($req->consultation_id)->update($consultation);

        return redirect('/patients')->with('status','updated');
    }

    function addConsultation($req,$id)
    {
        if($req->comorbidities){
            $c = count($req->comorbidities);
            if($c>0)
            {
                for($i=0; $i<$c; $i++)
                {
                    PatComorbid::create([
                        'pat_id' => $id,
                        'morbid_id' => $req->comorbidities[$i],
                        'date_consultation' => self::convertDate($req->date_consultation)
                    ]);
                }
            }
        }


        $consultation = array(
            'pat_id' => $id,
            'date_consultation' => self::convertDate($req->date_consultation),
            'comorbid' => $req->comorbid,
            'comorbid_details' => $req->comorbid_details,
            'home_isolation' => $req->home_isolation,
            'fever' => $req->fever,
            'date_fever' => null,
            'cough' => $req->cough,
            'date_cough' => null,
            'colds' => $req->colds,
            'date_colds' => null,
            'sorethroat' => $req->sorethroat,
            'date_sorethroat' => null,
            'diarrhea' => $req->diarrhea,
            'date_diarrhea' => null,
            'travel' => $req->travel,
            'travel_address' => $req->travel_address
        );

        if($req->fever=='Y')
            $consultation['date_fever'] = self::convertDate($req->date_fever);
        if($req->cough=='Y')
            $consultation['date_cough'] = self::convertDate($req->date_cough);
        if($req->colds=='Y')
            $consultation['date_colds'] = self::convertDate($req->date_colds);
        if($req->sorethroat=='Y')
            $consultation['date_sorethroat'] = self::convertDate($req->date_sorethroat);
        if($req->diarrhea=='Y')
            $consultation['date_diarrhea'] = self::convertDate($req->date_diarrhea);

        $con = Consultation::create($consultation);
        HistoryCtrl::addHistory($id, $con->id,'Consultation',self::convertDate($req->date_consultation));
        return redirect('/patients')->with('status','updated');
    }

    public function delete($id)
    {
        $check = AccessCtrl::allowProcess('patient_delete');
        if(!$check)
            return redirect()->back()->with('status','denied');

        Patient::find($id)->delete();
        PatCharges::where('pat_id',$id)->delete();
        PatComorbid::where('pat_id',$id)->delete();
        Consultation::where('pat_id',$id)->delete();

        return redirect('/patients')->with('status','deleted');
    }

    public function history($id)
    {
        $check = AccessCtrl::allowProcess('patient_history');
        if(!$check)
            return redirect('/');

        $data = PatHistory::where('pat_id',$id)->orderBy('date_transaction','desc')->get();
        return view('load.history',[
            'data' => $data
        ]);
    }

    function convertDate($date)
    {
        return $date = Carbon::parse($date)->format('Y-m-d');
    }
}

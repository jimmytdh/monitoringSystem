<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\PatComorbid;
use App\Patient;
use App\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ReportCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index()
    {
        $data = Patient::select(
                    'consultation.*',
                    'consultation.dob as bd',
                    'patients.*',
                    'patients.pat_id as pat_code'
                )
            ->leftJoin('consultation','consultation.pat_id','=','patients.id');

        $search = Session::get('reportSearch');
        if($search)
        {
            if($search['fname']) { $data = $data->where('fname','like', '%'.$search['fname'].'%'); }
            if($search['lname']) { $data = $data->where('lname','like', '%'.$search['lname'].'%'); }
            if($search['sex']) { $data = $data->where('sex','like', '%'.$search['sex'].'%'); }
            if($search['age']) {
                $age = $search['age'];
                if($age=='80+'){
                    $dateF = Carbon::yesterday()->addYear(-81)->format('Y-m-d');
                    $data = $data->where('dob','<=',$dateF);
                }else{
                    $str = explode('-',$age);
                    $agef = $str[0];
                    $aget = $str[1]+1;

                    $dateF = Carbon::yesterday()->addYear(-$aget)->format('Y-m-d');
                    $dateT = Carbon::tomorrow()->addYear(-$agef)->format('Y-m-d');
                    $data = $data->whereBetween('dob',[$dateF,$dateT]);
                }
            }
            if($search['province']) { $data = $data->where('province', $search['province']); }
            if($search['muncity']) { $data = $data->where('muncity', $search['muncity']); }
            if($search['brgy']) { $data = $data->where('brgy', $search['brgy']); }
            if($search['start'] && $search['end']) { $data = $data->whereBetween('date_consultation',[$search['start'],$search['end']]); }
            if($search['comorbid']) { $data = $data->where('comorbid', $search['comorbid']); }
            if($search['home_isolation']) { $data = $data->where('home_isolation', $search['home_isolation']); }
            if($search['fever']) { $data = $data->where('fever', 'Y'); }
            if($search['cough']) { $data = $data->where('cough', 'Y'); }
            if($search['colds']) { $data = $data->where('colds', 'Y'); }
            if($search['sorethroat']) { $data = $data->where('sorethroat', 'Y'); }
            if($search['diarrhea']) { $data = $data->where('diarrhea', 'Y'); }
            if($search['dob']) { $data = $data->where('consultation.dob', 'Y'); }
            if($search['travel']) { $data = $data->where('travel', $search['travel']); }
        }

        $data = $data->orderBy('patients.id','asc')
                    ->paginate(20);

        return view('report',[
            'data' => $data,
            'menu' => 'report',
            'province' => Province::orderBy('name','asc')->get(),
            'search' => $search
        ]);
    }

    static function getPatMorbidity($id,$date_consultation)
    {
        $r = PatComorbid::leftJoin('comorbid','comorbid.id','=','patmorbid.morbid_id')
                ->where('pat_id',$id)
                ->where('date_consultation',$date_consultation)
                ->get();
        $output = array();
        foreach($r as $row){
            $output[] = $row->name;
        }
        return implode(', ', $output);
    }

    function search(Request $req)
    {

        $string = explode('-',$req->daterange);

        $date1 = date('Y-m-d',strtotime($string[0]));
        $date2 = date('Y-m-d',strtotime($string[1]));

        $start = Carbon::parse($date1)->startOfDay();
        $end = Carbon::parse($date2)->endOfDay();

        $data = array(
            "fname" => $req->fname,
            "lname" => $req->lname,
            "sex" => $req->sex,
            "age" => $req->age,
            "province" => $req->province,
            "muncity" => $req->muncity,
            "brgy" => $req->brgy,
            "comorbid" => $req->comorbid,
            "home_isolation" => $req->home_isolation,
            "travel" => $req->travel,
            "fever" => $req->fever,
            "cough" => $req->cough,
            "colds" => $req->colds,
            "sorethroat" => $req->sorethroat,
            "diarrhea" => $req->diarrhea,
            "dob" => $req->dob
        );
        $data['start'] = $start;
        $data['end'] = $end;

        Session::put('reportSearch',$data);

        return self::index();
    }

    function excelExport()
    {
//        $filename = 'Report_'.date('Y_m_d').'.xlsx';
//        return Excel::download(new ReportExport, $filename);

        $data = Patient::select(
            'consultation.*',
            'consultation.dob as bd',
            'patients.*',
            'patients.pat_id as pat_code'
        )
            ->leftJoin('consultation','consultation.pat_id','=','patients.id');

        $search = Session::get('reportSearch');
        if($search)
        {
            if($search['fname']) { $data = $data->where('fname','like', '%'.$search['fname'].'%'); }
            if($search['lname']) { $data = $data->where('lname','like', '%'.$search['lname'].'%'); }
            if($search['sex']) { $data = $data->where('sex','like', '%'.$search['sex'].'%'); }
            if($search['age']) {
                $age = $search['age'];
                if($age=='80+'){
                    $dateF = Carbon::yesterday()->addYear(-81)->format('Y-m-d');
                    $data = $data->where('dob','<=',$dateF);
                }else{
                    $str = explode('-',$age);
                    $agef = $str[0];
                    $aget = $str[1]+1;

                    $dateF = Carbon::yesterday()->addYear(-$aget)->format('Y-m-d');
                    $dateT = Carbon::tomorrow()->addYear(-$agef)->format('Y-m-d');
                    $data = $data->whereBetween('dob',[$dateF,$dateT]);
                }
            }
            if($search['province']) { $data = $data->where('province', $search['province']); }
            if($search['muncity']) { $data = $data->where('muncity', $search['muncity']); }
            if($search['brgy']) { $data = $data->where('brgy', $search['brgy']); }
            if($search['start'] && $search['end']) { $data = $data->whereBetween('date_consultation',[$search['start'],$search['end']]); }
            if($search['comorbid']) { $data = $data->where('comorbid', $search['comorbid']); }
            if($search['home_isolation']) { $data = $data->where('home_isolation', $search['home_isolation']); }
            if($search['fever']) { $data = $data->where('fever', 'Y'); }
            if($search['cough']) { $data = $data->where('cough', 'Y'); }
            if($search['colds']) { $data = $data->where('colds', 'Y'); }
            if($search['sorethroat']) { $data = $data->where('sorethroat', 'Y'); }
            if($search['diarrhea']) { $data = $data->where('diarrhea', 'Y'); }
            if($search['dob']) { $data = $data->where('consultation.dob', 'Y'); }
            if($search['travel']) { $data = $data->where('travel', $search['travel']); }
        }

        $data = $data->orderBy('patients.id','asc')
            ->get();
        return view('export.report',[
            'data' => $data
        ]);
    }

    function reset()
    {
        Session::forget('reportSearch');
        return redirect('/report');
    }
}

<?php

namespace App\Exports;

use App\Patient;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
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
            ->get();
        return view('export.report',[
            'data' => $data
        ]);
    }
}

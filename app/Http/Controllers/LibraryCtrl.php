<?php

namespace App\Http\Controllers;

use App\Admit;
use App\Brgy;
use App\Consultation;
use App\Muncity;
use App\PatComorbid;
use App\PatHistory;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LibraryCtrl extends Controller
{
    public function getMuncityList($code)
    {
        $list = Muncity::where('prov_code',$code)
                ->orderBy('name','asc')
                ->get();
        return $list;
    }

    public function getBrgyList($code)
    {
        $list = Brgy::where('mun_code',$code)
                    ->orderBy('name','asc')
                    ->get();
        return $list;
    }

    static function getAge($date){
        //date in mm/dd/yyyy format; or it can be in other formats as well
        $birthDate = date('m/d/Y',strtotime($date));
        //explode the date to get month, day and year
        $birthDate = explode("/", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        return $age;
    }

    public function loadingPage()
    {
        return view('load.loading');
    }

    public function resetAll()
    {
        Consultation::truncate();
        Admit::truncate();
        PatHistory::truncate();
        Patient::truncate();
        PatComorbid::truncate();

        return redirect('/patients');
    }

    function fixConsultation()
    {
        $con = Consultation::get();
        foreach($con as $c){
            $date = Carbon::parse($c->date_consultation)->format('Y-m-d');
            HistoryCtrl::addHistory($c->pat_id,$c->id,'Consultation', $date);
        }
        return 'Done';
    }
}

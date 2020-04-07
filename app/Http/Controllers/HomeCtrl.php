<?php

namespace App\Http\Controllers;

use App\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index(){
        return view('home',[
            'menu' => 'home'
        ]);
    }

    public function chart()
    {
        $data = array(
            'area' => self::areaChart(),
            'donut' => self::donutChart()
        );

        return $data;
    }

    public function areaChart()
    {
        $data = array();
        $today = Carbon::today()->addDay(-6);
        for($i=0; $i<7; $i++)
        {
            $data['day'][] = $today->format('M/d');
            $start = Carbon::parse($today)->startOfDay();
            $end = Carbon::parse($today)->endOfDay();
            $count = Consultation::whereBetween('date_consultation',[$start,$end])->count();
            $data['count'][] = $count;
            $today->addDay(1);
        }

        return $data;
    }

    public function donutChart()
    {
        $data[] = self::countBySymptoms('fever');
        $data[] = self::countBySymptoms('cough');
        $data[] = self::countBySymptoms('colds');
        $data[] = self::countBySymptoms('sorethroat');
        $data[] = self::countBySymptoms('diarrhea');

        return $data;
    }

    public function countBySymptoms($symptoms)
    {
        $start = Carbon::today()->startOfYear();
        $end = Carbon::today()->endOfYear();
        $count = Consultation::whereBetween('date_consultation',[$start,$end])
                    ->where($symptoms,'Y')
                    ->count();
        return $count;
    }
}

<?php

namespace App\Http\Controllers;

use App\Brgy;
use App\Muncity;
use App\Province;
use Illuminate\Http\Request;

class LocationCtrl extends Controller
{
    static function getBrgy($code)
    {
        return Brgy::where('code',$code)->first()->name;
    }

    static function getMuncity($code)
    {
        return Muncity::where('code',$code)->first()->name;
    }

    static function getProvince($code)
    {
        return Province::where('code',$code)->first()->name;
    }
}

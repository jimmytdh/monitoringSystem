<?php

namespace App\Http\Controllers;

use App\Charges;
use App\PatCharges;
use App\Patient;
use Illuminate\Http\Request;

class SoaCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index($id)
    {
        $check = AccessCtrl::allowProcess('soa_update');
        if(!$check)
            return redirect('/patients')->with('status','denied');

        $patient = Patient::find($id);

        return view('updateSoa',[
            'id' => $id,
            'patient' => $patient,
            'menu' => 'patients',
            'fixed' => self::getListBySection('fixed'),
            'room' => self::getListBySection('room'),
            'procedure' => self::getListBySection('procedure'),
            'equipment' => self::getListBySection('equipment'),
            'gas' => self::getListBySection('gas'),
            'outsource' => self::getListBySection('outsource'),
            'ancillary' => self::getListBySection('ancillary'),
        ]);
    }

    public function getListBySection($section)
    {
        $list = Charges::where('section',$section)->get();
        return $list;
    }

    static function checkItem($patient_id,$item_id)
    {
        $check = PatCharges::where('pat_id',$patient_id)
            ->where('item_id',$item_id)
            ->first();
        if($check)
            return $check->qty;

        return false;
    }

    public function update(Request $req, $id)
    {
        $check = AccessCtrl::allowProcess('soa_update');
        if(!$check)
            return redirect('/patients')->with('status','denied');

        PatCharges::where('pat_id',$id)->delete();

        $items = $req->items;
        foreach($items as $item => $value)
        {
            if($value > 0 || $value=='on'){
                $data = array(
                    'pat_id' => $id,
                    'item_id' => $item,
                    'qty' => $value
                );
                PatCharges::create($data);
            }
        }
        return redirect()->back()->with('status','updated');
    }

    public function printSoa($id)
    {
        $check = AccessCtrl::allowProcess('soa_print');
        if(!$check)
            return redirect('/patients')->with('status','denied');

        $fixed = self::getCharges($id,'fixed');
        $room = self::getCharges($id,'room');
        $procedure = self::getCharges($id,'procedure');
        $equipment = self::getCharges($id,'equipment');
        $gas = self::getCharges($id,'gas');
        $outsource = self::getCharges($id,'outsource');
        $others = self::getCharges($id,'others');
        $ancillary = self::getCharges($id,'ancillary');
        $patient = Patient::find($id);

        return view('print',[
            'menu' => 'patients',
            'id' => $id,
            'fixed' => $fixed,
            'room' => $room,
            'procedure' => $procedure,
            'equipment' => $equipment,
            'gas' => $gas,
            'outsource' => $outsource,
            'others' => $others,
            'ancillary' => $ancillary,
            'patient' => $patient,
            'total' => 0
        ]);
    }

    public function getCharges($id,$section)
    {
        $charages = PatCharges::join('charges','charges.id','=','patcharge.item_id')
            ->select('patcharge.qty as final_qty','charges.*')
            ->where('patcharge.pat_id',$id)
            ->where('charges.section',$section)
            ->get();

        return $charages;
    }
}

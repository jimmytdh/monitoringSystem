<?php

namespace App\Http\Controllers;

use App\PatHistory;
use Illuminate\Http\Request;

class HistoryCtrl extends Controller
{
    static function addHistory($id, $ref_id, $transaction, $date)
    {
        PatHistory::create([
            'pat_id' => $id,
            'ref_id' => $ref_id,
            'transaction' => $transaction,
            'date_transaction' => $date
        ]);
    }
}

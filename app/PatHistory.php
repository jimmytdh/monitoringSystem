<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatHistory extends Model
{
    protected $table = 'pathistory';
    protected $fillable = ['pat_id','ref_id','transaction','date_transaction'];
}

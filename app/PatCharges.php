<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatCharges extends Model
{
    protected $table = 'patcharge';
    protected $fillable = ['pat_id','item_id','qty'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatComorbid extends Model
{
    protected $table = 'patmorbid';
    protected $fillable = ['pat_id','morbid_id','date_consultation'];
}

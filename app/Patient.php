<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'pat_id',
        'fname',
        'mname',
        'lname',
        'dob',
        'sex',
        'purok',
        'brgy',
        'muncity',
        'province',
        'parents',
        'contact_num',
        'no_household',
        'status',
        'outcome',
        'died',
        'date_died'
    ];
}

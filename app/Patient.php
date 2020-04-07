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
        'brgy',
        'muncity',
        'province',
        'parents',
        'contact_num',
        'no_household',
        'status'
    ];
}

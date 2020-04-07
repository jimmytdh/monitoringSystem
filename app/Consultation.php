<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $table = 'consultation';
    protected $fillable = [
        'pat_id',
        'date_consultation',
        'comorbid',
        'comorbid_details',
        'home_isolation',
        'fever',
        'date_fever',
        'cough',
        'date_cough',
        'colds',
        'date_colds',
        'sorethroat',
        'date_sorethroat',
        'diarrhea',
        'date_diarrhea',
        'travel',
        'travel_address',
    ];
}

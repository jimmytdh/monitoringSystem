<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admit extends Model
{
    protected $table = 'patadm';
    protected $fillable = [
        'pat_id',
        'age',
        'status',
        'date_admitted',
        'date_discharge',
        'disposition'
    ];
}

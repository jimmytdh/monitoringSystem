<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatServices extends Model
{
    protected $table = 'patservices';
    protected $fillable = [
        'pat_id',
        'service_id',
        'date_given',
        'qty'
    ];
}

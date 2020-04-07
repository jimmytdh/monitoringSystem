<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brgy extends Model
{
    protected $table = 'brgy';
    protected $fillable = ['code','mun_code','name'];
}

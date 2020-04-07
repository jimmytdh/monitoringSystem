<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoMorbid extends Model
{
    protected $table = 'comorbid';
    protected $fillable = ['name'];
}

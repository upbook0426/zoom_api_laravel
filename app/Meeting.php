<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    //
    protected $table="meeting";
    protected $casts = [
        'is_canceled' => 'boolean',
    ];
}

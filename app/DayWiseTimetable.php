<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayWiseTimetable extends Model
{
    protected $fillable = [
      'period',
      'period_timing',
      'day',
    ];
}

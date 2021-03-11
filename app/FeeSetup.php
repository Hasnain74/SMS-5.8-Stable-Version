<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeeSetup extends Model
{
    protected $fillable = [
      'fee_amount',
      'month'
    ];
}

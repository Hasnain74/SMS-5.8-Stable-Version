<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeeReport extends Model
{
    protected $fillable = [
        'invoice_no',
        'class_id',
        'class_name',
        'student_id',
        'student_name',
        'paid_amount',
        'paid_date',
        'issue_date',
        'due_date',
        'month',
        'month_year',
        'year',
        'invoice_created_by'
    ];


    public function fee()
    {
        return $this->belongsTo(Fee::class, 'invoice_no');
    }
}

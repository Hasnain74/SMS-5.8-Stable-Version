<?php

namespace App\Exports;

use App\TeachersSalary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class TeachersSalaryExport implements FromCollection
{


    use Exportable;


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TeachersSalary::all();
    }
}

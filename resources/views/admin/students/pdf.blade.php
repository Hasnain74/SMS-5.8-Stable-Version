<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Profile Print</title>

    <style type="text/css" media="all">

        .email_table {
            color: #333;
            font-family: sans-serif;
            font-size: 15px;
            font-weight: 300;
            text-align: center;
            border-collapse: separate;
            border-spacing: 0;
            width: 99%;
            margin: 6px auto;
            box-shadow:none;
        }
        table {
            color: #333;
            font-family: sans-serif;
            font-size: 15px;
            font-weight: 300;
            text-align: center;
            border-collapse: separate;
            border-spacing: 0;
            width: 99%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,.16);
        }

        th {font-weight: bold; padding:10px; border-bottom:2px solid #000;}

        tbody td {border-bottom: 1px solid #ddd; padding:10px;}



        .email_main_div{width:700px; margin:auto; background-color:#EEEEEE; min-height:500px; border:2px groove #999999;}
        strong{font-weight:bold;}
        .item_table{text-align:left;}

    </style>

</head>

<body>

@foreach($students as $student)
    <body>
    <div class="email_main_div">
        <table class="email_table">
            <tr>
                <td width="400px;" style="text-align:left; padding:10px;">
                    <img alt="Img" src="{{$student->photo ? public_path().$student->photo->file :  public_path('img/avatar.png')}}" width="100px" height="100px">
                </td>
                <td style="text-align:left; padding:10px;">
                   <strong>ID :</strong>     {{$student->student_id}}<br/>
                   <strong>Name :</strong>   {{$student->first_name. ' '. $student->last_name}}<br/>
                   <strong>Class :</strong>  {{$student->studentsClass['class_name']}}<br/>
                   <strong>Admission Date :</strong>  {{$student->admission_date}}<br/>
                   <strong>Created Date :</strong>  {{$student->created_at->DiffForHumans()}}<br/>
                </td>
            </tr>
        </table>

        <table class="item_table">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td>Student ID</td>
                <td style="text-align: right;">{{$student->student_id}}</td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td style="text-align: right;">{{$student->first_name. ' '. $student->last_name}}</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td style="text-align: right;">{{$student->DOB}}</td>
            </tr>
            <tr>
                <td>Class</td>
                <td style="text-align: right;">{{$student->studentsClass['class_name']}}</td>
            </tr>
            <tr>
                <td>Gender</td>
                <td style="text-align: right;">{{$student->gender}}</td>
            </tr>
            <tr>
                <td>Blood Group</td>
                <td style="text-align: right;">{{$student->blood_group}}</td>
            </tr>
            <tr>
                <td>Religion</td>
                <td style="text-align: right;">{{$student->religion}}</td>
            </tr>
            <tr>
                <td>Phone No</td>
                <td style="text-align: right;">{{$student->student_phone_no}}</td>
            </tr>
            <tr>
                <td>Full Address</td>
                <td style="text-align: right;">{{$student->student_address}}</td>
            </tr>
            <tr>
                <td>Guardian Name</td>
                <td style="text-align: right;">{{$student->guardian_name}}</td>
            </tr>
            <tr>
                <td>Guardian Gender</td>
                <td style="text-align: right;">{{$student->guardian_gender}}</td>
            </tr>
            <tr>
                <td>Guardian Relation</td>
                <td style="text-align: right;">{{$student->guardian_relation}}</td>
            </tr>
            <tr>
                <td>Guardian Occupation</td>
                <td style="text-align: right;">{{$student->guardian_occupation}}</td>
            </tr>
            <tr>
                <td>Guardian Phone No</td>
                <td style="text-align: right;">{{$student->guardian_phone_no}}</td>
            </tr>
            <tr>
                <td>Guardian NIC Number</td>
                <td style="text-align: right;">{{$student->NIC_no}}</td>
            </tr>
            <tr>
                <td>Full Address</td>
                <td style="text-align: right;">{{$student->guardian_address}}</td>
            </tr>
            <tr>
                <td>Fee Setup</td>
                <td style="text-align: right;">{{ucfirst(trans($student->fee_setup))}}</td>
            </tr>
            <tr>
                <td>Student Fee Discount %</td>
                <td style="text-align: right;">{{$student->discount_percent}}%</td>
            </tr>
            <tr>
                <td>Final Fee</td>
                <td style="text-align: right;">{{$student->total_fee}} RS</td>
            </tr>
            <tr>
                <td>Transport Fee</td>
                <td style="text-align: right;">{{$student->transport_fee}} RS</td>
            </tr>
            </tbody>
        </table>
        <div style="width:98%; padding:1%; margin-top:10px; font-size:15px; text-align:center;">
            {{ Config::get('school_name.school_name') }}
        </div>
    </div>
    </body>
@endforeach

</body>
</html>
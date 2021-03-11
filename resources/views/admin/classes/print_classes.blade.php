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


    <body>
    <div class="email_main_div">
        <table class="email_table">
            <tr>
                <td style="text-align:left; padding:10px;">
                    <strong>Class Name :</strong>     {{$class->class_name}}<br/>
                    <strong>Class Teacher Name :</strong>   {{$class->class_teacher}}<br/>
                    <strong>Total Students :</strong>   {{$no_of_students}}<br/>
                </td>
            </tr>
        </table>

        <table class="item_table">
            <thead>
                <tr>
                    <th class="text-center">Student ID</th>
                    <th class="text-center">Student Name</th>
                    <th class="text-center">Father Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td style="text-center">{{$student['student_id']}}</td>
                    <td style="text-center">{{$student['first_name']. ' '. $student['last_name']}}</td>
                    <td style="text-center">{{$student['guardian_name']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="width:98%; padding:1%; margin-top:10px; font-size:15px; text-align:center;">
            {{ Config::get('school_name.school_name') }}
        </div>
    </div>
    </body>


</body>
</html>
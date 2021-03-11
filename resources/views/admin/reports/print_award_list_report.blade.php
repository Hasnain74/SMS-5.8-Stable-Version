<!doctype>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report</title>

    <style type="text/css" media="all">

        body {
            font-family: Junge;
            position: relative;
            width: 30cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 90%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        #image {
/* the image you want to 'watermark' */
width: 100%;
  height: auto;
background-image: url(path/to/image/to/be/watermarked.png);
background-position: 0 0;
background-repeat: no-repeat;
position: relative;
margin-left: 350px;
margin-bottom: 15px;
}


    </style>

</head>
<body>

    <div id="image">
        <img  src="{{ asset('img/school_logo.jpg') }}" alt="Photo" />
    </div>

{{-- <div style="text-align: center; font-family: Tahoma">
<h1 style="width: 90%;border: 1px solid orange;" class="clearfix"> {{ Config::get('school_name.school_name') }} </h1>
</div> --}}

<div style="text-align: center; font-family: Tahoma; font-size: 15px;">
<strong style="border: 1px solid orange;">Class Teacher : {{$class_teacher}}</strong> &nbsp; &nbsp;
<strong style="border: 1px solid orange;">Teacher Sign : </strong> ____________ &nbsp; &nbsp;
<strong style="border: 1px solid orange;">Report Type : {{$report->report_categories_name}}  </strong>&nbsp; &nbsp;
</div>

<table style="margin-top: 10px; margin-bottom: 10px;">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Father Name</th>
        @foreach($total_subjects_marks_arr as $subject)
            <th>{{$subject->subject}} ({{$subject->subject_mark}})</th>
        @endforeach
        <th>Obtained Marks</th>
        <th>Percent</th>
        <th>Position</th>
        <th>Grade</th>
        <th>Status</th>
    </tr>
    @foreach($rows as $student_info)
        <tr>
            <td>{{$student_info->student_id}}</td>
            <td>{{$student_info->student_name}}</td>
            <td>{{$student_info->father_name}}</td>
            @foreach($student_info->marks as $i => $mark)
                <td>{{$mark}}</td>
            @endforeach
            <td>{{$student_info->total_marks}} / {{$student_info->total_subject_marks}}</td>
            <td>{{$student_info->percent}} %</td>
            <td>{{array_search($student_info->total_marks, $positions)+1}}</td>
            <td>{{$student_info->grade}}</td>
            <td>
                @php
                    $status = intval($student_info->percent)<40?'Fail':'Pass';
                    if( $status == 'Fail' )
                    $failed++;
                    else
                    $passed++;
                @endphp
                {{$status}}
            </td>
        </tr>
    @endforeach
    <tr>
        <th colspan="1"></th>
        <th colspan="2">Over All Class Subject %</th>
        @foreach($vertical_percent as $percent)
            <th>{{$percent/count($students)}} %</th>
        @endforeach
        <th colspan="4"></th>
        <th colspan="4"></th>
    </tr>
</table>

<div  style="margin-top: 20px; font-size: 15px; font-family: Tahoma; text-align: center">
<strong>Total Students : </strong>{{count($total_students)}} &nbsp; &nbsp;
<strong>Fail Students : </strong>{{$failed}} &nbsp; &nbsp;
<strong>Pass Students : </strong>{{$passed}} &nbsp; &nbsp;
<strong>Overall Class Percent : </strong>{{$overall_class_percent}} % &nbsp; &nbsp;
</div>

<div  style="margin-top: 20px; font-size: 15px; font-family: Tahoma; text-align: center">
    <strong>Controller Of Examination Sign : </strong>______________ &nbsp; &nbsp;
</div>

</body>
</html>
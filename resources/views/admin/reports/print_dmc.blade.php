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

        table, th, td {
            border: 1px solid orange;
        }

        table {
            width: 90%;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        td, th {
            text-align: center;
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

}




    </style>

</head>
<body>

    <div id="image">
        <img  src="{{ asset('img/school_logo.jpg') }}" alt="Photo" />
    </div>
    

{{-- <div style="text-align: center; font-family: Tahoma">
    <h1 style="width: 90%;border: 1px solid orange;" style="width: 90%;" class="clearfix"> {{ Config::get('school_name.school_name') }} </h1>
</div> --}}

<div style="text-align: center; font-family: Tahoma;">
    <h1 style="width: 90%;border: 1px solid orange;"> Detail Marks Certificate </h1>
</div>

<div style="text-align: center; font-family: Tahoma; font-size: 15px;">
@foreach($rows as $student)
    <strong style="border: 1px solid orange;">Student Name : {{$student->student_name}}</strong> &nbsp; &nbsp;
    <strong style="border: 1px solid orange;">Student Id : {{$student->student_id}}</strong> &nbsp; &nbsp;
    <strong style="border: 1px solid orange;">Class : {{$student->class}}</strong> &nbsp; &nbsp;
@endforeach
</div>

<div style="text-align: center; margin-top: 10px; font-size: 15px;">
    <strong style="border: 1px solid orange;">Father Name : {{$student->father_name}}</strong> &nbsp; &nbsp;
    <strong style="border: 1px solid orange;">Address : {{$student->student_address}}</strong> &nbsp; &nbsp;
</div>

<table>

    <tr>
        <th>Subjects</th>
        {{-- {{ dd($dmc_total_cats) }} --}}
        @foreach($_dmc_total_cats as $cat)
        {{-- {{ dd($cat) }} --}}
            <th>{{$cat}}</th>
        @endforeach
    </tr>

    @foreach($rows as $student_info)
                @foreach($student_info->marks as $i => $mark)
            <tr>
                    <td>{{$i}}</td>
                    @foreach($mark as $m)
                        <td>{{$m}}</td>
                    @endforeach
            </tr>
                @endforeach
    @endforeach

    <tr>
        <th>Overall Grade</th>
        @foreach($rows as $student_info)
            @foreach($student_info->grade as $grade)
            <th>{{$grade}}</th>
            @endforeach
        @endforeach
    </tr>
    <tr>
        <th>Total Marks</th>
        @foreach($rows as $student_info)
        <?php $k = 0;?>
        {{-- {{ dd($k) }} --}}
            @foreach($student_info->total_marks as $marks)
            <th>{{$marks}} ({{$student_info->total_subject_marks[$k]}})</th>
            <?php $k++; ?>
            @endforeach
           
        @endforeach
    </tr>
    <tr>
        <th>Overall Class Subject %</th>
        @foreach($vertical_percent as $percent)
            <th>{{$percent/count($students)}} %</th>
        @endforeach
    </tr>
</table>

<table>
    <tr>
        <th style="width: 53%">Overall Attendance</th>
        <th>{{count($attendance)}}</th>
    </tr>
</table>

<div style="margin-top: 30px; font-size: 15px; font-family: Tahoma; text-align: center">
<strong>Prepared By : </strong>______________ &nbsp; &nbsp;
<strong>Checked By : </strong>______________ &nbsp; &nbsp;
<strong>Controller Of Examination Sign : </strong>______________ &nbsp; &nbsp;
</div>
<div style="margin-top: 30px; font-size: 15px; font-family: Tahoma; text-align: center">
    <strong>Principle of {{ Config::get('school_name.school_name') }} </strong>&nbsp; &nbsp;
</div>

</body>
</html>
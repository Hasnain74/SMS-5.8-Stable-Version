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
<strong style="border: 1px solid orange;">Class Teacher : {{$report->teacher_name}}</strong> &nbsp; &nbsp;
<strong style="border: 1px solid orange;">Teacher Sign : </strong> ____________ &nbsp; &nbsp;
<strong style="border: 1px solid orange;">Report Type : {{$report->report_categories_name}}  </strong>&nbsp; &nbsp;
</div>

<div style="text-align: center; font-family: Tahoma; font-size: 15px; padding-top: 10px;">
    <strong style="border: 1px solid orange;">Subject : {{$report->subject}}</strong> &nbsp; &nbsp;
    <strong style="border: 1px solid orange;">Total Marks : {{$report->total_marks}}</strong> &nbsp; &nbsp;
    </div>

<table style="margin-top: 10px; margin-bottom: 10px;">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Obtained_marks</th>
        <th>Percentage</th>
        <th>Grade</th>
        <th>Status</th>
    </tr>
    @foreach($rows as $info)
    <tr>
        <td>{{ $info->student_id }}</td>
        <td>{{ $info->student_name }}</td>
        <td>{{ $info->marks }}</td>
        <td>{{ $info->percentage }}%</td>
        <td>{{ $info->grade }}</td>
        <td>
            @php
                $status = intval($info->percentage)<40?'Fail':'Pass';
                if( $status == 'Fail' )
                $failed++;
                else
                $passed++;
            @endphp
            {{$status}}
        </td>
    </tr>
    @endforeach
</table>

<div  style="margin-top: 20px; font-size: 15px; font-family: Tahoma; text-align: center">
    <strong>Overall Class Percent : </strong>{{$overall_class_percent}} % &nbsp; &nbsp;
    <strong>Controller Of Examination Sign : </strong>______________ &nbsp; &nbsp;
</div>

</body>
</html>
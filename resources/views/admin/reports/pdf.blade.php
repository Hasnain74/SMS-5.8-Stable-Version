<!doctype>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report</title>

    <style type="text/css" media="all">

        body {
            font-family: Junge;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        h1 {
            color: #5D6975;
            font-family: Tahoma;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
        }

        table{
            width: 90%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td{
            padding: 5px 0 5px 15px;
            border: 1px solid #EEE
        }

        tr {
            font-family: Tahoma;
        }


    </style>

</head>
<body>

@foreach($reports as $report)
    <h1 style="width: 90%" class="clearfix"> {{ Config::get('school_name.school_name') }} </h1>

    <table>
        <tr style="text-align: center">
            <td class="tableitem"><p>Class</p></td>
            <td class="tableitem"><p>{{$report->class_id}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Student ID</p></td>
            <td class="tableitem"><p>{{$report->student_id}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Student Name</p></td>
            <td class="tableitem"><p>{{$report->student_name}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Subject</p></td>
            <td class="tableitem"><p>{{$report->subject}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Report Type</p></td>
            <td class="tableitem"><p>{{$report->report_categories_name}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Total Marks</p></td>
            <td class="tableitem"><p>{{$report->total_marks}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Obtained Marks</p></td>
            <td class="tableitem"><p>{{$report->obtained_marks}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Percentage</p></td>
            <td class="tableitem"><p>{{$report->percentage}}%</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Position</p></td>
            <td class="tableitem"><p>{{$report->position}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Teacher Name</p></td>
            <td class="tableitem"><p>{{$report->teacher_name}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Report Created By</p></td>
            <td class="tableitem"><p>{{$report->created_by}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Created Date</p></td>
            <td class="tableitem"><p>{{$report->created_at}}</p></td>
        </tr>

    </table>
@endforeach

</body>
</html>
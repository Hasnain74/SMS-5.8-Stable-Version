<!doctype>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Timetable Print</title>

    <style type="text/css" media="all">

        body {
            font-family: Tahoma;
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
            font-weight: normal;
            text-align: center;
            margin-right: 80px;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
        }

        table{
            width: 90%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: center;
        }
        td{
            padding: 20px;
            border: 1px solid #EEE
        }

        tr {
            font-family: Tahoma;
        }


    </style>

</head>

<body>

<h1>{{ Config::get('school_name.school_name') }}</h1>
<h1>{{array_key_first($records)}}</h1>
<table>
    <thead>
    <tr>
        <th>Period</th>
        <th>Period Timing</th>
    </tr>
    </thead>
    @foreach ($records as $tb)
        @foreach($tb as $t)
        <tbody>
        <tr >
            <td>{{$t->period}}</td>
            <td>{{$t->period_timing}}</td>
        </tr>
        </tbody>
    @endforeach
    @endforeach
</table>


</body>
</html>
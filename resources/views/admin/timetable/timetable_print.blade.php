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
            margin-left: 200px;
        }

        h1 {
            color: #5D6975;
            font-family: Tahoma;
            font-size: 2.4em;
            font-weight: normal;
            margin-left: 20px;
            text-align: center;
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

    <table >
        <thead>
        <tr>
            <th>Class</th>
            <th>Period</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        </thead>
        @foreach ($records as $tb)
        <tbody>
        <tr>
            <td>{{$tb->class_name}}</td>
            <td>{{$tb->period}}</td>
            <td>{{$tb->monday}}</td>
            <td>{{$tb->tuesday}}</td>
            <td>{{$tb->wednesday}}</td>
            <td>{{$tb->thursday}}</td>
            <td>{{$tb->friday}}</td>
            <td>{{$tb->saturday}}</td>
        </tr>
        </tbody>
        @endforeach
    </table>


</body>
</html>
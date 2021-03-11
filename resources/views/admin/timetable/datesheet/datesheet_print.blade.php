<!doctype>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Datesheet Print</title>

    <style type="text/css" media="all">

        body {
            font-family: Tahoma;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            /* margin: 0 auto; */
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
            /* margin-left: 50%; */
            /* padding: 50px; */
            
        }

        h1 {
            color: #5D6975;
            font-family: Tahoma;
            font-size: 2.4em;
            font-weight: normal;
            text-align: center;
            margin-left: 150px;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
        }

        table{
            width: 0%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: center;
            font-size: 13px;
            margin-left: -60px;
        }
        td{
            padding: 5px 1px 5px 1px;
            border: 1px solid #EEE
        }

        tr {
            font-family: Tahoma;
        }
        


    </style>

</head>

<body id="myTable">

<h1>{{ Config::get('school_name.school_name') }}</h1>

<table style="table-layout: fixed; width: 150%; margin: 0 auto;">
    <thead>
    <tr>
        <th >Class</th>
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
            <td>{{$tb->monday}}</td>
            <td>{{$tb->tuesday}}</td>
            <td>{{$tb->wednesday}}</td>
            <td>{{$tb->thursday}}</td>
            <td>{{$tb->friday}}</td>
            <td>{{$tb->saturday}}</td>
        </tr>
        <tr></tr>
        </tbody>
    @endforeach 
</table>


</body>
</html>
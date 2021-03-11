<!doctype>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" >

    <title>Fee Invoice Print</title>

    <style type="text/css" media="all">
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 60%;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            /*background: url(dimension.png);*/
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 5px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }

    </style>

</head>

<body style="margin-top: 20px;">

<header class="clearfix">
    @foreach($result as $data)
        <h1>{{ Config::get('school_name.school_name') }}</h1>
        <div id="company" class="clearfix">
        </div>
        <div id="project">
            <div><span>Fee Report Date</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $data['date'] }}</div>
            <div><span>Total Paid Students</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{count($data['fee_report'])}}</div>
            <div><span>Total Paid Amount</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{$data['total_amount']}}</div>
            
        </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th class="service">Students ID</th>
            <th></th>
            <th class="service">Students Name</th>
            <th></th>
            <th class="service">Guardian Name</th>
            <th></th>
            <th class="service">Class</th>
            <th></th>
            <th style="text-align: right">Paid Amount</th>
        </tr>
        </thead>
        @foreach($data['fee_report'] as $fee_report)
            <tbody>
            <tr>
                <td class="service">{{$fee_report['student_id']}}</td>
                <td class="desc"></td>
                <td class="service">{{$fee_report['student_name']}}</td>
                <td class="unit"></td>
                <td class="service">{{$fee_report['guardian_name']}}</td>
                <td class="qty"></td>
                <td class="service">{{$fee_report['class_name']}}</td>
                <td class="qty"></td>
                <td class="total">RS {{$fee_report['paid_amount']}}</td>
            </tr>
            </tbody>
        @endforeach
    </table>
</main>
@endforeach
</body>
</html>
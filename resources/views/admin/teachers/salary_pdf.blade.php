<!doctype>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" >

    <title>Salary Print</title>

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
            width: 50%;
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
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
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
            padding: 10px;
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

        .vl {
            border-left: 6px solid green;
            height: 500px;
        }

    </style>

</head>

<body>

@foreach($invoices as $inv)
<header class="clearfix">
        <h1>{{ Config::get('school_name.school_name') }}</h1>
        <div id="company" class="clearfix">
            <div>Office copy</div>
        </div>
        <div id="project">
            <div><span>Invoice no</span> {{$inv->invoice_no}}</div>
            <div><span>Staff ID</span> {{$inv->teacher_id}}</div>
            <div><span>Name</span> {{$inv->teacher_name}}</div>
            <div><span>Absent Days</span> {{$inv->absent_days}}</div>
            <div><span>Invoice Date</span> {{$inv->date}}</div>
            <div><span>Invoice Created By</span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    {{$inv->invoice_created_by}}</div>
        </div>
</header>
<main>
    <table>
        <tbody>
        <tr>
            <td class="service">Salary</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->payable_amount}}</td>
        </tr>
        <tr>
            <td class="service">Cash Out</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->cash_out}}</td>
        </tr>
        <tr>
            <td class="service">Paid Amount</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->paid_amount}}</td>
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">Errors are accepted. In case of errors please call the institution</div>
    </div>
</main>

<header class="clearfix">
    <h1>{{ Config::get('school_name.school_name') }}</h1>
    <div id="company" class="clearfix">
        <div>Teacher's copy</div>
    </div>
    <div id="project">
        <div><span>Invoice no</span> {{$inv->invoice_no}}</div>
        <div><span>Staff ID</span> {{$inv->teacher_id}}</div>
        <div><span>Name</span> {{$inv->teacher_name}}</div>
        <div><span>Absent Days</span> {{$inv->absent_days}}</div>
        <div><span>Invoice Date</span> {{$inv->date}}</div>
        <div><span>Invoice Created By</span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{$inv->invoice_created_by}}</div>
    </div>
</header>
<main>
<table>
    <tbody>
    <tr>
        <td class="service">Salary</td>
        <td class="desc"></td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="total">RS {{$inv->payable_amount}}</td>
    </tr>
    <tr>
        <td class="service">Cash Out</td>
        <td class="desc"></td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="total">RS {{$inv->cash_out}}</td>
    </tr>
    <tr>
        <td class="service">Paid Amount</td>
        <td class="desc"></td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="total">RS {{$inv->paid_amount}}</td>
    </tr>
    </tbody>
</table>
@endforeach
<div id="notices">
    <div>NOTICE:</div>
    <div class="notice">Errors are accepted. In case of errors please call the institution</div>
</div>
</main>
</body>
</html>

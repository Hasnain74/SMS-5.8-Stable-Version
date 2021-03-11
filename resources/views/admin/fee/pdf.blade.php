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
            width: 70%;
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
            /*margin-bottom: 30px;*/
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

<body>

@foreach($invoice as $inv)
<header class="clearfix">
        <h1>{{ Config::get('school_name.school_name') }}</h1>
        <div id="company" class="clearfix">
            <div>Office copy</div>
        </div>
        <div id="project">
            <div><span>Invoice no</span> {{$inv->invoice_no}}</div>
            <div><span>Student ID</span> {{$inv->student_id}}</div>
            <div><span>Name</span> {{$inv->student_name}}</div>
            <div><span>Class</span> {{$inv->class_name}}</div>
            <div><span>Fee Slip</span> {{$inv->month}}</div>
            <div><span>Created At</span> {{$inv->created_at}}</div>
            <div><span>Paid Date</span> {{$inv->paid_date}} __________</div>
        </div>
        <div id="project" style="margin-left: 10px">
            <p>Current Fee Record</p>
            @foreach($records as $record)
                @foreach($record as $r)
                    <div><span>{{$r->paid_date}}</span> {{$r->paid_amount}}</div>
                @endforeach
            @endforeach
        </div>
</header>
<main>
    <table>
        <tbody>
        <tr>
            <td class="service">Preivous Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->previous_month_fee}}</td>
        </tr>
        <tr>
            <td class="service">Other Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->other_amount}} ({{$inv->other_fee_type}})</td>
        </tr>
        <tr>
            <td class="service">Transport Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->transport_fee}}</td>
        </tr>
        <tr>
            <td class="service">Current Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->total_amount}}</td>
        </tr>
        <tr>
            <td colspan="4">Payable Fee</td>
            <td class="total">RS {{$inv->total_payable_fee}}</td>
        </tr>
        <tr>
            <td colspan="4">Paid Fee</td>
            <td class="total">RS {{$inv->paid_amount}} ________
        </tr>
        <tr>
            <td colspan="4">Concession</td>
            <td class="total">RS {{$inv->concession}}</td>
        </tr>
        <tr>
            <td colspan="4" class="grand total">Remaining Fee</td>
            <td class="grand total">RS {{$inv->arrears}} ________
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">Errors are accepted. In case of errors please call the institution</div>
    </div>
</main>

<header class="clearfix">
    <h1>School Name</h1>
    <div id="company" class="clearfix">
        <div>Student's copy</div>
    </div>
    <div id="project">
        <div><span>Invoice no</span> {{$inv->invoice_no}}</div>
        <div><span>Student ID</span> {{$inv->student_id}}</div>
        <div><span>Name</span> {{$inv->student_name}}</div>
        <div><span>Class</span> {{$inv->class_name}}</div>
        <div><span>Fee Slip</span> {{$inv->month}}</div>
        <div><span>Created At</span> {{$inv->created_at}}</div>
        <div><span>Paid Date</span> {{$inv->paid_date}} __________</div>
    </div>
    <div id="project" style="margin-left: 10px">
        <p>Current Fee Record</p>
        @foreach($records as $record)
            @foreach($record as $r)
                <div><span>{{$r->paid_date}}</span> {{$r->paid_amount}}</div>
            @endforeach
        @endforeach
    </div>
</header>
<main>
    <table>
        <tbody>
        <tr>
            <td class="service">Previous Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->previous_month_fee}}</td>
        </tr>
        <tr>
            <td class="service">Other Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->previous_month_fee}} ({{$inv->other_fee_type}})</td>
        </tr>
        <tr>
            <td class="service">Transport Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->transport_fee}}</td>
        </tr>
        <tr>
            <td class="service">Current Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv->total_amount}}</td>
        </tr>
        <tr>
            <td colspan="4">Payable Fee</td>
            <td class="total">RS {{$inv->total_payable_fee}}</td>
        </tr>
        <tr>
            <td colspan="4">Paid Fee</td>
            <td class="total">RS {{$inv->paid_amount}} ________
        </tr>
        <tr>
            <td colspan="4">Concession</td>
            <td class="total">RS {{$inv->concession}}</td>
        </tr>
        <tr>
            <td colspan="4" class="grand total">Remaining Fee</td>
            <td class="grand total">RS {{$inv->arrears}} ________
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
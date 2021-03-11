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
            width: 90%;
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
            font-size: 1.4em;
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
            margin-right: 20px;
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
            padding: 500px 20px;
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

        /* table td.service,
        table td.desc {
            vertical-align: top;
        } */

        table td.unit,
        table td.qty,
        table td.total,
        table td.service,
        table td.desc {
            font-size: 0.8em;
            
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

<div  style="width:1200px;  margin-left: -50px;">
@foreach($result as $inv)
<div  style="width:400px; float:left;">
<header class="clearfix">
    <h1>{{ Config::get('school_name.school_name') }}</h1>
    <div id="company" class="clearfix">
        <div>Office copy</div>
    </div>
        <div id="project">
            <div><span>Invoice no</span> {{$inv['invoice_no']}}</div>
            <div><span>Student ID</span> {{$inv['student_id']}}</div>
            <div><span>Name</span> {{$inv['student_name']}}</div>
            <div><span>Guardian Name</span> &nbsp;&nbsp;&nbsp;&nbsp;  {{$inv['guardian_name']}}</div>
            <div><span>Class</span> {{$inv['class_name']}}</div>
            <div><span>Fee Slip</span> {{$inv['month']}}</div>
            <div><span>Created At</span> {{$inv['created_at']}}</div>
            <div><span>Created By</span> {{$inv['invoice_created_by']}}</div>
            <div><span>Paid Date</span> {{$inv['paid_date']}} __________ </div>
        </div>
        <div id="project" style="margin-left: 45px">
            <p>Current Fee Record</p>
            @foreach ($inv['fee_reports'] as $fee_report)
                <div>
                    <span>{{$fee_report->paid_date}}</span> {{$fee_report->paid_amount}}
                </div>
            @endforeach
        </div>
</header>
<main>
    <table>
        <tbody>
            <tr>
                <td class="service">Scholorhship Percentage</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">{{$inv['percentage']}}%</td>
            </tr>
        <tr>
            <td class="service">Previous Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['previous_month_fee']}}</td>
        </tr>
        <tr>
            <td class="service">Prospectus</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['prospectus']}}</td>
        </tr>
        <tr>
            <td class="service">Admin & Management Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['admin_and_management_fee']}}</td>
        </tr>
        <tr>
            <td class="service">Books</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['books']}}</td>
        </tr>
        <tr>
            <td class="service">Security Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['security_fee']}}</td>
        </tr>
        <tr>
            <td class="service">Uniform</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['uniform']}}</td>
        </tr>
        <tr>
            <td class="service">Fine Panalties</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['fine_panalties']}}</td>
        </tr>
        <tr>
            <td class="service">Printing & Stationary</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['printing_and_stationary']}}</td>
        </tr> 
        <tr>
            <td class="service">Promotion Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['promotion_fee']}}</td>
        </tr>
        <tr>
            <td class="service">Transport Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['transport_fee']}}</td>
        </tr>
        <tr>
            <td class="service">Other Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['other_amount']}} ({{$inv['other_fee_type']}})</td>
        </tr>
        <tr>
            <td class="service">Current Month Fee</td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty"></td>
            <td class="total">RS {{$inv['current_month_fee']}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;" class="total" colspan="4">Payable Fee</td>
            <td style="font-weight: bold;" class="total">RS {{$inv['payable_fee']}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;" class="total" colspan="4">Paid Fee</td>
            <td style="font-weight: bold;" class="total">RS {{$inv['paid_amount']}} ________
        </tr>
        <tr>
            <td style="font-weight: bold;"  class="total" colspan="4">Concession</td>
            <td style="font-weight: bold;" class="total">RS {{$inv['concession']}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;" class="total" colspan="4" class="grand total">Remaining Fee</td>
            <td style="font-weight: bold;" class="grand total">RS {{$inv['arrears']}} ________
        </tr>
        </tbody>
    </table>
    <p style="padding: 20px;">Accountant Sign _______________</p>
</main>
</div>

<div  style="width:400px; float:left;">
    <header class="clearfix">
        <h1>{{ Config::get('school_name.school_name') }}</h1>
        <div id="company" class="clearfix">
            <div>Bank copy</div>
        </div>
            <div id="project">
                <div><span>Invoice no</span> {{$inv['invoice_no']}}</div>
                <div><span>Student ID</span> {{$inv['student_id']}}</div>
                <div><span>Name</span> {{$inv['student_name']}}</div>
                <div><span>Guardian Name</span>  &nbsp;&nbsp;&nbsp;&nbsp; {{$inv['guardian_name']}}</div>
                <div><span>Class</span> {{$inv['class_name']}}</div>
                <div><span>Fee Slip</span> {{$inv['month']}}</div>
                <div><span>Created At</span> {{$inv['created_at']}}</div>
                <div><span>Created By</span> {{$inv['invoice_created_by']}}</div>
                <div><span>Paid Date</span> {{$inv['paid_date']}} __________ </div>
            </div>
            <div id="project" style="margin-left: 45px">
                <p>Current Fee Record</p>
                @foreach ($inv['fee_reports'] as $fee_report)
                    <div>
                        <span>{{$fee_report->paid_date}}</span> {{$fee_report->paid_amount}}
                    </div>
                @endforeach
            </div>
    </header>
    <main>
        <table>
            <tbody>
                <tr>
                    <td class="service">Scholorhship Percentage</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">{{$inv['percentage']}}%</td>
                </tr>
            <tr>
                <td class="service">Previous Month Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['previous_month_fee']}}</td>
            </tr>
            <tr>
                <td class="service">Prospectus</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['prospectus']}}</td>
            </tr>
            <tr>
                <td class="service">Admin & Management Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['admin_and_management_fee']}}</td>
            </tr>
            <tr>
                <td class="service">Books</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['books']}}</td>
            </tr>
            <tr>
                <td class="service">Security Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['security_fee']}}</td>
            </tr>
            <tr>
                <td class="service">Uniform</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['uniform']}}</td>
            </tr>
            <tr>
                <td class="service">Fine Panalties</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['fine_panalties']}}</td>
            </tr>
            <tr>
                <td class="service">Printing & Stationary</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['printing_and_stationary']}}</td>
            </tr> 
            <tr>
                <td class="service">Promotion Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['promotion_fee']}}</td>
            </tr>
            <tr>
                <td class="service">Transport Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['transport_fee']}}</td>
            </tr>
            <tr>
                <td class="service">Other Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['other_amount']}} ({{$inv['other_fee_type']}})</td>
            </tr>
            <tr>
                <td class="service">Current Month Fee</td>
                <td class="desc"></td>
                <td class="unit"></td>
                <td class="qty"></td>
                <td class="total">RS {{$inv['current_month_fee']}}</td>
            </tr>
            <tr>
                <td  style="font-weight: bold;" class="total"colspan="4">Payable Fee</td>
                <td style="font-weight: bold;" class="total">RS {{$inv['payable_fee']}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;" class="total" colspan="4">Paid Fee</td>
                <td style="font-weight: bold;" class="total">RS {{$inv['paid_amount']}} ________
            </tr>
            <tr>
                <td style="font-weight: bold;" class="total" colspan="4">Concession</td>
                <td style="font-weight: bold;" class="total">RS {{$inv['concession']}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;" class="total" colspan="4" class="grand total">Remaining Fee</td>
                <td  style="font-weight: bold;" class="grand total">RS {{$inv['arrears']}} ________
            </tr>
            </tbody>
        </table>
        <p style="padding: 20px;">Accountant Sign _______________</p>
    </main>
    </div>
    <div  style="width:400px; float:left;">
        <header class="clearfix">
            <h1>{{ Config::get('school_name.school_name') }}</h1>
            <div id="company" class="clearfix">
                <div>Student copy</div>
            </div>
                <div id="project">
                    <div><span>Invoice no</span> {{$inv['invoice_no']}}</div>
                    <div><span>Student ID</span> {{$inv['student_id']}}</div>
                    <div><span>Name</span> {{$inv['student_name']}}</div>
                    <div><span>Guardian Name</span> &nbsp;&nbsp;&nbsp;&nbsp;  {{$inv['guardian_name']}}</div>
                    <div><span>Class</span> {{$inv['class_name']}}</div>
                    <div><span>Fee Slip</span> {{$inv['month']}}</div>
                    <div><span>Created At</span> {{$inv['created_at']}}</div>
                    <div><span>Created By</span> {{$inv['invoice_created_by']}}</div>
                    <div><span>Paid Date</span> {{$inv['paid_date']}} __________ </div>
                </div>
                <div id="project" style="margin-left: 45px">
                    <p>Current Fee Record</p>
                    @foreach ($inv['fee_reports'] as $fee_report)
                        <div>
                            <span>{{$fee_report->paid_date}}</span> {{$fee_report->paid_amount}}
                        </div>
                    @endforeach
                </div>
        </header>
        <main>
            <table>
                <tbody>
                    <tr>
                        <td class="service">Scholorhship Percentage</td>
                        <td class="desc"></td>
                        <td class="unit"></td>
                        <td class="qty"></td>
                        <td class="total">{{$inv['percentage']}}%</td>
                    </tr>
                <tr>
                    <td class="service">Previous Month Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['previous_month_fee']}}</td>
                </tr>
                <tr>
                    <td class="service">Prospectus</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['prospectus']}}</td>
                </tr>
                <tr>
                    <td class="service">Admin & Management Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['admin_and_management_fee']}}</td>
                </tr>
                <tr>
                    <td class="service">Books</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['books']}}</td>
                </tr>
                <tr>
                    <td class="service">Security Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['security_fee']}}</td>
                </tr>
                <tr>
                    <td class="service">Uniform</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['uniform']}}</td>
                </tr>
                <tr>
                    <td class="service">Fine Panalties</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['fine_panalties']}}</td>
                </tr>
                <tr>
                    <td class="service">Printing & Stationary</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['printing_and_stationary']}}</td>
                </tr> 
                <tr>
                    <td class="service">Promotion Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['promotion_fee']}}</td>
                </tr>
                <tr>
                    <td class="service">Transport Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['transport_fee']}}</td>
                </tr>
                <tr>
                    <td class="service">Other Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['other_amount']}} ({{$inv['other_fee_type']}})</td>
                </tr>
                <tr>
                    <td class="service">Current Month Fee</td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="qty"></td>
                    <td class="total">RS {{$inv['current_month_fee']}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;" class="total" colspan="4">Payable Fee</td>
                    <td style="font-weight: bold;" class="total">RS {{$inv['payable_fee']}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;" class="total" colspan="4">Paid Fee</td>
                    <td style="font-weight: bold;" class="total">RS {{$inv['paid_amount']}} ________
                </tr>
                <tr>
                    <td  style="font-weight: bold;" class="total" colspan="4">Concession</td>
                    <td style="font-weight: bold;" class="total">RS {{$inv['concession']}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;" class="total" colspan="4" class="grand total">Remaining Fee</td>
                    <td style="font-weight: bold;" class="grand total">RS {{$inv['arrears']}} ________
                </tr>
                </tbody>
            </table>
            <p style="padding: 20px; margin-bottom: 100px;">Accountant Sign _______________</p>
        </main>
        </div>

@endforeach
</div>
</body>
</html>
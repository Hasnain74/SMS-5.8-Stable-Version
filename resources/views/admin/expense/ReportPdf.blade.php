<!doctype>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Report</title>

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
            <td class="tableitem"><p>Total Amount</p></td>
            <td class="tableitem"><p>{{$report->total_amount}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Note</p></td>
            <td class="tableitem"><p>{{$report->note}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Created By</p></td>
            <td class="tableitem"><p>{{$report->created_by}}</p></td>
        </tr>
        <tr style="text-align: center">
            <td class="tableitem"><p>Issue Date</p></td>
            <td class="tableitem"><p>{{$report->date}}</p></td>
        </tr>

    </table>
@endforeach

</body>
</html>
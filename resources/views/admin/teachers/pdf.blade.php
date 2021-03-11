<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Staff Profile Print</title>

    <style type="text/css" media="all">

        .email_table {
            color: #333;
            font-family: sans-serif;
            font-size: 15px;
            font-weight: 300;
            text-align: center;
            border-collapse: separate;
            border-spacing: 0;
            width: 99%;
            margin: 6px auto;
            box-shadow:none;
        }
        table {
            color: #333;
            font-family: sans-serif;
            font-size: 15px;
            font-weight: 300;
            text-align: center;
            border-collapse: separate;
            border-spacing: 0;
            width: 99%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,.16);
        }

        th {font-weight: bold; padding:10px; border-bottom:2px solid #000;}

        tbody td {border-bottom: 1px solid #ddd; padding:10px;}



        .email_main_div{width:700px; margin:auto; background-color:#EEEEEE; min-height:500px; border:2px groove #999999;}
        strong{font-weight:bold;}
        .item_table{text-align:left;}

    </style>

</head>

<body>

@foreach($users as $user)
    <body>
    <div class="email_main_div">
        <table class="email_table">
            <tr>
                
                <td width="400px;" style="text-align:left; padding:10px;">
                    <img alt="Img" src="{{$user->photo ? public_path().$user->photo->file :  public_path('img/avatar.png')}}" width="100px" height="100px">
                </td>
                <td style="text-align:left; padding:10px;">
                    <strong>Staff ID :</strong>     {{$user->teacher_id}}<br/>
                    <strong>Name :</strong>   {{$user->first_name. ' '. $user->last_name}}<br/>
                    <strong>Joined Date :</strong>  {{$user->join_date}}<br/>
                    <strong>Created Date :</strong>  {{$user->created_at->diffForHumans()}}<br/>
                </td>
            </tr>
        </table>

        <table class="item_table">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td>Staff ID</td>
                <td style="text-align: right;">{{$user->teacher_id}}</td>
            </tr>
            <tr>
                <td>Staff Name</td>
                <td style="text-align: right;">{{$user->first_name. ' '. $user->last_name}}</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td style="text-align: right;">{{$user->date_of_birth}}</td>
            </tr>
            <tr>
                <td>Gender</td>
                <td style="text-align: right;">{{$user->gender}}</td>
            </tr>
            <tr>
                <td>Subject</td>
                <td style="text-align: right;">{{$user->teacher_subject}}</td>
            </tr>
            <tr>
                <td>Qualification</td>
                <td style="text-align: right;">{{$user->teacher_qualification}}</td>
            </tr>
            <tr>
                <td>Experience Detail</td>
                <td style="text-align: right;">{{$user->exp_detail}}</td>
            </tr>
            <tr>
                <td>NIC Number</td>
                <td style="text-align: right;">{{$user->nic_no}}</td>
            </tr>
            <tr>
                <td>Phone No</td>
                <td style="text-align: right;">{{$user->phone_no}}</td>
            </tr>
            <tr>
                <td>Emergency No</td>
                <td style="text-align: right;">{{$user->emergency_no}}</td>
            </tr>
            <tr>
                <td>Full Address</td>
                <td style="text-align: right;">{{$user->full_address}}</td>
            </tr>
            <tr>
                <td>Salary</td>
                <td style="text-align: right;">{{$user->salary}}</td>
            </tr>
            </tbody>
        </table>
        <div style="width:98%; padding:1%; margin-top:10px; font-size:15px; text-align:center;">
            {{ Config::get('school_name.school_name') }}
        </div>
    </div>
    </body>
@endforeach

</body>
</html>
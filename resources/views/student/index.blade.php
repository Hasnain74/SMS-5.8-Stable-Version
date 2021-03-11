<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.5/css/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link href="{{asset('css/student_style.css')}}" rel="stylesheet" type="text/css"/>

    <title>Student Profile</title>
</head>

<body>

    <div>
        <header id="main-header">
            <div class="row no-gutters">
                <div class="col-lg-4 col-md-5">
                    <img src="{{\Illuminate\Support\Facades\Auth::user()->photo ? \Illuminate\Support\Facades\Auth::user()->photo->file : asset('img/avatar.png')}}" alt="Student Image">
                </div>

                <div class="col-lg-8 col-md-7 col-md-12" >
                    <div class="d-flex flex-column" >

                        @foreach($student as $s)

                        <div class="p-5 bg-dark text-white">
                            <div class="d-inline align-items-center ">
                                <h1 class="display-4">{{$user->username}}</h1>
                                <h4 class="mt-4 mx-2">student of class {{$s->studentsClass['class_name']}}</h4>
                            </div>
                            <h4 class="mx-2">Guardian Name : {{$s->guardian_name}}</h4>
                        </div>

                        @endforeach

                        <div class="p-4 bg-black">
                            {{$user->username}}'s Record Over View
                        </div>

                        <div  style=" width: 100%; height:100%;  padding: 1%">
                            <div class="d-flex flex-row text-white align-items-stretch text-center" style="margin: 0% -13% 0% -1%;">
                                <div class="port-item p-2 bg-primary" data-toggle="collapse" data-target="#home">
                                    <i class="fas fa-calendar-alt fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Attendance</span>
                                </div>
                                <div class="port-item p-4 bg-secondary" data-toggle="collapse" data-target="#timetable">
                                    <i class="fas fa-clock fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Timetable</span>
                                </div>
                                <div class="port-item p-4 bg-info" data-toggle="collapse" data-target="#datesheet">
                                    <i class="fas fa-calendar-alt fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Datesheet</span>
                                </div>
                            </div>
                            <div class="d-flex flex-row text-white align-items-stretch text-center" style="margin: 0% -13% 0% -1%;">
                                <div class="port-item p-4 bg-success" data-toggle="collapse" data-target="#resume">
                                    <i class="fas fa-graduation-cap fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Academic</span>
                                </div>
                                <div class="port-item p-4 bg-warning" data-toggle="collapse" data-target="#work">
                                    <i class="fas fa-comment-dollar fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Fee</span>
                                </div>
                                <div class="port-item p-4 bg-danger" data-toggle="collapse" data-target="#contact">
                                    <i class="fas fa-envelope fa-3x d-block"></i>
                                    <span class="d-none d-sm-block">Messages</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </header>

        <!--HOME-->
        <div class="collapse show" id="home">
            <div class="card card-body text-white py-5 bg-primary">
                <h2>Daily Attendance Record</h2>
            </div>

            <div class="card card-body py-5">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Date</th>
                        <th class="text-center">Attendance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($attendance)
                        @foreach($attendance as $atd)
                            <tr>
                                <td class="text-center align-middle">{{$atd->date}}</td>
                                <td class="text-center align-middle">{{$atd->attendance}}</td>
                            </tr>
                        @endforeach
                     @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!--TIMETABLE-->
        <div class="collapse show" id="timetable">
            <div class="card card-body text-white py-5 bg-secondary">
                <h2>Timetable</h2>
            </div>

            <div class="card card-body py-5">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Period</th>
                        <th class="text-center">Timing</th>
                        <th class="text-center">Days</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($timetable)
                        @foreach($timetable as $t)
                            <tr>
                                <td class="text-center align-middle">{{$t->period}}</td>
                                <td class="text-center align-middle">{{$t->period_timing}}</td>
                                <td class="text-center align-middle">{{$t->day}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!--TIMETABLE-->
        <div class="collapse show" id="datesheet">
            <div class="card card-body text-white py-5 bg-info">
                <h2>Datesheet</h2>
            </div>

            <div class="card card-body py-5">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Class_name</th>
                        <th class="text-center">Monday</th>
                        <th class="text-center">Tuesday</th>
                        <th class="text-center">Wednesday</th>
                        <th class="text-center">Thursday</th>
                        <th class="text-center">Friday</th>
                        <th class="text-center">Saturday</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($datesheet)
                        @foreach($datesheet as $d)
                            <tr>
                                <td class="text-center align-middle">{{$d->class_name}}</td>
                                <td class="text-center align-middle">{{$d->monday}}</td>
                                <td class="text-center align-middle">{{$d->tuesday}}</td>
                                <td class="text-center align-middle">{{$d->wednesday}}</td>
                                <td class="text-center align-middle">{{$d->thursday}}</td>
                                <td class="text-center align-middle">{{$d->friday}}</td>
                                <td class="text-center align-middle">{{$d->saturday}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!--RESUME-->
        <div class="collapse" id="resume">
            <div class="card card-body text-white py-5 bg-success">
                <h2>Overall Academic Record</h2>
            </div>

            <div class="card card-body py-5">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Subject</th>
                        <th class="text-center">Total Marks</th>
                        <th class="text-center">Obtained Marks</th>
                        <th class="text-center">Percentage</th>
                        <th class="text-center">Report Category</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Issue Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($reports)
                        @foreach($reports as $report)
                            <tr>
                                <td class="text-center align-middle">{{$report->subject}}</td>
                                <td class="text-center align-middle">{{$report->total_marks}}</td>
                                <td class="text-center align-middle">{{$report->obtained_marks}}</td>
                                <td class="text-center align-middle">{{$report->percentage}}%</td>
                                <td class="text-center align-middle">{{$report->report_categories_name}}</td>
                                <td class="text-center align-middle">{{$report->created_by}}</td>
                                <td class="text-center align-middle">{{$report->created_at->DiffForHumans()}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!--WORK-->
        <div class="collapse" id="work">
            <div class="card card-body text-white py-5 bg-warning">
                <h2>Overall Fee Record</h2>
            </div>

            <div class="card card-body py-5">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Invoice No</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Paid Date</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Invoice Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($fees)
                        @foreach($fees as $fee)
                            <tr>
                                <td class="text-center align-middle">{{$fee->invoice_no}}</td>
                                <td class="text-center align-middle">{{$fee->total_amount}}</td>
                                <td class="text-center align-middle">
                                    Paid: Rs {{$fee->paid_amount}} <br>
                                    Pre Fee: Rs {{$fee->previous_month_fee}} <br>
                                    {{$fee->other_fee_type}} : Rs {{$fee->other_amount}} <br>
                                    Payable: Rs {{$fee->total_payable_fee}} <br>
                                    Remaining: Rs {{$fee->arrears}}<br>
                                </td>
                                <td class="text-center align-middle">{{$fee->paid_date}}</td>
                                <td class="text-center align-middle">{{$fee->month}}</td>
                                <td class="text-center align-middle">{{$fee->invoice_created_by}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!--CONTACT-->
        <div class="collapse" id="contact">
            <div class="card card-body text-white py-5 bg-danger">
                <h2>Messages sent to you until now</h2>
            </div>

            <div class="card card-body">
                <table id="table" class="table table-dark table-bordered table-responsive-lg">
                    <thead>
                    <tr class="filters">
                        <th class="text-center">Sent To</th>
                        <th class="text-center">Sent By</th>
                        <th class="text-center">Message</th>
                        <th class="text-center">Sent Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($msgs)
                        @foreach($msgs as $msg)
                            <tr>
                                <td class="text-center align-middle">{{$msg->guardian_phone_no}}</td>
                                <td class="text-center align-middle">{{$msg->sent_by}}</td>
                                <td class="text-center align-middle">{{$msg->message}}</td>
                                <td class="text-center align-middle">{{$msg->created_at}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

        <!--FOOTER-->
        <footer id="main-footer" class="p-5 bg-dark text-white">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ url('/logout') }}" class="btn peach-gradient">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </footer>
    </div>


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.5/js/mdb.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>

    <script>
        $('.table').DataTable();

        $('.port-item').click(function() {
            $('.collapse').collapse('hide');
        });

        $(document).on('click', '[data-toggle="lightbox"]', function(e) {
            e.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>

</body>

</html>

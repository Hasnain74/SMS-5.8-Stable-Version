@extends('layouts.dashboard')

@section('content')

	<!--Main Page-->
	<div style="background-size: cover; " class="header-backgroud">
	<nav class="navbar navbar-expand-md navbar-light">
		<div class="ml-2">
				<a href="/" class="navbar-brand d-flex">
					<img style="border-radius: 50%;" src="{{\Illuminate\Support\Facades\Auth::user()->photo ? \Illuminate\Support\Facades\Auth::user()->photo->file : asset('img/avatar.png')}}"
						 width="100" height="100" alt="logo" class="d-none d-md-block">
					<h3 style="font-family: Tahoma; font-size: 30px;" class="d-sm-inline align-middle text-white mt-4 ml-2">
						{{\Illuminate\Support\Facades\Auth::user() ? ucfirst(\Illuminate\Support\Facades\Auth::user()->username) : "No Name"}}
					</h3>
				</a>

			<div class="collapse navbar-collapse text-white" id="navbarCollapse">
				<ul class="navbar-nav">

					@permission('view.students|create.students|delete.students|edit.students|delete.students_account|edit.students_account')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.students.index')}}" class="nav-link text-white">Students</a>
					</li>
					@endpermission
					@permission('view.attendance|create.attendance|delete.attendance|edit.attendance')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('student_attendance_register')}}" class="nav-link text-white">Students Attendance</a>
					</li>
					@endpermission
					@permission('view.teachers|create.teachers|delete.teachers|edit.teachers|edit.teachers_account|delete.teachers_account|delete.teachers_roles')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.teachers.index')}}" class="nav-link text-white">Manage Staff</a>
					</li>
					@endpermission
					@permission('view.teachers_attendance|create.teachers_attendance|delete.teachers_attendance|edit.teachers_attendance')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('attendance_register')}}" class="nav-link text-white">Staff Attendance</a>
					</li>
					@endpermission
					@permission('view.teachers_salary|create.teachers_salary|delete.teachers_salary|edit.teachers_salary')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('index')}}" class="nav-link text-white">Staff Finance</a>
					</li>
					@endpermission
					@permission('create.students_classes|edit.students_classes')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.classes.index')}}" class="nav-link text-white">Classes</a>
					</li>
					@endpermission
					@permission('view.timetable|create.timetable|delete.timetable|edit.timetable|pdf.timetable|
					view.datesheet|create.datesheet|delete.datesheet|edit.datesheet|pdf.datesheet')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.timetable.index')}}" class="nav-link text-white">Timetable</a>
					</li>
					@endpermission
					@permission('pdf.reports|view.reports|create_subject.reports|delete.reports|edit.reports|print.reports|
					delete_subject.reports|add_marks.reports')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.reports.index')}}" class="nav-link text-white">Reports</a>
					</li>
					@endpermission
					@permission('create.manage_roles|delete.manage_roles|edit.manage_roles')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.mng_roles.index')}}" class="nav-link text-white">User Roles</a>
					</li>
					@endpermission
					@permission('view.fee|create.fee|delete.fee|pdf.fee|sms.fee|print.fee')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.fee.index')}}" class="nav-link text-white">Fees</a>
					</li>
					@endpermission
					@permission('salary.expense|exp.expense|fee.expense|pdf.expense|edit.expense|delete.expense|pdf.expense|
					monthly_summary.expense|yearly_summary.expense|total_summary.expense')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.expense.index')}}" class="nav-link text-white">Finance</a>
					</li>
					@endpermission
					@permission('create.sms|delete.sms|view.sms')
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{route('admin.sms.index')}}" class="nav-link text-white">SMS</a>
					</li>
					@endpermission
					<li class="nav-item d-lg-none d-md-none">
						<a href="{{ url('/logout') }}" class="nav-link text-white">Logout</a>
					</li>

				</ul>
			</div>
		</div>

		<button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
			<span class="navbar-toggler-icon"></span>
		</button>

	</nav>

	<!--HEADER-->
	<header id="main-header" class="py-2">
		<div class="ml-2">
			<div class="row">
				<div class="col-md-6 text-white">
					<h1><i class="fas fa-cog"></i> Dashboard</h1>
				</div>
			</div>

			<div class="d-flex text-center">
				@permission('view.students|create.students|delete.students|edit.students|delete.students_account|edit.students_account')
				<a href="{{route('admin.students.index')}}">
					<div class="active-item hover-effect port-item p-3 d-none d-md-block" style="color: #FFC300  ">
						<i class="fas fa-user-graduate fa-3x d-block pointer"></i>
						<span>Manage Students</span>
					</div>
				</a>
				@endpermission
				@permission('view.attendance|create.attendance|delete.attendance|edit.attendance')
				<a href="{{route('student_attendance_register')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-calendar-alt fa-3x d-block pointer"></i>
						<span>Students Attendance</span>
					</div>
				</a>
				@endpermission
				@permission('view.teachers|create.teachers|delete.teachers|edit.teachers|edit.teachers_account|delete.teachers_account|delete.teachers_roles')
				<a href="{{route('admin.teachers.index')}}">
					<div class="active-item hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300 ">
						<i class="fas fa-chalkboard-teacher fa-3x d-block pointer"></i>
						<span>Manage Staff</span>
					</div>
				</a>
				@endpermission
				@permission('view.teachers_attendance|create.teachers_attendance|delete.teachers_attendance|edit.teachers_attendance')
				<a href="{{route('attendance_register')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-calendar-alt fa-3x d-block pointer"></i>
						<span>Staff Attendance</span>
					</div>
				</a>
				@endpermission
				@permission('view.teachers_salary|create.teachers_salary|delete.teachers_salary|edit.teachers_salary')
				<a href="{{route('index')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-money-bill-alt fa-3x d-block pointer"></i>
						<span>Staff Finance</span>
					</div>
				</a>
				@endpermission
				@permission('create.students_classes|edit.students_classes')
				<a href="{{route('admin.classes.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:  #FFC300  ;">
						<i class="fas fa-building fa-3x d-block pointer"></i>
						<span>Classes</span>
					</div>
				</a>
				@endpermission
				@permission('view.timetable|create.timetable|delete.timetable|edit.timetable|pdf.timetable|
				view.datesheet|create.datesheet|delete.datesheet|edit.datesheet|pdf.datesheet')
				<a href="{{route('admin.timetable.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:    #FFC300  ">
						<i class="fas fa-clock fa-3x d-block pointer"></i>
						<span>Timetable</span>
					</div>
				</a>
				@endpermission
				@permission('pdf.reports|view.reports|create_subject.reports|delete.reports|edit.reports|print.reports|
				delete_subject.reports|add_marks.reports')
				<a href="{{route('admin.reports.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:   #FFC300 ">
						<i class="fas fa-flag fa-3x d-block pointer"></i>
						<span>Reports</span>
					</div>
				</a>
				@endpermission
				@permission('create.manage_roles|delete.manage_roles|edit.manage_roles')
				<a href="{{route('admin.mng_roles.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color: #FFC300  ">
						<i class="fas fa-award fa-3x d-block pointer"></i>
						<span>User Roles</span>
					</div>
				</a>
				@endpermission
				@permission('view.fee|create.fee|delete.fee|pdf.fee|sms.fee|print.fee')
				<a href="{{route('admin.fee.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
						<i class="fas fa-file-invoice fa-3x d-block pointer"></i>
						<span>Fees</span>
					</div>
				</a>
				@endpermission
				@permission('salary.expense|exp.expense|fee.expense|pdf.expense|edit.expense|delete.expense|pdf.expense|
				monthly_summary.expense|yearly_summary.expense|total_summary.expense')
				<a href="{{route('admin.expense.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:   #FFC300  ">
						<i class="fas fa-file-invoice-dollar fa-3x d-block pointer"></i>
						<span>Finance</span>
					</div>
				</a>
				@endpermission
				@permission('create.sms|delete.sms|view.sms')
				<a href="{{route('admin.sms.index')}}">
					<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:  #FFC300 ">
						<i class="fas fa-envelope fa-3x d-block pointer"></i>
						<span>SMS</span>
					</div>
				</a>
				@endpermission
				<a href="{{ url('/logout') }}">
				<div class="hover-effect active-item port-item p-3 d-none d-md-block" style="color:  #FFC300 ">
					<i class="fas fa-sign-out-alt fa-3x d-block pointer"></i>
					<span>Logout</span>
				</div>
				</a>
			</div>

		</div>
	</header>
	</div>

@permission('view.dashboard')
	<div class="js--wp-1 py-3">
		<div class="row mb-4 mx-2">
			<div class="col-md-4">
				<div class="card-counter young-passion-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$students}}</span>
					<span class="count-name">Total Students</span>
					<span><i class="fas fa-user-graduate"></i></span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card-counter ripe-malinka-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$teachers}}</span>
					<span class="count-name">Total Staff</span>
					<span><i class="fas fa-chalkboard-teacher"></i></span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card-counter dusty-grass-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$users}}</span>
					<span class="count-name">Total Users</span>
					<span><i class="fas fa-users"></i></span>
				</div>
			</div>

			<div class="col-md-6 mt-3">
				<div class="card-counter aqua-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$maleStudents}}</span>
					<span class="count-name">Male Students</span>
					<span><i class="fas fa-users"></i></span>
				</div>
			</div>

			<div class="col-md-6 mt-3">
				<div class="card-counter peach-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$femaleStudents}}</span>
					<span class="count-name">Female Students</span>
					<span><i class="fas fa-users"></i></span>
				</div>
			</div>

			<div class="col-md-6 mt-3">
				<div class="card-counter purple-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$maleUsers}}</span>
					<span class="count-name">Male Teachers</span>
					<span><i class="fas fa-users"></i></span>
				</div>
			</div>

			<div class="col-md-6 mt-3">
				<div class="card-counter blue-gradient">
					<i class="fa fa-code-fork"></i>
					<span class="count-numbers">{{$femaleUsers}}</span>
					<span class="count-name">Female Teachers</span>
					<span><i class="fas fa-users"></i></span>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 mt-3 mb-3">
		<div id="chartContainer" style="height: 500px; width: 100%;"></div>
	</div>

	<div class="col-md-12 mt-5 mb-3">
		<div id="chart2" style="height: 500px; width: 100%;"></div>
	</div>

	<div class="col-md-12 mt-5 mb-3">
		<div id="chart3" style="height: 500px; width: 100%;"></div>
	</div>

@endpermission

@stop

@section('script')

	<script>

		CanvasJS.addColorSet("colours", [
					"#FF8800",
				]);

		CanvasJS.addColorSet("colours2", [
			'#0099CC',
		]);

		CanvasJS.addColorSet("colours3", [
			"#CC0000",
		]);

		window.onload = function () {

			var chart = new CanvasJS.Chart("chartContainer", {
				exportEnabled: true,
				animationEnabled: true,
				colorSet: "colours",
				title: {
					text: "Paid Fee",
					fontFamily: "tahoma"
				},
				axisX: {
					title: "Months"
				},
				toolTip: {
					shared: true
				},
				legend: {
					cursor: "pointer",
				},
				data: [{
					type: "column",
					name: "Fee",
					showInLegend: true,
					yValueFormatString: "#,##0.# RS",
					dataPoints: [
							@foreach($fees as $date => $fees_group)
							@php($total=0)
							@foreach($fees_group as $fee)
							@php($total += intval($fee->paid_amount) )
							@endforeach
						{
							label: "{{date('F, Y', strtotime($fee->paid_date))}}", y: {{$total}} },
						@endforeach
					]
				}],
			});
			chart.render();

			var chart = new CanvasJS.Chart("chart2", {
				exportEnabled: true,
				animationEnabled: true,
				colorSet: "colours3",
				title: {
					text: "Paid Salaries",
					fontFamily: "tahoma"
				},
				axisX: {
					title: "Months"
				},
				toolTip: {
					shared: true
				},
				legend: {
					cursor: "pointer",
				},
				data : [{
					type: "column",
					name: "Salaries",
					axisYType: "secondary",
					showInLegend: true,
					yValueFormatString: "#,##0.# RS",
					dataPoints: [
							@foreach($salaries as $date => $salary_group)
							@php($total=0)
							@foreach($salary_group as $salary)
							@php($total += intval($salary->paid_amount) )
							@endforeach
						{
							label: "{{date('F, Y', strtotime($salary->date))}}", y: {{$total}} },
						@endforeach
					]

				}],
			});
			chart.render();

			var chart = new CanvasJS.Chart("chart3", {
				exportEnabled: true,
				animationEnabled: true,
				colorSet: "colours2",
				title: {
					text: "Other Expense",
					fontFamily: "tahoma"
				},
				axisX: {
					title: "Months"
				},
				toolTip: {
					shared: true
				},
				legend: {
					cursor: "pointer",
				},
				data: [{
					type: "column",
					name: "Expense",
					showInLegend: true,
					yValueFormatString: "#,##0.# RS",
					dataPoints: [
							@foreach($expenses as $date => $expense_group)
							@php($total=0)
							@foreach($expense_group as $expense)
							@php($total += intval($expense->total_amount) )
							@endforeach
						{
							label: "{{date('F, Y', strtotime($expense->date))}}", y: {{$total}} },
						@endforeach
					]
				},

				]
			});
			chart.render();
		};

	</script>

@stop

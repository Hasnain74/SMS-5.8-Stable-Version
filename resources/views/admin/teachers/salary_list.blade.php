@extends('layouts.dashboard')

@section('content')

@include('includes.salary_list_nav')

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center text-white">
					<h1><i class="fas fa-money-bill-alt"></i> Staff Salary</h1>
				</div>
			</div>

			{{-- <div class="d-flex text-white text-center">
				@permission('create.teachers_salary|delete.teachers_salary|edit.teachers_salary|
				view.teachers_salary')
				{{-- <a href="{{route('index')}}">
					<div class="active-item hover-effect port-item p-3 d-none d-md-block">
						<i class="fas fa-money-bill-alt fa-3x d-block pointer"></i>
						<span>Teachers Salary</span>
					</div>
				</a> --}}
				{{-- <a href="{{route('manage_salary_invoice')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-calendar-check fa-3x d-block pointer"></i>
						<span>Manage Invoice</span>
					</div>
				</a> --}}
				{{-- @endpermission --}}
			{{-- </div> --}}

		</div>
	</header>
</div>


	<div class="mx-4 py-4">

		@if ($message = Session::get('create_teacher_salary'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('update_teacher_salary'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('delete_teacher_salary'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('delete_teacher_invoices'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		@include('includes.form_errors')

		<div class="filterable">

			<div>
				<div class="float-right">
					@permission('delete.teachers_salary')
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
					@endpermission
				</div>
				<div class="mb-2">
					@permission('create.teachers_salary')
					<a data-toggle="modal" data-target="#createInvoiceModal">
						<button type="button" class="btn purple-gradient"><span class="fas fa-plus-square"></span> Create Invoice</button>
					</a>
					@endpermission
				</div>
			</div>

			<select name="teacher_id_teachers_salary" class="browser-default custom-select bg-dark text-white mb-3" >
				<option value="">Select Staff ID</option>
				@foreach ($teacher_id as $key => $value)
					<option value="{{ $value }}">{{ $value }}</option>
				@endforeach
			</select>

			<table id="table_teachers_salary" class="table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="align-middle text-center"><input type="checkbox" id="options"></th>
						<th class="text-center">Invoice No</th>
						<th class="text-center">Staff ID</th>
						<th class="text-center">Staff Name</th>
						<th class="text-center">Issue Date</th>
						<th class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
				@if($invoices)
					@foreach($invoices as $invoice)
					<tr>
						<td class="text-center"><input class="checkBoxes align-middle" type="checkbox" name="checkBoxArray[]" value="{{$invoice->id}}"></td>
						<td class="text-center align-middle">{{$invoice->invoice_no}}</td>
						<td class="text-center align-middle">{{$invoice->teacher_id}}</td>
						<td class="text-center align-middle">{{$invoice->teacher_name}}</td>
						<td class="text-center align-middle">{{$invoice->date}}</td>
						<td class="text-center align-middle">
							@permission('edit.teachers_salary')
							<a data-toggle="modal" data-target="#editModal{{$invoice->id}}">
								<button title="Edit" class="btn btn-primary">
									<span class="fas fa-pencil-alt"></span>
								</button>
							</a>
							@endpermission
							@permission('view.teachers_salary')
							<a data-toggle="modal" data-target="#viewModal{{$invoice->id}}">
								<button title="View" class="btn btn-warning">
									<span class="fas fa-eye"></span>
								</button>
							</a>
							@endpermission
							@permission('delete.teachers_salary')
							<a data-toggle="modal" data-target="#deleteInvModal{{$invoice->id}}">
								<button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
							</a>
							@endpermission
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>

			<a target="_blank" href="{{action('TeachersSalaryController@export')}}"
				class="btn text-white float-right mt-2 bg-dark">
			 	<i class="fas fa-print"></i> Export Data
		    </a>
			
		</div>

	</div>

	<!--CREATE INVOICE VIEW MODAL-->
	<div class="modal fade" id="createInvoiceModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Create New Salary Invoice</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="col-md-12 py-2 ">

					<div class="row">
						<div class="col-md-12">
							{!! Form::open(['method'=>'POST', 'action'=>'TeachersSalaryController@store']) !!}
							<div class="md-form">
								{{Form::date('date', null, ['class' => 'form-control', 'id'=>'date'])}}
								<label class="mt-3">Issue Date <span style="color: red">*</span></label>
							</div>
							<div class="form-group">
								<select name="teacher_id" class="browser-default custom-select" >
									<option value="">Select Staff ID</option>
									@foreach ($teacher_id as $key => $value)
										<option value="{{$key}}">{{$value}}</option>
									@endforeach
								</select>
							</div>
							<div class="md-form md-outline">
								{!! Form::label('teacher_name', 'Staff Name', ['class'=>'control-label']) !!}
								{!! Form::text('teacher_name', null,  ['class'=>'form-control', 'name'=>'teacher_name']) !!}
							</div>
					
							<div class="md-form md-outline">
								{!! Form::label('absent_days', 'Absent Days', ['class'=>'control-label']) !!}
								{!! Form::text('absent_days', null,  ['class'=>'form-control', 'name'=>'absent_days', 'id'=>'absent_days']) !!}
							</div>
							<div class="md-form md-outline">
								{!! Form::label('payable_amount', 'Salary', ['class'=>'control-label']) !!}
								<input name="payable_amount" class="form-control payable_amount" id="txt3" type="text"  />
							
							</div>
							<div class="md-form md-outline">
								{!! Form::label('cash_out', 'Cash out', ['class'=>'control-label']) !!}
								<input name="cash_out" class="form-control cash_out" id="txt1" type="text" onkeyup="GetTextboxId_Value(this)" value="0"/>
							</div>
							<div class="md-form md-outline">
								{!! Form::label('paid_amount', 'Paid Amount', ['class'=>'control-label']) !!}
								{!! Form::text('paid_amount', null, ['class'=>'form-control paid_amount_salary', 
								'name'=>'paid_amount',  'id'=>'txt2']) !!}
							</div>
							<div class="md-form md-outline">
								{!! Form::text('teacherPhNo', null, ['class'=>'form-control teacherPhNo', 'name'=>'teacherPhNo'
								, 'hidden']) !!}
							</div>
							
							{!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block 
							peach-gradient mb-2']) !!}
							{!! Form::close() !!}
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

@permission('delete.teachers_salary')
@foreach($invoices as $invoice)
	<div class="modal fade" id="deleteModal">
		<div class="modal-dialog modal-md">
			<div class="modal-content">

				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title">Delete Confirmation</h5>
					<button class="close" data-dismiss="modal">
						<span class="text-white">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="modal-body">
						<div class="col-md-12">
							<p class="text-center">Do you really want to delete ?</p>
							{!! Form::model($invoice, ['method'=>'DELETE', 'action'=>['TeachersSalaryController@deleteInv',  $invoice->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteInv_teachers_salary btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach
@endpermission


@permission('edit.teachers_salary')
@foreach($invoices as $invoice)
<div class="modal fade" id="editModal{{$invoice->id}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header header-backgroud text-white">
				<h5 class="modal-title">Edit Salary Invoice</h5>
				<button class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="col-md-12 py-2 ">

				<div class="row">
					<div class="col-md-12">
						{!! Form::model($invoice, ['method'=>'PATCH', 'action'=>['TeachersSalaryController@update', $invoice->id]]) !!}
						<div class="md-form">
							{{Form::date('date', old('date', $invoice->date), ['class' => 'form-control'])}}
							{!! Form::label('date', 'Issue Date', ['class'=>'mt-3']) !!}
						</div>
						{!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient mb-2']) !!}
						{!! Form::close() !!}
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endforeach
@endpermission

@permission('delete.teachers_salary')
@foreach($invoices as $invoice)
	<div class="modal fade" id="deleteInvModal{{$invoice->id}}">
		<div class="modal-dialog modal-md">
			<div class="modal-content">

				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title">Delete Confirmation</h5>
					<button class="close" data-dismiss="modal">
						<span class="text-white">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="modal-body">
						<div class="col-md-12">
							<p class="text-center">Do you really want to delete ?</p>
							{!! Form::model($invoice, ['method'=>'DELETE', 'action'=>['TeachersSalaryController@destroy',  $invoice->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach
@endpermission


@permission('view.teachers_salary')
	@foreach($invoices as $invoice)
	<div class="modal fade" id="viewModal{{$invoice->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Salary Invoice View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
					<a target='blank' name="gnrt_single" href="{{action('TeachersSalaryController@downloadPDF', $invoice->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print
					</a>
				</div>

				<div class="col-md-12 py-2 ">

					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">
								<div>
									<i class="fas fa-arrow-circle-down"></i> Salary Invoice View
								</div>
							</h5>
						</div>

						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Invoice Number</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->invoice_no}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Staff ID</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->teacher_id}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Staff Name</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->teacher_name}}</p>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Salary</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{$invoice->payable_amount}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Absent Days</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{$invoice->absent_days}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Total Cash Out</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{$invoice->cash_out}}</p>
                                </div>
                            </div>
							<div class="row">
								<div class="col-md-6">
									<label>Paid Amount</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->paid_amount}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Invoice Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->date}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Invoice Created By</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->invoice_created_by}}</p>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	@endforeach
@endpermission

@stop

@section('script')

<script>
    function GetTextboxId_Value(textBox) 
              {
                  console.log("helllfdjkl");
                   if(textBox.value != "" && isNaN(textBox.value) === false){
                 		var value1 = parseInt(document.getElementById("txt3").value) - parseInt(textBox.value);
			    document.getElementById("txt2").value = value1;
					}else if(textBox.value == "" || textBox.value == "0" ){
						document.getElementById("txt2").value = document.getElementById("txt3").value;
					}
					else{
						document.getElementById("txt2").value = document.getElementById("txt3").value;

					}
               
             }
</script>

@stop
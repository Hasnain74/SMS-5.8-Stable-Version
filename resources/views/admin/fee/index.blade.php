@extends('layouts.dashboard')

@section('content')


	<div class="header-backgroud">
		<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
			<div class="ml-2">
				@include('includes.school_profile')
			</div>
		</nav>

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-file-invoice"></i> Fee Invoices List</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				<a href="{{route('admin.fee.index')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
						<i class="fas fa-file-invoice fa-3x d-block pointer"></i>
						<span>Monthly Fee</span>
					</div>
				</a>

				<a href="{{route('instalment_fee')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
						<i class="fas fa-file-invoice fa-3x d-block pointer"></i>
						<span>Instalment Setup</span>
					</div>
				</a>
				<a href="{{route('admission_fee')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
						<i class="fas fa-file-invoice fa-3x d-block pointer"></i>
						<span>Admission Fee</span>
					</div>
				</a>
			</div>

		</div>
	</header>
</div>



	<div class="mx-4 py-2">

		@if ($message = Session::get('warning'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('delete_fee'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('delete_fees'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		@if ($message = Session::get('create_fee'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_fee'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@include('includes.form_errors')

		<div class="filterable">


			{!! Form::open(['method'=>'GET', 'action'=>'FeeController@invoicesActions']) !!}

			
			<div class="md-form">
				{{Form::date('date', null, ['class' => 'form-control'])}}
			</div>

			
			<div class="float-right">
				@permission('delete.fee')
				<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
					<option value="">Delete</option>
				</select>
				<a data-toggle="modal" data-target="#deleteInvoiceModal">
					<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
				</a>
				@endpermission
			</div>

			<div class="form-group float-right d-flex">
				<select name="options" class="browser-default custom-select bg-dark text-white mt-2">

					<option value="select">Select Action</option>
					@permission('print.fee')
					<option value="print">Print Invoices</option>
					@endpermission
					@permission('delete.fee')
					<option value="delete">Delete</option>
					@endpermission
					@permission('sms.fee')
					<option value="sms">Send Message</option>
					@endpermission

				</select>
				<a ><input value="Apply" type="submit" name="apply" class="btn blue-gradient mx-2"></a>
			</div>


			<div class="mb-3">
				@permission('create.fee')
				<a href="{{route('admin.fee.create')}}">
					<button type="button" class="btn purple-gradient">
						<span class="fas fa-pencil-alt"></span> Create Fee Invoice
					</button>
				</a>
				@endpermission
			</div>

			@permission('view.fee')
			<div class="form-group">
				<div class="form-group">
					{!! Form::select('status', [''=>'Choose Status', 'paid'=>'Paid', 'arrears'=>'Unpaid'], null,
					['class'=>'browser-default custom-select bg-dark text-white', 'name'=>'status']) !!}
				</div>
			</div>

			<div class="form-group">
				<select name="class_id_fee_index" class="browser-default custom-select bg-dark text-white" >
					<option value="">Choose Class</option>
					@foreach ($classes as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>

			<div class="my-3">
				<select name="std_id_fee_index" class="browser-default custom-select bg-dark text-white" >
					<option value="">Choose Student ID</option>
					@foreach ($student_id as $key => $value)
						<option value="{{ $value }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>
			@endpermission

			<table id="studentsData_fee_index" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="align-middle text-center"><input type="checkbox" id="options"></th>
						<th class="text-center">Invoice ID</th>
						<th class="text-alignment text-center">Student ID</th>
						<th class="text-center">Student Name</th>
						<th class="text-center">Fee Amount</th>
						<th style="width: 10%;"  class="align-middle text-center">Status</th>
						<th  class="text-center">Paid Date</th>
						<th  class="text-center">Month</th>
						<th  class="text-center">Class</th>
						<th width="15%" class="align-middle text-center">Actions</th>
					</tr>
				</thead>

				@permission('view.fee')
				<tbody>
				@if($invoices)
					@foreach($invoices as $invoice)
					<tr>
						<td class="text-center align-middle"><input name="checkBoxArray[]" class="checkBoxes" type="checkbox" value="{{$invoice->id}}"></td>
						<td class="text-center align-middle">{{$invoice->invoice_no}}</td>
						<td class="text-center align-middle">{{$invoice->student_id}}</td>
						<td class="text-center align-middle">{{$invoice->student_name}}</td>
						<td class="text-center align-middle">Rs {{$invoice->total_amount}}</td>
						<td class="text-center align-middle">
							Paid: Rs {{$invoice->paid_amount}} <br>
							Pre Fee: Rs {{$invoice->previous_month_fee}} <br>
							Payable: Rs {{$invoice->total_payable_fee}} <br>
							Remaining: Rs {{$invoice->arrears}}<br>
						</td>
						<td class="text-center align-middle">{{$invoice->paid_date}}</td>
						<td class="text-center align-middle">{{$invoice->month}}</td>
						<td class="text-center align-middle">{{$invoice->class_name}}</td>
						{!! Form::close() !!}
						<td class="text-center d-flex">
							<a data-toggle="modal" data-target="#paidModal{{$invoice->id}}">
								<button type="button" title="Paid" class="btn sunny-morning-gradient"><span class="fas fa-check"></span></button>
							</a>
							<a data-toggle="modal" data-target="#editModal{{$invoice->id}}">
								<button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
							</a>
							<a data-toggle="modal" data-target="#viewInvoiceModal{{$invoice->id}}">
								<button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button>
							</a>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
				@endpermission

			</table>

		</div>
	</div>


@permission('view.fee')
	<!--INVOICE VIEW MODAL-->
@foreach($invoices as $invoice)
	<div class="modal fade" id="viewInvoiceModal{{$invoice->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Invoice View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				@permission('pdf.fee')
				{{-- <div class="container">
					<a target="_blank" name="gnrt_single" href="{{action('FeeController@downloadPDF', $invoice->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Generate Invoice PDF
					</a>
				</div> --}}
				@endpermission

				@permission('print.fee')
                <div class="container">
                    <a target="_blank" name="print_single" href="{{action('FeeController@printInvoice', $invoice->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
                        <i class="fas fa-print"></i> Print Invoice
                    </a>
                </div>
				@endpermission

				<div class="col-md-12 py-2 ">

					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">
								<div>
									<i class="fas fa-arrow-circle-down"></i> Invoice View
								</div>
							</h5>
						</div>

						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Class</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->class_name}}</p>
								</div>
							</div>
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
									<label>Student ID</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->student_id}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Student Name</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->student_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Guardian Name</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->guardian_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Scholorship Percentage</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->percentage}}%</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Tuition Fee</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->total_amount}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Previous Month Fee</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->previous_month_fee}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Other Fee</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->other_amount}} Rs ({{$invoice->other_fee_type}})</p>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<label>Total Payable Amount</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->total_payable_fee + $invoice->other_amount}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Transport Fee</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->transport_fee}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Paid Amount</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->paid_amount}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Concession</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->concession}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Remaining Amount</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->arrears}} Rs</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Month</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->month}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Paid Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->paid_date}}</p>
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
							<div class="row">
								<div class="col-md-6">
									<label>Invoice Created Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->created_at->DiffForHumans()}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Issue Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->issue_date}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Due Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$invoice->due_date}}</p>
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


	@foreach($invoices as $inv)
		<div class="modal fade" id="editModal{{$inv->id}}">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-header header-backgroud text-white">
						<h5 class="modal-title">Edit Invoice</h5>
						<button class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="container">
						<div class="row mt-2">
							<div class="col-md-12">

								{!! Form::model($inv, ['method'=>'PATCH', 'action'=>['FeeController@update', $inv->id]]) !!}
								<div class="md-form md-outline">
									{!! Form::hidden('student_id', null, ['class'=>'form-control']) !!}
								</div>
								<div class="md-form">
									{{Form::date('paid_date', Carbon::now()->toDateString() , ['class' => 'form-control'])}}
									<label class="mt-3">Paid Date <span style="color: red">*</span></label>
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Total Payable Amount <span style="color: red">*</span></label>
									{!! Form::text('total_payable_fee', null, ['class'=>'form-control', 'readonly']) !!}
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Paid amount <span style="color: red">*</span></label>
									{!! Form::text('paid_amount', null, ['class'=>'form-control paid_amount', 'id'=>'paid_amount']) !!}
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Concession </label>
									{!! Form::text('concession', null, ['class'=>'form-control concession', 'id'=>'concession']) !!}
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Remaining <span style="color: red">*</span></label>
									{!! Form::text('arrears', null, ['class'=>'form-control amount', 'readonly',
                                    'data-amount' => $inv->arrears, 'id'=>'amount']) !!}
								</div>
								{!! Form::button(' Edit Invoice', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
							</div>
							{!! Form::close() !!}

						</div>
					</div>

				</div>
			</div>
		</div>
	@endforeach

@foreach($invoices as $invoice)
	<div class="modal fade" id="deleteInvoiceModal">
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
							{!! Form::model($invoice, ['method'=>'POST', 'action'=>['FeeController@delete_invoices',  $invoice->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteInvoice_fee_index btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach

	@foreach($invoices as $invoice)
		<div class="modal fade" id="paidModal{{$invoice->id}}">
			<div class="modal-dialog modal-md">
				<div class="modal-content">

					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title">Paid Confirmation</h5>
						<button class="close" data-dismiss="modal">
							<span class="text-white">&times;</span>
						</button>
					</div>
					<div class="container">
						<div class="modal-body">
							<div class="col-md-12">
								<p class="text-center">Do you really want to Paid this Fee ?</p>
								{!! Form::model($invoice, ['method'=>'POST', 'action'=>['FeeController@paid',  $invoice->id]]) !!}
								{!! Form::button(' Paid', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right']) !!}
								{!! Form::close() !!}
								<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endforeach

@stop
@extends('layouts.dashboard')


@section('content')


	<!--Main Page-->
	<div class="header-backgroud">
	<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
		<div class="ml-2">
			@include('includes.school_profile')
		</div>
	</nav>

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="{{route('admin.timetable.datesheet.index')}}" class="a-resp btn aqua-gradient float-right mr-4">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">

					<h1><i class="fas fa-calendar-alt"></i> Create Datesheet</h1>
				</div>
			</div>

		</div>
	</header>
	</div>

	<div class="mx-4 py-4">

		@if ($message = Session::get('create_datesheet'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		{!! Form::open(['method'=>'POST', 'action'=>'DatesheetController@store', 'class' => 'mb-5', 'id'=>'datesheet']) !!}

			<span id="result"></span>
		<div class="form-group">
			{!! Form::select('class_id', [''=>'Choose Class'] + $classes, null,  ['class'=>'browser-default custom-select bg-dark text-white']) !!}
		</div>

		<div class="row">
			<div class="col-md-12">
				<table class=" table table-bordered table-hover table-sortable table-responsive-lg" id="tab_logic">
					<thead>
						<tr>
							<th class="text-center">Monday</th>
							<th class="text-center">Tuesday</th>
							<th class="text-center">Wednesday</th>
							<th class="text-center">Thursday</th>
							<th class="text-center">Friday</th>
							<th class="text-center">Saturday</th>

						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="6" align="right">&nbsp;</td>
						<td>
							@csrf
							<input type="submit" name="save" id="save" class="btn peach-gradient" value="Save" />
						</td>
					</tr>
					</tfoot>
				</table>
			</div>
		</div>

		{!! Form::close() !!}

		@include('includes.form_errors')

	</div>


@stop

@section('script')

	<script>
		$(document).ready(function(){

			var count = 1;

			dynamic_field(count);

			function dynamic_field(number)
			{
				html = '<tr>';
				html += '<td><input type="text" name="monday[]" class="form-control" placeholder="Paper/Time" /></td>';
				html += '<td><input type="text" name="tuesday[]" class="form-control" placeholder="Paper/Time" /></td>';
				html += '<td><input type="text" name="wednesday[]" class="form-control" placeholder="Paper/Time" /></td>';
				html += '<td><input type="text" name="thursday[]" class="form-control" placeholder="Paper/Time" /></td>';
				html += '<td><input type="text" name="friday[]" class="form-control" placeholder="Paper/Time" /></td>';
				html += '<td><input type="text" name="saturday[]" class="form-control" placeholder="Paper/Time" /></td>';
				if(number > 1)
				{
					html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
					$('tbody').append(html);
				}
				else
				{
					html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
					$('tbody').html(html);
				}
			}

			$(document).on('click', '#add', function(){
				count++;
				dynamic_field(count);
			});

			$(document).on('click', '.remove', function(){
				count--;
				$(this).closest("tr").remove();
			});

			$('#datesheet').on('submit', function(event){
				event.preventDefault();
				$.ajax({
					url:'{{ route("datesheet.store") }}',
					method:'post',
					data:$(this).serialize(),
					dataType:'json',
					beforeSend:function(){
						$('#save').attr('disabled','disabled');
					},
					success:function(data)
					{
						if(data.error)
						{
							var error_html = '';
							for(var count = 0; count < data.error.length; count++)
							{
								error_html += '<p>'+data.error[count]+'</p>';
							}
							$('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
						}
						else
						{
							dynamic_field(1);
							$('#result').html('<div class="alert alert-success">'+data.success+'</div>');
						}
						$('#save').attr('disabled', false);
					}
				})
			});

		});
	</script>

@stop

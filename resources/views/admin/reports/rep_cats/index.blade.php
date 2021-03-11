@extends('layouts.dashboard')

@section('content')


	@include('includes.report_nav')


	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">
			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-list-alt"></i> Report Categories List</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				@permission('pdf.reports|view.reports|create.reports|delete.reports|edit.reports|print.reports|post.reports')
				<a href="{{route('admin.reports.index')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-list-alt fa-3x d-block pointer"></i>
						<span>Reports List</span>
					</div>
				</a>
				@endpermission
				<a href="{{route('admin.reports.rep_cats.index')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
						<i class="fas fa-list-alt fa-3x d-block pointer"></i>
						<span>Report Categories</span>
					</div>
				</a>
				@permission('create_subject.reports|delete_subject.reports|edit_subject.reports|
				add_marks.reports')
				<a href="{{route('subject_marks')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-book fa-3x d-block pointer"></i>
						<span>Subject Marks</span>
					</div>
				</a>
				@endpermission
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-4">

		@if ($message = Session::get('delete_report_cat'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('exist_cat'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('cat_removed'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		@if ($message = Session::get('create_report_cat'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('add_report_type'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		@if ($message = Session::get('update_report_cat'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@include('includes.form_errors')

		@permission('create.report_categories')
		<div class="mb-2">
			<a data-toggle="modal" data-target="#addNewCatModal">
				<button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Add New Category</button>
			</a>
		</div>
		@endpermission

		<div class="py-2">

			<table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr>
						<th class="text-center">Category Name</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
				@if($rep_cats)
					@foreach($rep_cats as $cat)
					<tr>
						<td class="text-center align-middle">{{$cat->name}}</td>
						<td class="text-center d-flex ml-5">
							@permission('edit.report_categories')
							<a data-toggle="modal" data-target="#editCatModal{{$cat->id}}">
								<button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
							</a>
							@endpermission
							@permission('delete.report_categories')
							<a data-toggle="modal" data-target="#deleteModal{{$cat->id}}">
								<button type="button" title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
							</a>
							@endpermission
							{!! Form::model($cat, ['method'=>'POST', 'action'=>['ReportCategoriesController@dmc_setup',  $cat->id]]) !!}
							<a>
								<button type="submit" title="Add" class="btn dusty-grass-gradient">
									<span class="fas fa-plus-square"></span>
								</button>
							</a>
							{!! Form::close() !!}
							{!! Form::model($cat, ['method'=>'DELETE', 'action'=>['ReportCategoriesController@remove_cat',  $cat->id]]) !!}
							<a>
								<button type="submit" title="Remove" class="btn ripe-malinka-gradient">
									<span class="fas fa-times-circle"></span>
								</button>
							</a>
							{!! Form::close() !!}
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			
		</div>
	</div>






	<!--Add CAT MODAL-->
	<div class="modal fade" id="addNewCatModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Add Report Category</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
					{!! Form::open(['method'=>'POST', 'action'=>'ReportCategoriesController@store', 'class' => 'mb-3']) !!}
						<div class="row">
							<div class="col-md-12">
								<div class="md-form md-outline">
									<label class="name">Category Name <span style="color: red">*</span></label>
									{!! Form::text('name', null, ['class'=>'form-control']) !!}
								</div>
								{!! Form::button(' Add Category', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>

@foreach($rep_cats as $cat)
	<div class="modal fade" id="deleteModal{{$cat->id}}">
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
							{!! Form::model($cat, ['method'=>'DELETE', 'action'=>['ReportCategoriesController@destroy',  $cat->id]]) !!}
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


@foreach($rep_cats as $cat)
	<!--EDIT CAT MODAL-->
	<div class="modal fade" id="editCatModal{{$cat->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Edit Report Cateogry</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
					{!! Form::model($cat, ['method'=>'PATCH', 'action'=>['ReportCategoriesController@update',  $cat->id]]) !!}
						<div class="row">
							<div class="col-md-12">
								<h3>Edit Report Category</h3>
								<div class="md-form md-outline">
									<label class="name">Category Name <span style="color: red">*</span></label>
									{!! Form::text('name', null, ['class'=>'form-control']) !!}
								</div>
								{!! Form::button(' Save Changes', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>
	@endforeach

@stop

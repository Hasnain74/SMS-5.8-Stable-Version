@extends('layouts.dashboard')

@section('content')

@include('includes.students_nav')

<!--HEADER-->
<header id="main-header" class="py-2 text-white">
	<div class="ml-4">

		<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
			<i class="fas fa-arrow-left"></i> Back
		</a>

		<div class="row">
			<div class="col-md-12 text-center">
				<h1><i class="fas fa-address-book"></i> Students List</h1>
			</div>
		</div>

		<div class="d-flex text-white text-center">
			@permission('delete.students_account|edit.students_account')
			<a href="{{route('admin.students.index')}}">
				<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
					<i class="fas fa-user-graduate fa-3x d-block pointer"></i>
					<span>Manage Students</span>
				</div>
			</a>
			<a href="{{route('student_accounts')}}">
				<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
					<i class="fas fa-user-circle fa-3x d-block pointer"></i>
					<span>Student Accounts</span>
				</div>
			</a>

			<a href="{{route('student_accounts')}}">
				<div class="active-item hover-effect port-item p-3 d-none d-md-block">
					<i class="fas fa-arrow-circle-right fa-3x d-block pointer"></i>
					<span>Students Promotion</span>
				</div>
			</a>
			@endpermission
		</div>

	</div>
</header>
</div>



<div class="mx-4 py-4">

    <div class="col-sm-12">

    <select name="from_class_id" class="browser-default custom-select bg-dark text-white" id="from_class_id">
        <option value="">From Class</option>
        @foreach ($classes as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    <select name="to_class_id" class="browser-default custom-select bg-dark text-white mt-2" >
        <option value="">To Class</option>
        @foreach ($classes as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    </div>



    <div class="col-sm-12">

    <table id="promotion_table" class="table table-striped table-bordered table-list-search mt-3 table-responsive-lg">
        <thead>
        <tr>
            <th class="align-middle text-center"><input type="checkbox" id="options"></th>
            <th class="text-center">Student ID</th>
            <th class="text-center">Student Name</th>
            <th class="text-center">Class</th>
        </tr>
        </thead>
    </table>
    <a id="save-student-promotion-btn" class="fas fa-folder-open btn peach-gradient float-right mb-4 mr-2">
        Save
    </a>

    </div>

</div>




@stop

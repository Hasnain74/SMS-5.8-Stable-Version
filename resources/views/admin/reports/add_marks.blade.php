@extends('layouts.dashboard')

@section('content')


    @include('includes.report_nav')

    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="{{route('subject_marks')}}" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">

                    <h1><i class="fas fa-pencil-alt"></i> Add Marks</h1>
                </div>
            </div>

        </div>
    </header>
    </div>


    <div class="mx-4 py-3">
        
         @include('includes.form_errors')

        <div class="filterable">

            @if ($message = Session::get('add_reports'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

            {!! Form::open(['method'=>'POST', 'action'=>'ReportController@store', 'class' => 'mb-5']) !!}

            <div class="md-form">
                <label class="">Teacher Name <span style="color: red">*</span></label>
                <input type="text" name="teacher_name" class="form-control" value="{{ $teacher }}" disabled>
                <input type="hidden" name="teacher_name" class="form-control" value="{{ $teacher }}">
            </div>

            <div class="md-form">
                <input type="date" name="date" class="form-control">
                <label class="mt-3">Date <span style="color: red">*</span></label>
            </div>

            <table id="table" class="table table-striped table-bordered table-responsive-lg">
                <thead id="theadcb">
                <tr class="filters">
                    <th class="text-center">Class</th>
                    <th class="text-center">Student ID</th>
                    <th class="text-center">Student Name</th>
                    <th class="text-center">Subject</th>
                    <th class="text-center">Report Type</th>
                    <th class="text-center">Total Marks</th>
                    <th class="text-center">Obtained Marks</th>
                    <th hidden class="text-center"></th>
                    <th hidden class="text-center"></th>
                    <th hidden class="text-center"></th>
                    <th hidden class="text-center"></th>
                    <th hidden class="text-center"></th>
                </tr>
                </thead>
                <tbody id="tbodycb">
                @if($result)
                    @foreach($result as $output)
                        <tr>
                            <td class="text-center align-middle"><input type="hidden" value="{{$output['class']}}" name="class[]">{{$output['class']}}</td>
                            <td class="text-center align-middle"><input type="hidden" value="{{$output['student_id']}}" name="student_id[]">{{$output['student_id']}}</td>
                            <td class="text-center align-middle"><input type="hidden" value="{{$output['student_name']}}" name="student_name[]">{{$output['student_name']}}</td>
                            <td class="text-center align-middle"><input type="hidden" value="{{$output['subject_name']}}" name="subject_name[]">{{$output['subject_name']}}</td>
                            <td class="text-center align-middle"><input type="hidden"><input hidden name="rep_cat_name[]" value="{{$output['rep_cat_name']}}" type="text" class="form-control">{{$output['rep_cat_name']}}</td>
                            <td class="text-center align-middle"><input type="hidden" value="{{$output['total_marks']}}" name="total_marks[]">{{$output['total_marks']}}</td>
                            <td class="text-center align-middle"><input type="hidden" value="0" class="cb1"><input value="0" name="obt_marks[]" type="number" max="{{$output['total_marks']}}" class="form-control"></td>
                            <td hidden><input type="hidden"><input hidden name="rep_cat_id[]" value="{{$output['rep_cat_id']}}" type="text" class="form-control">{{$output['rep_cat_id']}}</td>
                            <td hidden><input type="hidden"><input hidden name="class_id[]" value="{{$output['class_id']}}" type="text" class="form-control">{{$output['class_id']}}</td>
                            {{-- <td class="text-center align-middle"><input type="hidden"><input hidden name="report_type[]" value="{{$output['rep_cat_name']}}" type="text" class="form-control">{{$output['rep_cat_name']}}</td> --}}
                            <td hidden><input type="hidden"><input hidden name="id[]" value="{{$output['id']}}" type="text" class="form-control">{{$output['id']}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

            {!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn peach-gradient float-right mb-4']) !!}
            {!! Form::close() !!}

        </div>
    </div>


@stop

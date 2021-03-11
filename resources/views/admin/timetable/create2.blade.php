@extends('layouts.dashboard')

@section('content')

    @include('includes.timetable_nav')


    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="{{route('day_wise_timetable')}}" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">
                    <h1><i class="fas fa-clock"></i> Timetables</h1>
                </div>
            </div>

        </div>
    </header>
    </div>

    <div class="mx-4 py-4">


        <div class="table-responsive">
            {!! Form::open(['method'=>'POST', 'action'=>'TimetableController@store_day_wise_timetable',
            'files'=>true, 'class' => 'mb-5', 'id'=>'day_wise_timetable']) !!}
                <span id="result"></span>
            {!! Form::text('day', null, ['class'=>'form-control mb-3', 'placeholder' => 'Day Name']) !!}
                <table class="table table-bordered table-striped" id="user_table">
                    <thead>
                    <tr>
                        <th width="35%">Period</th>
                        <th width="35%">Period Timing</th>
                        <th width="35%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" align="right">&nbsp;</td>
                        <td>
                            @csrf
                            <input type="submit" name="save" id="save" class="btn peach-gradient" value="Save" />
                        </td>
                    </tr>
                    </tfoot>
                </table>
            {!! Form::close() !!}
        </div>

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
                html += '<td><input type="text" name="period[]" class="form-control" /></td>';
                html += '<td><input type="text" name="period_timing[]" class="form-control" /></td>';
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

            $('#day_wise_timetable').on('submit', function(event){
                event.preventDefault();
                $.ajax({
                    url:'{{ route("timetable.store_day_wise_timetable") }}',
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
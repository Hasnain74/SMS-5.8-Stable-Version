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
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
                            <i class="fas fa-file-invoice fa-3x d-block pointer"></i>
                            <span>Monthly Fee</span>
                        </div>
                    </a>

                    <a href="{{route('instalment_fee')}}">
                        <div class="active-item port-item p-3 d-none d-md-block">
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

        <div>
            <div class="mb-2">
                <a data-toggle="modal" data-target="#addFeeSetup">
                    <button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Create Fee Setup</button>
                </a>
            </div>
        </div>

        <div class="filterable mt-3">

            <table id="studentsData_fee_index" class="table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="text-center">Month</th>
                    <th class="text-alignment text-center">Fee Amount</th>
                    <th width="15%" class="align-middle text-center">Actions</th>
                </tr>
                </thead>

                <tbody>
                @if($fee_setup)
                    @foreach($fee_setup as $fee)
                        <tr>
                            <td class="text-center align-middle">{{$fee->month}}</td>
                            <td class="text-center align-middle">{{$fee->fee_amount}}</td>
                            <td class="text-center d-flex">
                                <a data-toggle="modal" data-target="#editFeeSetup{{$fee->id}}">
                                    <button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
                                </a>
                                <a data-toggle="modal" data-target="#deleteFeeSetup">
                                    <button title="View" class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>

            </table>

        </div>
    </div>


    <div class="modal fade" id="addFeeSetup">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Add New Setup</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="container">
                {!! Form::open(['method'=>'POST', 'action'=>'FeeController@storeFeeSetup', 'class' => 'mb-3']) !!}
                <!--CLASS DETAIL-->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="md-form md-outline">
                                {!! Form::date('month', null, ['class'=>'form-control']) !!}
                                <label class="control-label mt-2">Month <span style="color: red">*</span></label>
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Fee Amount <span style="color: red">*</span></label>
                                {!! Form::text('fee_amount', null, ['class'=>'form-control']) !!}
                            </div>
                            {!! Form::button(' Add Class', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>


    @foreach($fee_setup as $setup)
    <div class="modal fade" id="editFeeSetup{{$setup->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Add New Setup</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="container">
                {!! Form::model($setup, ['method'=>'POST', 'action'=>['FeeController@update_fee_setup', $setup->id], 'class' => 'mb-3']) !!}
                <!--CLASS DETAIL-->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="md-form md-outline">
                                <label class="control-label">Month <span style="color: red">*</span></label>
                                {!! Form::text('month', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Fee Amount <span style="color: red">*</span></label>
                                {!! Form::text('fee_amount', null, ['class'=>'form-control']) !!}
                            </div>
                            {!! Form::button(' Add Class', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
    @endforeach


    @foreach($fee_setup as $setup)
        <div class="modal fade" id="deleteFeeSetup">
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
                                {!! Form::model($setup, ['method'=>'POST', 'action'=>['FeeController@delete_fee_setup',  $setup->id]]) !!}
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

@stop

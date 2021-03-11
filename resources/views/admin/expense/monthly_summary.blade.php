@extends('layouts.dashboard')

@section('content')

    @include('includes.expense_nav')

    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="row">
                <div class="col-md-12 text-center">

                    <h1><i class="fas fa-file-alt"></i> Monthly Summary</h1>
                </div>
            </div>


            <div class="d-flex text-white text-center">
                <a href="{{route('admin.expense.index')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-money-check-alt fa-3x d-block pointer"></i>
                        <span>Fee Report</span>
                    </div>
                </a>
                <a href="{{route('salary_report')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-file-invoice-dollar fa-3x d-block pointer"></i>
                        <span>Salary Report</span>
                    </div>
                </a>
                <a href="{{route('expense_report')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-money-check-alt fa-3x d-block pointer"></i>
                        <span>Expense Report</span>
                    </div>
                </a>
                @permission('monthly_summary.expense')
                <a href="{{route('monthly_summary')}}">
                    <div class="active-item port-item p-3 d-none d-md-block">
                        <i class="fas fa-file-alt fa-3x d-block pointer"></i>
                        <span>Monthly Summary</span>
                    </div>
                </a>
                @endpermission
                @permission('yearly_summary.expense')
                <a href="{{route('yearly_summary')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-file-alt fa-3x d-block pointer"></i>
                        <span>Yearly Summary</span>
                    </div>
                </a>
                @endpermission
                @permission('total_summary.expense')
                <a href="{{route('total_summary')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-file-alt fa-3x d-block pointer"></i>
                        <span>Total Summary</span>
                    </div>
                </a>
                @endpermission
            </div>

        </div>
    </header>
    </div>

    <section class="mx-4 py-4">

        @permission('monthly_summary.expense')

        {!! Form::open(['method'=>'GET', 'action'=>'ExpenseController@print_multiple_months', 'target'=>'blank']) !!}

        <div class="t d-flex mb-3">
            <select hidden name="options" class="browser-default custom-select bg-dark text-white mt-2">
                <option value="print">Print Invoices</option>
            </select>
            <a><input value="Print" type="submit" name="Print" class="btn dusty-grass-gradient"></a>
        </div>

            <table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
                <thead>
                    <tr class="filters">
                        <th style="width: 2%;"><input type="checkbox" id="options"></th>
                        <th class="text-center">Date</th>
                        <th class="text-alignment text-center">Total Income</th>
                        <th width="15%" class="align-middle text-center">Total Expense</th>
                        <th width="15%" class="align-middle text-center">Total Cash In Hand</th>
                        <th width="15%" class="align-middle text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($result as $month => $entry)
                        <tr>
                            <td class="text-center align-middle"><input name="checkBoxArray[]" class="checkBoxes" type="checkbox" value="{{$month}}"></td>
                            <td class="text-center align-middle">{{ $month }}</td>
                            <td class="text-center align-middle">{{$entry['fee']}}</td>
                            <td class="text-center align-middle">{{$entry['salary'] + $entry['exp']}}</td>
                            <td class="text-center align-middle text-danger">{{$entry['fee'] - ($entry['salary'] + $entry['exp'])}}</td>
                            {!! Form::close() !!}
                            <td class="text-center">
                                <a target = "blank" href="{{route('print', $month)}}">
                                    <button type="button" title="Print" class="btn dusty-grass-gradient">
                                        <span class="fas fa-print"></span>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        @endpermission

        <button id="btn" title="Download Data" class="btn  bg-dark text-white float-right mt-4 mb-4">
            <span class="fas fa-cloud-download-alt"></span>  Export Data
        </button>

    </section>


@stop

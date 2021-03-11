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

                    <h1><i class="fas fa-file-alt"></i> Yearly Summary</h1>
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
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
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
                    <div class="active-item port-item p-3 d-none d-md-block">
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

        <div class="container">

            @permission('total_summary.expense')

            {!! Form::open(['method'=>'GET', 'action'=>'ExpenseController@print_daily_report', 'target'=>'blank']) !!}

            <div class="md-form">
                {{Form::date('date', null, ['class' => 'form-control'])}}
            </div>

            {!! Form::button(' Print Daily Report', ['type'=>'submit', 'class'=>'fas fa-save btn btn-block dusty-grass-gradient text-dark']) !!}

            {!! Form::close() !!}

            <div class="text-center mb-3">
                <a target="_blank" href="{{action('ExpenseController@pdf_total_summary')}}" class="btn dusty-grass-gradient btn-block mt-2">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Total Finance Overview</h2>

                </div>
                <div class="card-body">

                    <div class="table-responsive-sm">
                        <table class="export_table table table-striped">
                            @foreach($result as $r => $entry)
                            <tbody>
                            <tr>
                                <td class="center"></td>
                                <td class="left strong"><strong>Total Income</strong></td>
                                <td class="left"></td>
                                <td class="right"></td>
                                <td class="center"></td>
                                <td class="right"><strong>{{$entry['fee']}}</strong></td>
                            </tr>
                            <tr>
                                <td class="center"></td>
                                <td class="left strong"><strong>Total Expense</strong></td>
                                <td class="left"></td>
                                <td class="right"></td>
                                <td class="center"></td>
                                <td class="right"><strong>{{$entry['salary'] + $entry['exp']}}</strong></td>
                            </tr>
                            <tr>
                                <td class="center"></td>
                                <td class="left strong"><strong>Total Cash In Hand</strong></td>
                                <td class="left"></td>
                                <td class="right"></td>
                                <td class="center"></td>
                                <td class="right text-danger"><strong>{{$entry['fee'] - ($entry['salary'] + $entry['exp'])}}</strong></td>
                            </tr>
                            </tbody>
                                @endforeach
                        </table>
                    </div>

                </div>
            </div>
            @endpermission


            </div>

    </section>

@stop

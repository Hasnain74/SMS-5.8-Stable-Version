<!--Main Page-->
<div class="header-backgroud">
<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
    <div class="ml-2">
        @include('includes.school_profile')

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('admin.expense.index')}}" class="nav-link text-white">Fee Report</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('salary_report')}}" class="nav-link text-white">Salary Report</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('expense_report')}}" class="nav-link text-white">Expense Report</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('monthly_summary')}}" class="nav-link text-white">Monthly Summary</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('yearly_summary')}}" class="nav-link text-white">Yearly Summary</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('total_summary')}}" class="nav-link text-white">Total Summary</a>
                </li>
            </ul>
        </div>

    </div>

    <button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
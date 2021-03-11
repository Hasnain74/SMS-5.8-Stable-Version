<!--Main Page-->
<div class="header-backgroud">
<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
    <div class="ml-2">
        @include('includes.school_profile')

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                @permission('create.report_categories|delete.report_categories|edit.report_categories')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{{route('admin.reports.index')}}}" class="nav-link text-white">Reports List</a>
                </li>
                @endpermission
                @permission('create.report_categories|delete.report_categories|edit.report_categories')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{{route('admin.reports.rep_cats.index')}}}" class="nav-link text-white">Report Categories List</a>
                </li>
                @endpermission
               
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('subject_marks')}}" class="nav-link text-white">Subject Marks</a>
                </li>
            </ul>
        </div>

    </div>
    <button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
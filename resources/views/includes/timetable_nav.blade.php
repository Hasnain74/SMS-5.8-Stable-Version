<div class="header-backgroud">
    <nav class="navbar navbar-expand-md navbar-light" id="main-nav">
    <div class="ml-2">
        @include('includes.school_profile')

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('admin.timetable.index')}}" class="nav-link text-white">Timetables List</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('day_wise_timetable')}}" class="nav-link text-white">Daywise Timetable</a>
                </li>
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('admin.timetable.datesheet.index')}}" class="nav-link text-white">Datesheet</a>
                </li>
            </ul>
        </div>

    </div>
        <button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
</nav>
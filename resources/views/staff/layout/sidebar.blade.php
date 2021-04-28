<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    @if(!empty(auth()->guard('staff')->user()->business->logo_url))
        <img src="{{ auth()->guard('staff')->user()->business->logo_url }}" style="width: 50%;" class="mx-auto my-3" alt="" />
    @else
        <img src="{{ asset('img/logo.png') }}" style="width: 50%;" class="mx-auto my-3" alt="" />
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider my-3">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.calendar.index') }}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>@lang('messages.calendar')</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('staff.schedule.index') }}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>@lang('Schedules')</span>
        </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->

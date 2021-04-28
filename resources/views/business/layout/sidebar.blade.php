<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    @if(!empty(auth()->guard('business')->user()->business->logo_url))
        <img src="{{ auth()->guard('business')->user()->business->logo_url }}" style="width: 50%;" class="mx-auto my-3" alt="" />
    @else
        <img src="{{ asset('img/logo.png') }}" style="width: 50%;" class="mx-auto my-3" alt="" />
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider my-3">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('business.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if(auth()->guard('business')->user()->business()->exists())
        <!-- Nav Item - Business Information -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.business.edit', auth()->guard(App\Enums\GuardType::BUSINESS)->user()->business->id) }}">
                <i class="fas fa-fw fa-building"></i>
                <span>@lang('messages.business_information')</span>
            </a>
        </li>

        <!-- Nav Item - Business Schedule -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.businessSchedule.index') }}">
                <i class="fas fa-fw fa-calendar"></i>
                <span>@lang('messages.schedules')</span>
            </a>
        </li>

        <!-- Nav Item - Staff Members -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.businessStaffMember.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>@lang('messages.staffMembers')</span>
            </a>
        </li>

        <!-- Nav Item - Services -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.businessServiceCategory.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>@lang('messages.serviceCategories')</span>
            </a>
        </li>

        <!-- Nav Item - Services -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.businessService.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>@lang('messages.services')</span>
            </a>
        </li>

        <!-- Nav Item - Signup Form -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.businessServiceSignupForm.index') }}">
                <i class="fas fa-fw fa-pager"></i>
                <span>@lang('messages.signUpForms')</span>
            </a>
        </li>

        <!-- Nav Item - Calendar -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('business.calendar.index') }}">
                <i class="fas fa-fw fa-calendar"></i>
                <span>@lang('messages.calendar')</span>
            </a>
        </li>
    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->

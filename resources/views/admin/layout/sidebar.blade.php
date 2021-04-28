<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <img src="{{ asset('img/logo.png') }}" style="width: 50%;" class="mx-auto my-3" />

    <!-- Divider -->
    <hr class="sidebar-divider my-3">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @can('user-list', App\Enums\GuardType::ADMIN)
    <!-- Nav Item - Admin users -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.user.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>@lang('messages.users')</span>
        </a>
    </li>
    @endcan

    @can('user-role-list', App\Enums\GuardType::ADMIN)
    <!-- Nav Item - Admin Users Roles -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.role.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>@lang('messages.userRoles')</span>
        </a>
    </li>
    @endcan

    @can('business-list', App\Enums\GuardType::ADMIN)
    <!-- Nav Item - Business Roles -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.businessOwner.index') }}">
            <i class="fas fa-fw fa-broadcast-tower"></i>
            <span>@lang('messages.businesses')</span>
        </a>
    </li>
    @endcan

    @can('business-plan-list', App\Enums\GuardType::ADMIN)
    <!-- Nav Item - Business Roles -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.businessPlan.index') }}">
            <i class="fas fa-fw fa-broadcast-tower"></i>
            <span>@lang('messages.businessPlans')</span>
        </a>
    </li>
    @endcan

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->

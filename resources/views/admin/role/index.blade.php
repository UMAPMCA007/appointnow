@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.userRoles'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.userRoles')
        </h1>
        <div class="btn-group">
            @can('user-role-create', App\Enums\GuardType::ADMIN)
                <a href="{{ route('admin.role.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle fa-sm fa-fw text-white-50"></i> @lang('messages.create') @lang('messages.userRole')
                </a>
            @endcan
        </div>
    </div>
    <!-- End Page Heading -->

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead>
                    <tr>
                        <th>@lang('messages.name')</th>
                        <td>@lang('messages.actions')</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userRoles as $userRole)
                        <tr>
                            <td>{{ $userRole->name }}</td>
                            <td>
                                <div class="btn-group btn-block">
                                    @can('user-role-edit', App\Enums\GuardType::ADMIN)
                                        <a href="{{ route('admin.role.edit', $userRole->id) }}" class="btn btn-primary" title="@lang('messages.edit')">
                                            <i class="fa fa-edit fa-fw"></i> @lang('messages.edit')
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Table -->
@endsection

@section('javascript')
    <script>
        $(document).ready(function(){
            $("#datatable").dataTable();
        });
    </script>
@endsection

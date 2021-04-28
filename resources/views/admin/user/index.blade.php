@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.users'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.users')
        </h1>
        <div class="btn-group">
            @can('user-create', App\Enums\GuardType::ADMIN)
                <a href="{{ route('admin.user.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle fa-sm fa-fw text-white-50"></i> @lang('messages.create') @lang('messages.user')
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
                            <th>Email</th>
                            <th>@lang('messages.status')</th>
                            <th>@lang('messages.created_at')</th>
                            <td>@lang('messages.actions')</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="text-center">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status == App\Enums\StatusType::ACTIVE)
                                    <span class="badge badge-primary">@lang('status.'.$user->status)</span>
                                @else
                                    <span class="badge badge-danger">@lang('status.'.$user->status)</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <div class="btn-group btn-block">
                                    @can('user-edit', App\Enums\GuardType::ADMIN)
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary" title="@lang('messages.edit')">
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

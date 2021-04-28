@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.staffMembers'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.staffMembers')
        </h1>
        <div class="btn-group">
            <a href="{{ route('business.businessStaffMember.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle fa-sm fa-fw text-white-50"></i> @lang('messages.create') @lang('messages.staffMember')
            </a>
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
                    @foreach($staffMembers as $staffMember)
                        <tr>
                            <td>{{ $staffMember->name }}</td>
                            <td>{{ $staffMember->email }}</td>
                            <td>
                                @if($staffMember->status == App\Enums\StatusType::ACTIVE)
                                    <span class="badge badge-primary">@lang('status.'.$staffMember->status)</span>
                                @else
                                    <span class="badge badge-danger">@lang('status.'.$staffMember->status)</span>
                                @endif
                            </td>
                            <td>{{ $staffMember->created_at }}</td>
                            <td>
                                <div class="btn-group btn-block">
                                    <a href="{{ route('business.businessStaffMember.edit', $staffMember->id) }}" class="btn btn-primary" title="@lang('messages.edit')">
                                        <i class="fa fa-edit fa-fw"></i> @lang('messages.edit')
                                    </a>
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

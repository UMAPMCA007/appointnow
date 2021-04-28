@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.businesses'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-broadcast-tower"></i> @lang('messages.businesses')
        </h1>
        <div class="btn-group">
            @can('business-create', App\Enums\GuardType::ADMIN)
                <a href="{{ route('admin.businessOwner.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle fa-sm fa-fw text-white-50"></i> @lang('messages.create') @lang('messages.businessOwner')
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
                        <th>@lang('messages.businessName')</th>
                        <td>@lang('messages.actions')</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($businessOwners as $businessOwner)
                        <tr>
                            <td>{{ $businessOwner->name }}</td>
                            <td>{{ $businessOwner->email }}</td>
                            <td>
                                @if($businessOwner->status == App\Enums\StatusType::ACTIVE)
                                    <span class="badge badge-primary">@lang('status.'.$businessOwner->status)</span>
                                @else
                                    <span class="badge badge-danger">@lang('status.'.$businessOwner->status)</span>
                                @endif
                            </td>
                            <td>{{ $businessOwner->business->name ?? trans('messages.notSet') }}</td>
                            <td>
                                <a href="{{ route('admin.businessOwner.edit', $businessOwner->id) }}" class="btn btn-block btn-primary @if(!$businessOwner->business()->exists()) disabledN @endif">
                                    <i class="fa fa-edit fa-fw"></i> @lang('messages.edit') @lang('messages.business')
                                </a>
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

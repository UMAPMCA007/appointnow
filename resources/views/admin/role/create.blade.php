@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.create').' '.trans('messages.userRole'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.create') @lang('messages.userRole')
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('admin.role.store') }}" method="post" autocomplete="off" id="form">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.role.index') }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.userRole')
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-group">
                            @foreach ($errors->all() as $error)
                                <li class="list-group-item">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">@lang('messages.name') *</label>
                    <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') }}" placeholder="@lang('messages.name')...">
                </div>
                <div class="form-group row">
                    @foreach($permissions as $permission)
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}">
                                <label class="custom-control-label" for="permission-{{ $permission->id }}">@lang('permission.'.$permission->name)</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection

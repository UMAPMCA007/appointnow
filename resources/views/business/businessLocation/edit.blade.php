@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.location'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.edit') @lang('messages.location') - {{ $businessLocation->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('business.businessLocation.update', $businessLocation->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method('PATCH')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('business.business.edit', auth()->guard('business')->user()->business->id) }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.location')
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
                @if(session('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('messages.name') *</label>
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $businessLocation->name }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="address">@lang('messages.address') *</label>
                            <input type="text" id="address" name="address" maxlength="500" required class="form-control" value="{{ old('address') ?? $businessLocation->address }}" placeholder="@lang('messages.address')...">
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="latitude">@lang('messages.latitude')</label>
                                <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude') ?? $businessLocation->latitude }}" placeholder="@lang('messages.latitude')...">
                            </div>
                            <div class="col-6">
                                <label for="longitude">@lang('messages.longitude')</label>
                                <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude') ?? $businessLocation->longitude }}" placeholder="@lang('messages.longitude')...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">@lang('messages.code')</label>
                            <input type="text" id="code" name="code" maxlength="10" class="form-control" required value="{{ old('code') ?? $businessLocation->code }}" placeholder="@lang('messages.code')...">
                        </div>
                        <div class="form-group">
                            <label for="phone">@lang('messages.phone')</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') ?? $businessLocation->phone }}" placeholder="@lang('messages.phone')...">
                        </div>
                        <div class="form-group">
                            <label for="status">@lang('messages.status') *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status || $businessLocation->status == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script>
        $(document).ready(function(){
            $("#form").submit(function(){
                Swal.fire({
                    title: "@lang('messages.pleaseWait')",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });
    </script>
@endsection

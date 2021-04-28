@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.staffMember'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.edit') @lang('messages.staffMember') - {{ $businessStaffMember->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('business.businessStaffMember.update', $businessStaffMember->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method('PATCH')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('business.businessStaffMember.index') }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.staffMember')
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $businessStaffMember->name }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" maxlength="255" required class="form-control" value="{{ old('email') ?? $businessStaffMember->email }}" placeholder="Email...">
                        </div>
                        <div class="form-group">
                            <label for="code">@lang('messages.code')</label>
                            <input type="text" id="code" name="code" maxlength="10" class="form-control" required value="{{ old('code') ?? $businessStaffMember->code }}" placeholder="@lang('messages.code')...">
                        </div>
                        <div class="form-group">
                            <label for="timezone">@lang('messages.timezone')</label>
                            <select class="form-control" id="timezone" name="timezone" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->region }}" @if(old('timezone') == $timezone->region || $businessStaffMember->timezone == $timezone->region) selected @endif>{{ $timezone->region }} {{ $timezone->timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">@lang('messages.status') *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status || $businessStaffMember->status == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('messages.password') *</label>
                            <input type="password" id="password" name="password" class="form-control" value="" placeholder="@lang('messages.password')..." minlength="8" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">@lang('messages.confirmPassword') *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" value="" placeholder="@lang('messages.confirmPassword')..." minlength="8" maxlength="255">
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
            $("#timezone").select2({
                theme: 'bootstrap4'
            });
            $("#password").focusout(function(){
                const isRequired = !(this.value == null || this.value.length === 0 || /^\s+$/.test(this.value));
                $("#password_confirmation").prop("required", isRequired);
            });
        });
    </script>
@endsection

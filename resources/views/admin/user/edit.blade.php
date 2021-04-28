@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.user'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.edit') @lang('messages.user') - {{ $user->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('admin.user.update', $user->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method('PATCH')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.user')
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $user->name }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" maxlength="255" required class="form-control" value="{{ old('email') ?? $user->email }}" placeholder="Email...">
                        </div>
                        <div class="form-group">
                            <label for="code">@lang('messages.code')</label>
                            <input type="text" id="code" name="code" maxlength="10" class="form-control" value="{{ old('code') ?? $user->code }}" placeholder="@lang('messages.code')...">
                        </div>
                        <div class="form-group">
                            <label for="language">@lang('messages.language')</label>
                            <select class="form-control" id="language" name="language" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language }}" @if(old('language') == $language || $user->language == $language) selected @endif>@lang('language.'.$language)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="created_at">@lang('messages.created_at')</label>
                            <input type="text" id="created_at" class="form-control" value="{{ $user->created_at }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="updated_at">@lang('messages.updated_at')</label>
                            <input type="text" id="updated_at" class="form-control" value="{{ $user->updated_at }}" readonly>
                        </div>
                        @if(!empty($user->deleted_at))
                            <div class="form-group">
                                <label for="deleted_at">@lang('messages.deleted_at')</label>
                                <input type="text" id="deleted_at" class="form-control" value="{{ $user->deleted_at }}" readonly>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">@lang('messages.status')</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status || $user->status == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timezone">@lang('messages.timezone')</label>
                            <select class="form-control" id="timezone" name="timezone" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->region }}" @if(old('timezone') == $timezone->region || $user->timezone == $timezone->region) selected @endif>{{ $timezone->region }} {{ $timezone->timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('messages.password')</label>
                            <input type="password" id="password" name="password" class="form-control" value="" placeholder="@lang('messages.password')..." minlength="8" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">@lang('messages.confirmPassword')</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" value="" placeholder="@lang('messages.confirmPassword')..." minlength="8" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="user_role">@lang('messages.userRole')</label>
                            <select id="user_role" name="user_role" class="form-control" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @if($userRole == $role || old('user_role') == $role) selected @endif>{{ $role }}</option>
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

            $("#timezone").select2({
                theme: 'bootstrap4'
            });

            $("#password").focusout(function(){
                const isRequired = !(this.value == null || this.value.length === 0 || /^\s+$/.test(this.value));
                $("#password_confirmation").prop("required", isRequired);
            });

            $("#language").select2({
                theme: 'bootstrap4'
            });

            $("#user_role").select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection

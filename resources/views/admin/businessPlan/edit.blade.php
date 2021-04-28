@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.businessPlan'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.edit') @lang('messages.businessPlan') - {{ $businessPlan->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('admin.businessPlan.update', $businessPlan->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method('PATCH')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.businessPlan.index') }}" class="btn btn-warning">
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $businessPlan->name }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="code">@lang('messages.code')</label>
                            <input type="text" id="code" name="code" maxlength="10" class="form-control" required value="{{ old('code') ?? $businessPlan->code }}" placeholder="@lang('messages.code')...">
                        </div>
                        <div class="form-group">
                            <label for="staff_member_limit">@lang('messages.staffMemberLimit')</label>
                            <input type="number" id="staff_member_limit" name="staff_member_limit" class="form-control" required min="1" value="{{ old('staff_member_limit') ?? $businessPlan->staff_member_limit }}" placeholder="@lang('messages.staffMemberLimit')...">
                        </div>
                        <div class="form-group">
                            <label for="location_limit">@lang('messages.locationLimit')</label>
                            <input type="number" id="location_limit" name="location_limit" class="form-control" required min="1" value="{{ old('location_limit') ?? $businessPlan->location_limit }}" placeholder="@lang('messages.locationLimit')...">
                        </div>
                        <div class="form-group">
                            <label for="categories_limit">@lang('messages.categoriesLimit')</label>
                            <input type="number" id="categories_limit" name="categories_limit" class="form-control" required min="1" value="{{ old("categories_limit") ?? $businessPlan->categories_limit }}" placeholder="@lang('messages.categoriesLimit')...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">@lang('messages.status') *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status || $businessPlan->status == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="can_add_staff_members">@lang('messages.can_add_staff_members') *</label>
                            <select id="can_add_staff_members" name="can_add_staff_members" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($booleanTypes as $boolean)
                                    <option value="{{ $boolean }}" @if(old('can_add_staff_members') == $boolean || $businessPlan->can_add_staff_members == $boolean) selected @endif>@lang('messages.'.$boolean)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="services_limit">@lang('messages.servicesLimit')</label>
                            <input type="number" id="services_limit" name="services_limit" class="form-control" required min="1" value="{{ old("services_limit") ?? $businessPlan->services_limit }}" placeholder="@lang('messages.servicesLimit')...">
                        </div>
                        <div class="form-group">
                            <label for="signup_form_limit">@lang('messages.signupFormLimit')</label>
                            <input type="number" id="signup_form_limit" name="signup_form_limit" class="form-control" required min="1" value="{{ old('signup_form_limit') ?? $businessPlan->signup_form_limit }}" placeholder="@lang('messages.signupFormLimit')...">
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

@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.create').' '.trans('messages.service'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-users"></i> @lang('messages.create') @lang('messages.service')
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('business.businessService.store') }}" method="post" autocomplete="off" id="form" enctype="multipart/form-data">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('business.businessService.index') }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.service')
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('messages.name') *</label>
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('messages.description') *</label>
                            <input type="text" id="description" name="description" maxlength="500" required class="form-control" value="{{ old('description') }}" placeholder="@lang('messages.description')...">
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="price">@lang('messages.price')</label>
                                <input type="number" id="price" name="price" class="form-control" required value="{{ old('price') ?? 0 }}" min="0" placeholder="@lang('messages.price')..." />
                            </div>
                            <div class="col-6">
                                <label for="currency">@lang('messages.currency')</label>
                                <select id="currency" name="currency" class="form-control" required style="width: 100%;">
                                    <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency }}" @if($currency == old('$currency')) selected @endif>{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="code">@lang('messages.code')</label>
                            <input type="text" id="code" name="code" maxlength="10" class="form-control" value="{{ old('code') }}" placeholder="@lang('messages.code')...">
                        </div>
                        <div class="form-group">
                            <label for="status">@lang('messages.status') *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="signup_form_id">@lang('messages.signUpForm')</label>
                            <select id="signup_form_id" name="signup_form_id" class="form-control" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($signupForms as $signupForm)
                                    <option value="{{ $signupForm->id }}" @if(old("signup_form_id") == $signupForm->id) selected @endif>{{ $signupForm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">@lang('messages.image')</label>
                            <button type="button" id="btnImage" class="btn btn-primary btn-block">
                                <i class="fa fa-picture-o fa-fw"></i> @lang('messages.add') @lang('messages.image')
                            </button>
                            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                            <br>
                            <img src="{{ asset('images/addimage.png') }}" id="preview" class="img-fluid img-thumbnail" alt="Add image" />
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

            $("#currency").select2({
                theme: 'bootstrap4'
            });

            $("#signup_form_id").select2({
                theme: 'bootstrap4'
            });

            $("#btnImage").click(function(){
                document.getElementById("image").click();
            });

            $("#preview").click(function(){
                document.getElementById("image").click();
            });

            $("#image").change(function(){
                let file    = document.getElementById('image').files[0];
                let preview = document.getElementById('preview');
                let reader  = new FileReader();

                reader.onloadend = function() {
                    preview.src = reader.result;
                };

                if (file) reader.readAsDataURL(file);
            });
        });
    </script>
@endsection

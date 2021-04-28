@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.business'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-building"></i> @lang('messages.edit') @lang('messages.business') - {{ $business->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('admin.business.update', $business->id) }}" method="post" autocomplete="off" id="form" enctype="multipart/form-data">
        @csrf
        @method("PATCH")
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.businessOwner.edit', $business->business_owner_id) }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.business')
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $business->name }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="timezone">@lang('messages.timezone')</label>
                            <select class="form-control" id="timezone" name="timezone" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->region }}" @if(old('timezone') == $timezone->region || $business->timezone == $timezone->region) selected @endif>{{ $timezone->region }} {{ $timezone->timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="language">@lang('messages.language')</label>
                            <select class="form-control" id="language" name="language" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language }}" @if(old('language') == $language || $business->language == $language) selected @endif>@lang('language.'.$language)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('messages.description')</label>
                            <textarea id="description" name="description" class="form-control" rows="4" style="resize: none">{{ old('description') ?? $business->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <button type="button" id="btnLogo" class="btn btn-primary btn-block">
                                <i class="fa fa-picture-o fa-fw"></i> @lang('messages.add') Logo
                            </button>
                            <input type="file" id="logo" name="logo" accept="image/*" style="display: none;">
                            <br>
                            @if(!empty($business->logo_url))
                                <img src="{{ $business->logo_url }}" id="preview" class="img-fluid img-thumbnail" alt="Add image" />
                            @else
                                <img src="{{ asset('images/addimage.png') }}" id="preview" class="img-fluid img-thumbnail" alt="Add image" />
                            @endif
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

            $("#language").select2({
                theme: 'bootstrap4'
            });

            $("#btnLogo").click(function(){
                document.getElementById("logo").click();
            });

            $("#preview").click(function(){
                document.getElementById("logo").click();
            });

            $("#logo").change(function(){
                let file    = document.getElementById('logo').files[0];
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

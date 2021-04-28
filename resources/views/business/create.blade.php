@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.create').' '.trans('messages.business'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-building"></i> @lang('messages.create') @lang('messages.business')
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('business.business.store') }}" method="post" autocomplete="off" id="form" enctype="multipart/form-data">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('messages.name') *</label>
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') }}" placeholder="@lang('messages.name')...">
                        </div>
                        <div class="form-group">
                            <label for="timezone">@lang('messages.timezone')</label>
                            <select class="form-control" id="timezone" name="timezone" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->region }}" @if(old('timezone') == $timezone->region) selected @endif>{{ $timezone->region }} {{ $timezone->timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="language">@lang('messages.language')</label>
                            <select class="form-control" id="language" name="language" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language }}" @if(old('language') == $language) selected @endif>@lang('language.'.$language)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('messages.description')</label>
                            <textarea id="description" name="description" class="form-control" rows="4" style="resize: none">{{ old('description') }}</textarea>
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

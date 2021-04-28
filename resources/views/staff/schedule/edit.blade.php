@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.schedule'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-calendar"></i> @lang('messages.edit') @lang('messages.schedule') - {{ $staffSchedule->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('staff.schedule.update', $staffSchedule->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method('PATCH')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('staff.schedule.index') }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-arrow-circle-left"></i> @lang('messages.cancel')
                        </a>
                            <button type="button" class="btn btn-success" id="btnSetDefault" @if($staffSchedule->is_default == "yes") disabled @endif>
                            @if($staffSchedule->is_default == "no")
                                <i class="fa fa-fw fa-star"></i> @lang('messages.setAsDefault')
                            @else
                                <i class="fa fa-fw fa-check"></i> @lang('messages.default')
                            @endif
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-fw fa-save"></i> @lang('messages.save') @lang('messages.schedule')
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $staffSchedule->name }}" placeholder="@lang('messages.name')...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="timezone">@lang('messages.timezone')</label>
                            <select id="timezone" name="timezone" class="form-control" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->region }}" @if(old('timezone') == $timezone->region || $staffSchedule->timezone == $timezone->region) selected @endif>{{ $timezone->region }} {{ $timezone->timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form id="setDefault" action="{{ route('staff.schedule.makeDefault', $staffSchedule->id) }}" method="post">
        @csrf
    </form>

    <!-- Calendar -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6">

                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover" id="datatable">
                <thead>
                    <tr>
                        <td style="width: 5%;">@lang('messages.status')</td>
                        <th>@lang('messages.day')</th>
                        <th>@lang('messages.hours')</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach( $staffSchedule->days as $day)
                        <tr>
                            <td>

                                <form action="{{ route('staff.schedule.changeDayStatus', $day->id) }}" method="post" id="status-{{ $day->id }}">
                                    @csrf
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="day-{{ $day->id }}" onchange="changeStatus({{ $day->id }})" @if($day->status == "active") checked @endif>
                                        <label class="custom-control-label" for="day-{{ $day->id }}"></label>
                                    </div>
                                </form>
                            </td>
                            <td>@lang('weekdays.'.$day->day)</td>
                            <td>
                                <form action="{{route('staff.schedule.AddHour',$day->id)}}" method="post"  id="formHours-{{ $day->id }}"  >
                                    @csrf
                                    <input type="hidden"  value="{{$day->id}}" name="staff_schedule_day_id" required>
                                </form>

                            </td>


                            <td>
                                <form action="{{ route('staff.schedule.updateHour',$day->id)}}" method="post" id="form2" >
                                    @csrf
                                    @method('patch')
                                    <input type="hidden"  value="{{$day->id}}" name="staff_schedule_day_id" required>
                                    @foreach($staffSchedulesDayHour as $hour)
                                        @if($hour->staff_schedule_day_id==$day->id)

                                                 @foreach($hour->start_time as $s_t)
                                                <div class="input-group mb-3" >
                                                    <input type="time" class="form-control"  value="{{$s_t}}"  name='start_time[]' required>
                                                </div>
                                                 @endforeach
                                                    @foreach($hour->end_time as $e_t)
                                                    <div class="input-group mb-3" >
                                                     <input type="time" class="form-control"  value="{{$e_t}}"  name='end_time[]' required>
                                                    </div>
                                                    @endforeach


                                        @endif

                                    @endforeach
                                </form>



                            <td>
                                 @if($day->status=='active')
                                    <button class="btn btn-success" type="button" onclick="addHour({{ $day->id }})">
                                                <i class="fa fa-plus-circle fa-fw"></i>
                                    </button>
                                 @endif

                            </td>
                      @endforeach
                        </tr>
                </tbody>
            </table>
            <div class="col-md-8 offset-md-5 ">
                <button type="submit"  form="form2" class="btn btn-success col-offset" >Update</button>

            </div>
        </div>
    </div>
@endsection

@section('javascript')
            <script>
                function changeStatus(day_id)
                {
                    Swal.fire({
                        title: "@lang('messages.pleaseWait')",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onOpen: () => {
                            Swal.showLoading();
                            document.getElementById(`status-${day_id}`).submit();
                        }
                    });
                }

                function makeId(length)
                {
                    var result           = '';
                    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    var charactersLength = characters.length;
                    for (var i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    return `field-option-${result}`;
                }



                function addHour(day_id)
                {
                    const id = `hour-${makeId(5)}`;
                    let html = "";
                    html += `<div class="input-group" id="${id}">`;
                    html += `<input type="time" class="form-control " value="" name="start_time[]"  required>`;
                    html += `<input type="time" class="form-control " value="" name="end_time[]"  required>`;
                    html += `<div class="input-group-append">`;
                    html += `<button class="btn btn-success"  type="submit">`;
                    html += `Submit`;
                    html += `</button>`;
                    html += `<button class="btn btn-danger" type="button" onclick="removeHour('${id}')">`;
                    html += `<i class="fa fa-trash fa-fw"></i>`;
                    html += `</button>`;
                    html += `</div>`;
                    html += `</div>`;

                    $(`#formHours-${day_id}`).append(html);
                }
                function removeHour(id)
                {
                    $(`#${id}`).remove();
                }

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

                    $("#btnSetDefault").click(function(){
                        Swal.fire({
                            title: "@lang('messages.pleaseWait')",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                Swal.showLoading();
                                document.getElementById("setDefault").submit();
                            }
                        });
                    });
                });
            </script>

@endsection


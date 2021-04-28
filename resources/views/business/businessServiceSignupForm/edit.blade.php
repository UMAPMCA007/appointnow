@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.edit').' '.trans('messages.signUpForm'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-pager"></i> @lang('messages.edit') @lang('messages.signUpForm') - {{ $businessServiceSignupForm->name }}
        </h1>
    </div>
    <!-- End Page Heading -->

    <form action="{{ route('business.businessServiceSignupForm.update', $businessServiceSignupForm->id) }}" method="post" autocomplete="off" id="form">
        @csrf
        @method("PATCH")
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('business.businessServiceSignupForm.index') }}" class="btn btn-warning">
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
                            <input type="text" id="name" name="name" required autofocus maxlength="255" class="form-control" value="{{ old('name') ?? $businessServiceSignupForm->name }}" placeholder="@lang('messages.name')...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">@lang('messages.status') *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($statusTypes as $status)
                                    <option value="{{ $status }}" @if(old('status') == $status || $businessServiceSignupForm->status == $status) selected @endif>@lang('status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right" id="btnModalField">
                        <i class="fas fa-plus-circle fa-sm fa-fw text-white-50"></i> @lang('messages.create') @lang('messages.field')
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tableQuestions">
                    <thead>
                        <tr>
                            <th>@lang('messages.field')</th>
                            <th>@lang('messages.type')</th>
                            <th>@lang('messages.isRequired')</th>
                            <th>@lang('messages.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($businessServiceSignupForm->fields as $field)
                        <tr>
                            <td>{{ $field->description }}</td>
                            <td style="width: 25%;">@lang('fieldTypes.'.$field->type)</td>
                            <td style="width: 25%;">@lang('messages.'.$field->is_required)</td>
                            <td style="width: 25%;">
                                <form action="{{ route('business.businessServiceSignupForm.deleteField', $field->id) }}" method="post" id="formDeleteField-{{ $field->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="btn-group btn-block">
                                        <button type="button" class="btn btn-primary" onclick="openModal('{{ $field }}')">
                                            <i class="fa fa-edit fa-fw"></i> @lang('messages.edit')
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="deleteField({{ $field->id }})">
                                            <i class="fa fa-trash fa-fw"></i> @lang('messages.delete')
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{ route('business.businessServiceSignupForm.field') }}" autocomplete="off" method="post" id="formField">
        @csrf
        <input type="hidden" id="fieldMethod" name="fieldMethod" value="create" />
        <input type="hidden" id="signup_form_id" name="signup_form_id" value="{{ $businessServiceSignupForm->id }}" />
        <input type="hidden" id="business_service_signup_form_field_id" name="business_service_signup_form_field_id" value="">
        <!-- The Modal -->
        <div class="modal fade" id="modalField">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalFieldTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="description">@lang('messages.description')</label>
                            <input type="text" id="description" name="description" class="form-control" required min="255" value="" placeholder="@lang('messages.description')...">
                        </div>
                        <div class="form-group">
                            <label for="type">@lang('messages.type')</label>
                            <select id="type" name="type" class="form-control" required style="width: 100%;">
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                @foreach($fieldTypes as $fieldType)
                                    <option value="{{ $fieldType }}">@lang('fieldTypes.'.$fieldType)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="is_required">@lang('messages.isRequired')</label>
                            <select id="is_required" name="is_required" class="form-control" required>
                                <option value="" disabled selected hidden>@lang('messages.chooseItem')</option>
                                <option value="yes">@lang('messages.yes')</option>
                                <option value="no">@lang('messages.no')</option>
                            </select>
                        </div>
                        <div id="divFieldOptions" style="display: none;">
                            <hr>
                            <div id="inputFieldOptions"></div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-block" id="btnAddFieldOption">
                                    @lang('messages.add') @lang('messages.option')
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnCloseModalField">@lang('messages.close')</button>
                        <button type="submit" class="btn btn-success">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script>
        function openModal(item = null) {
            item = JSON.parse(item);

            if (item == null)
            {
                $("#modalFieldTitle").text("@lang('messages.create') @lang('messages.field')");
                closeModal();
            }
            else
            {
                $("#modalFieldTitle").text("@lang('messages.edit') @lang('messages.field')");
                document.getElementById("description").value = item.description;
                $("#type").val(item.type).trigger("change");
                $("#is_required").val(item.is_required).trigger("change");
                document.getElementById("fieldMethod").value = "update";
                var options = ['checkbox', 'select', 'radio'];
                document.getElementById("divFieldOptions").style.display = (options.includes(item.type)) ? "block": "none";
                document.getElementById("business_service_signup_form_field_id").value = item.id;

                if (item.options_fields != null) {
                    var html = ``;
                    item.options_fields.forEach(option => {
                        let id = makeId(5);
                        html += `<div class="form-group row" id="${id}">`;
                        html += `<div class="col-8">`;
                        html += `<input type="text" name="options[]" value="${option}" class="form-control" required placeholder="@lang('messages.option')..." />`;
                        html += `</div>`;
                        html += `<div class="col-4">`;
                        html += `<button type="button" class="btn btn-danger btn-block" onclick="deleteFieldOption('${id}')"><i class="fa fa-trash fa-fw"></i></button>`;
                        html += `</div>`;
                        html += `</div>`;
                    });
                }

                $("#inputFieldOptions").append(html);
            }

            $("#modalField").modal({
                backdrop: 'static'
            })
        }

        function closeModal()
        {
            document.getElementById("description").value = "";
            $("#type").val("").trigger("change");
            $("#is_required").val("").trigger("change");
            document.getElementById("fieldMethod").value = "create";
            document.getElementById("divFieldOptions").style.display = "none";
            document.getElementById("inputFieldOptions").innerHTML = "";
            document.getElementById("business_service_signup_form_field_id").value = "";
        }

        function deleteField(id)
        {
            Swal
                .fire({
                    title: "@lang('messages.confirmItemDeletion')",
                    icon: 'question',
                    showCancelButton: true,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    confirmButtonText: "@lang('messages.yes')",
                    cancelButtonText: "No",
                    reverseButtons: true
                })
                .then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: "@lang('messages.pleaseWait')",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                Swal.showLoading();
                                document.getElementById(`formDeleteField-${id}`).submit();
                            }
                        });
                    }
                });
        }

        function deleteFieldOption(id)
        {
            $("#"+id).remove();
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

            $("#formField").submit(function(){
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

            $("#tableQuestions").dataTable();

            $("#type").select2({
                theme: 'bootstrap4'
            });

            $("#btnModalField").click(function(){
                openModal();
            });

            $("#btnCloseModalField").click(function(){
                closeModal();
            });

            $('#type').change(function(){
                var options = ['checkbox', 'select', 'radio'];
                document.getElementById("divFieldOptions").style.display = (options.includes(this.value)) ? "block": "none";
            });

            $("#btnAddFieldOption").click(function(){
                const id = makeId(5);
                var html = `<div class="form-group row" id="${id}">`;
                html += `<div class="col-8">`;
                html += `<input type="text" name="options[]" value="" class="form-control" required placeholder="@lang('messages.option')..." />`;
                html += `</div>`;
                html += `<div class="col-4">`;
                html += `<button type="button" class="btn btn-danger btn-block" onclick="deleteFieldOption('${id}')"><i class="fa fa-trash fa-fw"></i></button>`;
                html += `</div>`;
                html += `</div>`;
                $("#inputFieldOptions").append(html);
            });
        });
    </script>
@endsection

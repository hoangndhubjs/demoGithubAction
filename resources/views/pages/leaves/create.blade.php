<style>
    .datepicker table tr td.disabled {
        background: #00000000;
        opacity: .35;
    }
    .datepicker table tr td.disabled:hover {
        background: #ffffff;
        color: #3F4254;
        cursor: no-drop;
    }
</style>
@if(!empty($leaveTypes))
<form method="POST" action="{{ route('leaves.ajax.create_leave') }}" id="form_create_leave" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
    <input type="hidden" name="employee_id" value="{{Auth::user()->user_id}}">
    <div class="form-group row" id="get_leave_types">
        <div class="col-lg-6">
            <label class="d-block required">{{ __("xin_leave_type") }} <span class="text-danger"> *</span></label>
            <select class="form-control selectpicker" id="leave_type" name="leave_type" style="width: 100%" required>
                @foreach($leaveTypes as $key => $type)
                    <option value="{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label>{{ __("xin_leave_time_types") }} <span class="text-danger"> *</span></label>
            <div class="row">
                <div class="col-md-4" id="hide_leave_all_day">
                    <input type="radio" id="is_leave_all_day" name="leave_time_types" value="1">
                    <label for="is_leave_all_day">{{__('xin_leave_all_day')}}</label>
                </div>
                <div class="col-md-4" id="hide_leave_half_day">
                    <input type="radio" id="is_leave_half_day" name="leave_time_types" value="2">
                    <label for="is_leave_half_day">{{__('xin_leave_half_day')}}</label>
                </div>
                <div class="col-md-4" id="hide_leave_in_day">
                    <input type="radio" id="is_leave_in_day" name="leave_time_types" value="3">
                    <label for="is_leave_in_day">{{__('xin_leave_in_day')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div id="leaveallday">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">{{__('xin_start_date')}} <span class="text-danger"> *</span></label>
                    <input class="form-control datepicker_leave start"
                           placeholder="{{__('xin_start_date')}}"
                           name="start_date" type="text" value="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">{{__('xin_end_date')}} <span class="text-danger"> *</span></label>
                    <input class="form-control datepicker_leave end"
                           placeholder="{{__('xin_end_date')}}"
                           name="end_date" type="text" value="">
                </div>
            </div>
        </div>
    </div>
    <div id="leavehalfday">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">{{__('xin_start_date')}} <span class="text-danger"> *</span></label>
                    <input class="form-control datepicker_leave start"
                           placeholder="{{__('xin_start_date')}}"
                           name="start_date_half_day" type="text" value="<?= date('d-m-Y') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_half_day">{{__('xin_leave_buoi')}} <span class="text-danger"> *</span></label>
                    <select class="form-control selectpicker" name="type_half_day" id="type_half_day"
                            data-plugin="select_hrm"
                            data-placeholder="{{__('xin_leave_buoi')}}">
                        <option value="0">{{__('xin_leave_morning_day')}}</option>
                        <option value="1">{{__('xin_leave_afternoon_day')}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="leaveinday">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="start_date">{{__('xin_start_date')}} <span class="text-danger"> *</span></label>
                    <input class="form-control datepicker_leave start"
                           placeholder="{{__('xin_start_date')}}"
                           name="start_date_in_day" type="text" value="<?= date('d-m-Y') ?>">
                </div>
            </div>
        </div>
        <div class="row">
            {{--<div class="col-md-6">
                <div class="form-group">
                    <label for="leave_time">{{__('xin_project_timelogs_starttime')}} <span class="text-danger"> *</span></label>
                    <input class="form-control timepicker_leave start"
                           placeholder="Thời gian bắt đầu" name="xin_start_time" type="text"
                           id="m_start_time">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="leave_time">{{__('xin_project_timelogs_endtime')}} <span class="text-danger"> *</span></label>
                    <input class="form-control timepicker_leave end"
                           placeholder="Thời gian kết thúc" name="xin_end_time" type="text"
                           id="m_end_time">
                </div>
            </div>--}}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label for="attachment">{{__('xin_attachment')}}</label>
            <input type="file" class="form-control-file" id="attachment"
                   name="attachment" accept="image/x-png, image/gif, image/jpeg, image/jpg, application/pdf">
            <small>{{__('xin_leave_file_type')}}</small>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label for="summary">{{__('xin_leave_reason')}} <span class="text-danger"> *</span></label>
            <textarea class="form-control"
                      placeholder="{{__('xin_leave_reason')}}"
                      name="reason" cols="30" rows="3" id="reason"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="description">{{__('xin_remarks')}}</label>
            <textarea class="form-control textarea"
                      placeholder="{{__('xin_remarks')}}"
                      name="remarks" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" class="btn btn-light-primary font-weight-bold">{{__('Thêm mới')}}</button>
        {{--<button type="button" class="btn btn-light-dark font-weight-bold" data-dismiss="modal">{{__('Đóng')}}</button>--}}
    </div>
</form>
@else
    <h3 class="text-center">{{ __("Bạn chưa được gán phép!") }}</h3>
@endif
@section('scripts')
    @parent
    @if(!empty($leaveTypes))
        <script>
            let formValidator = null;
            let submitButton = null;
            $(document).ready(function () {
                $('.datepicker_leave').datepicker({
                    todayHighlight: true,
                    format: 'dd-mm-yyyy',
                    disableTouchKeyboard: true,
                    autoclose:true,
                    changeYear: true,
                    startDate: new Date(),
                    language:'vi'
                }).on('changeDate', function(e) {
                    formValidator.revalidateField('start_date');
                    formValidator.revalidateField('end_date');
                    formValidator.revalidateField('start_date_half_day');
                    formValidator.revalidateField('start_date_in_day');
                });

               /* $('.timepicker_leave').timepicker({
                    disableFocus:true,
                    showMeridian:false
                }).on('changeTime.timepicker', function (e) {
                    formValidator.revalidateField('xin_start_time');
                    formValidator.revalidateField('xin_end_time');
                });*/

                checkLeaveType();
                // ẩn hiện loại ngày nghỉ theo các lý do nghỉ
                $("#get_leave_types").change(function () {
                    checkLeaveType();
                    clearField();
                });

                function checkLeaveType() {
                    let type = $('#leave_type').val();
                    if(type == 7){
                        $("#hide_leave_in_day").show();
                        $("#is_leave_in_day").prop('checked', true);
                        $("#hide_leave_all_day").hide();
                        $("#hide_leave_half_day").hide();
                        $("#is_leave_all_day").prop('checked', false);
                        $("#is_leave_half_day").prop('checked', false);
                        checkCheckbox();
                    } else if(type == 5){
                        $("#hide_leave_all_day").show();
                        $("#hide_leave_half_day").hide();
                        $("#hide_leave_in_day").hide();
                        $("#is_leave_all_day").prop('checked', true);
                        $("#is_leave_in_day").prop('checked', false);
                        $("#is_leave_half_day").prop('checked', false);
                        checkCheckbox();
                    } else {
                        $("#hide_leave_in_day").hide();
                        $("#hide_leave_all_day").show();
                        $("#hide_leave_half_day").show();
                        $("#is_leave_in_day").prop('checked', false);
                        $("#is_leave_all_day").prop('checked', false);
                        checkCheckbox();
                    }
                }

                /* ẩn hiện thời gian theo loại ngày nghỉ*/
                checkCheckbox();

                function checkCheckbox(){
                    let type = $("input:checked").val();
                    if(type == 1){
                        $("#leaveallday").show()
                        $("#leavehalfday").hide();
                        $("#leaveinday").hide();
                        clearInput();
                    } else if(type == 2) {
                        $("#leaveallday").hide();
                        $("#leavehalfday").show();
                        $("#leaveinday").hide();
                        clearInput();
                    } else if(type == 3){
                        $("#leaveallday").hide();
                        $("#leavehalfday").hide();
                        $("#leaveinday").show();
                        clearInput();
                    } else {
                        $("#leaveallday").hide();
                        $("#leavehalfday").hide();
                        $("#leaveinday").hide();
                        clearInput();
                    }
                }

                // clear data
                function clearInput() {
                    $("#leaveallday input").val('');
                    $("#leavehalfday input").val('');
                    $("#leaveinday input").val('');
                }
                $('input[type="radio"]').on('click change', function(e) {
                    if ($(this).val() == '1') {
                        $("#leaveallday").show();
                        $("#leavehalfday").hide();
                        $("#leaveinday").hide();
                        clearInput();
                    } else if ($(this).val() == '2'){
                        $("#leaveallday").hide();
                        $("#leavehalfday").show();
                        $("#leaveinday").hide();
                        clearInput();
                    } else if ($(this).val() == '3') {
                        $("#leaveallday").hide();
                        $("#leavehalfday").hide();
                        $("#leaveinday").show();
                        clearInput();
                    } else {
                        $("#leaveallday").hide();
                        $("#leavehalfday").hide();
                        $("#leaveinday").hide();
                        clearInput();
                    }
                    clearField();
                });

                /*$('#form_create_leave').submit(function (e) {
                    e.preventDefault();
                    let url = this.action;

                    let formData = new FormData($(this)[0]);
                    $.ajax({
                        url: url,
                        data: formData,
                        method: "POST",
                        dataType: "json",
                        timeout: 5000,
                        mimeType: 'multipart/form-data',
                        processData: false,
                        cache:false,
                        contentType: false,
                    }).done(function (response) {
                        toastr.success(response.data);
                        $('#add_leave_modal').modal('hide');
                        window._tables.leaves_list.datatable.reload();
                        /!*setTimeout(function () {window.location.reload()}, 500);*!/
                        $('#form_create_leave').trigger("reset");
                    }).fail(function (jqXHR, status) {
                        let statusCode = jqXHR.status;
                        if (statusCode !== 422) {
                            let errorText = {
                                "parsererror": __("Dữ liệu nhận được không đúng định dạng!"),
                                "error": (jqXHR.responseJSON.errors)?__(jqXHR.responseJSON.errors):__('Lỗi! Vui lòng thử lại'),
                                "timeout": __("Không có phản hồi từ máy chủ!"),
                                "abort": __("Yêu cầu bị hủy!")
                            };
                            toastr.error(errorText[status]);
                        } else {
                            let data = jqXHR.responseJSON?jqXHR.responseJSON:__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng");
                            toastr.error(__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng"));
                            console.log(jqXHR.responseJSON);
                        }
                    });
                    return false;
                });*/
                let formCreateLeave = document.getElementById('form_create_leave');
                submitButton = formCreateLeave.querySelector('[type="submit"]');

                formValidator = FormValidation.formValidation(
                    formCreateLeave,
                    {
                        fields: {
                            'leave_type': {
                                validators: {
                                    notEmpty: {
                                        message: __("xin_error_leave_type"),
                                    }
                                }
                            },
                            'leave_time_types': {
                                validators: {
                                    notEmpty: {
                                        message: __("xin_error_leave_time_types"),
                                    }
                                }
                            },
                            'start_date': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_start_date"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 1){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                            'end_date': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_end_date"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 1){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                            'type_half_day': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_type_half_day"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 2){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                            'start_date_half_day': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_start_date"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 2){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                            'start_date_in_day': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_start_date"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 3){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                           /* 'xin_start_time': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_xin_start_time"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 3){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },
                            'xin_end_time': {
                                validators: {
                                    callback: {
                                        message: __("xin_error_xin_end_time"),
                                        callback: function (input) {
                                            let type = $("input:checked").val();
                                            if(type == 3){
                                                return input.value.length > 0;
                                            } else {
                                                return true;
                                            }
                                        }
                                    }
                                }
                            },*/
                            'reason': {
                                validators: {
                                    notEmpty: {
                                        message: __("xin_error_leave_type_reason"),
                                    }
                                }
                            },
                            'attachment': {
                                validators: {
                                    file: {
                                        extension: 'pdf,gif,png,jpeg,jpg',
                                        type: 'application/pdf,image/gif,image/png,image/jpeg,image/jpg',
                                        message: __("xin_leave_file_type"),
                                    },
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                            bootstrap: new FormValidation.plugins.Bootstrap({})
                        }
                    }
                ).on('core.form.valid', function () {
                    submitButton.setAttribute('disabled', 'disabled');
                    let formData = new FormData($('#form_create_leave')[0]);

                    $.ajax({
                        url: '{{ route('leaves.ajax.create_leave')}}',
                        data: formData,
                        method: "POST",
                        dataType: "json",
                        timeout: 5000,
                        mimeType: 'multipart/form-data',
                        processData: false,
                        cache:false,
                        contentType: false,
                    }).done(function (response) {
                        if (response.success) {
                            /*window._display_alert_success('#form_create_leave', __("create_overtime_success"));*/
                            toastr.success(response.data);
                            $('#add_leave_modal').modal('hide');
                            window._tables.leaves_list.datatable.reload();
                            $('#form_create_leave').trigger("reset");
                            submitButton.setAttribute('disabled', 'disabled');
                            /*$('#form_create_leave').find('[type=submit]').attr('disabled', 'disabled');*/
                        } else {
                            submitButton.removeAttribute('disabled');
                            toastr.error(response.data);
                            //window._display_alert_error('#form_create_leave', __("field_wrong_format"));
                        }
                    }).fail(function (jqXHR, status) {
                        let statusCode = jqXHR.status;
                        if (statusCode !== 422) {
                            let errorText = {
                                "parsererror": __("Dữ liệu nhận được không đúng định dạng!"),
                                "error": (jqXHR.responseJSON.errors)?__(jqXHR.responseJSON.errors):__('Lỗi! Vui lòng thử lại'),
                                "timeout": __("Không có phản hồi từ máy chủ!"),
                                "abort": __("Yêu cầu bị hủy!")
                            };
                            toastr.error(errorText[status]);
                        } else {
                            let data = jqXHR.responseJSON?jqXHR.responseJSON:__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng");
                            let html = "";
                            if (typeof jqXHR.responseJSON.errors === "object") {
                                $.each(jqXHR.responseJSON.errors, function(fieldName, errors) {
                                   html += errors[0];
                                   return false;
                                });
                            }
                            toastr.error(html);
                        }
                        submitButton.removeAttribute('disabled');
                    });
                    return false;
                });
            });
            $('#add_leave_modal').on('hidden.bs.modal', function (e) {
                $('#leave_type').trigger('change');
                if (formValidator) {
                    formValidator.resetForm(true);
                    submitButton.removeAttribute('disabled');
                }
            });

            function clearField() {
                if(typeof formValidator != "undefined") {
                    formValidator.resetField('start_date', true);
                    formValidator.resetField('end_date', true);
                    formValidator.resetField('type_half_day', true);
                    formValidator.resetField('start_date_half_day', true);
                    formValidator.resetField('start_date_in_day', true);
                    /*formValidator.resetField('xin_start_time', true);
                    formValidator.resetField('xin_end_time', true);*/
                }
            }
        </script>
    @endif
@endsection

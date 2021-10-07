@if(isset($type))
    <form method="POST" action="{{ route('overtime_request.ajax.update_request') }}" id="form_overtime">
        @if(isset($overtime))
        <input type="hidden" name="id" value="{{$overtime->time_request_id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("Ngày") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_leave start"
                       placeholder="{{__('Ngày')}}"
                       name="request_date" type="text" value="@if(isset($overtime->request_date)){{date('d-m-Y', strtotime($overtime->request_date))}}@endif">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} <span class="text-danger"> *</span></label>
                <input class="form-control timepicker_leave start"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="request_clock_in" type="text"
                       value="@if(isset($overtime->request_clock_in)){{date('h:i A', strtotime($overtime->request_clock_in))}}@endif">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} <span class="text-danger"> *</span></label>
                <input class="form-control timepicker_leave end"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="request_clock_out" type="text"
                       value="@if(isset($overtime->request_clock_out)){{date('h:i A', strtotime($overtime->request_clock_out))}}@endif">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label>{{ __("xin_content") }}</label>
                <textarea name="reason" class="form-control" rows="3">{{ $overtime->request_reason ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $overtime ? __("Cập nhật") : __("Thêm mới") }}</button>
        </div>
    </form>
    <!-- JS Form -->
    <script>
        $('.datepicker_leave').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose:true,
            language:'vi'
        }).on('changeDate', function(e) {
            formValidator.revalidateField('request_date');
        });

        $('.timepicker_leave').timepicker({
            showMeridian:false,
            disableFocus:true,
        }).on('changeTime.timepicker', function (e) {
            formValidator.revalidateField('request_clock_in');
            formValidator.revalidateField('request_clock_out');
        });

        if(typeof formOvertime === "undefined") {
            let formOvertime = null;
        }
        if(typeof submitButton === "undefined") {
            let submitButton = null;
        }
        if(typeof formValidator === "undefined") {
            let formValidator = null;
        }
        formOvertime = document.getElementById('form_overtime');
        submitButton = formOvertime.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formOvertime,
            {
                fields: {
                    'request_date': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'request_clock_in': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'request_clock_out': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'reason':{
                        validators: {
                            stringLength: {
                                max: 255,
                                message: __("xin_error_complaint_length")
                            }
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
            $.ajax({
                url: '{{ route('overtime_request.ajax.update_request')}}',
                data: $('#form_overtime').serialize(),
                method: 'POST'
            }).done(function (response) {
                if (response.success) {
                    /*window._display_alert_success('#form_overtime', __("create_overtime_success"));
                    setTimeout(window.location.reload(), 3000);*/
                    toastr.success(__("create_overtime_success"));
                    $('#update_overtime_request').modal('hide');
                    window._tables.overtime_list.datatable.reload();
                    $('#form_overtime').trigger("reset");
                    submitButton.setAttribute('disabled', 'disabled');
                } else {
                    submitButton.removeAttribute('disabled');
                    window._display_alert_error('#form_overtime', __("field_wrong_format"));
                }
                /*$('#form_overtime').find('[type=submit]').attr('disabled', 'disabled');*/
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
                /*submitButton.removeAttribute('disabled');*/
            });
        });


        /*$('#update_overtime_request').on('hidden.bs.modal', function (e) {
            if (formValidator) {
                formValidator.resetForm(true);
                submitButton.removeAttribute('disabled');
            }
        });*/
    </script>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
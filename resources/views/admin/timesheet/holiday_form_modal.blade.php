@if(isset($type))
    <form method="POST" action="{{ route('admin.timesheet.ajax.holiday_store') }}" id="form_holiday" autocomplete="off">
        @if(isset($holiday))
        <input type="hidden" name="id" value="{{$holiday->holiday_id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_event_name") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{__('xin_event_name')}}"
                       name="event_name" type="text" value="@if(isset($holiday->event_name)){{$holiday->event_name}}@endif">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_start_date") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_leave start"
                       placeholder="{{__('xin_start_date')}}"
                       name="start_date" type="text" value="@if(isset($holiday->start_date)){{date('d-m-Y', strtotime($holiday->start_date))}}@endif">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_end_date") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_leave start"
                       placeholder="{{__('xin_end_date')}}"
                       name="end_date" type="text" value="@if(isset($holiday->end_date)){{date('d-m-Y', strtotime($holiday->end_date))}}@endif">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label>{{ __("xin_description") }}</label>
                <textarea name="description" class="form-control" rows="3">{{ $holiday->description ?? '' }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label>{{ __("dashboard_xin_status") }}</label>
                <select class="form-control" id="leave_type" name="is_publish" style="width: 100%" required>
                    @foreach($status as $key => $type)
                        <option value="{{$key}}" @if(isset($holiday) && isset($holiday->is_publish) && ($holiday->is_publish == $key)) {{'selected'}} @endif >{{$type}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $holiday ? __("Cập nhật") : __("Thêm mới") }}</button>
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

        if(typeof formOvertime === "undefined") {
            let formOvertime = null;
        }
        if(typeof submitButton === "undefined") {
            let submitButton = null;
        }
        if(typeof formValidator === "undefined") {
            let formValidator = null;
        }
        formOvertime = document.getElementById('form_holiday');
        submitButton = formOvertime.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formOvertime,
            {
                fields: {
                    'event_name': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'start_date': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'end_date': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'is_publish': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'description': {
                        validators: {
                            notEmpty: {
                                message: __("field_required"),
                            }
                        }
                    },
                    'xin_description':{
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
                url: '{{ route('admin.timesheet.ajax.holiday_store')}}',
                data: $('#form_holiday').serialize(),
                method: 'POST'
            }).done(function (response) {
                if (response.success) {
                    /*window._display_alert_success('#form_holiday', __("create_overtime_success"));
                    setTimeout(window.location.reload(), 3000);*/
                    toastr.success(__("create_overtime_success"));
                    $('#holiday_request').modal('hide');
                    window._tables.holiday_list.datatable.reload();
                    $('#form_holiday').trigger("reset");
                    submitButton.setAttribute('disabled', 'disabled');
                } else {
                    submitButton.removeAttribute('disabled');
                    window._display_alert_error('#form_holiday', __("field_wrong_format"));
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
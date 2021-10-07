{{--@if(isset($type))--}}
    <form method="POST" action="" id="formCompensations">
        @if($compensation != null)
            <input type="hidden" name="compensation_id" value="{{ $compensation ? $compensation->compensation_id : '' }}">
        @endif
        @csrf
        <div class="compensations_form">
            <div class="element">
                <div class="form-group row">
                    <div class="col-lg-3 element_mobile">
                        <label class="d-block required">Loại bù công</label>
                        <select class="form-control select_compensation" id="type_of_work" name="type_of_work">
                            <option value="off">Offline</option>
                            <option value="on">Online</option>
                        </select>
                    </div>
                    <div class="col-lg-3 element_mobile">
                        <label class="d-block required">Ngày muốn bù công <span class="text-danger"> *</span></label>
                        <input type="" autocomplete="off" name="compensation_date[]" readonly
                               class="compensation_date datepciker_compensation form-control"
                               placeholder="{{__('select_compensation_date')}}"
                               value="{{ $compensation && $compensation->compensation_date  ? date('d-m-Y', strtotime($compensation->compensation_date)) : '' }}"
                        >
                    </div>
                    <div class="col-lg-6 element_mobile">
                        <label class="d-block required">Lý do bù công <span class="text-danger"> *</span></label>
                        <textarea class="reason form-control" name="reason[]" id="" cols="30" rows="1">{{ $compensation && $compensation->reason  ? $compensation->reason : '' }}</textarea>
                        {{--<input type="" autocomplete="off" name="reason[]" readonly  class=" form-control" placeholder="{{__('select_compensation_date')}}">--}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6 value_compensations">
                        <label class="d-block required">Loại bù công <span class="text-danger"> *</span></label>
                        <div class="radio-inline mt-4">
                            <label class="radio">
                                <input type="radio" {{ $compensation && $compensation->compensation_type == 1 ? 'selected' : '' }} value="1" checked="checked" name="radios1[]" class="js-make-change"/>
                                <span></span>
                                Đủ công
                            </label>
                            <label class="radio">
                                <input type="radio" {{ $compensation && $compensation->compensation_type == 2 ? 'selected' : '' }} value="2" name="radios1[]" class="js-make-change"/>
                                <span></span>
                                Nửa công sáng
                            </label>
                            <label class="radio">
                                <input type="radio" {{ $compensation && $compensation->compensation_type == 3 ? 'selected' : '' }} value="3" name="radios1[]" class="js-make-change"/>
                                <span></span>
                                Nửa công chiều
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($compensation == null)
        <div class="form_compensations row">
{{--            <div class="col-md-6">--}}
{{--                <button id="compensations-add" type="button" class="btn btn-primary" data-toggle="modal" data-target="#model_add_event1">--}}
{{--                    <span>{{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}</span>  Thêm ngày bù công--}}
{{--                </button>--}}
{{--            </div>--}}
            <div class="col-md-6 text-right delete_element">

            </div>
        </div>
        @endif
        <div class="col-12 text-center">
            <button  type="button" class="add_compensations btn btn-primary mr-2">{{ __('xin_save')  }}</button>
            <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close')  }}</button>
        </div>
    </form>
    <script type="text/javascript" src="{{ mix('js/compensations.js') }}"></script>
{{--    <!-- JS Form -->--}}
{{--    <script>--}}
{{--        $('.datepicker_leave').datepicker({--}}
{{--            todayHighlight: true,--}}
{{--            format: 'dd-mm-yyyy',--}}
{{--            disableTouchKeyboard: true,--}}
{{--            autoclose:true,--}}
{{--            language:'vi'--}}
{{--        }).on('changeDate', function(e) {--}}
{{--            formValidator.revalidateField('request_date');--}}
{{--        });--}}

{{--        $('.timepicker_leave').timepicker({--}}
{{--            showMeridian:false,--}}
{{--            disableFocus:true,--}}
{{--        }).on('changeTime.timepicker', function (e) {--}}
{{--            formValidator.revalidateField('request_clock_in');--}}
{{--            formValidator.revalidateField('request_clock_out');--}}
{{--        });--}}

{{--        if(typeof formOvertime === "undefined") {--}}
{{--            let formOvertime = null;--}}
{{--        }--}}
{{--        if(typeof submitButton === "undefined") {--}}
{{--            let submitButton = null;--}}
{{--        }--}}
{{--        if(typeof formValidator === "undefined") {--}}
{{--            let formValidator = null;--}}
{{--        }--}}
{{--        formOvertime = document.getElementById('form_overtime');--}}
{{--        submitButton = formOvertime.querySelector('[type="submit"]');--}}

{{--        formValidator = FormValidation.formValidation(--}}
{{--            formOvertime,--}}
{{--            {--}}
{{--                fields: {--}}
{{--                    'request_date': {--}}
{{--                        validators: {--}}
{{--                            notEmpty: {--}}
{{--                                message: __("field_required"),--}}
{{--                            }--}}
{{--                        }--}}
{{--                    },--}}
{{--                    'request_clock_in': {--}}
{{--                        validators: {--}}
{{--                            notEmpty: {--}}
{{--                                message: __("field_required"),--}}
{{--                            }--}}
{{--                        }--}}
{{--                    },--}}
{{--                    'request_clock_out': {--}}
{{--                        validators: {--}}
{{--                            notEmpty: {--}}
{{--                                message: __("field_required"),--}}
{{--                            }--}}
{{--                        }--}}
{{--                    },--}}
{{--                },--}}
{{--                plugins: {--}}
{{--                    trigger: new FormValidation.plugins.Trigger(),--}}
{{--                    submitButton: new FormValidation.plugins.SubmitButton(),--}}
{{--                    // defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation--}}
{{--                    bootstrap: new FormValidation.plugins.Bootstrap({})--}}
{{--                }--}}
{{--            }--}}
{{--        ).on('core.form.valid', function () {--}}
{{--            submitButton.setAttribute('disabled', 'disabled');--}}
{{--            $.ajax({--}}
{{--                url: '{{ route('overtime_request.ajax.update_request')}}',--}}
{{--                data: $('#form_overtime').serialize(),--}}
{{--                method: 'POST'--}}
{{--            }).done(function (response) {--}}
{{--                if (response.success) {--}}
{{--                    /*window._display_alert_success('#form_overtime', __("create_overtime_success"));--}}
{{--                    setTimeout(window.location.reload(), 3000);*/--}}
{{--                    toastr.success(__("create_overtime_success"));--}}
{{--                    $('#update_overtime_request').modal('hide');--}}
{{--                    window._tables.overtime_list.datatable.reload();--}}
{{--                    $('#form_overtime').trigger("reset");--}}
{{--                    submitButton.setAttribute('disabled', 'disabled');--}}
{{--                } else {--}}
{{--                    submitButton.removeAttribute('disabled');--}}
{{--                    window._display_alert_error('#form_overtime', __("field_wrong_format"));--}}
{{--                }--}}
{{--                /*$('#form_overtime').find('[type=submit]').attr('disabled', 'disabled');*/--}}
{{--            }).fail(function (jqXHR, status) {--}}
{{--                let statusCode = jqXHR.status;--}}
{{--                if (statusCode !== 422) {--}}
{{--                    let errorText = {--}}
{{--                        "parsererror": __("Dữ liệu nhận được không đúng định dạng!"),--}}
{{--                        "error": (jqXHR.responseJSON.errors)?__(jqXHR.responseJSON.errors):__('Lỗi! Vui lòng thử lại'),--}}
{{--                        "timeout": __("Không có phản hồi từ máy chủ!"),--}}
{{--                        "abort": __("Yêu cầu bị hủy!")--}}
{{--                    };--}}
{{--                    toastr.error(errorText[status]);--}}
{{--                } else {--}}
{{--                    let data = jqXHR.responseJSON?jqXHR.responseJSON:__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng");--}}
{{--                    let html = "";--}}
{{--                    if (typeof jqXHR.responseJSON.errors === "object") {--}}
{{--                        $.each(jqXHR.responseJSON.errors, function(fieldName, errors) {--}}
{{--                            html += errors[0];--}}
{{--                            return false;--}}
{{--                        });--}}
{{--                    }--}}
{{--                    toastr.error(html);--}}
{{--                }--}}
{{--                submitButton.removeAttribute('disabled');--}}
{{--                /*submitButton.removeAttribute('disabled');*/--}}
{{--            });--}}
{{--        });--}}


{{--        /*$('#update_overtime_request').on('hidden.bs.modal', function (e) {--}}
{{--            if (formValidator) {--}}
{{--                formValidator.resetForm(true);--}}
{{--                submitButton.removeAttribute('disabled');--}}
{{--            }--}}
{{--        });*/--}}
{{--    </script>--}}
{{--@else--}}
{{--    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>--}}
{{--@endif--}}

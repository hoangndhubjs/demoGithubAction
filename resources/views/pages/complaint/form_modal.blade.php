@if(isset($type))
    <form method="POST" action="{{ route('complaint.ajax.update_complaint') }}" id="form_complaint" enctype="multipart/form-data">
        <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
        <input type="hidden" name="complaint_from" value="{{Auth::user()->user_id}}">
        @if(isset($complaint))
        <input type="hidden" name="id" value="{{$complaint->complaint_id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_company_name") }}</label>
                <input class="form-control datepicker_leave start"
                       placeholder="{{__('xin_company_name')}}"
                       name="company_name" readonly disabled type="text" value="@if(isset($company->name)){{$company->name}}@endif">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_complaint_title") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{__('xin_complaint_title')}}"
                       name="title" type="text" value="{{ $complaint->title ?? '' }}">
            </div>
        </div>
        {{--<div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_complaint_date") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker-complaint"
                       placeholder="{{__('xin_complaint_date')}}"
                       name="complaint_date" type="text" value="@if(isset($complaint->complaint_date)){{date('d-m-Y', strtotime($complaint->complaint_date))}}@endif">
            </div>
        </div>--}}
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_complaint_against") }} <span class="text-danger"> *</span></label>
                <select class="form-control select2 select2_primary_choose" name="complaint_against[]" style="width: 100%" required multiple>
                    @foreach($employees as $key => $employee)
                        <option value="{{ $key }}" {{ in_array($key, $complaint->complaint_against ?? []) ? 'selected' : null }}>{{ $employee }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label>{{ __("xin_description") }}</label>
                <textarea name="description" class="form-control" rows="3">{{ $complaint->description ?? '' }}</textarea>
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

        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $complaint ? __("Cập nhật") : __("Thêm mới") }}</button>
        </div>
    </form>
    <!-- JS Form -->
    <script>
        if(typeof formComplaint === "undefined") {
            let formComplaint = null;
        }
        if(typeof submitButton === "undefined") {
            let submitButton = null;
        }
        if(typeof formValidator === "undefined") {
            let formValidator = null;
        }
        $('.datepicker-complaint').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi'
        }).on('changeDate', function(e) {
            formValidator.revalidateField('complaint_date');
        });

        $('.select2_primary_choose').select2({
            closeOnSelect: false
        }).on('change', function (e) {
            let selected = $(this).find('option:selected').length;
            formValidator.revalidateField('complaint_against[]');
        });

        formComplaint = document.getElementById('form_complaint');
        submitButton = formComplaint.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formComplaint,
            {
                fields: {
                    'complaint_against[]': {
                        validators: {
                            callback: {
                                message: __("xin_error_complaint_against"),
                                callback: function (input) {
                                    let selected = $(input.element).find('option:selected').length;
                                    return selected > 0;
                                }
                            }
                        }
                    },
                    'title': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_title"),
                            },
                            /*stringLength: {
                                max: 255,
                                message: __("xin_error_complaint_length")
                            }*/
                        }
                    },
                    'complaint_date': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_date"),
                            },
                        }
                    },
                    'attachment': {
                        validators: {
                            file: {
                                extension: 'pdf,gif,png,jpeg,jpg',
                                type: 'application/pdf,image/gif,image/png,image/jpeg,image/jpg',
                                message: __("xin_leave_file_type"),
                            },
                            /*file: {
                                maxSize: 2097152,
                                message: __("max_file_2mb"),
                            }*/
                        }
                    },
                    'description':{
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
            let formData = new FormData($('#form_complaint')[0]);
            $.ajax({
                url: '{{ route('complaint.ajax.update_complaint')}}',
                data: formData,//$('#form_complaint').serialize(),
                method: 'POST',
                dataType: "json",
                timeout: 5000,
                mimeType: 'multipart/form-data',
                processData: false,
                cache:false,
                contentType: false,
            }).done(function (response) {
                if (response.success) {
                    window._display_alert_success('#form_complaint', __("create_overtime_success"));
                    toastr.success(__("create_overtime_success"));
                    $('#complaint_request').modal('hide');
                    window._tables.complaint_list.datatable.reload();
                    $('#form_complaint').trigger("reset");
                    submitButton.setAttribute('disabled', 'disabled');
                } else {
                    submitButton.removeAttribute('disabled');
                    window._display_alert_error('#form_complaint', __("field_wrong_format"));
                }
                $('#form_complaint').find('[type=submit]').attr('disabled', 'disabled');
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
        });

        /*$('#complaint_request').on('hidden.bs.modal', function (e) {
            if (formValidator) {
                formValidator.resetForm(true);
                submitButton.removeAttribute('disabled');
            }
        });*/

    </script>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
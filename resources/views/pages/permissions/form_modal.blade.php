@if(isset($type))
    <form method="POST" action="{{ route('permission.ajax.store') }}" id="form_permission" enctype="multipart/form-data">
        @csrf
        {{--<input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
        <input type="hidden" name="complaint_from" value="{{Auth::user()->user_id}}">--}}
        @if(isset($permission))
        <input type="hidden" name="id" value="{{$permission->id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_company_name") }} <span class="text-danger"> *</span></label>
                <select class="form-control {{--selectpicker--}}" name="company_id" style="width: 100%">
                    @foreach($companies as $key => $company)
                        <option value="{{ $key }}" >{{ $company }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_group_name") }} <span class="text-danger"> *</span></label>
                <select class="form-control {{--selectpicker--}}" name="group_id" style="width: 100%" required>
                    @foreach($groups as $key => $group)
                        <option value="{{ $key }}">{{ $group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_name_permission") }} <span class="text-danger"> *</span></label>
                <select class="form-control {{--selectpicker--}}" name="name_permission" style="width: 100%" required>
                    @foreach($names as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $permission ? __("Cập nhật") : __("Thêm mới") }}</button>
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

        formComplaint = document.getElementById('form_permission');
        submitButton = formComplaint.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formComplaint,
            {
                fields: {
                    'company_id': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_title"),
                            }
                        }
                    },
                    'group_id': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_date"),
                            },
                        }
                    },
                    'name_permission': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_date"),
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
            let formData = new FormData($('#form_permission')[0]);
            $.ajax({
                url: '{{ route('permission.ajax.store')}}',
                data: formData,//$('#form_permission').serialize(),
                method: 'POST',
                dataType: "json",
                timeout: 5000,
                mimeType: 'multipart/form-data',
                processData: false,
                cache:false,
                contentType: false,
            }).done(function (response) {
                if (response.success) {
                    window._display_alert_success('#form_permission', __("create_overtime_success"));
                    toastr.success(__("create_overtime_success"));
                    $('#permission_modal').modal('hide');
                   /* $('#kt_tree_6').jstree(true).settings.core.data = arrayCollection;*/
                    $('#kt_tree_6').jstree(true).refresh();
                    submitButton.setAttribute('disabled', 'disabled');
                } else {
                    submitButton.removeAttribute('disabled');
                    window._display_alert_error('#form_permission', __("field_wrong_format"));
                }
                $('#form_permission').find('[type=submit]').attr('disabled', 'disabled');
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
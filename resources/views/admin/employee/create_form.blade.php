@if(isset($type))
    <form method="POST" id="form_allowance" autocomplete="off">
        @if(isset($id))
            <input type="hidden" name="user_id" value="{{ $id }}">
        @endif
        <input class="form-control" name="pin_code" type="hidden" value="{{random_int(100000,999999)}}">
        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_first_name") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_employee_first_name") }}" name="first_name" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->first_name }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_last_name") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_employee_last_name") }}" name="last_name" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->last_name }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("employee_id") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("employee_id") }}" name="employee_id" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->employee_id }}@endif">
            </div>
        </div>

        <div class="form-group row">

            <div class="col-lg-4">
                <label class="d-block required">{{ __("dashboard_username") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("dashboard_username") }}" name="username" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->username }}@endif">
            </div>

            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_gender") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" name="gender"
                        data-plugin="select_hrm"
                        data-placeholder="{{__('xin_employee_gender')}}">
                    <option value="Male" @if(isset($employeeDetail) && $employeeDetail->gender == 'Male'){{ 'selected' }}@endif>{{__('xin_gender_male')}}</option>
                    <option value="Female" @if(isset($employeeDetail) && $employeeDetail->gender == 'Female'){{ 'selected' }}@endif>{{__('xin_gender_female')}}</option>
                </select>
            </div>

            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_dob") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_employee"
                       placeholder="{{ __("xin_employee_dob") }}" name="date_of_birth" type="text"
                       value="@if(isset($employeeDetail)){{ date('d-m-Y', strtotime($employeeDetail->date_of_birth)) }}@endif">
            </div>

        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_doj") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_employee"
                       placeholder="{{ __("xin_employee_doj") }}" name="date_of_joining" type="text"
                       value="@if(isset($employeeDetail)){{ date('d-m-Y', strtotime($employeeDetail->date_of_joining)) }}@else {{date('d-m-Y')}} @endif">
            </div>

            @if(!isset($employeeDetail))
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_password") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_employee_password") }}" name="password" type="password"
                       value="">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_cpassword") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_employee_cpassword") }}" name="confirm_password" type="password"
                       value="">
            </div>
            @else
            <div class="col-lg-4">
                <label class="d-block required">{{ __("dashboard_xin_status") }}</label>
                <select class="form-control selectpicker_config" name="status"
                        data-plugin="select_hrm"
                        data-placeholder="{{__('dashboard_xin_status')}}">
                    <option value="0" @if(isset($employeeDetail) && $employeeDetail->is_active == '0'){{ 'selected' }}@endif>{{__('xin_employees_inactive')}}</option>
                    <option value="1" @if(isset($employeeDetail) && $employeeDetail->is_active == '1'){{ 'selected' }}@endif>{{__('xin_employees_active')}}</option>
                </select>
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_dol") }}</label>
                <input class="form-control datepicker_employee"
                       placeholder="{{ __("xin_employee_dol") }}" name="date_of_leaving" type="text"
                       value="@if(isset($employeeDetail)){{ date('d-m-Y', strtotime($employeeDetail->date_of_leaving)) }}@endif">
            </div>
            @endif
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_phone") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_phone") }}" name="contact_no" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->contact_no }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("dashboard_email") }} @if(!isset($employeeDetail)) <span class="text-danger"> *</span> @endif</label>
                <input class="form-control" @if(isset($employeeDetail)) {{'readonly'}}@endif
                       placeholder="{{ __("dashboard_email") }}" name="email" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->email }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_address") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_employee_address") }}" name="address" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->address }}@endif">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("left_company") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="company_id" name="company_id" style="width: 100%" >
                    <option value="">{{__('left_company')}}</option>
                    @foreach($companies as $key => $company)
                        <option value="{{$key}}" @if(isset($employeeDetail) && $key == $employeeDetail->company_id){{ 'selected' }}@endif>{{$company}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("left_location") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="location_id" name="location_id">
                    @if(isset($employeeDetail) && $employeeDetail->location)<option selected value="{{$employeeDetail->location_id}}">{{$employeeDetail->location->location_name}}</option>@else<option value="">{{__('left_location')}}</option>@endif
                </select>
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("left_department") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="department_id" name="department_id">
                    @if(isset($employeeDetail) && $employeeDetail->department)<option selected value="{{$employeeDetail->department_id}}">{{$employeeDetail->department->department_name}}</option>@else<option value="">{{__('left_department')}}</option>@endif
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("kpi_statistics_title") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="designation_id" name="designation_id">
                    @if(isset($employeeDetail) && $employeeDetail->designation)<option selected value="{{$employeeDetail->designation_id}}">{{$employeeDetail->designation->designation_name}}</option>@else<option value="">{{__('kpi_statistics_title')}}</option>@endif
                </select>
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_role") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="role" name="role">
                    @if(isset($employeeDetail) && $employeeDetail->role)<option selected value="{{$employeeDetail->user_role_id}}">{{$employeeDetail->role->name}}</option>@else<option value="">{{__('xin_employee_role')}}</option>@endif
                </select>
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_reports_to") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="reports_to" name="reports_to">
                    @if(isset($employeeDetail) && $employeeDetail->report_to)
                        <option selected value="{{$employeeDetail->reports_to}}">{{$employeeDetail->report_to->first_name.' '.$employeeDetail->report_to->last_name}}</option>
                    @else
                        <option value="">{{__('xin_reports_to')}}</option>
                    @endif
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_employee_office_shift") }} <span class="text-danger"> *</span></label>
                <select class="form-control selectpicker_config" id="office_shift_id" name="office_shift_id">
                    @if(isset($employeeDetail) && $employeeDetail->office_shift)<option selected value="{{$employeeDetail->office_shift_id}}">{{$employeeDetail->office_shift->shift_name}}</option>@else<option value="">{{__('xin_employee_office_shift')}}</option>@endif
                </select>
            </div>
            <div class="col-lg-8">
                <label class="d-block required">{{ __("xin_hr_leave_cat") }} <span class="text-danger"> *</span></label>
                {{--<input class="form-control"
                       placeholder="{{ __("xin_hr_leave_cat") }}" name="leave_categories[]" type="text"
                       value="">--}}
                <select class="form-control select2 select2_primary_choose" id="leave_categories" name="leave_categories[]" style="width: 100%" multiple>
                    @if(isset($employeeDetail) && isset($employeeDetail->leave_categories))
                        @foreach( $categories = explode (',', $employeeDetail->leave_categories) as $category)
                            @if(isset($listLeaveType) && isset($listLeaveType[$category]))<option value="{{ $category }}" selected >{{ $listLeaveType[$category] }}</option>@endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_salary_trail_work") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_salary_trail_work") }}" name="salary_trail_work" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->salary_trail_work }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_salary_basic") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                       placeholder="{{ __("xin_salary_basic") }}" name="basic_salary" type="text"
                       value="@if(isset($employeeDetail)){{$employeeDetail->basic_salary }}@endif">
            </div>
            <div class="col-lg-4">
                <label class="d-block required">{{ __("xin_end_trail_work") }} <span class="text-danger"> *</span></label>
                <input class="form-control datepicker_employee"
                       placeholder="{{ __("xin_end_trail_work") }}" name="end_trail_work" type="text"
                       value="@if(isset($employeeDetail)){{ date('d-m-Y', strtotime($employeeDetail->end_trail_work)) }}@endif">
            </div>
        </div>

        <div class="form-group text-right mb-0">
            <button type="submit" class="update_allowance btn btn-primary">{{ isset($employeeDetail) ? __("xin_edit_leave") : __("xin_save") }}</button>
            <button type="reset" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close')  }}</button>
        </div>
    </form>
    <!-- JS Form -->
{{-- form-deductions  --}}
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
<script>
    //[name=is_allowance_taxable] [name=amount_option]
    $('.selectpicker_config').select2().on('change', function (e) {
        formValidator.revalidateField('company_id');
        formValidator.revalidateField('location_id');
        formValidator.revalidateField('department_id');
        formValidator.revalidateField('designation_id');
        formValidator.revalidateField('role');
        formValidator.revalidateField('reports_to');
        formValidator.revalidateField('office_shift_id');
        formValidator.revalidateField('gender');
    });
    $('.select2_primary_choose').select2({
        closeOnSelect: false
    }).on('change', function (e) {
        let selected = $(this).find('option:selected').length;
        formValidator.revalidateField('leave_categories[]');
    });

    $('.datepicker_employee').datepicker({
        todayHighlight: true,
        format: 'dd-mm-yyyy',
        disableTouchKeyboard: true,
        autoclose:true,
        language:'vi'
    }).on('changeDate', function(e) {
        formValidator.revalidateField('date_of_birth');
        formValidator.revalidateField('date_of_joining');
        formValidator.revalidateField('end_trail_work');
    });

    $('#department_id').select2({
        placeholder: "Bộ phận",
        ajax: {
            url: '{{ route("leaves.ajax.department") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#reports_to').select2({
        placeholder: "Báo cáo đến",
        ajax: {
            url: '{{ route("ajax.reports_to") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#designation_id').select2({
        placeholder: "Chức vụ",
        ajax: {
            url: '{{ route("employee_managements.ajax.designation") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    department_id : $('#department_id').val(),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#role').select2({
        placeholder: "Vai trò",
        ajax: {
            url: '{{ route("employee_managements.ajax.role") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#office_shift_id').select2({
        placeholder: "Ca làm việc",
        ajax: {
            url: '{{ route("office-shift.ajax.office_shift_by_company") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#leave_categories').select2({
        placeholder: "Danh mục được phép nghỉ",
        ajax: {
            url: '{{ route("employee_managements.ajax.leave_type") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#location_id').select2({
        placeholder: "Vị trí địa lý",
        ajax: {
            url: '{{ route("employee_managements.ajax.location") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term),
                    company_id : $('#company_id').val(),
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#company_id').change(function () {
        $('#department_id').val(null).trigger('change');
        $('#reports_to').val(null).trigger('change');
        $('#designation_id').val(null).trigger('change');
        $('#role').val(null).trigger('change');
        $('#office_shift_id').val(null).trigger('change');
        $('#leave_categories').val(null).trigger('change');
        $('#location_id').val(null).trigger('change');
    });
    $('#department_id').change(function () {
        $('#designation_id').val(null).trigger('change');
    });

    if(typeof form === "undefined") {
        let form1 = null;
    }
    if(typeof formValidator === "undefined") {
        let formValidator = null;
    }
    form1 = document.getElementById('form_allowance');

    formValidator =  FormValidation.formValidation(
        form1,
        {
            fields: {
                first_name: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                last_name: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                employee_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                gender: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                date_of_birth: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                date_of_joining: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                confirm_password: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                        identical: {
                            compare: function() {
                                return form.querySelector('[name="password"]').value;
                            },
                            message: __('passwork_not_same')
                        }
                    }
                },
                contact_no: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                        emailAddress: {
                            message: __('xin_employee_error_invalid_email')
                        }
                    }
                },
                address: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                company_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                location_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                department_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                designation_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                role: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                reports_to: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                office_shift_id: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                'leave_categories[]': {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                salary_trail_work: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                basic_salary: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                end_trail_work: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
            },
            plugins: { //Learn more: https://formvalidation.io/guide/plugins
                trigger: new FormValidation.plugins.Trigger(),
                // Bootstrap Framework Integration
                bootstrap: new FormValidation.plugins.Bootstrap(),
                // Validate fields when clicking the Submit button
                submitButton: new FormValidation.plugins.SubmitButton()
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            }
        }
    ).on('core.form.valid', function () {
        $(".update_allowance").attr('disabled','disabled');
        $.ajax({
            type: "POST",
            url: '{{ route('employee_managements.ajax.store_request')}}',
            data: $('#form_allowance').serialize(),
            cache: false,
            /*success: function (result_data) {
                if (result_data.success == true){
                    toastr.success(result_data.data);
                    window._tables.payroll_list.datatable.reload();
                    $("#employee_modal").modal('hide');
                }
            }*/
        }).done(function (response) {
            if (response.success) {
                toastr.success(response.data);
                $('#employee_modal').modal('hide');
                window._tables.payroll_list.datatable.reload();
            } else {
                $('.update_allowance').prop("disabled", false);
                window._display_alert_error('#form_allowance', __("field_wrong_format"));
            }
        }).fail(function (jqXHR, status) {
            $('.update_allowance').prop("disabled", false);
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
        });
    });
</script>
@section('scripts')

@endsection

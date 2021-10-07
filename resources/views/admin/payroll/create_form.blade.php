@if($type == 'create')
<form method="POST" id="form_advance" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-lg-{{ Auth::user()->isAdmin() ? '6' : '12' }}">
            <label class="d-block required">{{ __("left_company") }} <span class="text-danger"> *</span></label>
            <select class="form-control select2 is-valid" id="select2_company" name="company_id">
{{--                <option selected disabled>{{ __('xin_acc_all') }}</option>--}}
                @foreach($all_companies as $company_id)
                    <option {{ Auth::user()->isAdmin() ? '' : 'selected' }}  value="{{ $company_id->company_id }}">{{ $company_id->name }}</option>
                @endforeach
            </select>
        </div>
        @if(Auth::user()->isAdmin())
        <div class="col-lg-6">
            <label class="d-block required">{{ __("empolyee__") }} <span class="text-danger"> *</span></label>
            <select class="form-control select2 is-valid" id="select2_empolyee__" name="employee_id">
                <option value="" selected disabled>{{ __('empolyee__') }}</option>
            </select>
        </div>
        @endif
        <div class="col-lg-6 pt-5">
            <label class="d-block required">{{ __("advan_money") }} <span class="text-danger"> *</span></label>
            <input autocomplete="off"  class="form-control format_number"
                   placeholder="{{__('advan_money')}}"
                   name="advance_money" type="text" value="">
        </div>
        <div class="col-lg-6 pt-5">
            <label class="d-block required">{{ __("xin_select_month") }} <span class="text-danger"> *</span></label>
            <input autocomplete="off" class="form-control month_year_advance"
                   placeholder="{{__('xin_select_month')}}"
                   name="month_year" type="text" value="{{ date('m-Y') }}">
        </div>
        <div><input type="hidden" id="salary_user"></div>
        @if(Auth::user()->isAdmin())
        <div class="col-xxl-12 col-lg-12 pt-5">
            <div class="form-group">
                <label>{{ __('xin_reason') }} <span class="text-danger"> *</span></label>
                <input class="form-control" placeholder="{{__('xin_reason')}}" name="reason" type="text" value="">
            </div>
            <div class="form-group">
                <label>{{ __('xin_attendance_upload_file') }}</label>
                <input style="background: #F3F6F9;padding-bottom: 2.6rem;" type="file" class="form-control" placeholder="{{ __('xin_attendance_upload_file') }}" name="file_advance" />
            </div>
        </div>
        @endif
    </div>

    <div class="form-group text-center mb-0">
        <button  type="submit" class="btn btn-primary mr-3 save_">{{ __("xin_save")  }}</button>
        <button data-dismiss="modal" class="btn btn-light-primary ml-3">{{ __("cancel")  }}</button>
    </div>
</form>
    <script>
        $(document).ready(function() {
            $(".month_year_advance").datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose:true,
                todayHighlight: true,
                default: 'toDay',
                setDate: new Date(),
                orientation: "bottom left",
                language: window._locale,
                endDate: "toDay",
            });
            window.formart_number(".format_number");
            // $(".format_number").keyup(function() {
            //     var price = $(this).val();
            //     text  = price.split(/[a-zA-Z]/g).join("");
            //     string = text.replace(/_|-|\./gi, "").split(/(?=(?:\d{3})+$)/).join(".");
            //     // text.replace(/\./gi, "").split(/(?=(?:\d{3})+$)/).join(".");
            //     $(this).val(string);
            // });
            $("#select2_company, #select2_empolyee__").select2();
            //show bankAcoount
            $("#select2_empolyee__ , .month_year_advance").change(function (e) {
                let employee_id = $('#select2_empolyee__').val();
                let month_year_advance = $('.month_year_advance').val();
                $.ajax({
                    type: "GET",
                    url: '{{ route('payrolls.getBankAccount') }}',
                    data: {employee_id:employee_id, month_year : month_year_advance},
                    cache: false,
                    success: function (result_data) {
                        // console.log(result_data.salary_advance, result_data);
                        if (result_data){
                            formValidator.revalidateField('advance_money');
                            formValidator.revalidateField('employee_id');
                            let price = window._userCurrency(result_data.salary_advance);
                          $("#salary_advance").html(price.split("₫").join(" đồng"));
                            $("input[name=advance_money]").val(result_data.salary_advance.toString().replace(/\./gi, "").split(/(?=(?:\d{3})+$)/).join("."));
                            $("#salary_user").val(result_data.salary_advance);
                            if (result_data.salary_advanced_amounted.length > 0){
                                var html = '<ul class="list_advanced text-danger">';
                                var count = 1;
                                result_data.salary_advanced_amounted.forEach(function (data) {
                                    let price_advanced = window._userCurrency(parseInt(data));
                                    html += '<li><span>'+ count++ +'.</span> Bạn đã ứng: '+price_advanced+'</li>';
                                });
                                html += '</ul>';
                                $("#advanced_user").html(html);
                            }else{
                                $("#advanced_user").html("");
                            }
                        }
                    }
                });

            });
            $("#select2_company").change(function(e) {
                e.preventDefault();
                const self = $(this);
                let company_id = self.val();
                var html = '';
                $.ajax({
                    type: "GET",
                    url: '/payrolls/listEmployeeCompany',
                    data: {company_id:company_id},
                    cache: false,
                    success: function (result_data) {
                        if(result_data){
                            let is_admin = {{ Auth::user()->isAdmin() ? 1 : 2  }};
                            let user_id = {{ Auth::id() }};
                            html += '<option value="0" selected disabled>'+__('xin_complaint_employees')+'</option>';
                            $.each(result_data, function (key,item){
                                    html += '<option value="'+item['user_id']+'">';
                                    html += item['first_name'] + ' ' + item['last_name'];
                                    html += '</option>';
                            });
                            $("#select2_empolyee__").html(html);
                        }

                    }
                });
            });
            $("#select2_company").trigger("change");
        //    form-validation
            function check(value){
                let data_price= $("#salary_user").val();
                let price_advance = data_price.split(".").join("");
                value = parseInt(value);
                data_price = parseInt(data_price);
                if (value === 0 && data_price === 0) {
                    return false;
                }else if (isNaN(value) || isNaN(data_price)) {
                    return {
                        valid: false,
                        message: 'Chưa có thông tin tạm ứng.'
                    };
                } else if (value > data_price) {
                    return {
                        valid: false,
                        message: 'Bạn có thể tạm ứng tối đa ' + window._userCurrency(data_price)
                    };
                }
                return true;
                // if (value == ''){
                //     return {
                //         valid: false,
                //         message: __('field_is_required_meetings'),
                //     };
                // }else if($.isNumeric(value.split(".").join("")) == false){
                //     return {
                //         valid: false,
                //         message: 'Dữ liệu không phải là số',
                //     };
                // }else if (value > data_price){
                //     console.log(value, data_price);
                //     return {
                //         valid: false,
                //         message: 'Bạn có thể tạm ứng tối đa ' + data_price
                //     };
                // }else if (value < 0){
                //     return {
                //         valid: false,
                //         message: 'Số tiền phải là số dương'
                //     };
                // }else{
                //     return true;
                // }
            }

            formValidator = FormValidation.formValidation(
                document.getElementById('form_advance'),
                {
                    fields: {
                        company_id: {
                            validators: {
                                notEmpty: {
                                    message: __('field_is_required_meetings')
                                },
                            }
                        },
                        advance_money: {
                            validators: {
                                callback: {
                                    message: __('field_is_required_meetings'),
                                    callback: function(input) {
                                        return check(input.value.split(".").join(""));
                                    }
                                }
                                // between: {
                                //     min: 0,
                                //     max: 90,
                                //     message: 'The latitude must be between -90.0 and 90.0'
                                // }
                            }
                        },
                        employee_id: {
                            validators: {
                                // notEmpty: {
                                //     message: __('field_is_required_meetings')
                                // },
                                callback: {
                                    message: __('field_is_required_meetings'),
                                    callback: function(input) {
                                        if (input.value == 0){
                                            return {
                                                valid: false,
                                                message: __('field_is_required_meetings'),
                                            }
                                        }else{
                                            return true;
                                        }

                                    }
                                }
                            }
                        },
                        file_advance: {
                            // fileInput: {
                            //     validators: {
                            //         notEmpty: {
                            //             message: 'Please select an image'
                            //         },
                            //         // file: {
                            //         //     extension: 'jpeg,jpg,png',
                            //         //     type: 'image/jpeg,image/png',
                            //         //     maxSize: 2097152,   // 2048 * 1024
                            //         //     message: 'The selected file is not valid'
                            //         // },
                            //     }
                            // }
                            // validators: {
                            //     notEmpty: {
                            //         message: __('file_scan_accpet_advance'),
                            //     },
                            //
                            // }
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
                let data = new FormData($('#form_advance')[0]);
                $(document).ready(function () {
                    $(".save_").text(__('saving')).attr('disabled','disabled');
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('payrolls.createRequest') }}",
                    data: data,
                    mimeType: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                }).done(function(result_data) {
                    console.log(result_data.success);
                    if (result_data.success === false){
                        toastr.error(result_data.errors);
                    }else{
                        toastr.success(result_data.success ?? __("xin_theme_success"));
                        setTimeout(function () {
                            window._tables.advance_list.datatable.reload();
                            $("#update_advance_request").modal('hide');
                            $("#form_advance").trigger("reset");
                        }, 1000);
                    }

                }).fail(function (jqXHR, status){
                    // console.log(jqXHR);
                    result_data = JSON.parse(jqXHR.responseText);
                    $(".save_").text(__('xin_save')).removeAttr('disabled');
                    toastr.error(result_data.errors ?? __("error"));
                    });
            });
        });
    </script>
@else
    <div class="modal-header justify-content-center border-bottom-0">
        <h5 class="modal-title font_title_">{{ __("Chi tiết lương").' '.$salaryPayslips->employeeSalary->first_name .' '.$salaryPayslips->employeeSalary->last_name }} </h5>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="box-body">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                                <tbody>
                                <tr>
                                    <td><strong>Lương cơ bản:</strong> <span class="pull-right">
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->wages_type == 2 ? $salaryPayslips->trail_salary : $salaryPayslips->basic_salary) }}
                                        </span></td>
                                </tr>
                                <!-- công/tháng -->

                                <tr>
                                    <td><strong>Ngày công/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_working_days }} ngày</span></td>
                                </tr>
                                <!--  -->
                                <!-- công thực tế/tháng -->
                                <tr>
                                    <td><strong>Ngày công thực tế/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_all_work_month }} ngày</span></td>
                                </tr>
                                <!--  -->
                                <!-- Lễ -->
                                <tr>
                                    <td><strong>Nghỉ lễ/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_holidays }} ngày</span></td>
                                </tr>
                                <!--  -->
                                <!-- nghỉ phép -->
                                <tr>
                                    <td><strong>Nghỉ phép/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_leave_days }} ngày</span></td>
                                </tr>
                                <!--  -->
                                <!-- thời gian đến muộn -->
                                <tr>
                                    <td><strong>Tổng thời gian đến muộn/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_all_late_month }} phút</span></td>
                                </tr>
                                <!--  -->
                                <!-- thời gian về sớm -->
                                <tr>
                                    <td><strong>Tổng thời về sớm/tháng:</strong> <span class="pull-right">{{ $salaryPayslips->total_all_leave_month }} phút</span></td>
                                </tr>
                                <!-- Tổng công OT trong tháng -->
                                <tr>
                                    <td><strong>(-) Tiền BH bắt buộc:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->total_statutory_deductions) }}</span></td>
                                </tr>

                                <tr>
                                    <td><strong>Tổng thu trước thuế:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->net_salary) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>(+) Tổng số phụ cấp:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->total_allowances) }}</span></td>
                                </tr>
                                <!-- tien tam ung  -->
                                <tr>
                                    <td><strong>(-) Tiền tạm ứng:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->total_advance) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>(-) Tổng tiền đặt cơm/tháng:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->total_price_datcom) }}</span></td>
                                </tr>
                                <!-- -->
                                <tr>
                                    <td><strong>(-) Tổng số cho vay:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->total_loan) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>(-) Khấu trừ thuế:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->saudi_gosi_amount) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Số tiền thực nhận:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salaryPayslips->grand_net_salary) }}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

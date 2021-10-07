@if(isset($type))
    <form method="POST"  id="form_deductions">
        <input type="hidden" name="employee_id" value="{{ $employee_id }}">
        <input type="hidden" name="module" value="{{ $module }}">
        @if($find_data_module)
            <input type="hidden" name="loan_deduction_id" value="{{ $find_data_module->loan_deduction_id }}">
        @endif
        <div class="form-group row">
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_partially_taxable") }} <span class="text-danger"> *</span></label>
                <select class="form-control select2 is-valid select2_allowance"  name="loan_options">
                    <option {{ $find_data_module && $find_data_module->loan_options == 1 ? 'selected' : '' }} value="1">
                        {{ __('xin_loan_ssc_title') }}
                    </option>
                    <option {{ $find_data_module && $find_data_module->loan_options == 2 ? 'selected' : '' }} value="2">
                        {{ __('xin_loan_hdmf_title') }}
                    </option>
                    <option {{ $find_data_module && $find_data_module->loan_options == 0 ? 'selected' : '' }} value="0">
                        {{ __('xin_loan_other_sd_title') }}
                    </option>

                </select>
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("dashboard_xin_title") }} <span class="text-danger"> *</span></label>
                <input class="form-control  end"
                       placeholder="{{ __("dashboard_xin_title") }}" name="loan_deduction_title" type="text"
                       value="{{ $find_data_module ? $find_data_module->loan_deduction_title : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("xin_employee_monthly_installment_title") }} <span class="text-danger"> *</span></label>
                <input class="form-control  end"
                       placeholder="{{ __("xin_employee_monthly_installment_title") }}" name="monthly_installment" type="text"
                       value="{{ $find_data_module ? $find_data_module->monthly_installment : '' }}">
                <div class="checkBox">
                    <label class="checkbox mt-1 font-italic">
                        <input type="checkbox" {{ $find_data_module && $find_data_module->is_deduction_salary == 1 ? 'checked' : '' }}  name="is_deduction_salary" value="1"/>
                        <span class="mr-2"></span>
                        {{ __('xin_is_deduction_salary') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group mb-1">
            <label for="exampleTextarea">{{ __('Lý do vay') }} <span class="text-danger">*</span></label>
            <textarea class="form-control" name="reason" id="exampleTextarea" rows="3">{{ $find_data_module ? $find_data_module->reason : '' }}</textarea>
        </div>
        </div>
        <div class="form-group text-center mb-0">
            <button type="submit" class="update_deductions btn btn-primary">{{ $find_data_module ? __("xin_edit_leave") : __("xin_save") }}</button>
            <button type="reset" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close')  }}</button>
        </div>
    </form>
    <!-- JS Form -->
    <script>
        FormValidation.formValidation(
            document.getElementById('form_deductions'),
            {
                fields: {
                    statutory_options: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    loan_deduction_title: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    loan_deduction_amount: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    reason: {
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
            $(document).ready(function () {
                // $(".update_deductions").text(__('saving')).attr('disabled','disabled');
            });
            $.ajax({
                type: "POST",
                url: '{{ route('employee_managements.updateRequestloanDeductions') }}',
                data: $('#form_deductions').serialize(),
                cache: false,
                success: function (result_data) {
                    console.log(result_data);
                    if (result_data.success == true){
                        toastr.success(result_data.data);
                        window._tables.loan_deductions_list.datatable.reload();

                        $("#loan_deductions_form").modal('hide');
                    }else{
                        toastr.success(result_data.error);
                    }
                }
            });
        });
    </script>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
<script>
    //[name=is_allowance_taxable] [name=amount_option]
    $(".select2_allowance").select2({placeholder: __('xin_partially_taxable'),});
    $(".select2_allowance").select2({placeholder: __('xin_amount_option'),});
</script>

@section('scripts')

@endsection

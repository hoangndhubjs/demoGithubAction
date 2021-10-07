@if(isset($type))
    <form method="POST"  id="form_deductions">
        <input type="hidden" name="employee_id" value="{{ $employee_id }}">
        <input type="hidden" name="module" value="{{ $module }}">
        @if($find_data_module)
            <input type="hidden" name="statutory_deductions_id" value="{{ $find_data_module->statutory_deductions_id }}">
        @endif
        <div class="form-group row">
            <div class="col-lg-6">
                <label class="d-block required">{{ __("dashboard_xin_title") }} <span class="text-danger"> *</span></label>
                <input class="form-control start"
                       placeholder="{{ __("dashboard_xin_title") }}" name="deduction_title" type="text"
                       value="{{ $find_data_module ? $find_data_module->deduction_title : '' }}">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("basic_salary_region") }} <span class="text-danger"> *</span></label>
                <input class="form-control  end"
                       placeholder="{{ __("basic_salary_region") }}" name="basic_salary_region" type="text"
                       value="{{ $find_data_module ? $find_data_module->basic_salary_region : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_partially_taxable") }} <span class="text-danger"> *</span></label>
                <select class="form-control select2 is-valid select2_allowance"  name="statutory_options">
                    <option {{ $find_data_module && $find_data_module->statutory_options == 1 ? 'selected' : '' }} value="1">{{ __('xin_title_tax_fixed') }}</option>
                    <option {{ $find_data_module && $find_data_module->statutory_options == 2 ? 'selected' : '' }} value="2">{{ __('xin_title_tax_percent') }}</option>
                </select>
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_title_tax_percent") }} <span class="text-danger"> *</span></label>
                <input class="form-control  end"
                       placeholder="{{ __("xin_title_tax_percent") }}" name="tax_percent" type="text"
                       value="{{ $find_data_module ? $find_data_module->tax_percent : '' }}">
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
                    deduction_title: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    basic_salary_region: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    statutory_options: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    tax_percent: {
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
                $(".update_deductions").text(__('saving')).attr('disabled','disabled');
            });
            $.ajax({
                type: "POST",
                url: '{{ route('employee_managements.updateRequestDeductions') }}',
                data: $('#form_deductions').serialize(),
                cache: false,
                success: function (result_data) {
                    console.log(result_data);
                    if (result_data.success == true){
                        toastr.success(result_data.data);
                        window._tables.deductions_list.datatable.reload();

                        $("#deductions_form").modal('hide');
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

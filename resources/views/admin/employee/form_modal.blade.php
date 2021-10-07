
@if(isset($type))
    <form method="POST"  id="form_allowance">
        <input type="hidden" name="employee_id" value="{{ $employee_id }}">
        @if($allowance)
            <input type="hidden" name="allowance_id" value="{{ $allowance->allowance_id }}">
        @endif
        <div class="form-group row">
            <div class="col-lg-12">
                <label class="d-block required">{{ __("money_plus") }} <span class="text-danger"> *</span></label>
                <input class="form-control start"
                       placeholder="{{ __("money_plus") }}" name="allowance_title" type="text"
                       value="{{ $allowance ? $allowance->allowance_title : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_select_month") }} <span class="text-danger"> *</span></label>
                <input class="form-control start"
                       placeholder="{{ __("xin_select_month") }}" id="year_month_allowance" name="year_month" type="text"
                       value="{{ $allowance ? date('m-Y', strtotime($allowance->year_month)) : date('m-Y') }}">
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_amount") }} <span class="text-danger"> *</span></label>
                <input class="numberfm form-control"
                       placeholder="{{ __('xin_amount') }}" name="allowance_amount" type="text"
                       value="{{ $allowance ? $allowance->allowance_amount : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_partially_taxable") }} <span class="text-danger"> *</span></label>
{{--                <input  class="form-control start "--}}
{{--                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="is_allowance_taxable" type="text"--}}
{{--                       value="">--}}
                <select class="form-control select2 is-valid select2_allowance"  name="is_allowance_taxable">
                    <option {{ $allowance && $allowance->is_allowance_taxable==0 ? "selected" : "" }} value="0" >{{ __('xin_salary_allowance_non_taxable') }}</option>
{{--                    <option {{ $allowance && $allowance->is_allowance_taxable==1 ? "selected" : "" }} value="1" >{{ __('xin_fully_taxable') }}</option>--}}
{{--                    <option {{ $allowance && $allowance->is_allowance_taxable==2 ? "selected" : "" }} value="2" >{{ __('xin_partially_taxable') }}</option>--}}
                </select>
            </div>
            <div class="col-lg-6">
                <label class="d-block required">{{ __("xin_amount_option") }} <span class="text-danger"> *</span></label>
{{--                <input  class="form-contro end select2_allowance"--}}
{{--                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="amount_option" type="text"--}}
{{--                       value="">--}}
                <select class="form-control select2 is-valid select2_allowance"  name="amount_option">
                    <option {{ $allowance && $allowance->amount_option==1 ? "selected" : "" }} value="1">{{ __('xin_title_tax_fixed') }}</option>
{{--                    <option {{ $allowance && $allowance->amount_option==2 ? "selected" : "" }} value="2">{{ __('xin_title_tax_percent') }}</option>--}}
                </select>
            </div>
        </div>
        <div class="form-group text-center mb-0">
            <button type="submit" class="update_allowance btn btn-primary">{{ $allowance ? __("xin_edit_leave") : __("xin_save") }}</button>
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
    $(".select2_allowance").select2({placeholder: __('xin_partially_taxable'),});
    $(".select2_allowance").select2({placeholder: __('xin_amount_option'),});
    $(document).ready(function () {
        var date=new Date();
        var year=date.getFullYear();
        var month=date.getMonth();

        $("#year_month_allowance").datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            orientation: "bottom",
            endDate: new Date(year, month, '01'),
            todayHighlight: true,
            language: window._locale,
            autoclose:true
        });
    })

</script>
<script>
    $(document).ready(function () {
        window.formart_number(".numberfm");
    });
    FormValidation.formValidation(
        document.getElementById('form_allowance'),
        {
            fields: {
                allowance_title: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                is_allowance_taxable: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                amount_option: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                allowance_amount: {
                    validators: {
                        callback: {
                            message: __('field_is_required_meetings'),
                            callback: function(input) {
                                if (input.value.split('.').join('') < 0){
                                    return {
                                        valid: false,
                                        message: 'Số tiền nhập là số nguyên dương',
                                    };
                                }else{
                                    return { valid: true}
                                }
                            }
                        }
                        // notEmpty: {
                        //     message: __('field_is_required_meetings')
                        // },
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
            $(".update_allowance").text(__('saving')).attr('disabled','disabled');
        });
        $.ajax({
            type: "POST",
            url: '/employee_managements/ajax/update-request',
            data: $('#form_allowance').serialize(),
            cache: false,
            success: function (result_data) {
                if (result_data.success == true){
                    toastr.success(result_data.data);
                    window._tables.allowance_list.datatable.reload();
                    $("#allowance_form").modal('hide');
                }
            }
        });
    });
</script>
@section('scripts')
@endsection

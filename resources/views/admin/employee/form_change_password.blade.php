@if(isset($profile))
<p>Mật khẩu mới cho <b>{{ $profile->getFullname() }}</b></p>
    <form method="POST"  id="form_change_password">
        @if($profile)
            <input type="hidden" name="id" value="{{ $profile->user_id }}">
        @endif
        <div class="form-group row">
            <div class="col-md-12 mb-3 mt-5">
                <label class="d-block required">{{ __("xin_e_details_enpassword") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                    placeholder="{{ __("xin_e_details_enpassword") }}" name="password" type="password">
            </div>
            <div class="col-md-12">
                <label class="d-block required">{{ __("xin_employee_cpassword") }} <span class="text-danger"> *</span></label>
                <input class="form-control"
                    placeholder="{{ __("xin_employee_cpassword") }}" name="confirm_password" type="password">
            </div>
        </div>
        <div class="form-group text-right mb-0">
            <button type="submit" class="change_password btn btn-primary">{{ __("xin_save") }}</button>
            <button type="reset" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close')  }}</button>
        </div>
    </form>
<!-- JS Form -->
{{-- form-deductions  --}}
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
<script>
    formValidator =  FormValidation.formValidation(
        form = document.getElementById('form_change_password'),
        {
            fields: {
                password: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                        stringLength: {
                            min: 6,
                            message: __('xin_employee_error_password_least')
                        }
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
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                submitButton: new FormValidation.plugins.SubmitButton()
            }
        }
    ).on('core.form.valid', function () {
        $(document).ready(function () {
            $(".change_password").text(__('saving')).attr('disabled','disabled');
        });
        $.ajax({
            type: "POST",
            url: '/employee_managements/ajax/change-password-employee',
            data: $('#form_change_password').serialize(),
            cache: false,
            success: function (result_data) {
                if (result_data.success == true){
                    formValidator.resetForm(true);
                    toastr.success(result_data.data);
                    // window._tables.payroll_list.datatable.reload();
                    $("#change_password_modal").modal('hide');
                }
            },
            error: function (result_data) {
                $(".change_password").text(__('xin_save')).attr('disabled',false);
                toastr.error(result_data.error ?? __("error"));
            },
        });
    });
</script>

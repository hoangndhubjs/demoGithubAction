<form id="form_income_type">
    @if($income_type)
        <input type="hidden" name="id" value="{{$income_type->category_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_income_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại thu nhập" name="income_type" value="{{$income_type->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_income_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateIncomeType = document.getElementById('form_income_type');
    submitButton = formUpdateIncomeType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateIncomeType,
        {
            fields: {
                'income_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_employee_error_d_type') }}',
                        }
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                bootstrap: new FormValidation.plugins.Bootstrap({})
            }
        }
    ).on('core.form.valid', function () {
        $.ajax({
            url: window.income_type_update_url,
            data: $('#form_income_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.income_type && window._tables.income_type.datatable.reload();
                $('#sm_update_income_type').prop('disabled', true);
                $('#sm_update_income_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_income_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
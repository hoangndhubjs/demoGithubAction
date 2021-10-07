<form id="form_company_type">
    @if($company_type)
        <input type="hidden" name="id" value="{{$company_type->type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_company_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="company_type" value="{{$company_type->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_company_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateCompanyType = document.getElementById('form_company_type');
    submitButton = formUpdateCompanyType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateCompanyType,
        {
            fields: {
                'company_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_ctype_field') }}',
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
            url: window.company_type_update_url,
            data: $('#form_company_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.company_type && window._tables.company_type.datatable.reload();
                $('#sm_update_company_type').prop('disabled', true);
                $('#sm_update_company_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_company_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
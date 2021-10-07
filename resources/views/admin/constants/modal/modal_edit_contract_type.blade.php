<form id="form_contract_type">
    @if($contract_type)
        <input type="hidden" name="id" value="{{$contract_type->contract_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_e_details_contract_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="contract_type" value="{{$contract_type->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_contract_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateContractType = document.getElementById('form_contract_type');
    submitButton = formUpdateContractType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateContractType,
        {
            fields: {
                'contract_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_employee_error_contract_type') }}',
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
            url: window.contract_type_update_url,
            data: $('#form_contract_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.contract_type && window._tables.contract_type.datatable.reload();
                $('#sm_update_contract_type').prop('disabled', true);
                $('#sm_update_contract_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_contract_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
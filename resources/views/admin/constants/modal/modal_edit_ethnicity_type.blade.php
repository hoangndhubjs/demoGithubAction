<form id="form_ethnicity_type">
    @if($ethnicity_type)
        <input type="hidden" name="id" value="{{$ethnicity_type->ethnicity_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_ethnicity_type_title') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="ethnicity_type" value="{{$ethnicity_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_ethnicity_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateEthnicityType = document.getElementById('form_ethnicity_type');
    submitButton = formUpdateEthnicityType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateEthnicityType,
        {
            fields: {
                'ethnicity_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_ethnicity_type_error_field') }}',
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
            url: window.ethnicity_type_update_url,
            data: $('#form_ethnicity_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.ethnicity_type && window._tables.ethnicity_type.datatable.reload();
                $('#sm_update_ethnicity_type').prop('disabled', true);
                $('#sm_update_ethnicity_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_ethnicity_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
<form id="form_security_type">
    @if($security_type)
        <input type="hidden" name="id" value="{{$security_type->type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_security_level') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="security_type" value="{{$security_type->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_security_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateSecurityType = document.getElementById('form_security_type');
    submitButton = formUpdateSecurityType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateSecurityType,
        {
            fields: {
                'security_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_security_level_field') }}',
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
            url: window.security_type_update_url,
            data: $('#form_security_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.security_type && window._tables.security_type.datatable.reload();
                $('#sm_update_security_type').prop('disabled', true);
                $('#sm_update_security_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_security_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
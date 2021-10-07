<form id="form_warning_type">
    @if($warning_type)
        <input type="hidden" name="id" value="{{$warning_type->warning_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_warning_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại cảnh báo" name="type" value="{{$warning_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_warning_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateWarningType = document.getElementById('form_warning_type');
    submitButton = formUpdateWarningType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateWarningType,
        {
            fields: {
                'type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_employee_error_warning_type') }}',
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
            url: window.warning_type_update_url,
            data: $('#form_warning_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.warning_type && window._tables.warning_type.datatable.reload();
                $('#sm_update_warning_type').prop('disabled', true);
                $('#sm_update_warning_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_warning_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
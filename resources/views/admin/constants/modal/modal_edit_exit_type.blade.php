<form id="form_exit_type">
    @if($exit_type)
        <input type="hidden" name="id" value="{{$exit_type->exit_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('Hình thức nghỉ') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên hình thức nghỉ" name="exit_type" value="{{$exit_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_exit_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateExitType = document.getElementById('form_exit_type');
    submitButton = formUpdateExitType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateExitType,
        {
            fields: {
                'exit_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('field_required') }}',
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
            url: window.exit_type_update_url,
            data: $('#form_exit_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.exit_type && window._tables.exit_type.datatable.reload();
                $('#sm_update_exit_type').prop('disabled', true);
                $('#sm_update_exit_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_exit_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
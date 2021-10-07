<form id="form_termination_type">
    @if($termination_type)
        <input type="hidden" name="id" value="{{$termination_type->termination_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_termination_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="termination_type" value="{{$termination_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_termination_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateTerminationType = document.getElementById('form_termination_type');
    submitButton = formUpdateTerminationType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateTerminationType,
        {
            fields: {
                'termination_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_termination_type') }}',
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
            url: window.termination_type_update_url,
            data: $('#form_termination_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.termination_type && window._tables.termination_type.datatable.reload();
                $('#sm_update_termination_type').prop('disabled', true);
                $('#sm_update_termination_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_termination_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
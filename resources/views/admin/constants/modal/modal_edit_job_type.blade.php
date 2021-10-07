<form id="form_job_type">
    @if($job_type)
        <input type="hidden" name="id" value="{{$job_type->job_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_job_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên công việc" name="job_type" value="{{$job_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_job_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateJobType = document.getElementById('form_job_type');
    submitButton = formUpdateJobType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateJobType,
        {
            fields: {
                'job_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_jobpost_type') }}',
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
            url: window.job_type_update_url,
            data: $('#form_job_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.job_type && window._tables.job_type.datatable.reload();
                $('#sm_update_job_type').prop('disabled', true);
                $('#sm_update_job_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_job_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
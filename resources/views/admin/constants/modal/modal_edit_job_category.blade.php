<form id="form_job_category">
    @if($job_category)
        <input type="hidden" name="id" value="{{$job_category->category_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_rec_job_categories') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập danh mục công việc" name="job_category" value="{{$job_category->category_name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_job_category" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateJobCategory = document.getElementById('form_job_category');
    submitButton = formUpdateJobCategory.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateJobCategory,
        {
            fields: {
                'job_category': {
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
            url: window.job_category_update_url,
            data: $('#form_job_category').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.job_category && window._tables.job_category.datatable.reload();
                $('#sm_update_job_category').prop('disabled', true);
                $('#sm_update_job_category').html(__("saving"));
                setTimeout(function () {
                    $('#show_job_category').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
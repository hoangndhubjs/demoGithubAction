<form id="form_edu_level">
    @if($edu_level)
        <input type="hidden" name="id" value="{{$edu_level->education_level_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_e_details_edu_level') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập trình độ học vấn" name="edu_level" value="{{$edu_level->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_edu_level" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateEduLevel = document.getElementById('form_edu_level');
    submitButton = formUpdateEduLevel.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateEduLevel,
        {
            fields: {
                'edu_level': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_education_level') }}',
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
            url: window.edu_level_update_url,
            data: $('#form_edu_level').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.edu_level && window._tables.edu_level.datatable.reload();
                $('#sm_update_edu_level').prop('disabled', true);
                $('#sm_update_edu_level').html(__("saving"));
                setTimeout(function () {
                    $('#show_edu_level').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
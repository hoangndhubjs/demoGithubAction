<form id="form_skill">
    @if($skill)
        <input type="hidden" name="id" value="{{$skill->skill_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_skill') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên ngôn ngữ" name="skill" value="{{$skill->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_skill" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateSkill = document.getElementById('form_skill');
    submitButton = formUpdateSkill.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateSkill,
        {
            fields: {
                'skill': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_education_skill') }}',
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
            url: window.skill_update_url,
            data: $('#form_skill').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.skill && window._tables.skill.datatable.reload();
                $('#sm_update_skill').prop('disabled', true);
                $('#sm_update_skill').html(__("saving"));
                setTimeout(function () {
                    $('#show_skill').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
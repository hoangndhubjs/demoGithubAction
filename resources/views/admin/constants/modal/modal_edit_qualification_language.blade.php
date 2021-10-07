<form id="form_language">
    @if($language)
        <input type="hidden" name="id" value="{{$language->language_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_e_details_language') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên ngôn ngữ" name="language" value="{{$language->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_language" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateLanguage = document.getElementById('form_language');
    submitButton = formUpdateLanguage.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateLanguage,
        {
            fields: {
                'language': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_education_language') }}',
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
            url: window.language_update_url,
            data: $('#form_language').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.language && window._tables.language.datatable.reload();
                $('#sm_update_language').prop('disabled', true);
                $('#sm_update_language').html(__("saving"));
                setTimeout(function () {
                    $('#show_language').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
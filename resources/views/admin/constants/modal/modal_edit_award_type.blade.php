<form id="form_award_type">
    @if($award_type)
        <input type="hidden" name="id" value="{{$award_type->award_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_award_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="award_type" value="{{$award_type->award_type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_award_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateAwardType = document.getElementById('form_award_type');
    submitButton = formUpdateAwardType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateAwardType,
        {
            fields: {
                'award_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_award_error_award_type') }}',
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
            url: window.award_type_update_url,
            data: $('#form_award_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.award_type && window._tables.award_type.datatable.reload();
                $('#sm_update_award_type').prop('disabled', true);
                $('#sm_update_award_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_award_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
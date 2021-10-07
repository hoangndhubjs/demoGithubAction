<form id="form_arrangement_type">
    @if($arrangement_type)
        <input type="hidden" name="id" value="{{$arrangement_type->arrangement_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('Hình thức du lịch') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên hình thức nghỉ" name="arrangement_type" value="{{$arrangement_type->type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_arrangement_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateArrangementType = document.getElementById('form_arrangement_type');
    submitButton = formUpdateArrangementType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateArrangementType,
        {
            fields: {
                'arrangement_type': {
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
            url: window.arrangement_type_update_url,
            data: $('#form_arrangement_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.arrangement_type && window._tables.arrangement_type.datatable.reload();
                $('#sm_update_arrangement_type').prop('disabled', true);
                $('#sm_update_arrangement_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_arrangement_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
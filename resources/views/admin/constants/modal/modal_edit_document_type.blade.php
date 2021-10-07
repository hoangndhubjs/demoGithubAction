<form id="form_document_type">
    @if($document_type)
        <input type="hidden" name="id" value="{{$document_type->document_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_e_details_dtype') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại tài liệu" name="document_type" value="{{$document_type->document_type}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_document_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateDocumentType = document.getElementById('form_document_type');
    submitButton = formUpdateDocumentType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateDocumentType,
        {
            fields: {
                'document_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_employee_error_d_type') }}',
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
            url: window.document_type_update_url,
            data: $('#form_document_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.document_type && window._tables.document_type.datatable.reload();
                $('#sm_update_document_type').prop('disabled', true);
                $('#sm_update_document_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_document_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
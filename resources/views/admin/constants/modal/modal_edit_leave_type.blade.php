<form id="form_leave_type">
    @if($leave_type)
        <input type="hidden" name="id" value="{{$leave_type->leave_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <div>
            <label class="col-form-label">{{ __('Lý do nghỉ') }}</label>
            <div class="">
            <input type="text" class="form-control" placeholder="Nhập lý do nghỉ" name="type_name" value="{{$leave_type->type_name}}">
            </div>
        </div>
        <div>
            <label class="col-form-label">{{ __('Số ngày mỗi năm') }}</label>
            <div class="">
                <input type="text" class="form-control" name="days_per_year" placeholder="Nhập một số" value="{{$leave_type->days_per_year}}">
            </div>
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_leave_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateLeaveType = document.getElementById('form_leave_type');
    submitButton = formUpdateLeaveType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateLeaveType,
        {
            fields: {
                'type_name': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('field_required') }}',
                        }
                    }
                },
                'days_per_year': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('field_required') }}',
                        },
                        numeric: {
                            message: '{{ __('Số ngày phải là số') }}',
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
            url: window.leave_type_update_url,
            data: $('#form_leave_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.leave_type && window._tables.leave_type.datatable.reload();
                $('#sm_update_leave_type').prop('disabled', true);
                $('#sm_update_leave_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_leave_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
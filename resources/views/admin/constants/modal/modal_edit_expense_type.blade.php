<form id="form_expense_type">
    @if($expense_type)
        <input type="hidden" name="id" value="{{$expense_type->expense_type_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('left_company') }}</label>
        <div class="">
            <select name="company_id" id="" class="form-control selectpicker1">
                @foreach ($company as $item)
                    <option @if ($item->company_id == $expense_type->company_id)
                        selected
                    @endif value="{{ $item->company_id}}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="col-form-label">{{ __('xin_expense_type') }}</label>
        <div class="">
           <input type="text" class="form-control" placeholder="Nhập tên loại hợp đồng" name="expense_type" value="{{$expense_type->name}}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_expense_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    $('.selectpicker1').select2();
    formUpdateExpenseType = document.getElementById('form_expense_type');
    submitButton = formUpdateExpenseType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateExpenseType,
        {
            fields: {
                'expense_type': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_expense_type') }}',
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
            url: window.expense_type_update_url,
            data: $('#form_expense_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.expense_type && window._tables.expense_type.datatable.reload();
                $('#sm_update_expense_type').prop('disabled', true);
                $('#sm_update_expense_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_expense_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
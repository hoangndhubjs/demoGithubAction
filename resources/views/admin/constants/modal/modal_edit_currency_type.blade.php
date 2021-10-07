<form id="form_currency_type">
    @if($currency_type)
        <input type="hidden" name="id" value="{{$currency_type->currency_id}}"/>
    @endif
    <div class="form-group mb-2">
        <label class="col-form-label">{{ __('xin_currency_name') }}</label>
        <div class="">
            <input type="text" class="form-control" name="name" placeholder="Nhập tên tiền tệ" value="{{ $currency_type->name }}">
        </div>
        <label class="col-form-label">{{ __('xin_currency_code') }}</label>
        <div class="">
            <input type="text" class="form-control" name="code" placeholder="Nhập mã tiền tệ" value="{{ $currency_type->code }}">
        </div>
        <label class="col-form-label">{{ __('xin_currency_symbol') }}</label>
        <div class="">
            <input type="text" class="form-control" name="symbol" placeholder="Nhập ký hiệu tiền tệ" value="{{ $currency_type->symbol }}">
        </div>
    </div>
    <div class="form-group text-right mb-0">
        <button type="submit" id="sm_update_currency_type" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>{{ __('xin_update') }}</button>
    </div>
</form>

<script>
    formUpdateCurrencyType = document.getElementById('form_currency_type');
    submitButton = formUpdateCurrencyType.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formUpdateCurrencyType,
        {
            fields: {
                'name': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_currency_name_field') }}',
                        }
                    }
                },
                'code': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_currency_code_field') }}',
                        }
                    }
                },
                'symbol': {
                    validators: {
                        notEmpty: {
                            message: '{{ __('xin_error_currency_symbol_field') }}',
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
            url: window.currency_type_update_url,
            data: $('#form_currency_type').serialize(),
            method: 'POST',
            success: function(response) {
                window._tables.currency_type && window._tables.currency_type.datatable.reload();
                $('#sm_update_currency_type').prop('disabled', true);
                $('#sm_update_currency_type').html(__("saving"));
                setTimeout(function () {
                    $('#show_currency_type').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        })
    });
</script>
<form id="form_authority">
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="col-form-label">{{ __('Danh sách tài sản trong kho') }} <span class="text-danger"> *</span></label>
                <select name="asset_id[]" class="form-control selectpicker_list" style="width: 100%" multiple>
                    @foreach ($assets_not_working as $item)
                        <option value="{{ $item->company_asset_code }}">{{ $item->name }} còn lại ({{ $item->total }}) sản phẩm</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="col-form-label">{{ __('xin_companies') }} <span class="text-danger"> *</span></label>
                <select name="company_id" class="form-control selectpicker_company">
                    <option value=""></option>
                    @foreach ($company as $item)
                        <option value="{{ $item->company_id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6" id="employee_ajax">
                <label class="col-form-label">{{ __('xin_assets_assign_to') }} <span class="text-danger"> *</span></label>
                <select name="employee_id" class="form-control selectpicker_give" disabled>
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="col-form-label">{{ __('xin_asset_note') }}</label>
                <textarea name="asset_note" rows="3" placeholder="Ghi chú tài sản" class="form-control"></textarea>
            </div>
        </div>
    </div>

    <div class="form-group text-right mb-0">
        <button type="submit" class="btn btn-primary" id="sm_authority"><x-icon type="svg" category="Navigation" icon="Double-check"/>  {{ __("Ủy quyền") }}</button>
    </div>
</form>
<!-- JS Form -->
<script>
    $(".selectpicker_list").select2({
        placeholder: "Chọn danh sách",
        "language": {
        "noResults": function(){
                return "Không có sản phẩm nào, vui lòng thêm mới trước khi ủy quyền!";
            }
        },
    }).on('change', function (e) {
        let selected = $(this).find('option:selected').length;
        formValidator.revalidateField('asset_id[]');
    });
    $(".selectpicker_company").select2({
        placeholder: "Chọn công ty"
    }).on('change', function (e) {
        formValidator.revalidateField('company_id');
    });
    $(".selectpicker_give").select2({
        placeholder: "Vui lòng chọn công ty"
    }).on('change', function (e) {
        formValidator.revalidateField('employee_id');
    });
    $('.selectpicker_company').on("change", function(){
        $.get("get_employees/"+$(this).val(), function(data){
            let html = "";
            html += '<option value=""></option>';
            $.each(data,function(key,value){
                html += '<option value="'+value['user_id']+'">';
                    html += value['first_name'] + " " + value['last_name'];
                html += '</option>';
            });
            $(".selectpicker_give").html(html);
        });
        $(".selectpicker_give").prop('disabled', false);
        $(".selectpicker_give").select2({
            placeholder: "Chọn một nhân viên"
        });
    });
    formAuthority = document.getElementById('form_authority');
    submitButton = formAuthority.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formAuthority,
        {
            fields: {
                "asset_id[]": {
                    validators: {
                        notEmpty: {
                            message: "{{ __('field_required') }}",
                        }
                    }
                },
                "company_id": {
                    validators: {
                        notEmpty: {
                            message: "{{ __('field_required') }}",
                        }
                    }
                },
                "employee_id": {
                    validators: {
                        notEmpty: {
                            message: "{{ __('field_required') }}",
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
        $('#sm_authority').prop('disabled', true).html(__('saving'));
        $.ajax({
            url: '{{ route('admin.asset.add_authority')}}',
            data: $('#form_authority').serialize(),
            method: 'POST',
            success: function(response) {
                formValidator.resetForm(true);
                $('#sm_authority').prop('disabled', false).html('Ủy quyền');
                update_data_chart();
                setTimeout(function () {
                    $('#authority').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                $('#sm_authority').prop('disabled', false).html('Ủy quyền');
                toastr.error(response.error ?? __("error"));
            },
        });
    });
</script>

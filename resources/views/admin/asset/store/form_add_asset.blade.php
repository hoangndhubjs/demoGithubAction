<form id="form_add_asset" enctype="multipart/form-data">
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="col-form-label">{{ __('xin_categories') }} <span class="text-danger"> *</span></label>
                <select name="category_id" id="category_id" class="form-control selectpicker1" placeholder="Chọn danh mục">
                    <option value=""></option>
                    @foreach ($asset_category as $item)
                        <option value="{{ $item->assets_category_id}}">{{ $item->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-group assets-name-find">
                    <label class="col-form-label">{{ __('xin_asset_name') }} <span class="text-danger"> *</span></label>
                    <select name="asset_name" class="form-control" id="asset_name" autocomplete="off" disabled></select>
                </div>
            </div>
            <div class="col-md-4">
                <label class="col-form-label">{{ __('xin_company_asset_code') }} <span class="text-danger"> *</span></label>
                <input type="text" name="asset_code" placeholder="Dựa trên tên sản phẩm" class="form-control assets-code" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="col-form-label">{{ __('xin_quantity') }} <span class="text-danger"> *</span></label>
                <input type="text" name="quantity" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="col-form-label">{{ __('Giá tiền') }} <span class="text-danger"> *</span></label>
                <input type="text" name="price" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="col-form-label">{{ __('xin_purchase_date') }} <span class="text-danger"> *</span></label>
                <input type="text" value="{{ date('d-m-yy')}}" name="purchase_date" autocomplete="off" class="form-control datepciker_purchase" placeholder="{{__('xin_purchase_date')}}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="col-form-label">{{ __('xin_asset_image') }} <span class="text-danger"> *</span></label>
                <input type="file" accept="image/*" name="asset_image" class="form-control" style="padding-bottom: 2.6rem;">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="col-form-label">{{ __('Chi tiết tài sản') }} <span class="text-danger"> *</span></label>
                <textarea name="detail_assets" rows="3" placeholder="Linh kiện&#10Mỗi linh kiện là một dòng" class="form-control"></textarea>
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
        <button type="submit" class="btn btn-primary" id="sm_form_add_asset"><x-icon type="svg" category="Navigation" icon="Double-check"/>  {{ __("xin_add_new") }}</button>
    </div>
</form>
<!-- JS Form -->
<script>
    $(".selectpicker1").select2({
        placeholder: "Chọn danh mục"
    }).on('change', function (e) {
        formValidator1.revalidateField('category_id');
    });

    $("#asset_name").select2({
        placeholder: "Vui lòng chọn danh mục"
    }).on('change', function (e) {
        formValidator1.revalidateField('asset_name');
        var selectedAssetName = $(this).children("option:selected").val();
        formValidator1.revalidateField('asset_code');
        $(".assets-code").prop('disabled', false);
        $(".assets-code").val(selectedAssetName);
    });
    
    $(".datepciker_purchase").datepicker({
        todayHighlight: true,
        orientation: "bottom left",
        changeYear: true,
        format: "dd-mm-yyyy",
        autoclose: true,
        setDate: new Date(),
        endDate: "toDay",
        language: window._locale
    });
    $(".datepciker_purchase").datepicker().on('show.bs.modal', function(event) {
        event.stopPropagation(); 
    });
    $('.selectpicker1').on("change", function(){
        $("#asset_name").prop('disabled', false);
        $("#asset_name").select2({
            placeholder: "Chọn sản phẩm"
        })

        var categoryVal =  $(this).val();
        var resultDropdown = $('.assets-name-find input[type="text"]').siblings(".result-assets-name");
        $.post("/admin/asset/find-assets", {category_id:categoryVal}).done(function(data){
            $(".result-assets-name").show().addClass('border-bottom');
            let html = "";
            html += '<option value=""></option>';
            $.each(data,function(key,value){
                html += '<option value="'+value['company_asset_code']+'">';
                    html += value['name'];
                html += '</option>';
            });
            $("#asset_name").html(html);
        });
    });
   
    formAddAsset = document.getElementById('form_add_asset');
    submitButton = formAddAsset.querySelector('[type="submit"]');

    formValidator1 = FormValidation.formValidation(
        formAddAsset,
        {
            fields: {
                "category_id": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        }
                    }
                },
                "asset_name": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        }
                    }
                },
                "asset_code": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        }
                    }
                },
                "quantity": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        },
                        numeric: {
                            message: ('Số lượng phải là số')
                        }
                    }
                },
                "price": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        },
                        numeric: {
                            message: ('Giá tiền phải là số')
                        }
                    }
                },
                "asset_image": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
                        }
                    }
                },
                "detail_assets": {
                    validators: {
                        notEmpty: {
                            message: __("field_required"),
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
        $('#sm_form_add_asset').prop('disabled', true).html(__('saving'));
        let data = new FormData($('#form_add_asset')[0]);
        $.ajax({
            url: '{{ route('admin.asset.add_store')}}',
            data: data,
            method: 'POST',
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                formValidator1.resetForm(true);
                $('#sm_form_add_asset').prop('disabled', false).html(__('xin_add_new'));
                update_data_chart();
                setTimeout(function () {
                    $('#add_store').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                $('#sm_form_add_asset').prop('disabled', false).html(__('xin_add_new'));
                toastr.error(response.error ?? __("error"));
            },
        });
    });
</script>
<div class="modal-header justify-content-center header_asset">
    <h5 class="modal-title title_module">{{  $title_module  }}</h5>
{{--    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--        <i aria-hidden="true" class="ki ki-close"></i>--}}
{{--    </button>--}}
</div>
<div class="modal-body form-container image_parent" style="min-height:150px">
    @if($type == 'warranty')
            <form method="POST" id="asset_warranry_history">
            <input type="hidden" name="asset_id" value="{{ $id }}">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label>{{__('use_time')}}<span class="text-danger"> *</span></label>
                    <input class="form-control start_warranty warranty_date" autocomplete="off"  placeholder="{{ __('asset_warranty_from') }}" name="warranty_start" type="text" value="">
                </div>
                <div class="form-group col-md-6">
                    <label>&nbsp;<span class="text-danger"></span></label>
                    <input class="form-control end_warranty warranty_date" autocomplete="off"  placeholder="{{ __('optional') }}" name="warranty_end" type="text" value="">
                </div>
            </div>
            <div class="mb-5">
                <label>{{__('note_warranty')}}<span class="text-danger"> *</span></label>
                <textarea class="form-control"  name="warranty_note" rows="3"></textarea>
            </div>
            <div class="justify-content-center">
                <div class="text-center">
                    <button class="add_asset_warranty btn btn-primary mr-2">{{ __('xin_save')  }}</button>
                    <button type="reset" class="btn btn-light-primary" data-dismiss="modal" aria-label="Close">{{ __('cancel')  }}</button>
                </div>
            </div>
        </form>

        <script>
            $(".warranty_date").datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });
            $(".add_asset_warranty").click(function (e){
                e.preventDefault();
                // console.log($("#asset_warranry_history").serialize());
                $.ajax({
                    type: "POST",
                    url: 'create_warranty',
                    data: $("#asset_warranry_history").serialize(),
                }).done(function (result_asset) {
                    console.log(result_asset);
                    if (result_asset.success == true){
                        toastr.success(result_asset.data);
                        $("#createAsset").modal('hide');
                    }
                }).fail(function (jqXHR, status) {
                    $(".add_asset").text(__('xin_save')).removeAttr('disabled');
                    result_data = JSON.parse(jqXHR.responseText);
                    $(".add_asset_warranty").text(__('xin_save')).removeAttr('disabled');
                    toastr.error(result_data.errors ?? __("error"));
                });
                return false;
            });
            // FormValidation.formValidation(
            //     document.getElementById('asset_warranry_history'),
            //     {
            //         fields: {
            //             warranty_start: {
            //                 validators: {
            //                     notEmpty: {
            //                         message: __('field_is_required_meetings')
            //                     },
            //                 }
            //             },
            //             warranty_end: {
            //                 validators: {
            //                     callback:{
            //                         callback: function (input) {
            //                             let start_date = $(".start_warranty").val();
            //                             let end_date = $(".end_warranty").val();
            //                             let start = moment(start_date);
            //                             let end = moment(end_date);
            //                             if (end_date && end < start){
            //                                 return {
            //                                     valid:false,
            //                                     message : __('date_e_warranty'),
            //                                 };
            //                             }else{
            //                                 return true;
            //                             }
            //                         }
            //                     }
            //                 }
            //             },
            //             warranty_note: {
            //                 validators: {
            //                     notEmpty: {
            //                         message: __('field_is_required_meetings')
            //                     },
            //                 }
            //             },
            //         },
            //         plugins: { //Learn more: https://formvalidation.io/guide/plugins
            //             trigger: new FormValidation.plugins.Trigger(),
            //             // Bootstrap Framework Integration
            //             bootstrap: new FormValidation.plugins.Bootstrap(),
            //             // Validate fields when clicking the Submit button
            //             submitButton: new FormValidation.plugins.SubmitButton(),
            //             // Submit the form when all fields are valid
            //             // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            //         }
            //     }
            // ).on('core.form.valid', function () {
            //     alert('123');
            //     // $(function () {
            //     //     $(".add_asset_warranty").text(__('saving')).attr('disabled','disabled');
            //     // });
            //     $.ajax({
            //         type: "POST",
            //         url: 'create_warranty',
            //         data: $("#asset_warranry_history").serialize(),
            //     }).done(function (result_asset) {
            //         toastr.success(__('xin_theme_success'));
            //         $("#createAsset").modal('hide');
            //     }).fail(function (jqXHR, status){
            //         $(".add_asset").text(__('xin_save')).removeAttr('disabled');
            //         result_data = JSON.parse(jqXHR.responseText);
            //         $(".add_asset_warranty").text(__('xin_save')).removeAttr('disabled');
            //         toastr.error(result_data.errors ?? __("error"));
            //     });
            // });
        </script>

    @else
        <form method="POST" id="asset_form" enctype="multipart/form-data">
            @if($type === 'update')
                <input type="hidden" name="assets_id" value="{{ $asset->assets_id }}">
                <input type="hidden" name="user_id_had" value="{{ $asset->employee_id }}">
            @endif
            <div class="row">
                <div class="form-group col-md-4">
                    <label>{{__('select_asset')}}<span class="text-danger"> *</span></label>
                    <select style="height: 37px" class="form-control kt-select2-companies" name="assets_category_id" data-placeholder="{{__('left_company')}}">
                        @foreach($categoryAsset as $category_id)
                            <option {{  ($asset && $asset->assets_category_id == $category_id->assets_category_id) ? 'selected' : '' }}
                                    value="{{ $category_id->assets_category_id }}">{{ $category_id->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>{{__('xin_company_asset_code')}}<span class="text-danger"> *</span></label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('xin_company_asset_code') }}" name="company_asset_code" type="text" value="{{ $asset ? $asset->company_asset_code : ''  }}">
                </div>
                <div class="form-group col-md-4">
                    <label>{{__('xin_asset_name')}}<span class="text-danger"> *</span></label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('xin_asset_name') }}" name="name" type="text" value="{{ $asset ? $asset->name : ''  }}">
                </div>
                <!--- Tình trạng hoạt động --->
                <div class="form-group col-md-12">
                    <label>{{ __('kpi_status') }}<span class="text-danger"> *</span></label>
                    <div class="radio-inline">
                        <label class="radio col-md-2 pl-0">
                            <input type="radio" {{ ($asset && $asset->is_working == 1) ? 'checked' : ''  }} value="1" name="is_working"/>
                            <span></span>
                            {{ __('xin_employees_active') }}
                        </label>
                        <label class="radio col-md-2">
                            <input type="radio" {{ ($asset && $asset->is_working == 2) ? 'checked' : ''  }} value="2" name="is_working"/>
                            <span></span>
                            {{ __('broken') }}
                        </label>
                        <label class="radio col-md-2">
                            <input type="radio" {{ ($asset && $asset->is_working == 0) ? 'checked' : ''  }} value="0" name="is_working"/>
                            <span></span>
                            {{ __('inventory') }}
                        </label>
                    </div>
                    <smal class="is_checked_status text-danger"></smal>
                </div>
                <!-- Ngày nhập -->
                <div class="form-group col-md-4">
                    <label>{{__('date_input_asset')}}</label>
                    <input class="form-control date-add-asset"  placeholder="{{ __('date_input_asset') }}" name="date_add_asset" type="text" value="{{ $asset ? $info_asset['date'] : date('d-m-Y') }}">
                </div>
                <!-- Giá trị tiền -->
                <div class="form-group col-md-4">
                    <label>{{__('price_asset')}}</label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('price_asset') }}" name="price" type="number" value="{{ $asset ? $asset->price  : '' }}">
                </div>
                <!-- Số hóa đơn -->
                <div class="form-group col-md-4">
                    <label>{{__('number_bill')}}</label>
                    <input class="form-control " autocomplete="off"  placeholder="{{ __('number_bill') }}" name="invoice_number" type="text" value="{{ $asset ? $asset->invoice_number  : '' }}">
                </div>
                <!-- Công ty -->
                <div class="form-group col-md-4">
                    <label>{{__('left_company')}}</label>
                    <select style="height: 37px" class="form-control kt-select2-companies" id="select_compnay_" data-employee="{{ $asset ? $asset->employee_id : '' }}"  name="company_id" data-placeholder="{{__('left_company')}}">
                        @foreach($all_companies as $company_id)
                            <option {{  ($asset && $asset->company_id == $company_id->company_id) ? 'selected' : '' }} value="{{ $company_id->company_id }}">{{ $company_id->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- giao cho -->
                <div class="form-group col-md-4">
                    <label>{{__('xin_assets_assign_to')}}</label>
                    <select style="height: 37px" class="form-control kt-select2-companies" id="employee_select2_ajax" name="employee_id" data-placeholder="{{__('left_company')}}">
                    </select>
                </div>
                <!-- Nhãn hiệu -->
                <div class="form-group col-md-4">
                    <label>{{__('makeIn')}}</label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('makeIn') }}" name="manufacturer" type="text" value="{{ $asset ? $asset->manufacturer  : '' }}">
                </div>
                <!-- Số sê-ri -->
                <div class="form-group col-md-4">
                    <label>{{__('xin_serial_number')}}</label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('xin_serial_number') }}" name="serial_number" type="text" value="{{ $asset ? $asset->serial_number  : '' }}">
                </div>
                <!-- hạn bảo hành -->
                <div class="form-group col-md-4">
                    <label>{{__('warranty_period')}}</label>
                    <input class="form-control warranty_period" autocomplete="off"  placeholder="{{ __('warranty_period') }}" name="warranty_end_date" type="text" value="{{ $asset ? $info_asset['warranty_date']  : '' }}">
                </div>
                <!-- tuổi thọ -->
                <div class="form-group col-md-4">
                    <label>{{__('age_life')}}</label>
                    <input class="form-control" autocomplete="off"  placeholder="{{ __('age_life') }}" name="age_life_asset" type="text" value="{{ $asset ? $asset->age_life_asset  : '' }}">
                </div>
                <!-- Ghi chú -->
                <div class="form-group col-md-8">
                    <label>{{__('xin_note')}}</label>
                    <textarea class="form-control"  name="asset_note" id="exampleTextarea" rows="3">{{ $asset ? $asset->asset_note  : '' }}</textarea>
                </div>
                <!-- Hình ảnh tài sản -->
                    <div class="form-group col-md-4">
                        <label>{{__('xin_asset_image')}}</label>
                        <input style="background: #F3F6F9;padding-bottom: 2.6rem;" type="file" accept="image/*" class="form-control overflow-hidden" multiple name="asset_image[]" />
                        <!-- show image -->
                        @if($info_asset && $info_asset['image_asset'])
                            <div class="show_asset_image mt-2">
                                <div class="area_asset_image">
                                    @foreach($info_asset['image_asset'] as $key => $image_id)
                                    <span class="symbol-label image_asset__">
                                        <img data-delete="{{ $image_id->asset_image_id }}" src="{{ asset('storage/asset_image').'/'.$image_id->asset_image }}" class="w-100 h-100 align-self-end" alt="">
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

            </div>
            <div class="justify-content-center">
                <div class="text-center">
                    <button  type="submit" class="add_asset btn btn-primary mr-2">{{ __('xin_save')  }}</button>
                    <button type="reset" class="btn btn-light-primary" data-dismiss="modal" aria-label="Close">{{ __('cancel')  }}</button>
                </div>
            </div>
            <!-- show hình ảnh khi click -->
            <div class="show_image_child">
                <div class="imageaa">
                    <img src="" class="w-100 h-100 align-self-end" alt="">
                </div>
                <div class="close_asset_image">
                    <button data-toggle="tooltip" data-placement="left" title="{{ __('xin_close') }}" type="button" class="close close_assets">
                        <i aria-hidden="true" class="ki ki-close text-danger"></i>
                    </button>
                    <button data-toggle="tooltip" data-placement="left" title="{{ __('delete') }}" type="button" class="ml-3 close delete_image_asset">
                        <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
<script>
    $(".image_asset__ img").click(function (){
        let src_image = $(this).attr('src');
        let data = $(this).attr('data-delete');
       $(".delete_image_asset").attr('data-attr-delete', data);
       $(".show_image_child").show().find('img').attr('src', src_image);
    });
    $(".close_assets").click(function (){
        $(".show_image_child").hide();
    });
    $(".delete_image_asset").click(function (e) {
        let data = $(this).attr('data-attr-delete');

        $.ajax({
            method: 'DELETE',
            url: '{{ route("admin.asset.delete_asset_image") }}',
            data: { id: data }
        }).done(function (result_image) {
            toastr.success(__("xin_theme_success"));
            $(".show_image_child").hide();
            $(".image_asset__ img[data-delete='"+data+"']").parent().remove();
        }).fail(function (jqXHR, status){
            toastr.error(__("error_title"));
        });


    })
</script>
<script src="{{mix('/js/asset/asset.js')}}" type="text/javascript"></script>


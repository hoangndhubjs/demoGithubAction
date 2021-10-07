@if(isset($type))
                <form class="m-b-1" id="formAssetLocation" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('location.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        <div data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($location))
                                            <input type="hidden" id="location_id" name="location_id" value="{{$location->location_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_name')}} {{__('xin_branchs')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_name')}} {{__('xin_branchs')}}" id="location_name" type="text" value="{{$location ? $location->location_name : ''}}" name="location_name">
                                            <span class="form-text text-danger" id="errorLocation_name"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_company_name')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_company" name="company_id"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_company_name')}}" tabindex="-1" >
                                                @if (isset($location) && isset($location->company))
                                                    <option selected disabled id="company_id" value="{{$location->company_id}}" hidden>{{$location->company->name}}</option>
                                                    @else <option selected disabled id="company_id" hidden>{{__('xin_select_company')}}</option>
                                                @endif
                                                @foreach ($companies as $item)
                                                    <option class="form-select-lg" value="{{ $item->company_id }}">
                                                        {{ $item->name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_view_locationh')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_locationh" name="location_head"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_view_locationh')}}" tabindex="-1" >
                                                @if (isset($location) && isset($location->employee))
                                                    <option selected disabled value="{{$location->location_head}}" hidden>{{$location->employee->last_name}} {{$location->employee->first_name}}</option>
                                                @endif
                                                @foreach ($employees as $item)
                                                    <option value="{{$item->user_id}}">{{$item->last_name}} {{$item->first_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_phone')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_phone')}}" id="phone" value="{{$location ? $location->phone : ''}}" type="text" name="phone">
                                            <span class="form-text text-danger" id="errorPhone"></span></td>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_address')}} 1<span class="text-danger"> *</span></label>
                                            <input type="text" id="address_1" class="form-control" placeholder="{{__('xin_address_1')}}" name="address_1" value="{{$location ? $location->address_1 : ''}}">
                                            <span class="form-text text-danger" id="errorAddress_1"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_address')}} 2</label>
                                            <input type="text" id="address_2" class="form-control" placeholder="{{__('xin_address_2')}}" name="address_2" value="{{$location ? $location->address_2 : ''}}">
                                            <span class="form-text text-danger" id="errorAddress_2"></span>
                                        </td>
                                    </tr>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_city')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" id="city" placeholder="{{__('xin_city')}}" name="city" value="{{$location ? $location->city : ''}}">
                                        <span class="form-text text-danger" id="errorCity"></span></td>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_state')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" placeholder="{{__('xin_state')}}" id="state" name="state" value="{{$location ? $location->state : ''}}">
                                            <span class="form-text text-danger" id="errorState"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_zipcode')}}</label>
                                            <input class="form-control" type="text" placeholder="{{__('xin_zipcode')}}" id="zipcode" name="zipcode" value="{{$location ? $location->zipcode : ''}}">
                                            <span class="form-text text-danger" id="errorZipcode"></span></td>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_email')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_email')}}" type="email" id="email" value="{{$location ? $location->email : ''}}" name="email">
                                            <span class="form-text text-danger" id="errorEmail"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_country')}}</label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_country" name="country"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_country')}}" tabindex="-1" >
                                            @if (isset($location) && isset($location->countryy))
                                                    <option selected disabled id="country" hidden value="{{$location->country}}">{{$location->countryy->country_name}}</option>
                                                    @else <option selected disabled id="country" hidden value="237">Vietnam</option>
                                                @endif
                                                @foreach ($countries as $item)
                                                    <option class="form-select-lg" value="{{ $item->country_id }}">{{ $item->country_name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $location ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $('#select2_country, #select2_company, #select2_locationh').select2();
    $(document).ready(function(){
        $('#formAssetLocation').submit(function(event){
            $('.btn-save').attr("disabled", "disabled");
            $('.btn-save').val("{{__('saving')}}");
            event.preventDefault();
            var port_url = $(this).attr('action');
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $.ajax({
                type: request_method,
                url: port_url,
                data: form_data,
                dataType: 'json',
                cache: false,
                mimeType: 'multipart/form-data',
                processData: false,
                contentType:false,
            }).done(function (response) {
                if(response.errorsForm) {
                    $.each(response.errorsForm, function(key, value){
                        var name_err = '#error'+key.charAt(0).toUpperCase()+key.substr(1);
                        console.log(name_err);
                        console.log('Value :',value[0]);
                        if(value[0]){
                            $(name_err).html(value[0]);
                            $(name_err).show();
                        }else if (value[0] == null){
                            $(name_err).html("");
                            $(name_err).show();
                        }
                    });
                    $('.btn-save').removeAttr("disabled");
                    $('.btn-save').val("{{__('xin_save')}}");
                }
                if (response.success) {
                    toastr.success(response.data);
                    $('#popupAssetLocation').modal('hide');
                    $('#formAssetLocation').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

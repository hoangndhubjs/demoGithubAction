@if(isset($type))
                <form class="m-b-1" id="formAssetCompany" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('company.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        <div data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($company))
                                            <input type="hidden" id="company-id" name="company_id" value="{{$company->company_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_company_name')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_company_name')}}"  id="name" type="text" value="{{$company ? $company->name : ''}}" name="name">
                                            <span class="form-text text-danger" id="errorName"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_company_type')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_company_type" name="type_id"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_company_type')}}">
                                                @if (isset($company) && isset($company->company_type))
                                                    <option selected value="{{$company->type_id}}" hidden>{{$company->company_type->name}}</option>
                                                @endif
                                                @foreach ($company_type as $item)
                                                    <option  value="{{ $item->type_id }}">
                                                        {{ $item->name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_company_trading')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" id="trading_name" type="text" placeholder="{{__('xin_company_trading')}}" value="{{$company ? $company->trading_name : ''}}"  name="trading_name">
                                            <span class="form-text text-danger" id="errorTrading_name"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_company_registration')}}</label>
                                            <input class="form-control" id="registration_no" type="text" value="{{$company ? $company->registration_no : ''}}" placeholder="{{__('xin_company_registration')}}" name="registration_no">
                                            <span class="form-text text-danger" id="errorRegistration_no"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('dashboard_username')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" id="username" placeholder="{{__('dashboard_username')}}" value="{{$company ? $company->username : ''}}" name="username">
                                            <span class="form-text text-danger" id="errorUsername"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_employee_password')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="password" id="password" placeholder="{{__('xin_employee_password')}}" value="{{$company ? $company->password : ''}}" name="password">
                                            <span class="form-text text-danger" id="errorPassword"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_phone')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_phone')}}" id="contact_number" value="{{$company ? $company->contact_number : ''}}" type="text"    name="contact_number">
                                            <span class="form-text text-danger" id="errorContact_number"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_email')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_email')}}" type="email" id="email" value="{{$company ? $company->email : ''}}" name="email">
                                            <span class="form-text text-danger" id="errorEmail"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_website')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_website')}}" type="text" id="website_url" value="{{$company ? $company->website_url : ''}}" name="website_url">
                                            <span class="form-text text-danger" id="errorWebsite_url"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_gtax')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_gtax')}}" class="form-control" type="text" id="government_tax" name="government_tax" value="{{$company ? $company->government_tax : ''}}">
                                            <span class="form-text text-danger" id="errorGovernment_tax"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_currency_type')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_currency_type" name="default_currency"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_currencies')}}">
                                                @if (isset($company))
                                                    <option selected  value="{{$company->default_currency}}" hidden>{{$company->default_currency}}</option>
                                                @endif
                                                @foreach ($currency as $item)
                                                    <option value="{{ $item->code }} - {{ $item->symbol }}">{{ $item->code }} - {{ $item->symbol }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_setting_timezone')}}</label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_setting_timezone" name="default_timezone"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_setting_timezone')}}">
                                                <option selected value="Asia/Bangkok" hidden>(GTM+07:00) Bangkok</option>
                                                @foreach ($timezones as $key => $item)
                                                    <option value="{{ $key }}"> {{ $item }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_address')}} 1<span class="text-danger"> *</span></label>
                                            <input type="text" id="address_1" class="form-control" placeholder="{{__('xin_address_1')}}" name="address_1" value="{{$company ? $company->address_1 : ''}}">
                                            <span class="form-text text-danger" id="errorAddress_1"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_address')}} 2</label>
                                            <input type="text" id="address_2" class="form-control" placeholder="{{__('xin_address_2')}}" name="address_2" value="{{$company ? $company->address_2 : ''}}">
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_city')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" id="city" placeholder="{{__('xin_city')}}" name="city" value="{{$company ? $company->city : ''}}">
                                            <span class="form-text text-danger" id="errorCity"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_state')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" placeholder="{{__('xin_state')}}" id="state" name="state" value="{{$company ? $company->state : ''}}">
                                            <span class="form-text text-danger" id="errorState"></span>
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_zipcode')}}</label>
                                            <input class="form-control" type="text" placeholder="{{__('xin_zipcode')}}" id="zipcode" name="zipcode" value="{{$company ? $company->zipcode : ''}}">
                                        </td>
                                        <td class="border-top-0">
                                            <label class="d-block required">{{__('xin_country')}}</label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_country" name="country"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_country')}}" tabindex="-1" aria-hidden="true" >
                                                @if (isset($company) && isset($company->countries))
                                                    <option selected hidden value="{{$company->country}}">{{$company->countries->country_name}}</option>
                                                    @else <option selected hidden value="237">Vietnam</option>
                                                @endif
                                                @foreach ($country as $item)
                                                    <option class="form-select-lg" value="{{ $item->country_id }}">{{ $item->country_name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-top-0">
                                            @if (isset($company))
                                                <div class="avatar box-48 mr-0-5">
                                                    <label class="d-block required">&nbsp;</label>
                                                    <img class="rounded mx-auto d-block" id="img" src="uploads/company/{{$company->logo}}" width="150">
                                                </div>
                                            @else
                                                <label class="d-block required">{{__('xin_company_logo')}}<span class="text-danger"> *</span></label>
                                                <input type="file" class="form-control form-control-sm" id="logo" name="logo">
                                            @endif
                                        </td>
                                        <td class="border-top-0">
                                            @if (isset($company))
                                                <label class="d-block required">{{__('xin_company_logo')}}<span class="text-danger"> *</span></label>
                                                <input type="file" class="form-control form-control-sm" id="logo" name="logo">
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $company ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function(){
        $('#select2_country, #select2_setting_timezone, #select2_currency_type, #select2_company_type').select2();
        $('#formAssetCompany').submit(function(event){
            $('.btn-save').attr("disabled","disabled");
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
                        // console.log('#'+key);
                        // console.log(name_err);
                        // console.log('Value :',value[0]);
                        if(value[0]){
                            $(name_err).html(value[0]);
                            $(name_err).show();
                        }
                        $('#'+key).on('change', function(e){
                            // console.log(e.target.value);
                            $(name_err).html("");
                            $(name_err).show();
                        });
                    $('.btn-secondary').removeAttr("disabled");
                    });
                    $('#company-name').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorName').html("");
                            $('#errorName').show();
                        });
                    $('.btn-save').removeAttr("disabled");
                    $('.btn-save').val("{{__('xin_save')}}");
                }
                if (response.success) {
                    toastr.success(response.data);
                    $('#popupAssetCompany').modal('hide');
                    $('#formAssetCompany').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

@if(isset($type))
                <form class="m-b-1" id="formAssetPolicy" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('policy.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($policy))
                                            <input type="hidden" id="policy_id" name="policy_id" value="{{$policy->policy_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_title')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_title')}}"  id="name" type="text" value="{{$policy ? $policy->title : ''}}" name="title">
                                            <span class="form-text text-danger" id="errorTitle"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_company')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_company" name="company_id"
                                                data-plugin="xin_select" data-placeholder="{{__('left_company')}}" tabindex="-1" >
                                                @if (isset($policy) && isset($policy->companyAsset))
                                                    <option selected disabled value="{{$policy->company_id}}" hidden>{{$policy->companyAsset->name}}</option>
                                                    @else <option selected disabled " hidden>{{__('left_company')}}</option>
                                                @endif
                                                @foreach ($companies as $item)
                                                    <option class="form-select-lg" value="{{ $item->company_id }}">
                                                        {{ $item->name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            @if (isset($policy))
                                                <div class="avatar box-48 mr-0-5">
                                                    <label class="d-block required">{{__('xin_company_logo')}}<span class="text-danger"> *</span></label>
                                                    <img class="rounded mx-auto d-block" id="img" src="uploads/company_policy/{{$policy->attachment}}" width="150">
                                                </div>
                                            @else
                                                <label class="d-block required">{{__('xin_company_logo')}}<span class="text-danger"> *</span></label>
                                                <input type="file" class="form-control form-control-sm" id="logo" name="attachment">
                                            @endif
                                        </td>
                                            @if (isset($policy))
                                        <td class="col-3 border-top-0">
                                                <label class="d-block required">{{__('xin_company_logo')}}<span class="text-danger"> *</span></label>
                                                <input type="file" class="form-control form-control-sm" id="logo" name="attachment">
                                        </td>
                                            @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_description')}}<span class="text-danger"> *</span></label>
                                            <textarea class="form-control" placeholder="{{__('xin_description')}}"  id="description" type="text" name="description">{{$policy ? $policy->description : ''}}</textarea>
                                            <span class="form-text text-danger" id="errorDescription"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $policy ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function(){
        $('#select2_company').select2();
        // $('#company_id').on('change', function(e){
        //     var com_id = e.target.value;
        // });
        $('#formAssetPolicy').submit(function(event){
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
                        if(value[0]){
                            $(name_err).html(value[0]);
                            $(name_err).show();
                        }
                        $('#'+key).on('change', function(e){
                            $(name_err).html("");
                            $(name_err).show();
                        });
                    });
                    $('#title').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorTitle').html("");
                            $('#errorTitle').show();
                    });
                    $('#summary').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorSummary').html("");
                            $('#errorSummary').show();
                    });
                    $('#description').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorDescription').html("");
                            $('#errorDescription').show();
                    });
                    $('.btn-save').removeAttr("disabled");
                    $('.btn-save').val("{{__('xin_save')}}");
                }
                if (response.success) {
                    toastr.success(response.data);
                    $('#popupAssetPolicy').modal('hide');
                    $('#formAssetPolicy').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

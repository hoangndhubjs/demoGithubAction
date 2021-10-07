@if(isset($type))
                <form class="m-b-1" id="formAssetDesignation" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('designation.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        <div data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($designation))
                                            <input type="hidden" id="designation_id" name="designation_id" value="{{$designation->designation_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_company')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_company" name="company_id"
                                                data-plugin="xin_select" data-placeholder="{{__('left_company')}}" tabindex="-1">
                                                @if (isset($designation) && isset($designation->companyAsset))
                                                    <option selected disabled value="{{$designation->company_id}}" hidden>{{$designation->companyAsset->name}}</option>
                                                    @else <option selected disabled hidden>{{__('xin_select_company')}}</option>
                                                @endif
                                                @foreach ($companies as $item)
                                                    <option class="form-select-lg" value="{{ $item->company_id }}">{{ $item->name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_department')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_department" name="department_id"
                                                data-plugin="xin_select" data-placeholder="{{__('left_department')}}" tabindex="-1" >
                                                @if (isset($designation) && isset($designation->departmentAsset))
                                                    <option selected disabled value="{{$designation->departmentAsset->department_id}}" hidden>{{$designation->departmentAsset->department_name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_name')}} {{__('xin_designations')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_name')}} {{__('xin_designations')}}"  id="name" type="text" value="{{$designation ? $designation->designation_name : ''}}" name="designation_name">
                                        <span class="form-text text-danger" id="errorDesignation_name"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_description')}} {{__('xin_designations')}}<span class="text-danger"> *</span></label>
                                            <textarea  class="form-control" placeholder="{{__('xin_description')}} {{__('xin_designations')}}"  id="description" type="text" name="description">{{$designation ? $designation->description : ''}}</textarea>
                                            <span class="form-text text-danger" id="errorDescription"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $designation ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function(){
        $("#select2_company, #select2_department").select2();
        $('#select2_company').on('change', function(e){
            var com_id = e.target.value;
            console.log(com_id);
            $.ajax({
                url: '{{route('designation.optionDepartment')}}',
                type: 'get',
                data: { company_id : com_id },
                success: function(res){
                    console.log(res.data);
                    $('#select2_department').empty();
                    $('#select2_department').append('<option class="form-select-lg" disabled value="" selected hidden>' + __('xin_transfer_select_location') + '</option>');
                    res.data.forEach(element => {
                        console.log(element);
                        $('#select2_department').append('<option class="form-select-lg" value="'+ element.department_id + '">' + element.department_name + '</option>');
                    });
                }
            });
        });
        $('#formAssetDesignation').submit(function(event){
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
                    $('#name').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorDesignation_name').html("");
                            $('#errorDesignation_name').show();
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
                    $('#popupAssetDesignation').modal('hide');
                    $('#formAssetDesignation').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

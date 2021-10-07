@if(isset($type))
                <form class="m-b-1" id="formAssetDepartment" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('department.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        </strong></h5>
                        <div data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($department))
                                            <input type="hidden" id="department-id" name="department_id" value="{{$department->department_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_department_name')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_department_name')}}"  id="name" type="text" value="{{$department ? $department->department_name : ''}}" name="department_name">
                                            <span class="form-text text-danger" id="errorDepartment_name"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_company')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_company" name="company_id"
                                                data-plugin="xin_select" data-placeholder="{{__('left_company')}}" >
                                                @if (isset($department) && isset($department->companyy))
                                                    <option selected id="department-type" value="{{$department->company_id}}" hidden>{{$department->companyy->name}}</option>
                                                    @else <option selected id="company_id" hidden>{{__('xin_select_company')}}</option>
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
                                            <label class="d-block required">{{__('dashboard_locations')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_location" name="location_id"
                                                data-plugin="xin_select" data-placeholder="{{__('dashboard_locations')}}" tabindex="-1" >
                                                @if (isset($department) && isset($department->locationn))
                                                    <option selected id="department-type" value="{{$department->location_id}}" hidden>{{$department->locationn->location_name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_view_manager')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control form-select-lg select2 is-valid" id="select2_manager" name="employee_id"
                                                data-plugin="xin_select" data-placeholder="{{__('xin_view_manager')}}" tabindex="-1" >
                                                @if (isset($department) && isset($department->employeee))
                                                    <option selected id="department-type" value="{{$department->employee_id}}" hidden>{{$department->employeee->last_name}} {{$department->employeee->first_name}}</option>
                                                @endif
                                                @foreach ($employees as $item)
                                                    <option class="form-select-lg" value="{{ $item->user_id }}">
                                                        {{ $item->last_name }} {{ $item->first_name }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $department ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function(){
        $('#select2_manager, #select2_company, #select2_location').select2();
        $('#select2_company').on('change', function(e){
            var com_id = e.target.value;
            console.log(com_id);
            $.ajax({
                url: '{{route('department.optionLocation')}}',
                type: 'get',
                data: { company_id : com_id },
                success: function(res){
                    console.log(res.data);
                    $('#select2_location').empty();
                    $('#select2_location').append('<option class="form-select-lg" disabled selected hidden>' + __('xin_transfer_select_location') + '</option>');
                    res.data.forEach(element => {
                        console.log(element);
                        $('#select2_location').append('<option class="form-select-lg" value="'+ element.location_id + '">' + element.location_name + '</option>');
                    });
                }
            });
        });
        $('#formAssetDepartment').submit(function(event){
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
                    $('#department-name').on('change', function(e){
                            console.log(e.target.value);
                            $('#errorName').html("");
                            $('#errorName').show();
                        });
                    $('.btn-save').removeAttr("disabled");
                    $('.btn-save').val("{{__('xin_save')}}");
                }
                if (response.success) {
                    toastr.success(response.data);
                    $('#popupAssetDepartment').modal('hide');
                    $('#formAssetDepartment').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

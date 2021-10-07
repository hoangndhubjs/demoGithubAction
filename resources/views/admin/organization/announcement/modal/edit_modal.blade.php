@if(isset($type))
                <form class="m-b-1" id="formAssetAnnouncement" enctype="multipart/form-data" accept-charset="utf-8"
                    action="{{ route('announcement.update') }}" method="POST"> @csrf
                    <div class="modal-body">
                        <div data-pattern="priority-columns">
                            <table class="footable-details table toggle-circle">
                                <tbody>
                                    <tr class="form-group row-lg">
                                        @if (isset($announcement))
                                            <input type="hidden" id="announcement_id" name="announcement_id" value="{{$announcement->announcement_id}}">
                                        @endif
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_title')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_title')}}"  id="name" type="text" value="{{$announcement ? $announcement->title : ''}}" name="title">
                                            <span class="form-text text-danger" id="errorTitle"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_start_date')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control datepicker_leave start" autocomplete="off" placeholder="{{__('xin_start_date')}}"  id="start_date" type="text" value="{{$announcement ? $announcement->start_date : ''}}" name="start_date">
                                            <span class="form-text text-danger" id="errorStart_date"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_end_date')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control datepicker_leave start" autocomplete="off" placeholder="{{__('xin_end_date')}}"  id="end_date" type="text" value="{{$announcement ? $announcement->end_date : ''}}" name="end_date">
                                            <span class="form-text text-danger" id="errorEnd_date"></span>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_company')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control select2 is-valid" id="select2_company" name="company_id" data-placeholder="{{__('left_company')}}">
                                                @if (isset($announcement) && isset($announcement->companyAsset))
                                                    <option selected id="company_id" value="{{$announcement->company_id}}" disabled hidden>{{$announcement->companyAsset->name}}</option>
                                                @else <option selected disabled hidden>{{__('left_company')}}</option>
                                                @endif
                                                @foreach ($companies as $item)
                                                    <option  value="{{ $item->company_id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_branchs')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control select2 is-valid" id="select2_location" name="location_id" data-placeholder="{{__('xin_branchs')}}">
                                                @if (isset($announcement) && isset($announcement->locationAsset))
                                                    <option selected disabled value="{{$announcement->locationAsset->location_id}}" hidden>{{$announcement->locationAsset->location_name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('left_department')}}<span class="text-danger"> *</span></label>
                                            <select class="form-control select2 is-valid" id="select2_department" name="department_id" data-placeholder="{{__('left_department')}}">
                                                @if (isset($announcement) && isset($announcement->departmentAsset))
                                                    <option selected disabled value="{{$announcement->departmentAsset->department_id}}" hidden>{{$announcement->departmentAsset->department_name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="form-group row-lg">
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_summary')}}<span class="text-danger"> *</span></label>
                                            <input class="form-control" placeholder="{{__('xin_summary')}}"  id="summary" type="text" value="{{$announcement ? $announcement->summary : ''}}" name="summary">
                                            <span class="form-text text-danger" id="errorSummary"></span>
                                        </td>
                                        <td class="col-3 border-top-0">
                                            <label class="d-block required">{{__('xin_description')}}<span class="text-danger"> *</span></label>
                                            <textarea class="form-control" placeholder="{{__('xin_description')}}"  id="description" type="text" name="description">{{$announcement ? $announcement->description : ''}}</textarea>
                                            <span class="form-text text-danger" id="errorDescription"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $announcement ? __("Cập nhật") : __("Thêm mới") }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('xin_close')}}</button>
                    </div>
                </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function(){
        $("#select2_company, #select2_location, #select2_department").select2();
        $('.datepicker_leave').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose:true,
            language:'vi'
        }).on('changeDate', function(e) {
            formValidator.revalidateField('request_date');
        });

        $('#select2_company').on('change', function(e){
            var com_id = e.target.value;
            console.log(com_id);
            $.ajax({
                url: '{{route('announcement.optionLocation')}}',
                type: 'get',
                data: { company_id : com_id },
                success: function(res){
                    console.log(res.data);
                    $('#select2_location').empty();
                    $('#select2_department').empty();
                    $('#select2_location').append('<option disabled selected hidden>' + __('xin_branchs') + '</option>');
                    res.data.forEach(element => {
                        console.log(element);
                        $('#select2_location').append('<option value="'+ element.location_id + '">' + element.location_name + '</option>');
                    });
                }
            });
        });
        $('#select2_location').on('change', function(e){
            var lo_id = e.target.value;
            console.log(lo_id);
            $.ajax({
                url: '{{route('announcement.optionDepartment')}}',
                type: 'get',
                data: { location_id : lo_id },
                success: function(res){
                    console.log(res.data);
                    $('#select2_department').empty();
                    $('#select2_department').append('<option disabled selected hidden>' + __('xin_department') + '</option>');
                    res.data.forEach(element => {
                        console.log(element);
                        $('#select2_department').append('<option value="'+ element.department_id + '">' + element.department_name + '</option>');
                    });
                }
            });
        });
        // $("#select2_company").trigger("change");
        $('#formAssetAnnouncement').submit(function(event){
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
                    $('#popupAssetAnnouncement').modal('hide');
                    $('#formAssetAnnouncement').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
        return false;
        });
    });
</script>

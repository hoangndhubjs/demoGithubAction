<form id="form_office_shift">
    @if(isset($office_shift))
        <input type="hidden" name="id" value="{{$office_shift->office_shift_id}}"/>
    @endif
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="col-form-label">{{ __('Công ty') }}</label>
                <select name="company_id" id="" class="form-control selectpicker1">
                    @foreach ($company as $item)
                        <option 
                            @if ($office_shift && $office_shift->company_id == $item->company_id)
                                selected
                            @endif 
                        value="{{ $item->company_id}}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label class="col-form-label">{{ __('Tên ca làm việc') }} <span class="text-danger"> *</span></label>
                <input type="text" name="shift_name" class="form-control" value="@if($office_shift) {{$office_shift->shift_name}} @endif">
            </div>
        </div>
        <div class="row mb-3" id="mo">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ hai</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }}</label>
                <input class="form-control timepicker_leave clear-1"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="monday_in_time" type="text"
                       value="@if($office_shift && $office_shift->monday_in_time){{date('h:i A', strtotime($office_shift->monday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }}</label>
                <input class="form-control timepicker_leave clear-1"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="monday_out_time" type="text"
                       value="@if($office_shift && $office_shift->monday_out_time){{date('h:i A', strtotime($office_shift->monday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="1">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="tu">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ ba</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-2"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="tuesday_in_time" type="text"
                       value="@if($office_shift && $office_shift->tuesday_in_time){{date('h:i A', strtotime($office_shift->tuesday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-2"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="tuesday_out_time" type="text"
                       value="@if($office_shift && $office_shift->tuesday_out_time){{date('h:i A', strtotime($office_shift->tuesday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="2">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="we">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ tư</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-3"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="wednesday_in_time" type="text"
                       value="@if($office_shift && $office_shift->wednesday_in_time){{date('h:i A', strtotime($office_shift->wednesday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-3"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="wednesday_out_time" type="text"
                       value="@if($office_shift && $office_shift->wednesday_out_time){{date('h:i A', strtotime($office_shift->wednesday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="3">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="th">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ năm</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-4"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="thursday_in_time" type="text"
                       value="@if($office_shift && $office_shift->thursday_in_time){{date('h:i A', strtotime($office_shift->thursday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-4"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="thursday_out_time" type="text"
                       value="@if($office_shift && $office_shift->thursday_out_time){{date('h:i A', strtotime($office_shift->thursday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="4">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="fr">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ sáu</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-5"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="friday_in_time" type="text"
                       value="@if($office_shift && $office_shift->friday_in_time){{date('h:i A', strtotime($office_shift->friday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-5"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="friday_out_time" type="text"
                       value="@if($office_shift && $office_shift->friday_out_time){{date('h:i A', strtotime($office_shift->friday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="5">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="sa">
            <div class="col-md-2">
                 <span class="text-muted"> Thứ bảy</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-6"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="saturday_in_time" type="text"
                       value="@if($office_shift && $office_shift->saturday_in_time){{date('h:i A', strtotime($office_shift->saturday_in_time))}}@endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-6"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="saturday_out_time" type="text"
                       value="@if($office_shift && $office_shift->saturday_out_time){{date('h:i A', strtotime($office_shift->saturday_out_time))}}@endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="6">{{ __("xin_reset")}}</button>
            </div>
        </div>
        <div class="row mb-3" id="su">
            <div class="col-md-2">
                 <span class="text-muted"> Chủ nhật</span>
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_starttime") }} </label>
                <input class="form-control timepicker_leave clear-7"
                       placeholder="{{ __("xin_project_timelogs_starttime") }}" name="sunday_in_time" type="text"
                       value="@if($office_shift && $office_shift->sunday_in_time) {{date('h:i A', strtotime($office_shift->sunday_in_time))}} @endif">
            </div>
            <div class="col-md-4">
                <label class="d-block required">{{ __("xin_project_timelogs_endtime") }} </label>
                <input class="form-control timepicker_leave clear-7"
                       placeholder="{{ __("xin_project_timelogs_endtime") }}" name="sunday_out_time" type="text"
                       value="@if($office_shift && $office_shift->sunday_out_time) {{date('h:i A', strtotime($office_shift->sunday_out_time))}} @endif">
            </div>
            <div class="col-md-2 mt-xl-8 mt-sm-8 mt-1 text-right">
                <button type="button" class="btn btn-primary clear-time" data-clear-id="7">{{ __("xin_reset")}}</button>
            </div>
        </div>
    </div>

    <div class="form-group text-right mb-0">
        <button type="submit" class="btn btn-primary" id="sm_form_office_shift"><x-icon type="svg" category="Navigation" icon="Double-check"/>  {{ $office_shift ? __("Cập nhật") : __("Thêm mới") }}</button>
    </div>
    <i class="text-danger" style="font-size: 1rem;">* Lưu ý: Để trống thời gian nếu không có ca làm việc</i>
</form>
<!-- JS Form -->
<script>
    $(".clear-time").click(function(){
        var clear_id  = $(this).data('clear-id');
        $(".clear-"+clear_id).val('');
    });
    $('.selectpicker1').select2();
    $('.timepicker_leave').timepicker({
        showMeridian:false,
        disableFocus:true,
        defaultTime:"",
        minuteStep: 30
    });
    
    formOfficeShift = document.getElementById('form_office_shift');
    submitButton = formOfficeShift.querySelector('[type="submit"]');

    formValidator = FormValidation.formValidation(
        formOfficeShift,
        {
            fields: {
                "shift_name": {
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
        $.ajax({
            url: '{{ route('office-shift.ajax.add_office_shift')}}',
            data: $('#form_office_shift').serialize(),
            method: 'POST',
            success: function(response) {
                $('#sm_form_office_shift').prop('disabled', true);
                $('#sm_form_office_shift').html('Đang lưu...');
                window._tables.office_shift && window._tables.office_shift.datatable.reload();
                setTimeout(function () {
                    $('#add_office_shift').modal('hide');
                    toastr.success(response.data);
                }, 500);
            },
            error: function (response) {
                toastr.error(response.error ?? __("error"));
            },
        });
    });
</script>

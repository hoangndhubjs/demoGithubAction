@if(isset($type))

    <form class="form" id="formEmployeeSecurityLevel" method="POST" action="{{ route('employee_managements.ajax.create_security-level', request()->route('id')) }}">
        @csrf
        @if(isset($employee_security_level))
            <input type="hidden" name="id" value="{{ $employee_security_level->security_level_id }}"/>
        @endif
        <div class="card-body row">
            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_esecurity_level_title') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_esecurity_level_title') }}" name="security_type">
                        <option value=""></option>
                        @foreach($security_level as $key => $value)
                            <option value="{{ $value['type_id'] }}" {{ $employee_security_level && $value['type_id'] == $employee_security_level->security_type ? 'selected' : '' }}>
                                {{ $value['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-text text-danger" id="errorSecurityType"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_start_date') }}</label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_start_date') }}" name="date_of_clearance" value="{{ $employee_security_level ? $employee_security_level->date_of_clearance : '' }}"/>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_end_date') }}</label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_end_date') }}" name="expiry_date" value="{{ $employee_security_level ? $employee_security_level->expiry_date : '' }}"/>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <div class="col-12 text-center">
                <button type="submit" id="saveEmployeeSecurity" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
            </div>
        </div>

    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        $('#formEmployeeSecurityLevel').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveEmployeeSecurity").attr("disabled", true).html(__('saving'));

            $.ajax({
                type: request_method,
                url: post_url,
                data: form_data,
                dataType: "json",
                cache: false,
                mimeType: 'multipart/form-data',
                processData: false,
                contentType: false,
            }).done(function (response) {

                $("#saveEmployeeSecurity").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.security_type){
                        $('#errorSecurityType').html(response.errorsForm.security_type[0]);
                        $('#errorSecurityType').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupEmployeeSecurityLevel').modal('hide');
                    $('#formEmployeeSecurityLevel').trigger( "reset" );
                    window._tables.employeeSecurityLevel.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        })
    });
</script>
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>

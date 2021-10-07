@if(isset($type))

    <form class="form" id="formEmployeeContract" method="POST" action="{{ route('employee_managements.ajax.create_contract', request()->route('id')) }}">
        @csrf
        @if(isset($employee_contract))
            <input type="hidden" name="id" value="{{ $employee_contract->contract_id }}"/>
        @endif
        <div class="card-body row">
            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_contract_type') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_e_details_contract_type') }}" name="contract_type_id">
                        <option value=""></option>
                        @foreach($contract_type as $key => $value)
                            <option value="{{ $value['contract_type_id'] }}" {{ $employee_contract && $value['contract_type_id'] == $employee_contract->contract_type_id ? 'selected' : '' }}>
                                {{ $value['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-text text-danger" id="errorContractType"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_contract_title') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_e_details_contract_title') }}" name="title" value="{{ $employee_contract ? $employee_contract->title : '' }}"/>
                    <span class="form-text text-danger" id="errorTitle"></span>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('dashboard_designation') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('dashboard_designation') }}" name="designation_id">
                        <option value=""></option>
                        @foreach($designation as $key => $value)
                            @if ($info_user['designation_id'] == $value['designation_id'])
                                <option value="{{ $value['designation_id'] }}" {{ $employee_contract && $value['designation_id'] == $info_user['designation_id'] ? 'selected' : '' }}>
                                    {{ $value['designation_name'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <span class="form-text text-danger" id="errorDesignation"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_start_date') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_start_date') }}" name="from_date" value="{{ $employee_contract ? $employee_contract->from_date : '' }}"/>
                    <span class="form-text text-danger" id="errorFromDate"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_end_date') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_end_date') }}" name="to_date" value="{{ $employee_contract ? $employee_contract->to_date : '' }}"/>
                    <span class="form-text text-danger" id="errorToDate"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_description') }}</label>
                    <textarea class="form-control" rows="3" placeholder="{{ __('xin_description') }}" name="description">{{ $employee_contract ? $employee_contract->description : '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-12 text-center">
                <button type="submit" id="saveEmployeeContract" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
            </div>
        </div>

    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        $('#formEmployeeContract').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveEmployeeContract").attr("disabled", true).html(__('saving'));

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

                $("#saveEmployeeContract").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.contract_type_id){
                        $('#errorContractType').html(response.errorsForm.contract_type_id[0]);
                        $('#errorContractType').show()
                    }
                    if(response.errorsForm.title){
                        $('#errorTitle').html(response.errorsForm.title[0]);
                        $('#errorTitle').show()
                    }
                    if(response.errorsForm.designation_id){
                        $('#errorDesignation').html(response.errorsForm.designation_id[0]);
                        $('#errorDesignation').show()
                    }
                    if(response.errorsForm.from_date){
                        $('#errorFromDate').html(response.errorsForm.from_date[0]);
                        $('#errorFromDate').show()
                    }
                    if(response.errorsForm.to_date){
                        $('#errorToDate').html(response.errorsForm.to_date[0]);
                        $('#errorToDate').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupEmployeeContract').modal('hide');
                    $('#formEmployeeContract').trigger( "reset" );
                    window._tables.employeeContract.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        })

        $('.datepickerDefault').datepicker({
            todayHighlight: true,
            format: window._dateFormat.toLowerCase(),
            default: 'toDay',
            setDate: new Date(),
            orientation: "bottom left",
            language: window._locale,
            // endDate: "toDay",
        });

        $(':input').change(function () {
            $(this).closest('.form-group').find('.form-text.text-danger').hide();
        });

        $('.selectSearch').select2({ width: '100%' });
    });
</script>

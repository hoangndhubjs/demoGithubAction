@if(isset($type))

    <form class="form" id="formExperience" method="POST" action="{{ route('employee_managements.ajax.create_experience', request()->route('id')) }}">
        @csrf
        @if(isset($experience))
            <input type="hidden" name="id" value="{{ $experience->work_experience_id }}"/>
        @endif
        <div class="card-body row">
            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_company_name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_company_name') }}" name="company_name" value="{{ $experience ? $experience->company_name : '' }}"/>
                    <span class="form-text text-danger" id="errorCompanyName"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_frm_date') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_e_details_frm_date') }}" name="from_date" value="{{ $experience ? $experience->from_date : '' }}"/>
                    <span class="form-text text-danger" id="errorFromDate"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_to_date') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_e_details_to_date') }}" name="to_date" value="{{ $experience ? $experience->to_date : '' }}"/>
                    <span class="form-text text-danger" id="errorToDate"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_description') }}<span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" placeholder="{{ __('xin_description') }}" name="description">{{ $experience ? $experience->description : '' }}</textarea>
                    <span class="form-text text-danger" id="errorDescription"></span>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-12 text-center">
                <button type="submit" id="saveExperience" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
            </div>
        </div>

    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        $('#formExperience').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveExperience").attr("disabled", true).html(__('saving'));

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

                $("#saveExperience").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.company_name){
                        $('#errorCompanyName').html(response.errorsForm.company_name[0]);
                        $('#errorCompanyName').show()
                    }
                    if(response.errorsForm.from_date){
                        $('#errorFromDate').html(response.errorsForm.from_date[0]);
                        $('#errorFromDate').show()
                    }
                    if(response.errorsForm.to_date){
                        $('#errorToDate').html(response.errorsForm.to_date[0]);
                        $('#errorToDate').show()
                    }
                    if(response.errorsForm.description){
                        $('#errorDescription').html(response.errorsForm.description[0]);
                        $('#errorDescription').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupExperience').modal('hide');
                    $('#formExperience').trigger( "reset" );
                    window._tables.experience.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        })
    });
</script>
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>

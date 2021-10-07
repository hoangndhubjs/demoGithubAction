@if(isset($type))
    <form class="form" id="formQualification" method="POST" action="{{ route('ajax.create_qualification') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($qualification))
            <input type="hidden" name="id" value="{{ $qualification->qualification_id }}"/>
        @endif
        <div class="card-body row">
            <div class="col-xxl-12 col-lg-12">

                <div class="form-group">
                    <label>{{ __('xin_e_details_school_uni') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_e_details_school_uni') }}" name="name" value="{{ $qualification ? $qualification->name : '' }}"/>
                    <span class="form-text text-danger" id="errorName"></span>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_edu_level') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_e_details_edu_level') }}" name="education_level_id">
                        <option value=""></option>
                        @foreach($education_level as $key => $value)
                            <option value="{{ $value->education_level_id }}" {{ $qualification && $value->education_level_id == $qualification->education_level_id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-text text-danger" id="errorEducationLevel"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_rec_job_category') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_rec_job_category') }}" name="majors" value="{{ $qualification ? $qualification->majors : '' }}"/>
                    <span class="form-text text-danger" id="errorMajors"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_language') }}</label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_e_details_language') }}" name="language_id">
                        <option value=""></option>
                        @foreach($qualification_language as $key => $value)
                            <option value="{{ $value->language_id }}" {{ $qualification && $value->language_id == $qualification->language_id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_frm_year') }}</label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_e_details_frm_year') }}" name="from_year" value="{{ $qualification ? $qualification->from_year : '' }}"/>
                    <span class="form-text text-danger" id="errorFromYear"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_to_year') }}</label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_e_details_to_year') }}" name="to_year" value="{{ $qualification ? $qualification->to_year : '' }}"/>
                    <span class="form-text text-danger" id="errorToYear"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_description') }}</label>
                    <textarea type="text" class="form-control" rows="2" placeholder="{{ __('xin_description') }}" name="description">{!! $qualification ? $qualification->description : '' !!}</textarea>
                    <span class="form-text text-danger" id="errorDescription"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_document_file') }} <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" placeholder="{{ __('xin_e_details_document_file') }}" name="document_file" accept="image/x-png, image/gif, image/jpeg, image/jpg" value=""/>
                    <span class="form-text text-muted">&nbsp;&nbsp;Chỉ nhận file định dạng: Jpg, pdf, png, jpeg.</span>
                    <span class="form-text text-danger" id="errorDocumentFile"></span>
                </div>
            </div>
        </div>

        <div class="card-footer">
        <div class="col-12 text-center">
            <button type="submit" id="saveQualification" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
            <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
        </div>
    </div>
    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        //form add
        $('#formQualification').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveQualification").attr("disabled", true).html(__('saving'));

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
                $("#saveQualification").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.name){
                        $('#errorName').html(response.errorsForm.name[0]);
                        $('#errorName').show()
                    }
                    if(response.errorsForm.education_level_id){
                        $('#errorEducationLevel').html(response.errorsForm.education_level_id[0]);
                        $('#errorEducationLevel').show()
                    }
                    if(response.errorsForm.document_file){
                        $('#errorDocumentFile').html(response.errorsForm.document_file[0]);
                        $('#errorDocumentFile').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupQualification').modal('hide');
                    $('#formQualification').trigger( "reset" );
                    $('.resetSelect').val('').trigger('change');
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
            return false;
        });

    });
</script>
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>

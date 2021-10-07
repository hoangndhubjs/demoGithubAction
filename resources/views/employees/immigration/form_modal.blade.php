@if(isset($type))
    <form class="form" id="formImmigration" method="POST" action="{{ route('ajax.create_immigration') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($immigration))
            <input type="hidden" name="id" value="{{ $immigration->immigration_id }}"/>
        @endif
        <div class="card-body">
            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_document') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_e_details_document') }}" name="document_type_id">
                        <option value=""></option>
                        @foreach($document_type as $key => $value)
                            <option value="{{ $value->document_type_id }}" {{ $immigration && $value->document_type_id == $immigration->document_type_id ? 'selected' : '' }}>
                                {{ $value->document_type }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-text text-danger" id="errorDocument"></span>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_issue_date') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepickerDefault" placeholder="{{ __('xin_issue_date') }}" name="issue_date" value="{{ $immigration ? $immigration->issue_date : '' }}"/>
                    <span class="form-text text-danger" id="errorIssueDate"></span>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">

                <div class="form-group">
                    <label>{{ __('xin_employee_document_number') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_employee_document_number') }}" name="document_number" value="{{ $immigration ? $immigration->document_number : '' }}"/>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('place_of_issue') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('place_of_issue') }}" name="place_of_issue" value="{{ $immigration ? $immigration->place_of_issue : '' }}"/>
                    <span class="form-text text-danger" id="errorPlaceOfIssue"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_document_file') }} <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" placeholder="{{ __('xin_e_details_document_file') }}" name="document_file" accept="image/x-png, image/gif, image/jpeg, image/jpg" value="{{ $immigration ? $immigration->document_file : '' }}"/>
                    <span class="form-text text-muted">&nbsp;&nbsp;Chỉ nhận file định dạng: Jpg, pdf, png, jpeg.</span>
                    <span class="form-text text-danger" id="errorDocumentFile"></span>
                </div>
            </div>
        </div>

        <div class="card-footer">
        <div class="col-12 text-center">
            <button type="submit" id="saveImmigration" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
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
        $('#formImmigration').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveImmigration").attr("disabled", true).html(__('saving'));

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

                $("#saveImmigration").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.document_type_id){
                        $('#errorDocument').html(response.errorsForm.document_type_id[0]);
                        $('#errorDocument').show()
                    }
                    if(response.errorsForm.issue_date){
                        $('#errorIssueDate').html(response.errorsForm.issue_date[0]);
                        $('#errorIssueDate').show()
                    }
                    if(response.errorsForm.document_file){
                        $('#errorDocumentFile').html(response.errorsForm.document_file[0]);
                        $('#errorDocumentFile').show()
                    }
                    if(response.errorsForm.place_of_issue){
                        $('#errorPlaceOfIssue').html(response.errorsForm.place_of_issue[0]);
                        $('#errorPlaceOfIssue').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupImmigration').modal('hide');
                    $('#formImmigration').trigger( "reset" );
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

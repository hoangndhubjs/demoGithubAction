@if(isset($type))

    <form class="form" id="formEmailTemplate" method="POST" action="{{ route('config.update_template') }}">
        @csrf
        @if(isset($emailTemplate))
            <input type="hidden" name="id" value="{{ $emailTemplate->template_id }}"/>
        @endif
        <div class="card-body row">

            <div class="col-xxl-4 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_name_of_template') }} </label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_name_of_template') }}" name="name" value="{{ $emailTemplate ? $emailTemplate->name : '' }}"/>
                    <span class="form-text text-danger" id="errorName"></span>
                </div>
            </div>


            <div class="col-xxl-4 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_theme_title') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_theme_title') }}" name="subject" value="{{ $emailTemplate ? $emailTemplate->subject : '' }}"/>
                <span class="form-text text-danger" id="errorSubject"></span>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-12">
                <div class="form-group">
                    <label>{{ __('dashboard_xin_status') }}</label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('dashboard_xin_status') }}" name="status">
                        <option value="1" {{ $emailTemplate && $emailTemplate->status == 1 ? 'selected' : '' }}>{{ __('xin_employees_active') }}</option>
                        <option value="0" {{ $emailTemplate && $emailTemplate->status == 0 ? 'selected' : '' }}>{{ __('xin_employees_inactive') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group ">
                    <label>{{ __('xin_message') }}</label>
                    <textarea name="message" id="kt-ckeditor-1" class="form-control" rows="5">{{ $emailTemplate ? $emailTemplate->message : '' }}</textarea>
                    <span class="form-text text-danger" id="errorMessage"></span>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-12 text-center">
                <button type="submit" id="saveBankAccount" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
            </div>
        </div>
    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>
<script type="text/javascript" src="{{ mix('plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script>
    $(document).ready(function() {

        ckeditor();

        $('#formEmailTemplate').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            console.log(form_data)
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

                if(response.errorsForm) {
                    if(response.errorsForm.name){
                        $('#errorName').html(response.errorsForm.name[0]);
                        $('#errorName').show()
                    }
                    if(response.errorsForm.subject){
                        $('#errorSubject').html(response.errorsForm.subject[0]);
                        $('#errorSubject').show()
                    }
                    if(response.errorsForm.message){
                        $('#errorMessage').html(response.errorsForm.message[0]);
                        $('#errorMessage').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupEmailTemplate').modal('hide');
                    $('#formEmailTemplate').trigger( "reset" );
                    window._tables.email_template_list.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        })

    });

    function ckeditor() {
        ClassicEditor
            .create( document.querySelector( '#kt-ckeditor-1' ) )
            .catch( error => {
                console.error( error );
            } );
    }

</script>

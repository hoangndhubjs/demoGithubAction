@if(isset($type))
    <form class="form" id="formContact" method="POST" action="{{ route('ajax.create_contact') }}">
        @csrf
        @if(isset($contact))
            <input type="hidden" name="id" value="{{ $contact->contact_id }}"/>
        @endif
        <div class="card-body row">
            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('dashboard_fullname') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('dashboard_fullname') }}" name="contact_name" value="{{ $contact ? $contact->contact_name : '' }}"/>
                    <span class="form-text text-danger" id="errorContactName"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <div class="checkbox-inline">
                        <label class="checkbox checkbox-rounded">
                            <input type="checkbox" name="is_primary" value="1" {{ $contact && $contact->is_primary == '1' ? 'checked' : '' }}/>
                            <span></span>
                            {{ __('xin_e_details_pcontact') }}
                        </label>

                        <label class="checkbox checkbox-rounded ml-30">
                            <input type="checkbox" name="is_dependent" value="2" {{ $contact && $contact->is_dependent == '2' ? 'checked' : '' }}/>
                            <span></span>
                            {{ __('xin_e_details_dependent') }}
                        </label>
                    </div>
                </div>

            </div>


            <div class="col-xxl-6 col-lg-12">

                <div class="form-group">
                    <label>{{ __('xin_e_details_relation') }} <span class="text-danger">*</span></label>
                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_e_details_relation') }}" name="relation">
                        <option value=""></option>
                        <option value="Self"    {{ $contact && $contact->relation == 'Self' ? 'selected' : '' }}>{{ __('xin_self') }}</option>
                        <option value="Parent"  {{ $contact && $contact->relation == 'Parent' ? 'selected' : '' }}>{{ __('xin_parent') }}</option>
                        <option value="Spouse"  {{ $contact && $contact->relation == 'Spouse' ? 'selected' : '' }}>{{ __('xin_spouse') }}</option>
                        <option value="Child"   {{ $contact && $contact->relation == 'Child' ? 'selected' : '' }}>{{ __('xin_child') }}</option>
                        <option value="Sibling" {{ $contact && $contact->relation == 'Sibling' ? 'selected' : '' }}>{{ __('xin_sibling') }}</option>
                        <option value="In Laws" {{ $contact && $contact->relation == 'In Laws' ? 'selected' : '' }}>{{ __('xin_in_laws') }}</option>
                    </select>
                    <span class="form-text text-danger" id="errorRelation"></span>
                </div>

            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_phone') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control numberRq" placeholder="{{ __('xin_phone') }}" name="mobile_phone" value="{{ $contact ? $contact->mobile_phone : '' }}"/>
                    <span class="form-text text-danger" id="errorMobilePhone"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('dashboard_email') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('dashboard_email') }}" name="work_email" value="{{ $contact ? $contact->work_email : '' }}"/>
                    <span class="form-text text-danger" id="errorWorkEmail"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_address') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_address') }}" name="address_1" value="{{ $contact ? $contact->address_1 : '' }}"/>
                    <span class="form-text text-danger" id="errorAddress"></span>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <div class="col-12 text-center">
                <button type="submit" id="saveContact" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
            </div>
        </div>
    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        $('#formContact').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveContact").attr("disabled", true).html(__('saving'));

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

                $("#saveContact").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.contact_name){
                        $('#errorContactName').html(response.errorsForm.contact_name[0]);
                        $('#errorContactName').show()
                    }
                    if(response.errorsForm.relation){
                        $('#errorRelation').html(response.errorsForm.relation[0]);
                        $('#errorRelation').show()
                    }
                    if(response.errorsForm.mobile_phone){
                        $('#errorMobilePhone').html(response.errorsForm.mobile_phone[0]);
                        $('#errorMobilePhone').show()
                    }
                    if(response.errorsForm.work_email){
                        $('#errorWorkEmail').html(response.errorsForm.work_email[0]);
                        $('#errorWorkEmail').show()
                    }
                    if(response.errorsForm.address_1){
                        $('#errorAddress').html(response.errorsForm.address_1[0]);
                        $('#errorAddress').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupContact').modal('hide');
                    $('#formContact').trigger( "reset" );
                    $('.resetSelect').val('').trigger('change');
                    window._tables.contact.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        });
    });
</script>
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>

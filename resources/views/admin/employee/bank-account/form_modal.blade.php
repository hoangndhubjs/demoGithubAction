@if(isset($type))

    <form class="form" id="formBankAccount" method="POST" action="{{ route('employee_managements.ajax.create_bank_account', request()->route('id')) }}">
        @csrf
        @if(isset($bankAccount))
            <input type="hidden" name="id" value="{{ $bankAccount->bankaccount_id }}"/>
        @endif
        <div class="card-body row">

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_acc_title') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_e_details_acc_title') }}" name="account_title" value="{{ $bankAccount ? $bankAccount->account_title : '' }}"/>
                    <span class="form-text text-danger" id="errorAccountTitle"></span>
                </div>
            </div>


            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_bank_name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_e_details_bank_name') }}" name="bank_name" value="{{ $bankAccount ? $bankAccount->bank_name : '' }}"/>
                    <span class="form-text text-danger" id="errorBankName"></span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_e_details_acc_number') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control numberRq" placeholder="{{ __('xin_e_details_acc_number') }}" name="account_number" value="{{ $bankAccount ? $bankAccount->account_number : '' }}"/>
                    <span class="form-text text-danger" id="errorAccountNumber"></span>
                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group ">
                    <label>{{ __('xin_e_details_bank_branch') }} <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" placeholder="{{ __('xin_e_details_bank_branch') }}" name="bank_branch">{{ $bankAccount ? $bankAccount->bank_branch : '' }}</textarea>
                    <span class="form-text text-danger" id="errorBankBranch"></span>
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

<script>
    $(document).ready(function() {

        $('#formBankAccount').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            $("#saveBankAccount").attr("disabled", true).html(__('saving'));

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

                $("#saveBankAccount").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.account_title){
                        $('#errorAccountTitle').html(response.errorsForm.account_title[0]);
                        $('#errorAccountTitle').show()
                    }
                    if(response.errorsForm.bank_name){
                        $('#errorBankName').html(response.errorsForm.bank_name[0]);
                        $('#errorBankName').show()
                    }
                    if(response.errorsForm.account_number){
                        $('#errorAccountNumber').html(response.errorsForm.account_number[0]);
                        $('#errorAccountNumber').show()
                    }
                    if(response.errorsForm.bank_branch){
                        $('#errorBankBranch').html(response.errorsForm.bank_branch[0]);
                        $('#errorBankBranch').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupBankAccount').modal('hide');
                    $('#formBankAccount').trigger( "reset" );
                    window._tables.bankAccount.datatable.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        })

    });
</script>
<script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>

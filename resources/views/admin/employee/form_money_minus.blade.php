@if(isset($type))
    <form class="form" id="formMoneyMinus" method="POST" action="{{ route('employee_managements.create_money_minus') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($moneyMinus))
            <input type="hidden" name="id" value="{{ $moneyMinus->id }}"/>
        @endif
        <input type="hidden" name="user_id" value="{{ request()->route('id') }}">
        <div class="card-body">

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_payroll_hourly_wage_title_single') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_payroll_hourly_wage_title_single') }}" name="title" value="{{ $moneyMinus ? $moneyMinus->title : "" }}"/>
                    <span class="form-text text-danger" id="errorTitle"></span>
                </div>

            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="form-group">
                    <label>{{ __('xin_amount') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="money" placeholder="{{ __('xin_amount') }}" name="money" value="{{ $moneyMinus ? $moneyMinus->money : "" }}"/>
                    <span class="form-text text-danger" id="errorMoney"></span>
                </div>
                <!-- Tiền trừ (Chọn tháng) -->
                <div class="form-group selected_year_month">
                    <label>{{ __('monthly_minus') }} <span class="text-danger">*</span></label>
                    <input type="text" autocomplete="off" class="form-control" id="select_money_minus" placeholder="{{ __('monthly_minus') }}" name="" value="{{ $moneyMinus && $moneyMinus->year_month ? date('m-Y', strtotime($moneyMinus->year_month)) : date('m-Y') }}" />
                </div>
                <!-- -->
                <div class="form-group">
                    <div class="checkbox-inline">
                        <label class="checkbox">
                            <input id="amount_option_minus" type="checkbox" {{ $moneyMinus && $moneyMinus->amount_option == 2 ? 'checked' : '' }} value="2" name="amount_option"/>
                            <span></span>
                            {{ __('monthly_deductions') }}
                        </label>
                    </div>

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
        $('#amount_option_minus').change(function (e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $(".selected_year_month").hide();
                $("#select_money_minus").attr('name','')
            }else{
                $(".selected_year_month").show();
                $("#select_money_minus").attr('name','year_month')
            }
        })
        $("#amount_option_minus").trigger('change');
        window.formart_number("#money");
            var date=new Date();
            var year=date.getFullYear();
            var month=date.getMonth();

            $("#select_money_minus").datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                orientation: "bottom",
                endDate: new Date(year, month, '01'),
                todayHighlight: true,
                language: window._locale,
                autoclose:true
            });


        $('#formMoneyMinus').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData($(this)[0])

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
                    if(response.errorsForm.title){
                        $('#errorTitle').html(response.errorsForm.title[0]);
                        $('#errorTitle').show()
                    }
                    if(response.errorsForm.money){
                        $('#errorMoney').html(response.errorsForm.money[0]);
                        $('#errorMoney').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupMoneyMinus').modal('hide');
                    $('#formMoneyMinus').trigger( "reset" );
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

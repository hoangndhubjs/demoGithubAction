@extends('layout.default')

@section('styles')
    <style>
        .btn-xs {
            height: 0px !important;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <form class="form" id="updateAccountPayroll" method="POST" action="{{ route('config.setting.update-account-payroll') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_payslip_password_format') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_payslip_password_format') }}" name="payslip_password_format">
                                        <option value="dateofbirth" {{ $setting->payslip_password_format == 'dateofbirth' ? 'selected' : '' }}>Employee date of birth ({{ date('dmY') }})</option>
                                        <option value="contact_no" {{ $setting->payslip_password_format == 'contact_no' ? 'selected' : '' }}>Employee Contact Number. (123456789)</option>
                                        <option value="full_name" {{ $setting->payslip_password_format == 'full_name' ? 'selected' : '' }}>Employee First name Last name. (JhonDoe)</option>
                                        <option value="email" {{ $setting->payslip_password_format == 'email' ? 'selected' : '' }}>Employee Email Address (employee@example.com)</option>
                                        <option value="employee_id" {{ $setting->payslip_password_format == 'employee_id' ? 'selected' : '' }}>Employee ID (EMP001WA5)</option>
                                        <option value="dateofbirth_name" {{ $setting->payslip_password_format == 'dateofbirth_name' ? 'selected' : '' }}>Employee date of birth & 2 first character from Name ({{ date('dmY').'JD' }})</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_enable_password_generate_payslip') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="1" name="is_payslip_password_generate" {{ $setting->is_payslip_password_generate == '1' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_enable_saudi_gosi') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_enable_saudi_gosi') }}" name="enable_saudi_gosi">
                                        <option value="0" {{ $setting->enable_saudi_gosi == 0 || $setting->enable_saudi_gosi == '' ? 'selected' : '' }}>{{ __('xin_no') }}</option>
                                        <option value="5" {{ $setting->enable_saudi_gosi == 5 ? 'selected' : '' }}>{{ __('xin_yes') }} - 5%</option>
                                        <option value="10" {{ $setting->enable_saudi_gosi == 10 ? 'selected' : '' }}>{{ __('xin_yes') }} - 10%</option>
                                        <option value="15" {{ $setting->enable_saudi_gosi == 15 ? 'selected' : '' }}>{{ __('xin_yes') }} - 15%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4" id="half_monthly_is">
                                <div class="form-group">
                                    <label>{{ __('xin_is_half_monthly') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_is_half_monthly') }}" name="is_half_monthly" id="is_half_monthly">
                                        <option value="0" {{ $setting->is_half_monthly == 0 || $setting->is_half_monthly == '' ? 'selected' : '' }}>{{ __('xin_no') }}</option>
                                        <option value="1" {{ $setting->is_half_monthly == 1 ? 'selected' : '' }}>{{ __('xin_yes') }}</option>
                                    </select>
                                </div>
                            </div>
                            @php $setting->is_half_monthly == 1 ? $stl = 'style="display:block;"' : $stl = 'style="display:none;"'@endphp
                            <div class="col-md-4" {!! $stl !!} id="deduct_options">
                                <div class="form-group">
                                    <label>{{ __('xin_half_deduct_month') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_is_half_monthly') }}" name="half_deduct_month">
                                        <option value="1" {{ $setting->half_deduct_month == 1 ? 'selected' : '' }}>{{ __('xin_is_half_monthly_bs_only') }}</option>
                                        <option value="2" {{ $setting->half_deduct_month == 2 ? 'selected' : '' }}>{{ __('xin_is_half_monthly_bs_only_both') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            <button type="submit" id="saveAccountSystem" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>
    <script>
        $("#is_half_monthly").change(function(){
            var opt = $(this).val();
            if(opt == 1){
                $('#deduct_options').show();
            } else {
                $('#deduct_options').hide();
            }
        });

        $(document).ready(function() {
            $('#updateAccountPayroll').submit(function (event) {
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
                    if (response.success) {
                        toastr.success(response.data);
                    } else if (response.errors) {
                        toastr.error(response.data);
                    }
                })
            })
        });
    </script>
@endsection

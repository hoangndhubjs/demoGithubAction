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

                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">{{ __('xin_hr') }}</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm">{{ __('xin_change_setting_info') }}</span>
                    </h3>
                </div>
                <hr>

                <form class="form" id="updateAccountSystem" method="POST" action="{{ route('config.setting.update-account-system') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_application_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  placeholder="{{ __('xin_application_name') }}" name="application_name" value="{{ $setting->application_name }}"/>
                                    <span class="form-text text-danger" id="errorApplicationName"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_default_currency') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_default_currency') }}" name="default_currency_symbol">
                                        @foreach($currency as $val)
                                            @php $_currency = $val->code.' - '.$val->symbol @endphp
                                            <option value="{{ $_currency }}" {{ $setting->default_currency_symbol == $_currency ? 'selected' : '' }}>{{ $_currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{!! __('xin_default_currency_symbol_code') !!}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_default_currency_symbol_code') }}" name="show_currency">
                                        <option value="code" {{ $setting->show_currency == 'code' ? 'selected' : '' }}>{{ __('xin_currency_code') }}</option>
                                        <option value="symbol" {{ $setting->show_currency == 'symbol' ? 'selected' : '' }}>{{ __('xin_currency_symbol') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_currency_position') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_currency_position') }}" name="currency_position">
                                        <option value="Prefix" {{ $setting->currency_position == 'Prefix' ? 'selected' : '' }}>{{ __('xin_prefix') }}</option>
                                        <option value="Suffix" {{ $setting->currency_position == 'Suffix' ? 'selected' : '' }}>{{ __('xin_suffix') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_login_employee') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_login_employee') }}" name="employee_login_id">
                                        <option value="username" {{ $setting->employee_login_id == 'username' ? 'selected' : '' }}>{{ __('xin_login_employee_with_username') }}</option>
                                        <option value="email" {{ $setting->employee_login_id == 'email' ? 'selected' : '' }}>{{ __('xin_login_employee_with_email') }}</option>
                                        <option value="pincode" {{ $setting->employee_login_id == 'pincode' ? 'selected' : '' }}>{{ __('xin_login_employee_with_pincode') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_date_format') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_date_format') }}" name="date_format">
                                        <option value="d-m-Y" {{ $setting->date_format_xi == 'd-m-Y' ? 'selected' : '' }}>dd-mm-YYYY ({{ date('d-m-Y') }})</option>
                                        <option value="m-d-Y" {{ $setting->date_format_xi == 'm-d-Y' ? 'selected' : '' }}>mm-dd-YYYY ({{ date('m-d-Y') }})</option>
                                        <option value="d-M-Y" {{ $setting->date_format_xi == 'd-M-Y' ? 'selected' : '' }}>dd-MM-YYYY ({{ date('d-M-Y') }})</option>
                                        <option value="M-d-Y" {{ $setting->date_format_xi == 'M-d-Y' ? 'selected' : '' }}>MM-dd-YYYY ({{ date('M-d-Y') }})</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_footer_text') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  placeholder="{{ __('xin_footer_text') }}" value="{{ $setting->footer_text }}" name="footer_text"/>
                                    <span class="form-text text-danger" id="errorFooterText"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_setting_timezone') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_setting_timezone') }}" name="system_timezone">
                                        @foreach($timezones as $key => $val)
                                            <option value="{{ $key }}" {{ $setting->system_timezone == $key ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_hrsale_default_language') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_hrsale_default_language') }}" name="default_language">
                                        @foreach($languages as $val)
                                            <option value="{{ $val->language_code }}" {{ $val->language_code == $setting->default_language ? 'selected' : '' }}>{{ $val->language_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('xin_enable_year_on_footer') }} <small>(footer)</small></label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="enable_current_year" {{ $setting->enable_current_year == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_enable_codeigniter_on_footer') }} <small>(footer)</small></label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="enable_page_rendered" {{ $setting->enable_page_rendered == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ __('xin_enable_geolocation_ssl') }}
                                        <button type="button" class="btn btn-icon btn-xs" data-toggle="popover" data-placement="top" data-content="{{ __('xin_enable_geolocation_ssl_details') }}" data-trigger="hover" data-original-title="{{ __('xin_enable_geolocation_ssl') }}"><span class="fa fa-question-circle"></span></button>
                                    </label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="is_ssl_available" {{ $setting->is_ssl_available == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('xin_payroll_statutory_fixed') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="statutory_fixed" {{ $setting->statutory_fixed == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_setting_google_maps_api_key') }}
                                        <button type="button" class="btn btn-icon btn-xs" data-toggle="popover" data-placement="top" data-content="{{ __('xin_enable_geolocation_ssl_details') }}" data-trigger="hover" data-original-title="{{ __('xin_setting_google_maps_api_key') }}"><span class="fa fa-question-circle"></span></button>
                                    </label>
                                    <textarea class="form-control" rows="1" placeholder="{{ __('xin_setting_google_maps_api_key') }}" name="google_maps_api_key">{{ $setting->google_maps_api_key }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('left_show_projects') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('left_show_projects') }}" name="show_projects">
                                        <option value="0" {{ $setting->show_projects == '0' ? 'selected' : '' }}>{{ __('xin_list_view') }}</option>
                                        <option value="1" {{ $setting->show_projects == '1' ? 'selected' : '' }}>{{ __('xin_grid_view') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('left_show_tasks') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('left_show_tasks') }}" name="show_tasks">
                                        <option value="0" {{ $setting->show_tasks == '0' ? 'selected' : '' }}>{{ __('xin_list_view') }}</option>
                                        <option value="1" {{ $setting->show_tasks == '1' ? 'selected' : '' }}>{{ __('xin_grid_view') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_estimate_terms_condition') }}</label>
                                    <textarea class="form-control" rows="5" placeholder="{{ __('xin_estimate_terms_condition') }}" name="estimate_terms_condition">{{ $setting->estimate_terms_condition }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_invoice_terms_condition') }}</label>
                                    <textarea class="form-control" rows="5" placeholder="{{ __('xin_invoice_terms_condition') }}" name="invoice_terms_condition">{{ $setting->invoice_terms_condition }}</textarea>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#updateAccountSystem').submit(function (event) {
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
                        if(response.errorsForm.application_name){
                            $('#errorApplicationName').html(response.errorsForm.application_name[0]);
                            $('#errorApplicationName').show()
                        }
                        if(response.errorsForm.footer_text){
                            $('#errorFooterText').html(response.errorsForm.footer_text[0]);
                            $('#errorFooterText').show()
                        }
                    }

                    if (response.success) {
                        toastr.success(response.data);
                        // $('#popupExperience').modal('hide');
                        // $('#formExperience').trigger( "reset" );
                        // window._tables.experience.datatable.reload();
                    } else if (response.errors) {
                        toastr.error(response.data);
                    }
                })
            })
        });
    </script>
@endsection

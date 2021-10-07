@extends('layout.default')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <p class="font-weight-normal font-size-lg pb-2 pt-6">
                {!! sprintf( __('xin_setting_module_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}
            </p>

            <table class=" table table-bordered">
                <tbody>
                <tr>
                    <td style="width: 160px">{{ __('left_recruitment') }}</td>
                    <td>{!! sprintf( __('xin_setting_module_recruitment_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_recruitment=='true') checked @endif class="switch-setup-modules" id="m-recruitment"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('left_travels') }}</td>
                    <td>{{ __('xin_setting_module_travels_details') }}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_travel=='true') checked @endif class="switch-setup-modules" id="m-travel"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_files_manager') }}</td>
                    <td>{{ __('xin_setting_module_fmanager_details') }}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_files=='true') checked @endif class="switch-setup-modules" id="m-files"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_multi_language') }}</td>
                    <td>{{ __('xin_setting_module_mlanguage_details') }}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_language=='true') checked @endif class="switch-setup-modules" id="m-language"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_org_chart_title') }}</td>
                    <td>{{ __('xin_setting_module_orgchart_details') }}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_orgchart=='true') checked @endif class="switch-setup-modules" id="m-orgchart"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_hr_events_meetings') }}</td>
                    <td>{{ __('xin_hr_events_meetings_details') }}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_events=='true') checked @endif class="switch-setup-modules" id="m-events"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_hr_chat_box') }}</td>
                    <td>{!! sprintf( __('xin_hr_chat_box_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_chat_box=='true') checked @endif class="switch-setup-modules" id="m-chatbox"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('xin_enable_sub_departments') }}</td>
                    <td>{!! sprintf( __('xin_subdepartments_title_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="yes" @if($setting->is_active_sub_departments=='yes') checked @endif class="switch-setup-modules" id="m-sub_departments"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('left_payroll') }}</td>
                    <td>{!! sprintf( __('xin_payroll_title_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="yes" @if($setting->module_payroll=='yes') checked @endif class="switch-setup-modules" id="m-payroll"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('left_performance') }}</td>
                    <td>{!! sprintf( __('xin_setting_module_performance_details'), \App\Classes\Settings\SettingManager::getOption('company_name')) !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="yes" @if($setting->module_performance=='yes') checked @endif class="switch-setup-modules" id="m-performance"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('SSO') }}</td>
                    <td>{!! __('xin_setting_module_sso_details') !!}</td>
                    <td class="justify-content-center">
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" value="true" @if($setting->module_sso=='true') checked @endif class="switch-setup-modules" id="m-sso"/>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $(".switch").change(function(){
                if($('#m-recruitment').is(':checked')){
                    var mrecruitment = $("#m-recruitment").val();
                } else {
                    var mrecruitment = '';
                }
                if($('#m-travel').is(':checked')){
                    var mtravel = $("#m-travel").val();
                } else {
                    var mtravel = '';
                }
                if($('#m-files').is(':checked')){
                    var mfiles = $("#m-files").val();
                } else {
                    var mfiles = '';
                }
                if($('#m-language').is(':checked')){
                    var mlanguage = $("#m-language").val();
                } else {
                    var mlanguage = '';
                }
                if($('#m-orgchart').is(':checked')){
                    var morgchart = $("#m-orgchart").val();
                } else {
                    var morgchart = '';
                }
                if($('#m-events').is(':checked')){
                    var mevents = $("#m-events").val();
                } else {
                    var mevents = '';
                }
                if($('#m-chatbox').is(':checked')){
                    var chatbox = $("#m-chatbox").val();
                } else {
                    var chatbox = '';
                }
                if($('#m-sub_departments').is(':checked')){
                    var is_sub_departments = $("#m-sub_departments").val();
                } else {
                    var is_sub_departments = '';
                }
                if($('#m-payroll').is(':checked')){
                    var module_payroll = $("#m-payroll").val();
                } else {
                    var module_payroll = '';
                }
                if($('#m-performance').is(':checked')){
                    var module_performance = $("#m-performance").val();
                } else {
                    var module_performance = '';
                }
                if($('#m-sso').is(':checked')){
                    var module_sso = $("#m-sso").val();
                } else {
                    var module_sso = '';
                }

                $.ajax({
                    type: "get",  
                    url: "modules_info_update?mrecruitment="
                    +mrecruitment+"&mtravel="+mtravel+"&mfiles="+mfiles+"&mlanguage="+mlanguage+"&morgchart="+morgchart+
                    "&mevents="+mevents+"&chatbox="+chatbox+'&is_sub_departments='+is_sub_departments+'&module_payroll='+
                    module_payroll+'&module_performance='+module_performance+'&module_sso='+module_sso,
                    success: function(response) {
                        toastr.success(response.data);
                    },
                    error: function (response) {
                        toastr.error(response.error ?? __("error"));
                    },
                });
            });
        });
    </script>
@endsection

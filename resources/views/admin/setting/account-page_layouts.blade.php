@extends('layout.default')

@section('styles')

@endsection

@section('content')
    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <form class="form" id="updateAccountPageLayouts" method="POST" action="{{ route('config.setting.update-account-page-layouts') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_theme_show_dashboard_cards') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_theme_show_dashboard_cards') }}" name="statistics_cards">
                                        <option value="4" {{ $theme_setting->statistics_cards == 4 ? 'selected' : '' }}>{{ __('4') }}</option>
                                        <option value="8" {{ $theme_setting->statistics_cards == 8 ? 'selected' : '' }}>{{ __('8') }}</option>
                                    </select>
                                    <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('xin_theme_set_statistics_cards') }}</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_hrsale_dashboard_options') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_hrsale_dashboard_options') }}" name="dashboard_option">
                                        <option value="dashboard_1" {{ $theme_setting->dashboard_option == 'dashboard_1' ? 'selected' : '' }}>{{ __('xin_hrsale_dashboard_option_1') }}</option>
                                        <option value="dashboard_light_2" {{ $theme_setting->dashboard_option == 'dashboard_light_2' ? 'selected' : '' }}>{{ __('xin_hrsale_dashboard_2light') }}</option>
                                        <option value="dashboard_dark_2" {{ $theme_setting->dashboard_option == 'dashboard_dark_2' ? 'selected' : '' }}>{{ __('dashboard_dark_2') }}</option>
                                        <option value="dashboard_3" {{ $theme_setting->dashboard_option == 'dashboard_3' ? 'selected' : '' }}>{{ __('xin_hrsale_dashboard_option_3') }}</option>
                                    </select>
                                    <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('xin_hrsale_dashboard_options_details') }}</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_sign_in_page_options') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_sign_in_page_options') }}" name="login_page_options">
                                        <option value="login_page_1" {{ $theme_setting->login_page_options == 'dashboard_1' ? 'selected' : '' }}>{{ __('xin_hrsale_login_v1') }}</option>
                                        <option value="login_page_2" {{ $theme_setting->login_page_options == 'login_page_2' ? 'selected' : '' }}>{{ __('xin_hrsale_login_v2') }}</option>
                                        <option value="login_page_3" {{ $theme_setting->login_page_options == 'login_page_3' ? 'selected' : '' }}>{{ __('xin_hrsale_login_v3') }}</option>
                                    </select>
                                    <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('xin_sign_in_page_option_details') }}</small>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('xin_hrsale_show_calendar_on_dashboard') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="dashboard_calendar" {{ $theme_setting->dashboard_calendar == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('Văn bản trang đăng nhập') }}</label>
                                    <textarea class="form-control" rows="4" placeholder="{{ __('Văn bản trang đăng nhập') }}" name="login_page_text">{{ $theme_setting->login_page_text }}</textarea>
                                    <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('Văn bản trang đăng nhập mô tả') }}</small>
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
        $(document).ready(function () {

            $('#updateAccountPageLayouts').submit(function (event) {
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

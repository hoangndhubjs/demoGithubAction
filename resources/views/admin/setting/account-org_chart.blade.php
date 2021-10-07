@extends('layout.default')

@section('styles')

@endsection

@section('content')
    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                @if ($setting->module_orgchart == 'true')
                    <form class="form" id="updateAccountOrgChart" method="POST" action="{{ route('config.setting.update-account-org-chart') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="col-md-12 p-0 m-0 row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_org_chart_layout') }}</label>
                                        <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_org_chart_layout') }}" name="org_chart_layout">
                                            <option value="r2l" {{ $theme_setting->org_chart_layout == 'r2l' ? 'selected' : '' }}>{{ __('xin_org_chart_r2l') }}</option>
                                            <option value="l2r" {{ $theme_setting->org_chart_layout == 'l2r' ? 'selected' : '' }}>{{ __('xin_org_chart_l2r') }}</option>
                                            <option value="t2b" {{ $theme_setting->org_chart_layout == 't2b' ? 'selected' : '' }}>{{ __('xin_org_chart_t2b') }}</option>
                                            <option value="b2t" {{ $theme_setting->org_chart_layout == 'b2t' ? 'selected' : '' }}>{{ __('xin_org_chart_b2t') }}</option>
                                        </select>
                                        <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('xin_org_chart_set_layout') }}</small>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_org_chart_export_file_title') }} </label>
                                        <input type="text" class="form-control"  placeholder="{{ __('xin_org_chart_export_file_title') }}" name="export_file_title" value="{{ $theme_setting->export_file_title }}"/>
                                        <small class="text-muted pt-1"><i class="fas fa-hand-point-up mt-2 ml-2 mr-1 "></i> {{ __('xin_org_chart_export_file_title_details') }}</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_org_chart_export') }}
                                            <button type="button" class="btn btn-icon btn-xs" data-toggle="popover" data-placement="top" data-content="{{ __('xin_org_chart_export_details') }}" data-trigger="hover" data-original-title="{{ __('xin_org_chart_export') }}"><span class="fa fa-question-circle"></span></button>
                                        </label>
                                        <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="export_orgchart" {{ $theme_setting->export_orgchart == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_org_chart_zoom') }}
                                            <button type="button" class="btn btn-icon btn-xs" data-toggle="popover" data-placement="top" data-content="{{ __('xin_org_chart_zoom_details') }}" data-trigger="hover" data-original-title="{{ __('xin_org_chart_zoom') }}"><span class="fa fa-question-circle"></span></button>
                                        </label>
                                        <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="org_chart_zoom" {{ $theme_setting->org_chart_zoom == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_org_chart_pan') }}
                                            <button type="button" class="btn btn-icon btn-xs" data-toggle="popover" data-placement="top" data-content="{{ __('xin_org_chart_pan_details') }}" data-trigger="hover" data-original-title="{{ __('xin_org_chart_pan') }}"><span class="fa fa-question-circle"></span></button>
                                        </label>
                                        <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="org_chart_pan" {{ $theme_setting->org_chart_pan == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="col-md-12 text-right">
                                <button type="submit" id="saveAccountSystem" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#updateAccountOrgChart').submit(function (event) {
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

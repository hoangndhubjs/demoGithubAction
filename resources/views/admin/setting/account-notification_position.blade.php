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
                <form class="form" id="updateNotificationPosition" method="POST" action="{{ route('config.setting.update-account-notification-position') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('dashboard_position') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('dashboard_position') }}" name="notification_position">
                                        <option value="toast-top-right" {{ $setting->notification_position == 'toast-top-right' ? 'selected' : '' }}>{{ __('xin_top_right') }}</option>
                                        <option value="toast-bottom-right" {{ $setting->notification_position == 'toast-bottom-right' ? 'selected' : '' }}>{{ __('xin_bottom_right') }}</option>
                                        <option value="toast-bottom-left" {{ $setting->notification_position == 'toast-bottom-left' ? 'selected' : '' }}>{{ __('xin_bottom_left') }}</option>
                                        <option value="toast-top-left" {{ $setting->notification_position == 'toast-top-left' ? 'selected' : '' }}>{{ __('xin_top_left') }}</option>
                                        <option value="toast-top-center" {{ $setting->notification_position == 'toast-top-center' ? 'selected' : '' }}>{{ __('xin_top_center') }}</option>
                                    </select>
                                    <span class="form-text text-muted">{{ __('xin_set_position_for_notifications') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_close_button') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="notification_close_btn" {{ $setting->notification_close_btn == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_progress_bar') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="true" name="notification_bar" {{ $setting->notification_bar == 'true' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            <button type="submit" id="updateNotificationPosition" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
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
            $('#updateNotificationPosition').submit(function (event) {
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

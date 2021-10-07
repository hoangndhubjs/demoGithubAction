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
                <form class="form" id="updateAccountEmail" method="POST" action="{{ route('config.setting.update-account-email') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_email_notification_enable') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="enable_email_notification" {{ $setting->enable_email_notification == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_mail_type_config') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_mail_type_config') }}" id="email_type" name="email_type">
{{--                                        <option value="codeigniter" {{ $email_config->email_type == 'codeigniter' ? 'selected' : '' }}>{{ __('CodeIgniter') }}</option>--}}
                                        <option value="phpmail" {{ $email_config->email_type == 'phpmail' ? 'selected' : '' }}>{{ __('PHP') }}</option>
                                        <option value="smtp" {{ $email_config->email_type == 'smtp' ? 'selected' : '' }}>{{ __('SMTP') }}</option>
                                    </select>
                                </div>
                            </div>

                            @php $email_config->email_type == 'smtp' ? $sm_opt = 'style="display:flex;"' : $sm_opt = 'style="display:none;"' @endphp

                            <div class="col-md-12 row" id="smtp_options" {!! $sm_opt !!}>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_mail_smtp_host') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('xin_mail_smtp_host') }}" name="smtp_host" value="{{ $email_config->smtp_host }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_mail_smtp_username') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('xin_mail_smtp_username') }}" name="smtp_username" value="{{ $email_config->smtp_username }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_mail_smtp_password') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('xin_mail_smtp_password') }}" name="smtp_password" value="{{ $email_config->smtp_password }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_mail_smtp_port') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('xin_mail_smtp_port') }}" name="smtp_port" value="{{ $email_config->smtp_port }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('xin_mail_smtp_secure') }}</label>
                                        <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_mail_smtp_secure') }}" name="smtp_secure">
                                            <option value="tls" {{ $email_config->smtp_secure == 'tls' ? 'selected' : '' }}>{{ __('TLS') }}</option>
                                            <option value="ssl" {{ $email_config->smtp_secure == 'ssl' ? 'selected' : '' }}>{{ __('SSL') }}</option>
                                        </select>
                                    </div>
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
            $("#email_type").change(function(){
                var opt = $(this).val();
                if(opt == 'smtp'){
                    $('#smtp_options').show();
                } else {
                    $('#smtp_options').hide();
                }
            });

            $('#updateAccountEmail').submit(function (event) {
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

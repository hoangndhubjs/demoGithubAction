@extends('layout.default')

@section('styles')
    <style>
        .tagify {
            padding: 0!important;
        }
        .tagify__input {
            display: unset;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                @if ($setting->module_recruitment == 'true' || $setting->enable_job_application_candidates == '1')
                    <form class="form" id="updateAccountRecruitment" method="POST" action="{{ route('config.setting.update-account-recruitment') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="col-md-12 p-0 m-0 row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_enable_jobs_for_employees') }}</label>
                                        <span class="switch switch-icon">
                                            <label>
                                                <input type="checkbox" class="switch-setup-modules" value="1" name="enable_job_application_candidates" {{ $setting->enable_job_application_candidates == '1' ? 'checked' : '' }}/>
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_job_application_file_format') }}</label>
                                        <input id="tag1" class="form-control tagify" name='job_application_format' placeholder='type...' value='{{ $setting->job_application_format }}' autofocus="" />
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="col-md-12 text-right">
                                <button type="submit" id="saveAccountRecruitment" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
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
    <script>
        $(document).ready(function() {

            var tag1 = document.querySelector('#tag1');
            new Tagify(tag1)

            $('#updateAccountRecruitment').submit(function (event) {
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

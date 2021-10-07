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
                <form class="form" id="updateAccountFiles" method="POST" action="{{ route('config.setting.update-account-files') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_file_maxsize') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ __('xin_file_size_mb') }}" name="maximum_file_size" value="{{ $file_setting->maximum_file_size }}"/>
                                        <div class="input-group-append"><span class="input-group-text">MB</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_allowed_extensions') }} <span class="text-danger">*</span></label>
                                    <input id="tag1" class="form-control tagify" name='allowed_extensions' value='{{ $file_setting->allowed_extensions }}' autofocus="" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('xin_employee_can_view_download_other_files') }} <span class="text-danger">*</span></label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="is_enable_all_files" {{ $file_setting->is_enable_all_files == 'yes' ? 'checked' : '' }}/>
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
            </div>


        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var tag1 = document.querySelector('#tag1');
            new Tagify(tag1)

            $('#updateAccountFiles').submit(function (event) {
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

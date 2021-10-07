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
                <form class="form" id="updateAccountPerformance" method="POST" action="{{ route('config.setting.update-account-performance') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>{{ __('xin_performance_technical_competencies') }}</label>
                                    <input id="tag1" class="form-control tagify" name='technical_competencies' placeholder='type...' value='{{ $setting->technical_competencies }}' autofocus="" />
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>{{ __('xin_performance_behv_technical_competencies') }}</label>
                                    <input id="tag2" class="form-control tagify" name='organizational_competencies' placeholder='type...' value='{{ $setting->organizational_competencies }}' autofocus="" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('left_performance') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('left_performance') }}" name="performance_option">
                                        <option value="goal" {{ $setting->performance_option == 'goal' ? 'selected' : '' }}>{{ __('xin_hr_goal_title') }}</option>
                                        <option value="appraisal" {{ $setting->performance_option == 'appraisal' ? 'selected' : '' }}>{{ __('left_performance_xappraisal') }}</option>
                                        <option value="both" {{ $setting->performance_option == 'both' ? 'selected' : '' }}>{{ __('xin_both_goal_appraisal') }}</option>
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
        $(document).ready(function() {

            var tag1 = document.querySelector('#tag1');
            new Tagify(tag1)
            var tag2 = document.querySelector('#tag2');
            new Tagify(tag2)

            $('#updateAccountPerformance').submit(function (event) {
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

@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('employees.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_e_details_social') }}
                        </h3>
                        <span
                            class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_e_details_social_update') }}</span>
                    </div>
                </div>
                <form class="form" id="formSocial" method="POST" action="{{ route('update_social') }}">
                    @csrf
                    <div class="card-body">

                        <div class="form-group row">
                            <label  class="col-xl-3 col-lg-3 col-form-label">
                                {{ __('xin_e_details_fb_profile') }}
                            </label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" placeholder="{{ __('xin_e_details_fb_profile') }}" value="{{ $info_user->facebook_link }}" name="facebook_link"/>
                                <span class="form-text text-danger" id="errorFacebook"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label  class="col-xl-3 col-lg-3 col-form-label">
                                {{ __('xin_e_details_twit_profile') }}
                            </label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" placeholder="{{ __('xin_e_details_twit_profile') }}" value="{{ $info_user->twitter_link }}" name="twitter_link"/>
                                <span class="form-text text-danger" id="errorTwitter"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label  class="col-xl-3 col-lg-3 col-form-label">
                                {{ __('xin_e_details_utube_profile') }}
                            </label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" placeholder="{{ __('xin_e_details_utube_profile') }}" value="{{ $info_user->youtube_link }}" name="youtube_link"/>
                                <span class="form-text text-danger" id="errorYoutube"></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <button id="saveSocial" type="submit" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('xin_cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">

        $('#formSocial').submit(function (event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = $(this).serialize();
            $("#saveSocial").attr("disabled", true).html(__('saving'));

            $.ajax({
                type: request_method,
                url: post_url,
                data: form_data,
                cache: false,
            }).done(function (response) {

                $("#saveSocial").attr("disabled", false).html(__('xin_save'));
                if(response.errorsForm) {
                    if(response.errorsForm.facebook_link){
                        $('#errorFacebook').html(response.errorsForm.facebook_link[0]);
                        $('#errorFacebook').show()
                    }
                    if(response.errorsForm.twitter_link){
                        $('#errorTwitter').html(response.errorsForm.twitter_link[0]);
                        $('#errorTwitter').show()
                    }
                    if(response.errorsForm.blogger_link){
                        $('#errorBlogger').html(response.errorsForm.blogger_link[0]);
                        $('#errorBlogger').show()
                    }
                    if(response.errorsForm.youtube_link){
                        $('#errorYoutube').html(response.errorsForm.youtube_link[0]);
                        $('#errorYoutube').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    window.location.reload();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            })
        });

        $(':input').change(function () {
            $(this).closest('.form-group').find('.form-text.text-danger').hide();
        });

    </script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
@endsection

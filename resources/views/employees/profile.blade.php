@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">

        @include('employees.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('dashboard_personal_details')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('update_profile') }}</span>
                    </div>
                    <div class="card-toolbar">
                        <button id="saveInfo" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                        <a href="/" class="btn btn-secondary">{{ __('xin_cancel') }}</a>
                    </div>
                </div>
                <form class="form" id="formUpdateProfile" enctype="multipart/form-data">
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_avatar') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="image-input image-input-outline" id="kt_profile_avatar">
                                    <div class="image-input-wrapper" style="@if($info_user->profile_picture ) background-image: url('{{ $info_user->profile_picture }}') @else '{{ asset('media/users/blank.png') }}' @endif"></div>
                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-original-title="Change avatar"
                                           data-action="change" data-toggle="tooltip">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
                                        <input type="hidden" name="profile_avatar_remove"/>
                                    </label>
                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                          data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" id="btnRemove"
                                          data-action="remove" data-toggle="tooltip" title="Remove avatar" style="display: none">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                </div>
                                <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_name') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" placeholder="{{ __('xin_name') }}"
                                       disabled name="first_name" value="{{ $info_user->first_name }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_last_name') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" placeholder="{{ __('xin_employee_last_name') }}" disabled
                                       name="last_name" value="{{ $info_user->last_name }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('dashboard_email') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-at"></i>
                                                </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="{{ __('dashboard_email') }}" disabled name="email"
                                           value="{{ $info_user->email }}"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_phone') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="la la-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control numberRq" placeholder="{{ __('xin_phone') }}"
                                           name="contact_no" value="{{ $info_user->contact_no }}"/>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_dob') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                    </div>
                                    <input type="text" class="form-control datepickerDefautl" name="date_of_birth"
                                           value="{{ date('d-m-Y', strtotime($info_user->date_of_birth)) }}"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_gender') }}</label>
                            <div class="col-lg-9 col-xl-6 col-form-label">
                                <div class="radio-inline row col-md-12">
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="Male" name="gender" {{ $gender == 'Male' ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('xin_gender_male') }}
                                    </label>

                                    <label class="radio col px-md-5">
                                        <input type="radio" value="Female" name="gender" {{ $gender == 'Female' ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('xin_gender_female') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_mstatus') }}</label>
                            <div class="col-lg-9 col-xl-6 col-form-label">
                                <div class="radio-inline row col-md-12">
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="Single" name="marital_status" {{ $marital_status == 'Single' ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('xin_status_single') }}
                                    </label>

                                    <label class="radio col px-md-5">
                                        <input type="radio" value="Married" name="marital_status" {{ $marital_status == 'Married' ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('xin_status_married') }}
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_address') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <textarea class="form-control" rows="3" name="address">{!! $info_user->address !!}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script type="text/javascript">
        @if (app('hrm')->isSSO())
            $(document).ready(function() {
                $("#kt_profile_avatar").click(function () {
                    window.location.href = '{{ config('services.sso.url').'/account/profile' }}';
                });
            })
        @else
            $("#btnRemove").show();
        @endif
    </script>
@endsection

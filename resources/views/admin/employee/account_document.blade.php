@extends('layout.default')

@section('content')

    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="d-flex flex-row">
                @include('employees.nav_employee')

                <div class="flex-row-fluid ml-lg-8">
                    <div class="card card-custom card-stretch">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">
                                    {{ __('xin_e_details_document') }}
                                </h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_e_details_document_update') }}</span>
                            </div>
                            <div class="card-toolbar">
                                <button id="saveInfo" class="btn btn-success mr-2">{{ __('xin_save') }}</button>
                                <button class="btn btn-secondary">{{ __('xin_cancel') }}</button>
                            </div>
                        </div>
                        <form class="form">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_avatar') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar">
                                            <div class="image-input-wrapper"
                                                 style="@if($info_user->profile_picture ) background-image: url({{ $info_user->profile_picture }}) @else {{ asset('media/users/blank.png') }} @endif"></div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
                                                <input type="hidden" name="profile_avatar_remove"/>
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                  data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                <i class="ki ki-bold-close icon-xs text-muted"></i>
            </span>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                  data-action="remove" data-toggle="tooltip" title="Remove avatar">
                <i class="ki ki-bold-close icon-xs text-muted"></i>
            </span>
                                        </div>
                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" placeholder="{{ __('xin_name') }}" disabled name="first_name" value="{{ $info_user->first_name }}"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_last_name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" type="text" placeholder="{{ __('xin_employee_last_name') }}" disabled name="last_name" value="{{ $info_user->last_name }}"/>
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
                                            <input type="text" class="form-control" placeholder="{{ __('dashboard_email') }}" disabled name="email" value="{{ $info_user->email }}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_phone') }} <strong class="required">*</strong></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="la la-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="{{ __('xin_phone') }}" name="contact_no" value="{{ $info_user->contact_no }}"/>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_dob') }} <strong class="required">*</strong></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="la la-calendar-check-o"></i>
                </span>
                                            </div>
                                            <input type="text" class="form-control datepickerDefautl" readonly="readonly" name="date_of_birth" value="{{ date('d-m-Y', strtotime($info_user->date_of_birth)) }}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_gender') }} <strong class="required">*</strong></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group">
                                            <select class="form-control selectSearch" id="sex" name="gender">
                                                <option value="Male" @if ($gender == 'Male') selected @endif>{{ __('xin_gender_male') }}</option>
                                                <option value="Female" @if ($gender == 'Female') selected @endif>{{ __('xin_gender_female') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_mstatus') }} <strong class="required">*</strong></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group">
                                            <select class="form-control selectSearch" id="marital_status" name="marital_status">
                                                <option value="Single" @if ($marital_status == 'Single') selected @endif>{{ __('xin_status_single') }}</option>
                                                <option value="Married" @if ($marital_status == 'Married') selected @endif>{{ __('xin_status_married') }}</option>
                                                <option value="Widowed" @if ($marital_status == 'Widowed') selected @endif>{{ __('xin_status_widowed') }}</option>
                                                <option value="Divorced or Separated" @if ($marital_status == 'Divorced or Separated') selected @endif>{{ __('xin_status_divorced_separated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_address') }} <strong class="required">*</strong></label>
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
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
@endsection


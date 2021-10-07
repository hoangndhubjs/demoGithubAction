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
                <form class="form" id="updateAccountRole" method="POST" action="{{ route('config.setting.update-account-role') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_contact_info') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_contact" {{ $setting->employee_manage_own_contact == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_documents') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_document" {{ $setting->employee_manage_own_document == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_bank_account') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_bank_account" {{ $setting->employee_manage_own_bank_account == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_profile_picture') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_picture" {{ $setting->employee_manage_own_picture == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_qualification') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_qualification" {{ $setting->employee_manage_own_qualification == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_profile_info') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_profile" {{ $setting->employee_manage_own_profile == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_work_experience') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_work_experience" {{ $setting->employee_manage_own_work_experience == 'yes' ? 'checked' : '' }}/>
                                            <span></span>
                                        </label>
                                     </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('xin_employe_can_manage_social_info') }}</label>
                                    <span class="switch switch-icon">
                                        <label>
                                            <input type="checkbox" class="switch-setup-modules" value="yes" name="employee_manage_own_social" {{ $setting->employee_manage_own_social == 'yes' ? 'checked' : '' }}/>
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
    <script>
        $(document).ready(function() {
            $('#updateAccountRole').submit(function (event) {
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

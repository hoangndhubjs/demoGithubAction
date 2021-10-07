<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px overflow-auto" id="kt_profile_aside">
    <div class="card card-custom card-stretch">
        <div class="card-body pt-15">
            <div class="text-center mb-5">
                <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                    <div class="symbol-label" style="background-image:url('{{ $info_user->profile_picture }}')"></div>
                    <i class="symbol-badge symbol-badge-bottom bg-success"></i>
                </div>
                <h4 class="font-weight-bold my-2">{{ $info_user->getFullname() }}</h4>
                <div class="text-muted mb-2">{{ $info_user->designation && $info_user->designation->designation_name ? $info_user->designation->designation_name : '' }} </div>
                <div class="text-muted mb-2">
                    {{ __('work_time') }}: {{ $info_user->office_shift->monday_in_time }} - {{ $info_user->office_shift->monday_out_time }}
                </div>
                <div class="text-muted mb-2">
                    {{ __('form_monday_to_saturday') }}
                </div>
                <span class="label label-light-success label-inline font-weight-bold label-lg">
                    {{ __('xin_employees_active') }}
                </span>
            </div>
            <div class="mb-5 text-center">
                <a href="{{ $info_user->facebook_link ?? '#' }}" target="_blank" class="btn btn-icon btn-circle btn-light-facebook mr-2">
                    <i class="socicon-facebook"></i>
                </a>
                <a href="{{ $info_user->twitter_link ?? '#' }}" target="_blank" class="btn btn-icon btn-circle btn-light-twitter mr-2">
                    <i class="socicon-twitter"></i>
                </a>
                <a href="{{ $info_user->youtube_link ?? '#' }}" target="_blank" class="btn btn-icon btn-circle btn-light-google">
                    <i class="socicon-youtube"></i>
                </a>
            </div>

            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">

                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.immigration', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.immigration', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_employee_immigration') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.contact', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.contact', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_employee_emergency_contacts') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.social', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.social', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_e_details_social') }}</span>
                    </a>
                </div>
                    <div class="navi-item mb-2">
                        <a href="{{ route('employee_managements.qualification', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.qualification', request()->route('id')) ? 'active' : '' }}">
                            <span class="navi-text font-size-lg">{{ __('xin_e_details_qualification') }}</span>
                        </a>
                    </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.experience', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.experience', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_e_details_w_experience') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.baccount', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.baccount', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_e_details_baccount') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.asset_assign', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.asset_assign', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_asset_assign') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.security-level', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.security-level', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_esecurity_level_title') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.contract', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.contract', request()->route('id')) ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_e_details_contract') }}</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>


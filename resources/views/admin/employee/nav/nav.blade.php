<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px overflow-auto" id="kt_profile_aside">
    <div class="card card-custom card-stretch">
        <div class="card-body pt-15">
            <div class="text-center mb-5">
                <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                    <div class="symbol-label" style="background-image:url('{{ $getUser->profile_picture }}')"></div>
                    <i class="symbol-badge symbol-badge-bottom bg-success"></i>
                </div>
                <h4 class="font-weight-bold my-2">{{ $getUser->getFullname() }}</h4>
                <div class="text-muted mb-2">{{ $getUser->designation && $getUser->designation->designation_name ? $getUser->designation->designation_name : '' }} </div>
                <div class="text-muted mb-2">
                    {{ __('work_time') }}: {{ $getUser->office_shift->monday_in_time }} - {{ $getUser->office_shift->monday_out_time }}
                </div>
                <div class="text-muted mb-2">
                    {{ __('form_monday_to_saturday') }}
                </div>
                <span class="label label-light-success label-inline font-weight-bold label-lg">
                    {{ __('xin_employees_active') }}
                </span>
            </div>
            <div class="mb-5 text-center">
                <a href="{{ $getUser->facebook_link ? $getUser->facebook_link : '' }}" target="_blank" class="btn btn-icon btn-circle btn-light-facebook mr-2">
                    <i class="socicon-facebook"></i>
                </a>
                <a href="{{ $getUser->twitter_link ? $getUser->twitter_link : '' }}" target="_blank" class="btn btn-icon btn-circle btn-light-twitter mr-2">
                    <i class="socicon-twitter"></i>
                </a>
                <a href="#" target="_blank" class="btn btn-icon btn-circle btn-light-google">
                    <i class="socicon-google"></i>
                </a>
            </div>

            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ url('employee_managements/set_staff',request()->route('id')) }}" class="navi-link py-4 {{ request()->is('set_staff/*') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_employee_set_salary') }}</span>
                    </a>
                </div>
                <div class="navi-item mb-2">
                    <a href="{{ url('employee_managements/staff_allowance',request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('immigration') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('money_plus') }}</span>
                    </a>
                </div>

                <div class="navi-item mb-2">
                    <a href="{{ url('employee_managements/statutory_deductions',request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_employee_set_statutory_deductions') }}</span>
                    </a>
                </div>
{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ url('employee_managements/other_payment',request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('social') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_employee_set_other_payment') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ url('employee_managements/loan_deductions',request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('qualification') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_employee_set_loan_deductions') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ url('employee_managements/fine', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('experience') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_employee_set_mulct') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

                <div class="navi-item mb-2">
                    <a href="{{ route('employee_managements.money_minus', request()->route('id')) }}" class="navi-link py-4 {{ request()->routeIs('employee_managements.money_minus') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('money_minus') }}</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>


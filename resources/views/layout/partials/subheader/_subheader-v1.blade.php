{{-- Subheader V1 --}}
@php
    $id = request()->route('id');
    $activeRouteViewInfoUser =  Route::is('employee_managements.immigration', $id) ||
                                Route::is('employee_managements.contact', $id) ||
                                Route::is('employee_managements.social', $id) ||
                                Route::is('employee_managements.qualification', $id) ||
                                Route::is('employee_managements.experience', $id) ||
                                Route::is('employee_managements.baccount', $id) ||
                                Route::is('employee_managements.asset_assign', $id) ||
                                Route::is('employee_managements.security-level', $id) ||
                                Route::is('employee_managements.contract', $id);

    $activeRouteViewSalary =    Route::is('employee_managements.setStaff', $id) ||
                                Route::is('employee_managements.staff_allowance', $id) ||
                                Route::is('employee_managements.statutory_deductions', $id) ||
                                Route::is('employee_managements.loan_deductions', $id) ||
                                Route::is('employee_managements.fine', $id) ||
                                Route::is('employee_managements.money_minus', $id);
@endphp
<div class="subheader py-2 py-lg-4 subheader-solid {{ Metronic::printClasses('subheader', false) }}" id="kt_subheader">
    <div class="{{ Metronic::printClasses('subheader-container', false) }} d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

		{{-- Info --}}
        <div class="d-flex align-items-center flex-wrap mr-2 {{ Request::routeIs('dashboard') ? 'justify-content-between w-100' : '' }}">

            @if (!empty($isEmployeeModule) or !empty($adminSetting) or !empty($adminViewProfileUser))
                <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                    <span></span>
                </button>
            @endif

			{{-- Page Title --}}
            <h5 class="text-dark font-weight-bold my-2 mr-5">
                {{ @$page_title ?? '' }}
                @if (request()->has('dong_nat'))
                    <marquee>"Ai đồng nát sắt vụt bán đêêêêêêeeeee... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Team đồng nát </marquee>
                @endif

                @if (isset($page_description) && config('layout.subheader.displayDesc'))
                    <small>{{ @$page_description }}</small>
                @endif
            </h5>
                @if(\Request::routeIs('dashboard'))
                <div class="checkin_checkout">
                    <div class="clock_in">
                        <button data-type="check_in" class="btn btn-light-success">
                            Checkin
                        </button>
                        <button data-type="check_out" class="btn btn-light-danger ml-3">
                            Checkout
                        </button>
                    </div>
                    <div class="checkout"></div>
                </div>
                @endif
            @if (!empty($page_breadcrumbs))
				{{-- Separator --}}
                <div class="subheader-separator subheader-separator-ver my-2 mr-4 d-none"></div>

				{{-- Breadcrumb --}}
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2">
                    <li class="breadcrumb-item"><a href="#"><i class="flaticon2-shelter text-muted icon-1x"></i></a></li>
                    @foreach ($page_breadcrumbs as $k => $item)
						<li class="breadcrumb-item">
                        	<a href="{{ url($item['page']) }}" class="text-muted">
                            	{{ $item['title'] }}
                        	</a>
						</li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Toolbar --}}
        <div class="d-flex align-items-center flex-wrap">
            @if($isEmployeeModule ?? '')
                @if (config('layout.subheader.displayDaterangepicker'))
                    <a href="#" class="btn btn-light btn-sm font-weight-bold mr-2" id="kt_dashboard_daterangepicker" data-toggle="tooltip" title="Select dashboard daterange" data-placement="left">
                        <span class="text-muted font-weight-bold" id="kt_dashboard_daterangepicker_title mr-2">Today</span>
                        <span class="text-primary font-weight-bold" id="kt_dashboard_daterangepicker_date">Aug 16</span>
                    </a>
                @endif

                    <a href="{{ route('profile') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $isEmployeeModule && Route::is('profile') || Route::is('immigration') || Route::is('contact') || Route::is('social') || Route::is('qualification') || Route::is('experience') || Route::is('baccount') || Route::is('asset_assign') ? 'btn-primary' : 'btn-light-primary' }}">
                        <x-icon type="svg" category="General" icon="Settings-2"/>{{ __('dashboard_personal_details') }}
                    </a>

                    {{-- <a href="{{ url('employees/detail_salary_user') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $isEmployeeModule && Route::is('salary_payslips') || Route::is('detail_salary_user') || Route::is('month_salary') ? 'btn-primary' : 'btn-light-primary' }}">
                        <x-icon type="svg" category="Communication" icon="Mail"/>{{ __('paycheck_employee') }}
                    </a> --}}
                    <a href="{{ url('employees/user_monthly_salary_slip') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $isEmployeeModule && Route::is('salary_payslips') || Route::is('detail_salary_user') || Route::is('month_salary') ? 'btn-primary' : 'btn-light-primary' }}">
                        <x-icon type="svg" category="Communication" icon="Mail"/>{{ __('paycheck_employee') }}
                    </a>

                    <a href="{{ route('work') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $isEmployeeModule && Route::is('work') ? 'btn-primary' : 'btn-light-primary' }}">
                        <x-icon type="svg" category="Code" icon="Code"/>{{ __('left_tasks') }}
                    </a>

                    <a href="@if (app('hrm')->isSSO()){{ config('services.sso.url').'/account/password' }}@else{{ route('changePassword') }}@endif" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $isEmployeeModule && Request::routeIs('changePassword') ? 'btn-primary' : 'btn-light-primary' }}">
                        <x-icon type="svg" category="Code" icon="Settings4"/>{{ __('header_change_password') }}
                    </a>
            @endif

            @if ($adminSetting ?? '')
                <a href="{{ route('config.setting.account-system') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminSetting && App\Classes\Theme\Menu::isSubRouteActive('config.setting.') ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="General" icon="Settings-2"/>{{ __('system_configuration') }}
                </a>

                <a href="{{ route('config.email-template') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminSetting && Route::is('config.email-template') ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="Communication" icon="Mail"/>{{ __('email_template') }}
                </a>

                <a href="{{ route('config.constants.contract-type') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminSetting && (App\Classes\Theme\Menu::isSubRouteActive('config.constants.') || Route::is('admin.timesheet.full_attendance')) ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="Code" icon="Code"/>{{ __('constants') }}
                </a>

                <a href="{{ route('config.module') }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminSetting && Route::is('config.module') ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="Code" icon="Settings4"/>{{ __('set_up_the_modules') }}
                </a>

            @endif

            @if ($adminViewProfileUser ?? '')
                <a href="{{ route('employee_managements.immigration', request()->route('id')) }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminViewProfileUser && $activeRouteViewInfoUser ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="Code" icon="Settings4"/>{{ __('xin_e_details_basic') }}
                </a>

                <a href="{{ route('employee_managements.setStaff', request()->route('id')) }}" class="btn font-weight-bolder btn-sm mr-3 my-1 {{ $adminViewProfileUser && $activeRouteViewSalary ? 'btn-primary' : 'btn-light-primary' }}">
                    <x-icon type="svg" category="Code" icon="Settings4"/>{{ __('Thiết lập lương') }}
                </a>
            @endif

        </div>



    </div>
</div>

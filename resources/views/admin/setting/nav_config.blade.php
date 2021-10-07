<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px overflow-auto" id="kt_profile_aside">
    <div class="card card-custom card-stretch">
        <div class="card-body">
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-system')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-system') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_system') }}</span>
                    </a>
                </div>

                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-general')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-general') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_general') }}</span>
                    </a>
                </div>

                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-role')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-role') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_employee_role') }}</span>
                    </a>
                </div>

                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-payroll')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-payroll') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('left_payroll') }}</span>
                    </a>
                </div>

                @if ($setting->module_recruitment == 'true')
                    <div class="navi-item mb-2">
                        <a href="{{ route('config.setting.account-recruitment')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-recruitment') ? 'active' : '' }}">
                            <span class="navi-text font-size-lg">{{ __('left_recruitment') }}</span>
                        </a>
                    </div>
                @endif

                @if ($setting->module_performance == 'yes')
                    <div class="navi-item mb-2">
                        <a href="{{ route('config.setting.account-performance')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-performance') ? 'active' : '' }}">
                            <span class="navi-text font-size-lg">{{ __('left_performance') }}</span>
                        </a>
                    </div>
                @endif

                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-system-logos')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-system-logos') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_first_logo') }}</span>
                    </a>
                </div>

{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ route('config.setting.account-payment-gateway')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-payment-gateway') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_acc_payment_gateway') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

                <div class="navi-item mb-2">
                    <a href="{{ route('config.setting.account-email')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-email') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('xin_email_notifications') }}</span>
                    </a>
                </div>

{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ route('config.setting.account-page-layouts')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-page-layouts') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_page_layouts') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ route('config.setting.account-notification-position')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-notification-position') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_notification_position') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

                @if ($setting->module_files == 'true')
{{--                    <div class="navi-item mb-2">--}}
{{--                        <a href="{{ route('config.setting.account-files')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-files') ? 'active' : '' }}">--}}
{{--                            <span class="navi-text font-size-lg">{{ __('xin_files_manager') }}</span>--}}
{{--                        </a>--}}
{{--                    </div>--}}
                @endif

{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ route('config.setting.account-org-chart')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-org-chart') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_org_chart_lnk') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <div class="navi-item mb-2">--}}
{{--                    <a href="{{ route('config.setting.account-top-menu')  }}" class="navi-link py-4 {{ Route::is('config.setting.account-top-menu') ? 'active' : '' }}">--}}
{{--                        <span class="navi-text font-size-lg">{{ __('xin_manage_top_menu') }}</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>

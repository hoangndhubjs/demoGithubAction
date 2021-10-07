<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px overflow-auto" id="kt_profile_aside">
    <div class="card card-custom card-stretch">
        <div class="card-body">
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.contract-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.contract-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại hợp đồng') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.qualification_edu_level') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.qualification_edu_level')
                    || request()->routeIs('config.constants.qualification_language') || request()->routeIs('config.constants.qualification_skill')
                    ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Chứng chỉ') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.document-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.document-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại tài liệu') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.award-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.award-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại giải thưởng') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.ethnicity-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.ethnicity-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại dân tộc') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.leave-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.leave-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Các lý do nghỉ') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.warning-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.warning-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại cảnh báo') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.expense-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.expense-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại chi phí') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.income-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.income-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại thu nhập') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.currency-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.currency-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại tiền tệ') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.company-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.company-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại công ty') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.security-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.security-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại bảo mật') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.termination-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.termination-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Loại chấm dứt') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.exit-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.exit-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Các hình thức nghỉ') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('config.constants.arrangement-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.arrangement-type') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('Các hình thức du lịch') }}</span>
                    </a>
                </div>
            </div>
            @if(!app('hrm')->isSSO())
                <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                    <div class="navi-item mb-2">
                        <a href="{{ route('config.constants.job-type') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.job-type') ? 'active' : '' }}">
                            <span class="navi-text font-size-lg">{{ __('Loại công việc') }}</span>
                        </a>
                    </div>
                </div>
                <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                    <div class="navi-item mb-2">
                        <a href="{{ route('config.constants.job-category') }}" class="navi-link py-4 {{ request()->routeIs('config.constants.job-category') ? 'active' : '' }}">
                            <span class="navi-text font-size-lg">{{ __('Danh mục công việc') }}</span>
                        </a>
                    </div>
                </div>
            @endif
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('admin.timesheet.full_attendance') }}" class="navi-link py-4 {{ request()->routeIs('admin.timesheet.full_attendance') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('employee_full_attendance') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('admin.timesheet.full_allowance') }}" class="navi-link py-4 {{ request()->routeIs('admin.timesheet.full_allowance') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('employee_full_allowance') }}</span>
                    </a>
                </div>
            </div>
            <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                <div class="navi-item mb-2">
                    <a href="{{ route('admin.timesheet.holiday') }}" class="navi-link py-4 {{ request()->routeIs('admin.timesheet.holiday') ? 'active' : '' }}">
                        <span class="navi-text font-size-lg">{{ __('left_holidays') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card card-custom gutter-b">
    <div class="card-body p-3 col-md-12">
        <div class="d-flex align-items-center flex-wrap row">

            <div class="d-flex align-items-center flex-lg-fill my-1 col-xxl-4 col-lg-12">
                    <span class="mr-4 pl-15">
                        <x-icon type="svg" category="General" icon="User" class="{{ Request::routeIs('profile') ? 'svg-icon-primary' : '' }} svg-icon-3x" />
                    </span>
                <a href="{{ route('profile') }}">
                    <div class="align-items-start flex-column">
                        <h6 class="card-label font-weight-bolder {{ Request::routeIs('profile') ? 'text-primary' : 'text-dark' }}">
                            Hồ sơ cá nhân
                        </h6>
                        <span class="text-muted mb-2">Hồ sơ cá nhân của bạn</span>
                    </div>
                </a>
            </div>

            <div class="d-flex align-items-center flex-lg-fill my-1 col-xxl-4 col-lg-12">
                    <span class="mr-4 pl-15">
                        <x-icon type="svg" category="Shopping" icon="Dollar" class="{{ Request::routeIs('') ? 'svg-icon-primary' : '' }} svg-icon-3x" />
                    </span>
                <a href="{{ url('employees/detail_salary_user')}}">
                    <div class="align-items-start flex-column">
                        <h6 class="card-label font-weight-bolder {{ Request::routeIs('') ? 'text-primary' : 'text-dark' }}">
                            Phiếu lương
                        </h6>
                        <span class="text-muted mb-2">Chi tiết phiếu lương tháng</span>
                    </div>
                </a>
            </div>

            <div class="d-flex align-items-center flex-lg-fill my-1 col-xxl-4 col-lg-12">
                    <span class="mr-4 pl-15">
                        <x-icon type="svg" category="Communication" icon="Shield-user" class="{{ Request::routeIs('changePassword') ? 'svg-icon-primary' : '' }} svg-icon-3x" />
                    </span>
                <a href="@if (app('hrm')->isSSO()){{ config('services.sso.url').'/account/password' }}@else{{ route('changePassword') }}@endif" class="">
                    <div class="align-items-start flex-column">
                        <h6 class="card-label font-weight-bolder {{ Request::routeIs('changePassword') ? 'text-primary' : 'text-dark' }}">
                            Đổi mật khẩu
                        </h6>
                        <span class="text-muted mb-2">Thay đổi mật khẩu</span>
                    </div>
                </a>
            </div>

        </div>

    </div>
</div>

<!-- Menu Organization -->
<div id="smartwizard-2" class="smartwizard-example sw-main sw-theme-default">
    <ul class="nav nav-tabs step-anchor">
        <li class="nav-item active "> <a href="{{ route('company.index') }}" data-link-data="company"
                class="mb-3 nav-link hrsale-link"><span class="far fa-building mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Công ty</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thiết lập Công ty
                        </div>
                    </li>
                </ul>
            </a> </li>
        <li class="nav-item @yield(`location_active`)"> <a href="{{ route('location.index') }}"
                data-link-data="location/" class="mb-3 nav-link hrsale-link">
                <span class="fas fa-compass mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Vị trí địa lý</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thiết lập Vị trí địa lý</div>
                    </li>
                </ul>
            </a> </li>
        <li class="nav-item @yield('department_active')"> <a href="{{ route('department.index') }}"
                data-link-data="department/" class="mb-3 nav-link hrsale-link"> <span
                    class="sw-icon fab fa-codepen mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Phòng ban</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thêm mới Phòng ban</div>
                    </li>
                </ul>
            </a> </li>
        <li class="nav-item @yield('designation_active')"> <a href="{{ route('designation.index') }}"
                data-link-data="designation/" class="mb-3 nav-link hrsale-link"> <span
                    class="sw-icon fab fa-dev mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Chỉ định</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thêm mới Chỉ định</div>
                    </li>
                </ul>
            </a> </li>
        <li class="nav-item @yield('announcement_active')"> <a href="{{ route('announcement.index') }}"
                data-link-data="announcement/" class="mb-3 nav-link hrsale-link"> <span
                    class="fas fa-sticky-note mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Thông báo</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thiết lập Thông báo</div>
                    </li>
                </ul>
            </a> </li>
        <li class="nav-item @yield('policy_active')"> <a href="{{ route('policy.index') }}" data-link-data="policy/"
                class="mb-3 nav-link hrsale-link">
                <span class="sw-icon fab fa-yelp mr-3 display-3"></span>
                <ul class="nav flex-column nav-pills">
                    <li class="">
                        <h3>Chính sách công ty</h3>
                    </li>
                    <li class="">
                        <div class="text-muted small">Thiết lập Chính sách</div>
                    </li>
                </ul>
            </a> </li>
    </ul>
</div>

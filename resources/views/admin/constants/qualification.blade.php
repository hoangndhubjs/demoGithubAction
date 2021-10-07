@extends('layout.default')

@section('content')
    <div class="d-flex flex-row">
        @include('admin.constants.nav')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom">
                <div class="card-header card-header-tabs-line">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('config.constants.qualification_edu_level') ? 'active' : ''}}" href="{{ route('config.constants.qualification_edu_level')}}">
                                    <span class="nav-icon"><i class="flaticon-book"></i></span>
                                    <span class="nav-text">Trình độ học vấn</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('config.constants.qualification_language') ? 'active' : ''}}" href="{{ route('config.constants.qualification_language')}}">
                                    <span class="nav-icon"><i class="flaticon2-chat-2"></i></span>
                                    <span class="nav-text">Ngôn ngữ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('config.constants.qualification_skill') ? 'active' : ''}}" href="{{ route('config.constants.qualification_skill')}}">
                                    <span class="nav-icon"><i class="flaticon2-drop"></i></span>
                                    <span class="nav-text">Kỹ năng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @yield('body')
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ mix('js/sidebar_mobile.js') }}"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    @yield('js')
@endsection 



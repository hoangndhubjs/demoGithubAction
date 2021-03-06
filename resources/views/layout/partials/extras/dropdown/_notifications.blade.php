{{-- Header --}}
@if (config('layout.extras.notifications.dropdown.style') == 'light')
    <div class="d-flex flex-column pt-12 bg-dark-o-5 rounded-top">
        {{-- Title --}}
        <h4 class="d-flex flex-center">
            <span class="text-dark">User Notifications</span>
            <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">23 new</span>
        </h4>

        {{-- Tabs --}}
        <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-primary mt-3 px-8" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications"  >Alerts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events"  >Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs"  >Logs</a>
            </li>
        </ul>
    </div>
@else
    <div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url('{{ asset('media/misc/bg-1.jpg') }}')">
        {{-- Title --}}
        <h4 class="d-flex flex-center rounded-top">
            <span class="text-white">{{__('notification me')}}</span>
            <a href="{{route('notification.list')}}"><span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ \App\Classes\Notification::totalNotification() }} {{__('new')}}</span></a>
        </h4>

        {{-- Tabs --}}
        <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8" role="tablist">
            {{--<li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications"  >Alerts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events"  >Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs"  >Logs</a>
            </li>--}}
        </ul>
    </div>
@endif

{{-- Content --}}
<div class="tab-content">
    {{-- Tabpane --}}
    <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
        {{-- Scroll --}}
        <?php $notifications = \App\Classes\Notification::listNotifications(); ?>
        @if(count($notifications) > 0)
            <div class="scroll pr-7 mr-n7" data-scroll="true" data-mobile-height="200">
                {{-- Item --}}
                @foreach($notifications as $notification)
                <div class="d-flex align-items-center mb-2">
                    {{-- Symbol --}}
                    <div class="symbol symbol-40 symbol-light-info mr-5">
                        <span class="symbol-label">
                            {{ Metronic::getSVG("media/svg/icons/Communication/Shield-user.svg", "svg-icon-lg  svg-icon-info") }}
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="d-flex flex-column font-weight-bold">
                        @if($notification->module_name == 'meetings')
                            <a href="#" data-toggle='modal' data-target='#meeting_notification' data-id='{{$notification->module_id}}'
                               class="@if($notification->is_notify == 1){{'text-dark'}} @else {{'text-muted'}} @endif text-hover-primary mb-1 font-size-lg">{{$notification->title}}</a>
                        @else
                            <a href="@if($notification->module_name == 'leave'){{route('leaves.detail', ['id'=>$notification->module_id])}}
                                    @elseif($notification->module_name == 'request_ot'){{route('overtime_request.detail', ['id'=>$notification->module_id])}}
                                    @elseif($notification->module_name == 'compensations'){{route('compensations.detail', ['id'=>$notification->module_id])}}
                                    @else {{route('meeting.detail', ['id'=>$notification->module_id])}} @endif"
                               class="@if($notification->is_notify == 1){{'text-dark'}} @else {{'text-muted'}} @endif text-hover-primary mb-1 font-size-lg">{{$notification->title}}</a>
                        {{--<span class="text-muted">{{$notification->title}}</span>--}}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{--@if(count($notifications) == 5)--}}
                <div class="d-flex flex-center pt-7">
                    <a href="{{route('notification.list')}}" class="btn btn-light-primary font-weight-bold text-center">{{__('all_notification')}}</a>
                </div>
            {{--@endif--}}
        @else
            <div class="d-flex flex-center text-center text-muted min-h-200px">
                All caught up!
                <br/>
                No new notifications.
            </div>
        @endif
    </div>

    {{-- Tabpane --}}
    {{--<div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
        --}}{{-- Nav --}}{{--
        <div class="navi navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">
            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-line-chart text-success"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New report has been received
                        </div>
                        <div class="text-muted">
                            23 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-paper-plane text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            Finance report has been generated
                        </div>
                        <div class="text-muted">
                            25 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-user flaticon2-line- text-success"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New order has been received
                        </div>
                        <div class="text-muted">
                            2 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-pin text-primary"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New customer is registered
                        </div>
                        <div class="text-muted">
                            3 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-sms text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            Application has been approved
                        </div>
                        <div class="text-muted">
                            3 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-pie-chart-3 text-warning"></i>
                    </div>
                    <div class="navinavinavi-text">
                        <div class="font-weight-bold">
                            New file has been uploaded
                        </div>
                        <div class="text-muted">
                            5 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon-pie-chart-1 text-info"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New user feedback received
                        </div>
                        <div class="text-muted">
                            8 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-settings text-success"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            System reboot has been successfully completed
                        </div>
                        <div class="text-muted">
                            12 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon-safe-shield-protection text-primary"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New order has been placed
                        </div>
                        <div class="text-muted">
                            15 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-notification text-primary"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            Company meeting canceled
                        </div>
                        <div class="text-muted">
                            19 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-fax text-success"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New report has been received
                        </div>
                        <div class="text-muted">
                            23 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon-download-1 text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            Finance report has been generated
                        </div>
                        <div class="text-muted">
                            25 hrs ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon-security text-warning"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New customer comment recieved
                        </div>
                        <div class="text-muted">
                            2 days ago
                        </div>
                    </div>
                </div>
            </a>

            --}}{{-- Item --}}{{--
            <a href="#" class="navi-item">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="flaticon2-analytics-1 text-success"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            New customer is registered
                        </div>
                        <div class="text-muted">
                            3 days ago
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>--}}

    {{-- Tabpane --}}
    {{--<div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
        --}}{{-- Nav --}}{{--
        <div class="d-flex flex-center text-center text-muted min-h-200px">
            All caught up!
            <br/>
            No new notifications.
        </div>
    </div>--}}
</div>

@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-xl-12 mb-5">
        <div class="card card-custom card-stretch" id="attendance_monthly_summary">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_late') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_late">0/300</span>
                                    <span>{{ __('minutes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_full_attendance') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_full_attendance">0/0</span>
                                    <span>{{ __('attendance') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_overtime') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_overtime">0</span>
                                    <span>{{ __('attendance') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_phep_ton') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_phep_ton">0</span>
                                    <span>{{ __('day') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_leave') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_early">0</span>
                                    <span>{{ __('minutes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                        <div class="card card-custom card-stretch-fourth attendance-full mb-5">
                            <div class="card-body">
                                <p class="text-title">{{ __('total_day_leave_with_request') }}</p>
                                <div class="text-summary">
                                    <span class="text-number" id="total_leave_requested">0</span>
                                    <span>{{ __('day') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 mb-5">
        <div class="card card-custom card-stretch">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __("attendance_symbol_guide") }}
                        <span class="d-block text-muted pt-2 font-size-sm"></span>
                    </h3>
                </div>
            </div>
            <div class="card-body" style="flex: none;">
                <div class="attendance-full font-weight-bold p-3 mb-3 rounded">{{ __("attendance_full") }}: X1 - X2</div>
                <div class="attendance-half font-weight-bold p-3 mb-3 rounded">{{ __("attendance_half") }}: N1 - N2</div>
                <div class="attendance-holiday font-weight-bold p-3 mb-3 rounded">{{ __("attendance_holiday") }}: L1 - L2</div>
                <div class="attendance-leave-requested font-weight-bold p-3 mb-3 rounded">{{ __("attendance_leave_requested") }}: P</div>
                <div class="attendance-leave font-weight-bold p-3 mb-3 rounded">{{ __("attendance_leave") }}: K</div>
                <div class="attendance-sunday font-weight-bold p-3 mb-3 rounded">{{ __("attendance_sunday") }}</div>
                <div class="attendance-go-on font-weight-bold p-3 mb-3 rounded">{{ __("attendance_go_on") }}</div>
                <div class="attendance-online font-weight-bold p-3 mb-3 rounded">Online: O1 - O2</div>
            </div>

            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __("group_employee") }}
                        <span class="d-block text-muted pt-2 font-size-sm"></span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div class="font-weight-bold mb-3 rounded">{{ __("emplployee_official") }}: 1</div>
                <div class="font-weight-bold mb-3 rounded">{{ __("emplployee_trial_work") }}: 2</div>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div class="card card-custom card-stretch">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __("attendance_monthly_list") }}
                        <span class="d-block text-muted pt-2 font-size-sm"></span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div id="attendance_month_calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link href="{{ mix('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script type="text/javascript">
        window._attendance_monthly_url = '{{ route("timesheet.ajax.attendance_monthly.list") }}';
        window._attendance_monthly_summary_url = '{{ route('timesheet.ajax.attendance_monthly.summary') }}';
    </script>
    <script src="{{ mix('js/fullcalendar.bundle.js') }}"></script>
	<script src="{{ mix('js/attendances/attendance_month.js') }}"></script>
@endsection

@extends('layout.default')

@section('styles')
    <style type="text/css">

        .fc-unthemed .fc-event .fc-content:before, .fc-unthemed .fc-event-dot .fc-content:before {
            content:unset
        }
        .fc-unthemed .fc-event .fc-content, .fc-unthemed .fc-event-dot .fc-content {
            padding: 3px 5px 3px 5px;
        }
        .fc-unthemed .fc-event, .fc-unthemed .fc-event-dot{
            border: 0;
        }
        .fc-scroller {
            overflow-y: hidden !important;
        }

        .content-center {
            text-align: center;
        }
        .fc-timeline .fc-body .fc-scroller::-webkit-scrollbar {
            height: 5px;
        }
        /*.fc-timeline .fc-body .fc-scroller::-webkit-scrollbar-track {*/
        /*    background: #c5c1c1;*/
        /*}*/

        /* Handle */
        .fc-timeline .fc-body .fc-scroller::-webkit-scrollbar-thumb {
            background: #B5B5C3;
            border-radius: 5px;
        }
    </style>

    <link rel="stylesheet" href="{{ mix('fullcalendar_v1/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ mix('fullcalendar_v1/css/scheduler.min.css') }}">
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('attendance_monthly_list_by_employee_ot')}}</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-7">
                <form id="request_overtime" action="{{ route('admin.timesheet.overtime_month') }}">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Phòng ban') }}</label>
                                        <select class="form-control selectSearch resetSelect" name="department_id" id="department">
                                            <option value="">Tất cả</option>
                                            @foreach($allDeparment as $val)
                                                <option value="{{ $val->department_id }}" {{ $val->department_id == $department_id ? 'selected' : '' }}>{{ $val->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Tên nhân viên') }}</label>
                                        <input type="text" autocomplete="off" name="employee_name" class=" form-control" value="{{ $employee_name }}" placeholder="Nhập tên hoặc mã nhân viên">
                                    </div>
                                </div>

                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Month') }}</label>
                                        <input class="form-control datepicker-month"
                                               placeholder="{{__('Month')}}"
                                               name="month" type="text" value="{{ $defaultDate ? date('m-Y', strtotime($defaultDate)) : date('m-Y') }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                                    <button type="submit" class="btn btn-primary font-weight-bold mr-3">{{ __('xin_search') }}
                                        <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                                    </button>
                                    <button type="button"  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div id="calendar">

                <span class="text-danger">TSG: Tổng số giờ | TSG (TW): Tổng số giờ trong thời gian thử việc</span>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script src="{{ mix('fullcalendar_v1/js/fullcalendar.js') }}"></script>
    <script src="{{ mix('fullcalendar_v1/js/scheduler.min.js') }}"></script>
    <script src="{{ mix('fullcalendar_v1/js/vi.js') }}"></script>

    <script type="text/javascript">
        let user_request_time = {!! @json_encode($total_hour_count) !!};
        let total_hour = {!! @json_encode($total_hour) !!};
        let getDate = @json($defaultDate);
        document.addEventListener('DOMContentLoaded', function() {
            $('#calendar').fullCalendar({
                lang: 'vi',
                defaultView: 'timelineMonth',
                eventDisplay: 'block',
                defaultDate: getDate,
                resourceColumns: [
                    {
                        field: 'employee_name',
                        labelText: 'Tên nhân viên'
                    },
                    {
                        field: 'employee_id',
                        labelText: 'Mã nhân viên'
                    },
                    {
                        field: 'department',
                        labelText: 'Bộ phận'
                    },
                    {
                        field: 'total_hour_user',
                        labelText: 'TSG'
                    },
                    {
                        field: 'total_hour_tw_user',
                        labelText: 'TSG (TW)'
                    }
                ],
                resources: user_request_time,
                events: total_hour,
            });
        });

        $('.datepicker-month').datepicker({
            todayHighlight: true,
            format: 'mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi',
            zIndexOffset:100,
            viewMode: "months",
            minViewMode: "months",
            endDate: new Date()
        });

        $('#resetForm').click(function(){
            let reset =  $('#request_overtime').attr('action');
            window.location.href = reset;
        });

        $(document).ready(function () {
            $('#calendar .fc-right').hide();
        })

    </script>

@endsection

@extends('layout.default')

@section('styles')
    <style type="text/css">

        .fc-unthemed .fc-event .fc-content:before, .fc-unthemed .fc-event-dot .fc-content:before {
            content:unset
        }
        .fc-unthemed .fc-event .fc-content, .fc-unthemed .fc-event-dot .fc-content {
            padding: 3px 5px 3px 5px;
        }
        .K {
            background-color: #FDE8E8 !important;
        }
        .K .fc-content .fc-title {
            color: #EC0000 !important;
            font-weight: 500;
        }

        .O1, .O2 {
            background-color: #F4E1EF !important;
        }
        .O1 .fc-content .fc-title, .O2 .fc-content .fc-title {
            color: #c64aa8 !important;
            font-weight: 500;
        }
        .L1, .L2 {
            background-color: #C9F7F5 !important;
        }
        .L1 .fc-content .fc-title, .L2 .fc-content .fc-title {
            color: #4BC8C3 !important;
            font-weight: 500;
        }

        .X1, .X2, .X1\/C1, .X2\/C2 {
            background-color: #aee0fa !important;
        }
        .X1 .fc-content .fc-title, .X2 .fc-content .fc-title,
        .X1\/C1 .fc-content .fc-title, .X2\/C2 .fc-content .fc-title {
            color: #1689FF !important;
            font-weight: 500;
        }

        .N1, .N2 {
            background-color: #E6FFE2 !important;;
        }
        .N1 .fc-content .fc-title, .N2 .fc-content .fc-title,
        .N1\/C1 .fc-content .fc-title, .N2\/C2 .fc-content .fc-title{
            color: #0EB10A !important;
            font-weight: 500;
        }

        .C1, .C2 {
            background-color: #FEE7DD !important;;
        }
        .C1 .fc-content .fc-title, .C2 .fc-content .fc-title {
            color: #EF6327 !important;
            font-weight: 500;
        }

        .P, .P_2 {
            background-color: #34A853 !important;
        }


        .CN {
            background-color: #e1d1ff !important;
        }
        .CN .fc-content .fc-title {
            color: #9662FF !important;
            font-weight: 500;
        }

        .P {
            background-color: #FFF7DB !important;
        }
        .P .fc-content .fc-title {
            color: #F4B000 !important;
            font-weight: 500;
        }


        .fc-scroller {
            overflow-y: hidden !important;
        }

        .content-center {
            text-align: center;
        }
    </style>

    <link rel="stylesheet" href="{{ mix('fullcalendar_v1/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ mix('fullcalendar_v1/css/scheduler.min.css') }}">
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 p-0 m-0">
                    <div class="card-header flex-wrap border-0 pb-0" style="min-height: 40px">
                        <div class="card-title">
                            <h3 class="card-label text-uppercase"> Nhóm nhân viên</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="row">Nhân viên chính thức</th>
                                <td class="content-center">1</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="col">Nhân viên thử việc</th>
                                <td class="content-center">2</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-9 p-0 m-0">
                    <div class="card-header flex-wrap border-0 pb-0" style="min-height: 40px">
                        <div class="card-title">
                            <h3 class="card-label text-uppercase"> Bảng ký hiệu các loại chấm công</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="content-center" scope="col">Loại</th>
                                <th class="content-center" scope="col">Đủ công</th>
                                <th class="content-center" scope="col">Nửa công</th>
                                <th class="content-center" scope="col">Lễ tết</th>
                                <th class="content-center" scope="col">{{ __("attendance_leave") }}</th>
                                <th class="content-center" scope="col">{{ __("attendance_leave_requested") }}</th>
                                <th class="content-center" scope="col">Chủ nhật</th>
                                <th class="content-center" scope="col">Công tác</th>
                                <th class="content-center" scope="col">Online</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="content-center" scope="row">Ký hiệu</th>
                                <td class="content-center" style="background-color: #aee0fa; color: #1689FF">X1 - X2</td>
                                <td class="content-center" style="background-color: #E6FFE2; color: #0EB10A">N1 - N2</td>
                                <td class="content-center" style="background-color: #C9F7F5; color: #4BC8C3">L1 - L2</td>
                                <td class="content-center" style="background-color: #FDE8E8; color: #EC0000">K</td>
                                <td class="content-center" style="background-color: #FFF7DB; color: #F4B000">P</td>
                                <td class="content-center" style="background-color: #EEE5FF; color: #9662FF">CN</td>
                                <td class="content-center" style="background-color: #FEE7DD; color: #EF6327">C1 - C2</td>
                                <td class="content-center" style="background-color: #F4E1EF; color: #c64aa8">O1 - O2</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('attendance_monthly_list_by_employee')}}</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-7">
                <form id="formMonthlyTimeSheet" method="GET" action="{{ route('admin.timesheet.monthlyTimesheet') }}">
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
                                        <select class="form-control selectSearch resetSelect" id="kt_select2_empolyee_deparment" name="user_id">
                                            <option value="">Tất cả</option>
                                            @foreach($employeeIsActive as $val)
                                                <option value="{{ $val->user_id }}" {{ $val->user_id == $user_id ? 'selected' : '' }}>{{ $val->first_name. ' ' .$val->last_name }}</option>
                                            @endforeach
                                        </select>
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
                                    <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            
            <div id="calendar"></div>
        </div>
    </div>
@endsection
@section('scripts')

<script src="{{ mix('fullcalendar_v1/js/fullcalendar.js') }}"></script>
<script src="{{ mix('fullcalendar_v1/js/scheduler.min.js') }}"></script>
<script src="{{ mix('fullcalendar_v1/js/vi.js') }}"></script>

<script type="text/javascript">

    let resources = {!! @json_encode($resources) !!};
    let events = {!! @json_encode($events) !!};
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
                    labelText: 'Họ tên'
                },
                {
                    field: 'total_attendance',
                    labelText: 'Tổng công'
                },
                {
                    field: 'come_late',
                    labelText: 'Đi muộn'
                },
                {
                    field: 'early_leave',
                    labelText: 'Về sớm'
                },
                {
                    field: 'total_allowances',
                    labelText: 'Công phụ cấp'
                },
                {
                    field: 'phep_ton',
                    labelText: 'Phép tồn'
                }
            ],
            resources: resources,
            events: events,
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
        $('#formMonthlyTimeSheet').trigger("reset");
        $('#formMonthlyTimeSheet select').trigger("change");
    });

    $(document).ready(function () {
        $('#calendar .fc-right').hide();

        $('#department').change(function(){
            let id = $(this).val();
            var html = '';
            $.ajax({
                url: '{{ route('admin.timesheet.get_department') }}',
                data: {
                    id: id
                },
                type: "POST",
                success: function(result_data){
                    if(result_data){
                        html += '<option value="">Tất cả</option>';
                        $.each(result_data, function (key,item){
                            html += '<option value="'+item['user_id']+'">';
                            html += item['first_name'] + ' ' + item['last_name'];
                            html += '</option>';
                        });

                        $("#kt_select2_empolyee_deparment").html(html);
                    }
                }
            });
        });
    })

</script>

@endsection

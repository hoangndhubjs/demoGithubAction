@extends('layout.default')
@section('content')
    <style>
        .dots_ps {
            position: relative;
        }
        .dots_as {
            position: absolute;
            width: 5px;
            height: 5px;
            content: 'a';
            left: -10px;
            top: 50%;
            transform: translate(-50%, -50%);
            border-radius: 100%;
        }
        .close_compens {
            position: absolute;
            top: 30%;
            right: 3%;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('attendance_daily_list_by_employee')}}</span></h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <form action="" id="payroll_list_search_form">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Nhập tên nhân viên') }}</label>
                                        <select class="form-control select2 is-valid" id="department_select" name="employee_name">
                                            <option value="0" selected disabled>{{ __('Phòng ban') }}</option>
                                            @foreach($listEmployee as $key => $employee)
                                                <option value="{{ $key }}" @if($key == $id){{'selected'}}@endif >{{ $employee }}</option>
                                            @endforeach
                                        </select>
                                        {{--<input type="" autocomplete="off"  name="employee_name" class=" form-control" value="{{$id}}" placeholder="{{ __('Nhập tên nhân viên') }}">--}}
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('day') }}</label>
                                        <input class="form-control datepicker-month"
                                               placeholder="{{__('day')}}"
                                               name="date" type="text" value="{{$date}}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
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
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="attendance_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.datepicker-month').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi',
            zIndexOffset:100,
        });

        window._tables = {
            'attendance_list': {
                'url': '{{ route('admin.timesheet.timesheet_by_employee')}}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return '<span style="font-weight: bold; cursor: pointer;">'+row.employee.first_name +' '+ row.employee.last_name+'</span>';
                        }
                    },
                    {
                        field: 'attendance',
                        title: '{{ __('attendance') }}',
                        textAlign: 'center',
                        template: function (row) {
                            let html = row.attendance_date?'<span>' + moment(row.attendance_date).format('DD-MM-YYYY') + '</span>':'';
                            return html;
                        }
                    },
                    {
                        field: 'checkin',
                        title: '{{ __('checkin') }}',
                        textAlign: 'center',
                        template: function (row) {
                            let html = row.clock_in?'<span>' + moment(row.clock_in).format('HH:mm') + '</span>':'';
                            return html;
                        }
                    },
                    {
                        field: 'checkout',
                        title: '{{ __('checkout') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let html = row.clock_out?'<span>' + moment(row.clock_out).format('HH:mm') + '</span>':'';
                            return html;
                        }
                    },
                ],
                'search_from': '#payroll_list_search_form'
            }
        };

        $('#resetForm').click(function(){
            $('#payroll_list_search_form').trigger("reset");
            $('#payroll_list_search_form select').trigger("change");
        });

    </script>
    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection

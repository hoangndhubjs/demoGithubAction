@extends('layout.default')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">{{ __("attendance_daily_list") }}
                <span class="d-block text-muted pt-2 font-size-sm"></span>
            </h3>
        </div>
        <div class="card-toolbar">
            <ul class="nav nav-bold nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#">{{ __("in_day") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('timesheet.attendance_monthly') }}">{{ __("in_month") }}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-lg-12 col-xl-12">
                    <form id="attendance_search_form">
                        <div class="row align-items-center">
                            <div class="col-md-3 my-2 my-md-0">
                                <x-daterangepicker id="attendance" name="attendance" max-date="{{ date('Y-m-d')}}"/>
                            </div>
                            <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">{{ __('search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="attendance_list"></div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
    window._tables = {
        'attendance_list': {
            'url': '{{ route('timesheet.ajax.attendance.list') }}',
            'columns': [
                {
                    field: 'employee_id',
                    title: '{{ __('employee_id') }}',
                    sortable: false,
                    template: function (row) {
                        return row.employee_id;
                    }
                },
                {
                    field: 'employee_name',
                    title: '{{ __('employee_name') }}',
                    sortable: false,
                    template: function (row) {
                        return row.employee.first_name+' '+row.employee.last_name;
                    },
                },
                {
                    field: 'attendance_date',
                    title: '{{ __('date') }}',
                    width: 100,
                    autoHide: false,
                    type: 'date',
                    format: 'YYYY-MM-DD',
                    template: function (row) {
                        return _userDate(row.attendance_date);
                    }
                },
                {
                    field: 'clock_in',
                    title: '{{ __('checkin') }}',
                    width: 100,
                    autoHide: false,
                    sortable: false,
                    type: 'date',
                    format: 'YYYY-MM-DD H:m:s',
                    template: function (row) {
                        return _userTime(row.clock_in);
                    }
                },
                {
                    field: 'clock_out',
                    title: '{{ __('checkout') }}',
                    width: 100,
                    autoHide: false,
                    sortable: false,
                    type: 'date',
                    format: 'YYYY-MM-DD H:m:s',
                    template: function (row) {
                        return row.clock_out ? _userTime(row.clock_out) : null;
                    }
                }
            ],
            'search_from': '#attendance_search_form'
        }
    }
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

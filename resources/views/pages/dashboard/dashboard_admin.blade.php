@extends('layout.default')
@section('styles')
    <link rel="stylesheet" href="{{ mix('/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-xxl-12">
            <div class="card card-custom px-10 py-5 mb-5">
                <div class="info-covid-19">
                    <h1 class="text-dark mb-0 text-center font-size-h1-xl pb-2">Thông tin tổng hợp Covid-19</span> </h1>
                </div>
                <iframe src="https://emag.thanhnien.vn/widget/toan-canh-covid-21" frameborder="0" style="height: 190px; width: 100%; margin-left:0;"></iframe>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xxl-9">
            <div class="card card-custom px-10 py-5">
                <!--begin::Body-->
                <div class="card-body rounded p-0 d-flex justify-content-between align-items-center">
                    <div class="w-sm-500px w-lg-450px w-xl-600px w-xxl-700px">
                        <h1 class="text-dark mb-0">{{ __('xin_title_wcb') }}, <a href="{{ route('profile')}}" class="text-primary">{{ Auth::user()->getFullName()}}</a> </h1>
                        <div class="font-size-h6 mt-5 text-muted" id="quote"></div>
                    </div>
                    <div class="d-none d-sm-block d-lg-block text-right w-350px ml-5">
                        <img src="{{ asset('media/covid-19.png') }}" alt="Cùng nhau chống lại kẻ hủy diệt" class="img-fluid">
                    </div>
                </div>
                <!--end::Body-->
            </div>

            <div class="row">
                <div class="col-md-4 pt-5">
                    <div class="bg-light-white w-100 px-6 py-5 rounded-xl">
                        <x-icon type="svg" category="General" icon="Clipboard" class="svg-icon-primary svg-icon-3x" />
                        <div class="d-flex flex-wrap justify-content-between mt-5">
                            <div class="w-100">
                                <p class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $working ? $working : 0 }}</p>
                                <p class="text-muted font-weight-bold m-0">{{ __('number_of_employees_present_today') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 pt-5">
                    <div class="bg-light-white w-100 px-6 py-5 rounded-xl">
                        <x-icon type="svg" category="Electric" icon="Shutdown" class="svg-icon-primary svg-icon-3x" />
                        <div class="d-flex flex-wrap justify-content-between mt-5">
                            <div class="w-100">
                                <p class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $leave ? $leave : 0 }}</p>
                                <p class="text-muted font-weight-bold m-0">{{ __('number_of_employees_are_absent') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 pt-5 pb-lg-0 pb-5 mb-sm-5">
                    <div class="bg-light-white w-100 px-6 py-5 rounded-xl">
                        <x-icon type="svg" category="General" icon="User" class="svg-icon-primary svg-icon-3x" />
                        <div class="d-flex flex-wrap justify-content-between mt-5">
                            <div class="w-100">
                                <p class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $totalEmployee ? $totalEmployee : 70 }}</p>
                                <p class="text-muted font-weight-bold m-0">{{ __('total_current_employees') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xxl-3 pt-lg-0 pt-sm-0 pt-0">
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('payroll_monthly_salary') }}</h3>
                    </div>
                </div>

                <div id="chart"></div>
            </div>
        </div>
    </div>

    <div class="row pt-sm-5 pt-md-5 pt-lg-5 pt-xl-0 pt-5">
        <div class="col-lg-6">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('payroll_by_department') }}</h3>
                    </div>
                </div>

                <div id="chart_3" style="min-height: 300px"></div>
            </div>

            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('number_of_employees_by_department') }}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div id="chartdiv" style="width: 100%; height: 330px"></div>
                    </div>

                    <div class="col-lg-6">
                        <div id="legenddiv" style="width: 100%; height: 330px; padding: 10px 0 10px 0;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_calendar"></div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('styles')
    <style>
        #kt_calendar .fc-list-table {
            width: 100%;
        }

        .fc-right .fc-see_details-button {
            color: #FFFFFF;
            background-color: #3699FF;
            border-color: #3699FF;
        }

    </style>
@endsection

@section('scripts')
    <script>
        var nameByMonth = @json($payrollByMonth[0]);
        var payrollByMonth = @json($payrollByMonth[1]);
        var this_month = @json($payrollByDepartment['this_month']);
        var lastMonth = @json($payrollByDepartment['last_month']);
        var nameCategory = @json($payrollByDepartment['name']);
        var employeeDepartment = @json($employeeDepartment[0]);
        var totalEmployeeDepartment = {{ $totalEmployeeDepartment }};
    </script>

    <script src="{{ mix('js/dashboard/dashboardAdmin.js') }}"></script>
    <script src="{{ mix('js/fullcalendar.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/amCharts/core.js') }}"></script>
    <script src="{{ mix('js/amCharts/charts.js') }}"></script>
    <script src="{{ mix('js/amCharts/animated.js') }}"></script>
@endsection

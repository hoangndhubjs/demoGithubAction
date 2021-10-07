@extends('layout.default')

@section('styles')

@endsection
@php
    $di_muon = $totalEmployeeLate['di_muon'] / $totalEmployee * 100;
    $ve_som = $totalEmployeeLate['ve_som'] / $totalEmployee * 100;
@endphp
@section('content')

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 p-2 m-0">
                <div class="mx-0 card px-10 py-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bolder">Tổng quan thời gian làm việc <br>
                                <small class="text-muted">Số nhân viên đã hết thời gian đi muộn về sớm</small>
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mt-5">
                                    <div class="@if(isset($di_muon) && $di_muon >= 100) bg-light-danger @elseif (isset($di_muon) && $di_muon >= 50) bg-light-warning @else bg-light-primary @endif w-100 px-6 py-5 rounded-xl">
                                        <x-icon type="svg" category="Home" icon="Timer" class="svg-icon-primary svg-icon-3x"/>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <div class="w-100">
                                                <p class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $totalEmployeeLate ? $totalEmployeeLate['di_muon'] : 0 }}/{{ $totalEmployee ?? 70 }}</p>
                                                <small class="text-dark-65 font-weight-bold m-0">Số nhân viên đã hết phút đi muộn</small>
                                                <div class="progress mt-5 progress-xs">
                                                    <div class="progress-bar @if(isset($di_muon) && $di_muon >= 100) bg-danger @elseif (isset($di_muon) && $di_muon >= 50) bg-warning @else bg-primary @endif" role="progressbar"
                                                         style="width: {{ $di_muon ? $di_muon : 0 }}%" aria-valuenow="50" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <div class="@if(isset($ve_som) && $ve_som >= 100) bg-light-danger @elseif (isset($ve_som) && $ve_som >= 50) bg-light-warning @else bg-light-primary @endif px-6 py-5 rounded-xl">
                                        <x-icon type="svg" category="Home" icon="Timer" class="svg-icon-primary svg-icon-3x"/>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <div class="w-100">
                                                <p class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $totalEmployeeLate ? $totalEmployeeLate['ve_som'] : 0 }}/{{ $totalEmployee ?? 70 }}</p>
                                                <small class="text-dark-65 font-weight-bold m-0">Số nhân viên đã hết phút về sớm</small>

                                                <div class="progress mt-5 progress-xs">
                                                    <div class="progress-bar @if(isset($ve_som) && $ve_som >= 100) bg-danger @elseif (isset($ve_som) && $ve_som >= 50) bg-warning @else bg-primary @endif" role="progressbar" style="width: {{ $ve_som ? $ve_som : 0 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 p-2 m-0">
                <div class="mx-0 card px-10 py-8">
                    <div class="card-body d-flex flex-column p-0">

                        <div class="col-md-12 m-0 p-0">

                            <div class="text-right position-absolute right-0">
                                <a href="{{ route('leaves.admin.list') }}" class="btn btn-sm btn-primary font-weight-bold" target="_blank">Duyệt đơn</a>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <h6 class="font-weight-bolder">Số đơn nghỉ việc trong tháng<br>
                                        <small class="text-muted">Tháng {{ date('m/Y') }}</small>
                                    </h6>

                                    <div class="row">
                                        <div class="col-6 d-flex align-items-center pt-8">
                                            <b class="rounded text-center font-weight-bolder w-50px py-2" style="background-color: #0EB10A; color: white; font-size: 16px">
                                                {{ $leaveApplicationByMonth ? $leaveApplicationByMonth['status']['approved'] : 0 }}
                                            </b>
                                            <b class="ml-2">{{__('approved') }}</b>
                                        </div>
                                        <div class="col-6 d-flex align-items-center pt-8">
                                            <b class="rounded text-center font-weight-bolder w-50px py-2" style="background-color: #F4B000; color: white; font-size: 16px">
                                                {{ $leaveApplicationByMonth ? $leaveApplicationByMonth['status']['waiting'] : 0 }}
                                            </b>
                                            <b class="ml-2">{{__('waiting') }}</b>
                                        </div>

                                        <div class="col-6 d-flex align-items-center pt-7">
                                            <b class="rounded text-center font-weight-bolder w-50px py-2" style="background-color: #EC0000; color: white; font-size: 16px">
                                                {{ $leaveApplicationByMonth ? $leaveApplicationByMonth['status']['reject'] : 0 }}
                                            </b>
                                            <b class="ml-2">{{__('not_approve') }}</b>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <div class="flex-grow-1 mb-5">
                                        <div id="kt_mixed_widget_14_chart" style="height: 176px; padding: 0; margin: 0"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 p-2 m-0">
                <div class="mx-0 card px-10 py-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bolder">Danh sách nhân viên đi muộn nhiều nhất <br>
                                <small class="text-muted">Tháng {{ date('m/Y') }}</small>
                            </h6>

                            <div class="card-body pt-2 pb-0 mt-n3 ml-0 mr-0 pl-0 pr-0">
                                <div class="tab-content mt-5">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                            <tr>
                                                <th class="p-0 w-20px"></th>
                                                <th class="p-0 w-30px"></th>
                                                <th class="p-0 min-w-100px"></th>
                                                <th class="p-0 min-w-200px"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @php $stt = 1; @endphp
                                                @foreach($employeeLateMost as $key => $val)
                                                    @php
                                                        $employee = \App\Models\Employee::where('user_id', $val['id'])->first();
                                                        $percent_come_late = $val['come_late'] / 300 * 100;
                                                        if(isset($percent_come_late) && $percent_come_late >= 100) $background = 'bg-danger'; elseif (isset($percent_come_late) && $percent_come_late >= 50) $background = 'bg-warning'; else $background = 'bg-primary';
                                                    @endphp
                                                    <tr>
                                                        <td class="pr-8 font-weight-bold">{{ $stt++ }}</td>
                                                        <th class="pl-0 py-5">
                                                            <div class="symbol symbol-50 symbol-light mr-2">
                                                            <span class="symbol-label">
                                                                <img src="{{ $employee->profile_picture }}" class="h-50 align-self-center" alt="{{ $val['employee_name'] }}">
                                                            </span>
                                                            </div>
                                                        </th>
                                                        <td class="py-6 pl-0">
                                                            <p class="text-dark font-weight-bolder mb-1 font-size-lg">{{ $val['employee_name'] }}</p>
                                                            <small class="text-muted">{{ $val['company_name'] }} - {{ $val['department_name'] }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column w-100 mr-2">
                                                                <div class="mb-2 w-100 text-right">
                                                                    <span class="text-muted font-size-sm font-weight-bold">{{ $val['come_late'] }}/300 phút</span>
                                                                </div>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar {{ $background }}" role="progressbar" style="width: {{ $percent_come_late }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @if($key == 4) @break @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 p-2 m-0">
                <div class="mx-0 card px-10 py-8">
                    <div class="card-body d-flex flex-column p-0">

                        <div class="col-md-12 m-0 p-0">
                            <h6 class="font-weight-bolder">Danh sách nhân viên nghỉ làm nhiều nhất <br>
                                <small class="text-muted">Tháng {{ date('m/Y') }}</small>
                            </h6>

                            <div class="card-body pt-2 pb-0 mt-n3 ml-0 mr-0 pl-0 pr-0">
                                <div class="tab-content mt-5">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 w-20px"></th>
                                                    <th class="p-0 w-30px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                    <th class="p-0 min-w-200px"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            @php $s = 1; @endphp
                                                @foreach($totalQuitWorkMost as $key => $val)
                                                    @php
                                                        $employee = \App\Models\Employee::where('user_id', $val['id'])->first();
                                                        $percent_quit_most = $val['total_day_off'] == "" ? 0 : $val['total_day_off'] / 4 * 100;
                                                        if(isset($percent_quit_most) && $percent_quit_most > 100) $background = 'bg-danger'; elseif (isset($percent_quit_most) && $percent_quit_most > 25) $background = 'bg-warning'; else $background = 'bg-primary';
                                                    @endphp
                                                    <tr>
                                                        <td class="pr-8 font-weight-bold">{{ $s++ }}</td>
                                                        <th class="pl-0 py-5">
                                                            <div class="symbol symbol-50 symbol-light mr-2">
                                                            <span class="symbol-label">
                                                                <img src="{{ $employee->profile_picture }}" class="h-50 align-self-center" alt="{{ $val['employee_name'] }}">
                                                            </span>
                                                            </div>
                                                        </th>
                                                        <td class="py-6 pl-0">
                                                            <p class="text-dark font-weight-bolder mb-1 font-size-lg">{{ $val['employee_name'] }}</p>
                                                            <small class="text-muted">{{ $val['company_name'] }} - {{ $val['department_name'] }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column w-100 mr-2">
                                                                <div class="mb-2 w-100 text-right">
                                                                    <span class="text-muted font-size-sm font-weight-bold">{{ $val['total_day_off'] }} ngày</span>
                                                                </div>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar {{$background}}" role="progressbar" style="width: {{ $percent_quit_most }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @if($key == 4) @break @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/amCharts/core.js') }}"></script>
    <script src="{{ mix('js/amCharts/charts.js') }}"></script>
    <script src="{{ mix('js/amCharts/animated.js') }}"></script>
    <script>
        let totalLeaveApplication = {!! $leaveApplicationByMonth['totalLeaveApplication'] !!};
        let approved = {!! $leaveApplicationByMonth['status']['approved'] !!};
        let waiting = {!! $leaveApplicationByMonth['status']['waiting'] !!};
        let reject = {!! $leaveApplicationByMonth['status']['reject'] !!};

        var _initMixedWidget14 = function () {
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("kt_mixed_widget_14_chart", am4charts.PieChart);

            // Add data
            chart.data = [
                {"sector": __('approved'), "size": approved, "color": am4core.color("#0EB10A")},
                {"sector": __('waiting'), "size": waiting, "color": am4core.color("#F4B000")},
                {"sector": __('not_approve'), "size": reject, "color": am4core.color("#EC0000")}
            ];

            chart.logo.disabled = true;
            chart.legend = null;
            chart.innerRadius = am4core.percent(50);
            chart.paddingTop = 0;
            chart.marginTop = 0;
            chart.valign = 'top';
            chart.contentValign = 'top';

            // Add label
            var label = chart.seriesContainer.createChild(am4core.Label);
            label.text = totalLeaveApplication;
            label.horizontalCenter = "middle";
            label.verticalCenter = "middle";
            label.fontSize = 20;
            label.tooltipText = __('Tổng số đơn xin nghỉ phép trong tháng');
            label.tooltip.dy = -15;

            // Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "size";
            pieSeries.dataFields.category = "sector";
            pieSeries.slices.template.propertyFields.fill = "color";
            pieSeries.labels.template.disabled = true;
            pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;
        }

        $(document).ready(function () {
            _initMixedWidget14();
        })
    </script>
@endsection

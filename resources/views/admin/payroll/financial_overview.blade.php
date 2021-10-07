@extends('layout.default')
<style>
    .price_max {
        font-size: 1.9rem;
    }
    .font-salary{font-size: 14px;font-weight: bold}
    .box_info {
        position: relative;
        cursor: pointer;
    }
     .box_info:hover > .box_this_info{
         display: block;
     }
    .box_this_info {
        display: none;
        position: absolute;
        background: #fff;
        right: 0;
        width: max-content;
        top: 2.2rem;
        border-radius: 6px;
        padding: 10px;
        z-index: 10;
        border: 1px solid #d4d4d4;
    }
    .info_size_text {
        font-size: 13px;
        color: #80808F;
    }
    .text_size_right{
        padding-right: 3rem !important;
    }
    .position-top{top: 0 !important;}
</style>
@section('content')
    <div class="financial_overview alert alert-white alert-shadow fade show gutter-b px-5">
        <div class="financial_business">
            <div class="card-title">
                <h3 class="card-label">{{ __('financial_overview') }}</h3>
                <span class="month_finalcial">{{ __('Month').' '.$date_before }}</span>
            </div>
        </div>
        <div class="box_view_info row">
            <div class="col-md-3 col-sm-6 col-lg-6 col-xxl-3">
                <div class="card card-custom gutter-b bg-light-primary">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="text-primary text-hover-primary mb-5 font-salary">
                                    {{ __('salary_user_max') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-1">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5 ">
                                    @if($salary)
                                        <span class="font-weight-bold price_max">
                                        {{ $salary ? str_replace('₫','', app('hrm')->getCurrencyConverter()->getUserFormat((float)$salary['max_salary']['salary'])) : '' }}
                                    </span>
                                        <small class="text-muted">Đồng</small>
                                    @else
                                        <span class="font-weight-bold">
                                            <p class="info_size_text text_size_right m-0">Không có dữ liệu</p>
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="box_info">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Code/Info-circle.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                        <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"/>
                                        <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                <div class="box_this_info">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        <!--begin::Item-->
                                        @if($salary)
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('employee_name') }}</td>
                                            <td class="info_size_text">{{ $salary['max_salary']['info']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('left_department') }}</td>
                                            <td class="info_size_text">{{ $salary['max_salary']['info']['departmen'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('module_company_title') }}</td>
                                            <td class="info_size_text">{{ $salary['max_salary']['info']['company'] }}</td>
                                        </tr>
                                        <tr class="pb-2">
                                            <td class="info_size_text text_size_right">{{ __('xin_location') }}</td>
                                            <td class="info_size_text ">{{ $salary['max_salary']['info']['position'] }}</td>
                                        </tr>
                                        <!--end::Item-->
                                        </tbody>
                                        @else
                                            <p class="info_size_text text_size_right m-0 p-2">Không có dữ liệu</p>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-6 col-xxl-3">
                <div class="card card-custom gutter-b bg-light-primary">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="text-primary text-hover-primary mb-5 font-salary">
                                    {{ __('salary_user_min') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-1">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5 ">
                                    @if($salary)
                                        <span class="font-weight-bold price_max">
                                        {{ $salary ? str_replace('₫','',app('hrm')->getCurrencyConverter()->getUserFormat((float)$salary['min_salary']['salary'])) : '' }}
                                    </span>
                                        <small class="text-muted">Đồng</small>
                                    @else
                                        <span class="font-weight-bold">
                                            <p class="info_size_text text_size_right m-0">Không có dữ liệu</p>
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="box_info">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Code/Info-circle.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                        <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"/>
                                        <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                <div class="box_this_info">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        <!--begin::Item-->
                                        @if($salary)
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('employee_name') }}</td>
                                            <td class="info_size_text">{{ $salary['min_salary']['info']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('left_department') }}</td>
                                            <td class="info_size_text">{{ $salary['min_salary']['info']['departmen'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="info_size_text text_size_right">{{ __('module_company_title') }}</td>
                                            <td class="info_size_text">{{ $salary['min_salary']['info']['company'] }}</td>
                                        </tr>
                                        <tr class="pb-2">
                                            <td class="info_size_text text_size_right">{{ __('xin_location') }}</td>
                                            <td class="info_size_text ">{{ $salary['min_salary']['info']['position'] }}</td>
                                        </tr>
                                        @else
                                            <p class="info_size_text text_size_right">Không có dữ liệu</p>
                                        @endif
                                        <!--end::Item-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-6 col-xxl-3">
                <div class="card card-custom gutter-b bg-light-primary">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="text-primary text-hover-primary mb-5 font-salary">
                                    {{ __('salary_user_average') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-1">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5 ">
                                    @if($salary)
                                        <span class="font-weight-bold price_max">
                                        {{ $salary ? str_replace('₫','',app('hrm')->getCurrencyConverter()->getUserFormat($salary['avg_salary'])) : '' }}
                                    </span>
                                        <small class="text-muted">Đồng</small>
                                    @else
                                        <span class="font-weight-bold">
                                            <p class="info_size_text text_size_right m-0">Không có dữ liệu</p>
                                        </span>
                                    @endif

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-6 col-xxl-3">
                <div class="card card-custom gutter-b bg-light-primary">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="text-primary text-hover-primary mb-5 font-salary">
                                    {{ __('amount_paid') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-1">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5 ">
                                    @if($salary)
                                    <span class="font-weight-bold price_max">
                                        {{ $salary ? str_replace('₫','',app('hrm')->getCurrencyConverter()->getUserFormat($salary['total_salary'])) : '' }}
                                    </span>
                                    <small class="text-muted">Đồng</small>
                                    @else
                                        <span class="font-weight-bold price_max">
                                            <p class="info_size_text text_size_right m-0">Không có dữ liệu</p>
                                        </span>
                                    @endif

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    {{-- chart --}}
        <div class="chart_overview">
            <div class="chart_body" id="chart">
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // $(document).ready(function () {
        //     $(".box_info").hover(function () {
        //         $(this).find(".box_this_info").;
        //         console.log(1);
        //         return false;
        //     })
        //
        // })
        var department = @json($payrollByDepartment['name']);
        var this_month = @json($payrollByDepartment['last_month']);
        var this_month_average = @json($payrollByDepartment['average']);
        const orange = '#F4B000';
        const blue = '#1689FF';
        var options = {
            series: [{
                name: 'Quỹ lương bộ phận',
                data: this_month
            }, {
                name: 'Lương trung bình bộ phận',
                data: this_month_average
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '30%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent', orange]
            },
            xaxis: {
                categories:department,
            },
            subtitle: {
                text: 'Đơn vị (Triệu đồng)',
                offsetX: 15,
                offsetY: 20,
                style: {
                    color:  '#263238',
                    fontSize: '12px',
                    color:'#80808F',
                }

            },
            title: {
                text: 'Quỹ lương theo bộ phận',
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize:  '14px',
                    fontWeight:  'bold',
                    fontFamily:  undefined,
                    color:  '#263238'
                },
            },
            yaxis: {
                labels: {
                    show: true,
                    formatter: function (value) {
                        return  value;
                    }
                },

            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                onItemClick: {
                    toggleDataSeries: false
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return window._userCurrency(Math.floor(val))+ " tổng quỹ";
                    }
                }
            },
            colors: [blue,orange]
        }
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

    </script>
@endsection

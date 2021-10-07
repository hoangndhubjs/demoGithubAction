
const primary = '#1689FF';
const last_month = '#3A528F';

//bien che luong theo thang
var _initStatsWidget12 = function () {
    var options = {
        series: [{
            name: __('total_money'),
            data: payrollByMonth
        }],
        chart: {
            type: 'area',
            height: 260,
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
            sparkline: {
                enabled: true
            }
        },
        floating: true,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        },
        labels: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            show: true,
        },
        xaxis: {
            categories: nameByMonth,
            labels: {
                show: false
            }
        },
        yaxis: {
            labels: {
                show: false
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return window._userCurrency(val);
                }
            }
        },

        legend: {
            show: false,
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}

//bien che luong theo bo phan
var _demo3 = function () {
    const apexChart = "#chart_3";
    var options = {
        series: [{
                name: __('xin_attendance_this_month'),
                data: this_month
            },{
                name: __('xin_attendance_last_month'),
                data: lastMonth
            }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            }
        },
        noData: {
            text: __('loading_data')
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '21%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            position: 'top',
            onItemClick: {
                toggleDataSeries: false
            },
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['transparent']
        },
        xaxis: {
            type: 'category',
            tickPlacement: 'on',
            labels: {
                rotate: -45,
                rotateAlways: true
            },
            categories: nameCategory,
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return window._userCurrency(val);
                }
            }
        },
        colors: [primary, last_month]
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();

}

//lich trong ngay
function calendarViewToday() {
    var todayDate = moment().startOf('day');

    console.log(todayDate)
    var YM = todayDate.format('YYYY-MM');
    var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
    var TODAY = todayDate.format('YYYY-MM-DD');
    var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

    var calendarEl = document.getElementById('kt_calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],

        isRTL: KTUtil.isRTL(),

        customButtons: {
            see_details: {
                text: 'Xem chi tiết',
                click: function() {
                    window.location.pathname = 'calendars/list';
                }
            }
        },

        header: {
            left: 'prev,next',
            center: 'title',
            right: 'see_details'
        },

        height: 800,
        contentHeight: 750,
        aspectRatio: 3,
        locale: window._locale,
        allDayText: __('All_day'),
        // views: {
        //     listWeek: { buttonText: 'list' }
        // },

        defaultView: 'timeGridDay',
        defaultDate: TODAY,

        editable: true,
        eventLimit: true, // allow "more" link when too many events
        navLinks: true,
        events: function (info, successCallback, failedCallback) {
            KTApp.block("#kt_calendar");
            let startDate = moment(info.start);
            let endDate = moment(info.end);
            $.ajax({
                url: "/calendars/calendar_post",
                method:'GET',
                data: {
                    startDate: startDate.format("YYYY-MM-DD"),
                    endDate: endDate.format("YYYY-MM-DD")
                }
            }).done(function (response) {
                var listEmpty = $('.fc-list-empty');

                KTApp.unblock("#kt_calendar");
                $.each(response, function (index, item) {
                    response[index]['meeting_note'] = window._htmlEscapeEntities(item['meeting_note']);
                    response[index]['description'] = window._htmlEscapeEntities(item['description']);
                });
                if (response.length == 0) {
                    // listEmpty.css('background-color', '#E1F0FF');
                    // listEmpty.text('Không có sự kiện vào hôm nay');
                } else {
                    successCallback(response);
                }
            });
        },
    });

    calendar.render();
}

//So nhan vien theo bo phan
function countEmployee() {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chartdiv", am4charts.PieChart);

    chart.data = employeeDepartment;

    chart.innerRadius = am4core.percent(50);
    chart.logo.disabled = true;

    var label = chart.seriesContainer.createChild(am4core.Label);
    label.text = totalEmployeeDepartment;
    label.horizontalCenter = "middle";
    label.verticalCenter = "middle";
    label.fontSize = 30;
    label.tooltipText = __('dashboard_total_employees');
    label.tooltip.dy = -15;

    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "total";
    pieSeries.dataFields.category = "name";
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.propertyFields.fill = "color";
    pieSeries.labels.template.disabled = true;
    pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;


    chart.legend = new am4charts.Legend();
    // chart.legend.position = "right";
    chart.legend.itemContainers.template.clickable = false;
    chart.legend.itemContainers.template.focusable = false;
    chart.legend.itemContainers.template.cursorOverStyle = am4core.MouseCursorStyle.default;

    var legendContainer = am4core.create("legenddiv", am4core.Container);
    legendContainer.width = am4core.percent(100);
    legendContainer.height = am4core.percent(100);
    chart.legend.parent = legendContainer;
    chart.legend.scrollable = true;
    legendContainer.logo.disabled = true;
}

function getQuote() {
    window._loading("#quote");
    $.ajax({
        url: '/quote',
        type: "GET",
        dataType: 'json',
    }).done(function (data) {
        window._loading("#quote", false);
        $('#quote').html(data);
    });
}

$(document).ready(function () {

    _initStatsWidget12();

    _demo3();

    calendarViewToday();

   countEmployee();

   getQuote();
});

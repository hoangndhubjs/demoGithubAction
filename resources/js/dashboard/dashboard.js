//Charts
var _initMixedWidget14 = function () {
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("kt_mixed_widget_14_chart", am4charts.PieChart);

    // Add data
    chart.data = [
        { "sector": __('attendance_full'), "size": full_working, "color": am4core.color("#3699FF") },
        { "sector": __('attendance_half'), "size": half_working, "color": am4core.color("#1BC5BD")},
        { "sector": __('xin_remaining'), "size": workday_remaining, "color": am4core.color("#E6E8F1") }
    ];

    chart.logo.disabled = true;
    chart.innerRadius = am4core.percent(94);
    chart.legend = null;
    // Add label
    var label = chart.seriesContainer.createChild(am4core.Label);
        label.text = total_working;
        label.horizontalCenter = "middle";
        label.verticalCenter = "middle";
        label.fontSize = 30;
        label.tooltipText = __('total_workdays_is_calculated');
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

    //datepicker + get event
    var arrows;
    var uri = '/event';

    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
        } else {
            arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }
    $('#kt_datepicker_6').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        templates: arrows,
        language: window._locale
    }).on('changeDate', function (e) {
        window._loading("#timelineEvent");
        let date = e.date;
        let yMD = moment(date.toISOString()).format("YYYY-MM-DD");
        let mD = moment(date.toISOString()).format("MM-DD");
        let html = '';
        $.ajax({
            url: uri,
            type: 'GET',
            data: {
                yMD: yMD,
                mD: mD
            },
            success: function(data) {
                if(data.birthday != "") {
                    $.each(data.birthday,function(key,value){
                        html += '<div class="timeline-item align-items-start">\n' +
                                    '<div class="timeline-label font-weight-bolder text-dark-75">Cả ngày</div>\n' +

                                    '<div class="timeline-badge">\n' +
                                        '<i class="fa fa-gift text-warning icon-1x" data-toggle="tooltip" title="'+__('birthday')+'"></i>\n' +
                                    '</div>\n' +

                                    '<div class="text-dark-75 pl-3 font-size-lg">'+__('birthday')+' '+value['first_name']+ ' ' +value['last_name']+'</div>\n' +
                                '</div>'
                    });
                }
                if(data.meeting != "") {
                    $.each(data.meeting,function(key,value){
                        array = value['employee_id'].split(',');
                        check_user_id = array.includes(auth_id.toString());
                        title_meeting = check_user_id ? window._htmlEscapeEntities(value['meeting_title']) : __('have_meeting');
                        color_meeting = check_user_id ? 'text-dark-75' : 'text-muted';
                        // color_element_i = check_user_id ? 'text-primary' : 'text-success';

                        html += '<div class="timeline-item align-items-start">\n' +
                                    '<div class="timeline-label font-weight-bolder text-dark-75">'+ moment(value['meeting_time'], "HH:mm:ss").format("HH:mm")+ ' - ' +moment(value['meeting_end'], "HH:mm:ss").format("HH:mm")+'</div>\n' +

                                    '<div class="timeline-badge">\n' +
                                        '<i class="fa fa-briefcase text-primary icon-1x" data-toggle="tooltip" title="'+__('xin_hr_meeting')+'"></i>\n' +
                                    '</div>\n' +

                                    '<div class="'+ color_meeting +' pl-3 font-size-lg">'+ title_meeting +'</div>\n' +
                                '</div>'
                    });
                }
                if (html != "") {
                    $("#showEvent").addClass("timeline-6");
                    $('#showEvent').html(html);
                } else {
                    $("#showEvent").removeClass("timeline-6");
                    $('#showEvent').html('<h5>'+"Không có lịch nào"+'</h5>');
                }
            }
        }).done(function () {
            window._loading("#timelineEvent", false);
        })
    });
    $('#kt_datepicker_6 .today').trigger('click');
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

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
    getQuote();
});

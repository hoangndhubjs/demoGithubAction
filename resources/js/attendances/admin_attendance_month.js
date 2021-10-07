"use strict";

var KTCalendarBackgroundEvents = function() {
    return {
        dates: {},
        events: {},
        init: function() {
            let self = this;
            var todayDate = moment(window._start_date).startOf('day');
            var TODAY = todayDate.format('YYYY-MM-DD');

            var calendarEl = document.getElementById('attendance_month_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid'],
                initialView: 'dayGridMonth',
                isRTL: KTUtil.isRTL(),
                locale: window._locale,

                height: 800,
                contentHeight: 780,
                aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

                nowIndicator: true,

                defaultView: 'dayGridMonth',
                defaultDate: TODAY,
                validRange: {
                    //end: todayDate.endOf('month').format('YYYY-MM-DD')
                },

                navLinks: false,
                events: function (info, successCallback, failureCallback) {
                    successCallback([]);
                    $('#attendance_month_calendar .fc-attendance-detail').remove();
                    let startDate = moment(info.start);
                    let month = null;
                    if (startDate.date() === 1) {
                        month = startDate;
                    } else {
                        //Find closed first day it will be current month;
                        month = startDate.add(1, 'month');
                    }
                    self.loadAttendanceCalendar(month);
                    self.loadAttendanceSummary(month);
                },
                dayRender: function (arg) {
                    let dateStr = moment(arg.date.toISOString()).format("YYYY-MM-DD");
                    self.dates[dateStr] = arg;
                }
            });
            calendar.render();
        },
        renderEvents: function () {
            let self = this;
            $.each(this.dates, function (date, object) {
                let event = self.events[date];
                let dayNumber = moment(object.date.toISOString()).format("D");
                let todayStr = moment().format('YYYY-MM-DD');
                let isToday = todayStr === date;
                if (event || isToday) {
                    let html = '<span class="fc-day-number">'+dayNumber+'</span>';
                    let el = $(object.el);
                    if (event) {
                        let attendance = event.attendance;
                        html += '<span class="fc-attendance-content fc-event-label ' + event.class + '">' + event.event_label + '</span>';
                        let day_detail_html = '<div class="fc-attendance-detail shadow" data-date="'+date+'">';
                        let checkin = attendance.check_in_at ? moment(attendance.check_in_at).format('HH:mm') : '--:--';
                        let checkout = attendance.check_out_at ? moment(attendance.check_out_at).format('HH:mm') : '--:--';
                        day_detail_html += '<table>';
                        day_detail_html += '<thead>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<th class="text-left text-capitalize">'+__('date')+'</th>';
                        day_detail_html += '<th class="text-right">'+moment(attendance.day).format(window._dateFormat)+'</th>';
                        day_detail_html += '</tr>';
                        day_detail_html += '</thead>';
                        day_detail_html += '<tbody>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<td class="text-left">'+__('checkin')+'</td>';
                        day_detail_html += '<td class="text-right">'+checkin+'</td>';
                        day_detail_html += '</tr>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<td class="text-left">'+__('checkout')+'</td>';
                        day_detail_html += '<td class="text-right">'+checkout+'</td>';
                        day_detail_html += '</tr>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<td class="text-left">'+__('late_minutes')+'</td>';
                        day_detail_html += '<td class="text-right">'+attendance.late_minutes+' '+__('minutes')+'</td>';
                        day_detail_html += '</tr>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<td class="text-left">'+__('leave_minutes')+'</td>';
                        day_detail_html += '<td class="text-right">'+attendance.early_leave_minutes+' '+__('minutes')+'</td>';
                        day_detail_html += '</tr>';
                        day_detail_html += '<tr>';
                        day_detail_html += '<td class="text-left">'+__('orders')+'</td>';
                        day_detail_html += '<td class="text-right">'+attendance.total_order_rice+' '+__('times')+'</td>';
                        day_detail_html += '</tr>';
                        day_detail_html += '</tbody>';
                        day_detail_html += '</table>';
                        day_detail_html += '</div>';
                        let el = $(object.el);
                        el.addClass('fc-attendance-event');
                        $('#attendance_month_calendar').append(day_detail_html);
                        el.on('mousemove', function (e) {
                            let mouseBuffer = 20;
                            let target = $(e.currentTarget);
                            let calendar = $('#attendance_month_calendar');
                            let mouseX = e.clientX;
                            let mouseY = e.clientY;
                            let calendarOffset = calendar.offset();
                            let scrollTop = document.documentElement.scrollTop;
                            let positionX = mouseX - calendarOffset.left + mouseBuffer;
                            let positionY = mouseY - calendarOffset.top + mouseBuffer + scrollTop;
                            let mydate = target.data('date');
                            let popup = $('#attendance_month_calendar .fc-attendance-detail[data-date='+mydate+']');
                            let popupWidth = popup.outerWidth();
                            let endOfPopup = mouseX + mouseBuffer + popupWidth;
                            let calendarWidth = calendar.outerWidth();
                            let endOfCalendar = calendarWidth + calendarOffset.left;
                            if (endOfPopup >= endOfCalendar) {
                                positionX = calendarWidth - popupWidth;
                            }
                            popup.css({'position':'absolute', 'top':positionY, 'left': positionX, 'z-index': 999, 'display':'block'});
                        }).on('mouseleave', function (e) {
                            let target = $(e.currentTarget);
                            let mydate = target.data('date');
                            let popup = $('#attendance_month_calendar .fc-attendance-detail[data-date='+mydate+']');
                            popup.css({'display':'none'});
                        });
                    }
                    el.append(html);
                }
            });
            // console.log(this.dates, this.events);
        },
        loadAttendanceCalendar: function (month) {
            let self = this;
            KTApp.block('#attendance_month_calendar');
            $.ajax({
                method: 'GET',
                url: window._attendance_monthly_url,
                data: {
                    start_date: month.startOf('month').format("YYYY-MM-DD"),
                    end_date: month.endOf('month').format("YYYY-MM-DD")
                }
            }).done(function (response) {
                KTApp.unblock('#attendance_month_calendar');
                if (response.success) {
                    self.events = response.data;
                    self.renderEvents();
                }
            });
        },
        loadAttendanceSummary: function (month) {
            KTApp.block('#attendance_monthly_summary');
            $.ajax({
                method: 'GET',
                url: window._attendance_monthly_summary_url,
                data: {
                    month: month.startOf('month').format("YYYY-MM")
                }
            }).done(function (response) {
                KTApp.unblock('#attendance_monthly_summary');
                if (response.success) {
                    $('#total_late').html(response.data.total_late);
                    $('#total_early').html(response.data.total_early_leave);
                    $('#total_full_attendance').html(response.data.total_attendance);
                    $('#total_leave_requested').html(response.data.total_leave_day);
                    $('#total_overtime').html(response.data.total_overtime);
                    $('#total_phep_ton').html(response.data.total_phep_ton);
                }
            });
        }
    };
}();

jQuery(document).ready(function() {
    KTCalendarBackgroundEvents.init();
});

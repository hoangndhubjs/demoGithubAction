"use strict";

var KTCalendarExternalEvents = function() {



    var initExternalEvents = function() {
        $('#kt_calendar_external_events .fc-draggable-handle').each(function() {
            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method)
                classNames: [$(this).data('color'),'hidden_drop'],
                description:  $.trim($(this).text())
            });

        });
    }
    var initCalendar = function() {
        var todayDate = moment().startOf('day');

        var YM = todayDate.format('YYYY-MM');
        var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
        var TODAY = todayDate.format('YYYY-MM-DD');

        var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

        var calendarEl = document.getElementById('kt_calendar');
        var containerEl = document.getElementById('kt_calendar_external_events');

        var Draggable = FullCalendarInteraction.Draggable;

        new Draggable(containerEl, {
            itemSelector: '.fc-draggable-handle',
            eventData: function(eventEl) {
                return $(eventEl).data('event');
            }
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],

            isRTL: KTUtil.isRTL(),
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 800,
            contentHeight: 780,
            aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio
            locale: window._locale,
            allDayText: __('All_day'),
            nowIndicator: true,
            now: TODAY + 'T09:25:00', // just for demo

            views: {
                dayGridMonth: { buttonText: __('Month') },
                timeGridWeek: { buttonText: __('Week') },
                timeGridDay: { buttonText: __('Day') }
            },

            defaultView: 'dayGridMonth',

            defaultDate: TODAY,
            droppable: true, // this allows things to be dropped onto the calendar
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,

            // events: [],
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
                    KTApp.unblock("#kt_calendar");
                    $.each(response, function (index, item) {
                        response[index]['meeting_note'] = window._htmlEscapeEntities(item['meeting_note']);
                        response[index]['description'] = window._htmlEscapeEntities(item['description']);
                    });

                    successCallback(response);
                });
            },

            // eventSources: [{
            //         url: "/calendars/calendar_post",
            //         method:'GET',
            //         data: { data_week:'12'  },
            //             error: function(){ alert("error"); }
            //         }
            // ],

            drop : function(arg) {

                let date_today = moment().format('YYYY-MM-DD');
                var event_date = moment(arg.date).format('YYYY-MM-DD');
                var this_record = $(arg.draggedEl).data('record');
                // var date =  $(arg.draggedEl).
                $("#meeting_datepicker").val(event_date);
                if(this_record === 2 && event_date >= date_today) {
                    // $("#model_add_event").modal('show');
                    $("#model_add_event1").modal('show');
                }else{
                    toastr.error('Không thể tạo cuộc họp trong quá khứ');
                    return false;
                }
                return false;
                $(arg.view).remove();
                // is the "remove after drop" checkbox checked?
                if ($('#kt_calendar_external_events_remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(arg.draggedEl).remove();
                }
            },

            eventRender: function(info) {
                var element = $(info.el);
                element.click(function () {
                    const meeting_id  = info.event.id;
                    const unq_id = info.event._def.extendedProps;
                    //unq = 1 => meeting
                    //unq = 2 => birtday
                    //unq = 10 => Leave
                    const leave_id = unq_id.leave_id;
                    var element_image = '';var count=0;
                    if(unq_id.unq != 2){
                        window._loading("#timelineEvent");
                        if(meeting_id && unq_id.unq == 1){
                            $.ajax({
                                url: '/calendars/detail_calendar',
                                type:'POST',
                                data : {meeting_id : meeting_id},
                                success:function (meeting) {
                                    $('#moment_lh').html(meeting[1]['logo_company']);
                                    $("#company_name").html(meeting[1]['company_name']);
                                    $("#metting_title_popup").html(meeting[0]['meeting_title']);
                                    $("#mettingRoom").html(meeting[0]['meeting_room'].charAt(0).toUpperCase() + meeting[0]['meeting_room'].slice(1));
                                    $("#metting_company_popup").html(meeting[0]['meeting_date']);
                                    $("#metting_note_popup").html(window._htmlEscapeEntities(meeting[0]['meeting_note']));
                                    $("#metting_time_start").html(moment(meeting[0]['meeting_time'], "HH:mm:ss").format("HH:mm"));
                                    $("#metting_time_end").html(moment(meeting[0]['meeting_end'], "HH:mm:ss").format("HH:mm"));
                                    let countMember = meeting['user_info'].length;
                                    for ( count=0 ; count <= countMember; count++ ){
                                        let info = meeting['user_info'][count];
                                        let memberMore = countMember-5;
                                        let hidden_class = count+1 > 5 ? 'hiddenMemeber':'';
                                        $(".more_member:last").addClass('12');
                                        if(meeting['user_info'][count] != undefined ){ //symbol symbol-30 symbol-circle
                                            element_image += '<div class="member_meetingday '+hidden_class+' " data-toggle="tooltip" title="'+info['user_name']+'" data-original-title="'+info['user_name']+'"><img alt="'+info['user_name']+'" src="'+info['image_user']+'"></div>';
                                            if(count+1 == countMember && countMember > 5){
                                                element_image += '<div class="symbol symbol-30 symbol-circle symbol-light more_member" data-toggle="tooltip" title="'+ __('show_more_member_meeting')+'" data-original-title="'+ __('show_more_member_meeting')+'"><span class="symbol-label font-weight-bold">'+ memberMore +'+</span></div>';
                                            }
                                        }
                                    }
                                    $("#member_schelu").html(element_image);
                                }
                            }).done(function () {
                                $("#model_add_event").modal('show');
                                window._loading("#timelineEvent", false);
                            });
                        }else if(leave_id && unq_id.unq == 10){
                            $.ajax({
                                url: '/calendars/detail_leave',
                                type:'POST',
                                data : {leave_id : leave_id},
                                success:function (leave_result) {
                                    let leave_info = leave_result[0];
                                    let fullname = leave_info.employee.last_name + ' ' + leave_info.employee.first_name;
                                    let company_name = leave_info.company_leave.company_name;
                                    let fields = [
                                        'company_id',
                                        'employee_id',
                                        'leave_type_id',
                                        'from_date',
                                        'to_date',
                                        'leave_types',
                                        'remarks',
                                        'reason',
                                        'confirm_list'
                                    ];
                                    // leave_types = 1 => cả ngày else leave_types = 2 => nửa ngày
                                    var chil = 0;
                                    let countChild = $("#user_leave_info").children().length - 1;
                                    for (chil; chil <= countChild; chil++) {
                                        let innertHtml = $("#user_leave_info").children('tr').children('td')[chil];
                                        if (fields[chil] == 'employee_id') {
                                            $(innertHtml).text(fullname);
                                        } else if (fields[chil] == 'company_id') {
                                            $(innertHtml).text(company_name);
                                        }else if(fields[chil] == 'leave_type_id'){
                                            $(innertHtml).text(leave_info.leave_type.type_name ? leave_info.leave_type.type_name : '');
                                        }else {
                                            $(innertHtml).text(leave_info[fields[chil]]);
                                        }
                                    }
                                }
                            }).done(function () {
                                $("#model_leave_user").modal('show');
                                window._loading("#timelineEvent", false);
                            });
                        }
                    }
                });
                element.removeClass('fc-draggable');
                if (info.event.extendedProps && info.event.extendedProps.description) {
                    if(element.hasClass('fc-draggable')){
                        element.removeClass('fc-draggable');
                    }
                    if (element.hasClass('fc-day-grid-event')) {
                        element.find(".fc-resizer").remove();
                        element.find(".hidden_drop").remove();
                        element.removeClass('fc-draggable');
                        element.data('content', info.event.extendedProps.description);
                        element.data('placement', 'top');
                        KTApp.initPopover(element);
                    } else if (element.hasClass('fc-time-grid-event')) {
                        element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                    } else if (element.find('.fc-list-item-title').lenght !== 0) {
                        element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                    }
                }
            },
        });
        calendar.render();
    }
    return {
        //main function to initiate the module
        init: function() {
            initExternalEvents();
            initCalendar();
        }
    };
}();

jQuery(document).ready(function() {

    KTCalendarExternalEvents.init();
});
//    date pciker
// Class definition
var KTBootstrapDatepicker = function () {

    var arrows;
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

    // Private functions
    var demos = function () {
        // minimum setup
        $('#start_date').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows,
            changeYear: true,
            format: "yyyy-mm-dd",
            minDate: '-3M',
            maxDate: '+28D',
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
        $("#meeting_datepicker").datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows,
            changeYear: true,
            startDate: new Date(),
            format: "yyyy-mm-dd",
            // maxDate: '+28D',
            autoclose: true
        });
        $('#end_date').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows,
            changeYear: true,
            format: "yyyy-mm-dd",
            minDate: '-3M',
            maxDate: '+28D',
        });
        // minimum setup for modal demo
        $('#kt_datepicker_1_modal').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            templates: arrows
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();
//validation
$(document).ready(function () {
    $(".hidden_drop").remove();
    // $('#m_meeting_time').timepicker();
    $(document).on('click',  ".more_member",function () {
        $(this).remove();
        $(".member_meetingday").removeClass('hiddenMemeber');
    });
    // minimum setup
    $('#m_meeting_time, #m_meeting_end').timepicker({
        minuteStep: 15,
        defaultTime: '',
        showSeconds: false,
        showMeridian: false,
        snapToStep: true,
    });
    //    select value on change company && member
    $("#company_select2_ajax").change(function(e) {
        e.preventDefault();
        const self = $(this);
        let company_id = self.val();
        var html = '';
        $.ajax({
            type: "GET",
            url: '/calendars/get_employee_company',
            data: {company_id:company_id},
            cache: false,
            success: function (result_data) {
                if(result_data){
                    $.each(result_data, function (key,item){
                        html += '<option url="'+item['profile_picture']+'" value="'+item['user_id']+'">';
                        html += item['first_name'] + ' ' + item['last_name'];
                        html += '</option>';
                        // html += '<span><img class="" width="50px" src="'+item['profile_picture']+'" /></span>';
                    });
                    $("#member_select2").html(html);
                }

            }
        });
    });
    // let option = $('#company_select2_ajax').find("option");
    $("#company_select2_ajax").trigger('change');

    $("#member_select2").change(function (e) {
        e.preventDefault();
        $("#employee_idc").val($(this).val());
    });
    $(".clickForDate").click(function () {
        $(this).parent().find("input").focus();
    });
});
function checkTime(time1, time2, check) {
    // metting_start => check=1 else metting_end => check=2
    if (time1 === '' &&  time2 === '') {
        return false;
    } else {
        let today = moment();
        let mtime1 = moment(today.format("YYYY-MM-DD ")+time1, "YYYY-MM-DD HH:mm");
        let mtime2 = moment(today.format("YYYY-MM-DD ")+time2, "YYYY-MM-DD HH:mm");
        return mtime2.diff(mtime1) > 0;
    }
}
var KTFormControls = function () {
    var _metting = function () {
        FormValidation.formValidation(
            document.getElementById('meeting_schedule'),
            {
                fields: {
                    company_id: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    employee_select: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    meeting_title: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    meeting_date: {
                        validators: {
                            notEmpty: {
                                message: __('your_not_selected_meeting')
                            },
                        }
                    },
                    meeting_time: { //thời gian bắt đầu
                        validators: {
                            callback:{
                                message: __('select_meeting_startDate'),
                                callback: function () {
                                    let timeStart = $("#m_meeting_time").val();
                                    let timeEnd = $("#m_meeting_end").val();
                                    return checkTime(timeStart,timeEnd,1);
                                }
                            }
                        },
                    },
                    meeting_end: {//thời gian kêt thúc
                        validators: {
                            callback:{
                                message: __('select_meeting_endDate'),
                                callback: function () {
                                    let timeStart = $("#m_meeting_time").val();
                                    let timeEnd = $("#m_meeting_end").val();
                                    return checkTime(timeStart,timeEnd,2);
                                }
                            }
                        }
                    },
                    meeting_room :{
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    meeting_note :{
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                            // callback: {
                            //     message: obligatory,
                            //     callback: function () {
                            //         const regxCharset = /[`@#$%^&*()_+\\[\]{};':"\\|<>\/?~]/;
                            //         let metting_note = $("#meeting_note").val();
                            //         let alert = '';
                            //         if(metting_note.test(regxCharset)==true){
                            //             .
                            //         }
                            //         return
                            //     }
                            // }
                        }
                    }

                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
                    submitButton: new FormValidation.plugins.SubmitButton()
                    // Submit the form when all fields are valid
                    // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                }
            }
        ).on('core.form.valid', function () {
            $(document).ready(function () {
                $(".add_meetting").text(__('saving')).attr('disabled','disabled');
            });
            $.ajax({
                type: "POST",
                url: '/calendars/add_mettings',
                data: $('#meeting_schedule').serialize(),
                cache: false,
                success: function (result_data) {
                    if (result_data.success == true){
                        toastr.success(result_data.data);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else{
                        if(result_data.error_title){
                            let html = '';
                            let i = 1;
                            $(".modal_view_time").show();
                            $('.modal_view_time > .modal-title').html(__('duplicate_time_meeting'));
                            $.each(result_data.meetings,function(key,value){
                                let meeting_time_formart = moment(value['meeting_time'], "HH:mm:ss").format("HH:mm");
                                let meeting_end_formart = moment(value['meeting_end'], "HH:mm:ss").format("HH:mm");
                                html += '<p>'+ i++ +'. '+ __('xin_started') + ' : ' + meeting_time_formart + ' -- ' + __('xin_end_metting') + ' : '  + meeting_end_formart + '</p>';
                            });
                            $('.view_time_body').html(html);
                            if (i > 4) {
                                $('.view_time_body').addClass("view_time_body_scroll");
                            }
                            toastr.error(result_data.error_title);
                            $(".add_meetting").text(__('xin_save')).removeAttr('disabled');
                        }else{
                            $(".add_meetting").text(__('xin_save')).attr('disabled');
                            toastr.error(result_data.data);
                        }
                    }
                }
            });
        });
    }
    return {
        // public functions
        init_metting: function() {
            _metting();
        }
    };
}();

jQuery(document).ready(function() {


    KTBootstrapDatepicker.init();
    KTFormControls.init_metting();
    $(".close_view_time").click(function () {
        $(".modal_view_time").hide();
    });
});

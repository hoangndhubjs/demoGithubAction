<!-- Modal xin nghi -->

<style>
    .select2 {
        width: 100% !important;
    }
    /*#model_add_event .dialog-leave {*/
    /*    max-width: 806px !important;*/
    /*    margin: 1.75rem auto;*/
    /*}*/
   .modal-dialog{
       max-width: 806px !important;
       margin: 1.75rem auto;
    }
    .group_metting_date{
        background: #072f5e;
        width: 10%;
        height: auto;
        position: relative;
    }
   .timeMeeting_lh{
       position: absolute;
       top: 50%;
       left: 50%;
       transform: translate(-50%, -50%);
       color: #fff;
       font-size: 15px;
       text-align: center;
   }
    .meeting_date_{
        font-size: 15px;
        color: #fd4b4b;
    }
    #moment_lh img {
        width: 100%;
        max-width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    .hiddenMemeber{
        display: none;
    }
    .modal_view_time{display: none;padding-top: 10px;}
    .view_time_body {
        padding: 10px 20px 0;
    }
    .view_time_body_scroll {
        height: 131px;
        overflow-x: auto;
    }
    .view_time_body_scroll::-webkit-scrollbar {
        width: 3px;
    }
    .view_time_body_scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 5px;
    }
    .member_meetingday {
        width: 30px;
        height: 30px;
        margin: 5px;
    }
    .member_meetingday img{
        width: 100%;
        height: 100%;
    }
    .mettingRoom:after {
        content: '';
        position: absolute;
        width: 5px;
        height: 5px;
        background: #3699FF;
        border-radius: 50%;
        left: 4px;
        top: 50%;
        transform: translate(-50%, -20%);
    }
    .mettingRoom {
        font-size: 14px;
        margin-left: 5px;
        position: relative;
        padding-left: 20px;
    }
    .col-meeting-detail {
        width: max-content;
        margin-right: 30px;
        padding-right: 20px;
    }
    @media (max-width: 800px) {
        .pt_mobile{
            padding: 10px;
        }
        .col-mobile-company {
            width: max-content;
        }
        .col-mobile-company-z {
            width: 70%;
        }
        .member_join{margin-top: 10px;padding: 0}
    }
    @media (max-width: 600px) {
        .title_date_time {
            display: block;
            padding-left: 10px;
        }
        .title_date_time .col-meeting-detail {
            width: unset;
            margin: 0 !important;
            padding: 0;
        }
        .col-mobile-company {
            width: max-content;
        }
        .col-mobile-company-z {
            width: 70%;
        }
        .member_join{margin-top: 10px;padding: 0}
    }
    .datepicker table tr td.disabled {
        background: #00000000;
        opacity: .35;
    }
    .datepicker table tr td.disabled:hover {
        background: #ffffff;
        color: #3F4254;
        cursor: no-drop;
    }
</style>
<div class="container">
    <div class="modal fade" id="model_add_event" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable dialog-leave" role="document">
            <div class="modal-content">
{{--modal-header--}}
{{--                <div class="p-2">--}}
{{--                    <h5 class="modal-title text-center" id="exampleModalLabel"></h5>--}}
{{--                    <button type="reset" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <i aria-hidden="true" class="ki ki-close"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
                {{--            <div class="modal-body modal_leave" style="overflow-y: unset !important;">--}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Section-->
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column mr-auto">
                                        <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1" id="metting_title_popup"></a>
{{--                                        <span class="font-weight-bold meeting_date_">Ngày họp: </span>--}}
                                    </div>
                                    <!--begin::Pic-->
                                </div>
                                <div class="row title_date_time align-items-center">
                                    <div class="col-meeting-detail border-right ml-3"><span class="text-primary mettingRoom" id="mettingRoom"></span></div>
                                    <div class="col-meeting-detail border-right font-weight-bold row align-items-center">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Clock.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="20" height="20"/>
                                                    <path d="M12,22 C7.02943725,22 3,17.9705627 3,13 C3,8.02943725 7.02943725,4 12,4 C16.9705627,4 21,8.02943725 21,13 C21,17.9705627 16.9705627,22 12,22 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>
                                                </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
                                        <span class="ml-2">Ngày tạo: <span id="metting_company_popup"></span></span>
                                    </div>
                                    <div class="col-meeting-detail row align-items-center">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Clock.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="20" height="20"/>
                                                    <path d="M12,22 C7.02943725,22 3,17.9705627 3,13 C3,8.02943725 7.02943725,4 12,4 C16.9705627,4 21,8.02943725 21,13 C21,17.9705627 16.9705627,22 12,22 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>
                                                </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
                                        <span class="font-weight-bold ml-2" id="metting_time_start"></span>
                                        <span class="mx-1">-</span>
                                        <span class="font-weight-bold" id="metting_time_end"></span>
                                    </div>
                                </div>
                                <p class="mb-7 mt-3" id="metting_note_popup"></p>
                                <!--end::Text-->
                                <!--begin::Blog-->
                                <div class="d-flex flex-wrap">
                                    <!--begin: Item-->
                                    <div class="row col-md-5">
{{--                                        <div class="mr-12 d-flex flex-column mb-7">--}}
{{--                                            <span class="font-weight-bolder mb-4">Thời gian bắt đầu</span>--}}
{{--                                            <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text" id="metting_time_start"></span>--}}
{{--                                        </div>--}}
                                        <!--end::Item-->
                                        <!--begin::Item-->
{{--                                        <div class="mr-12 d-flex flex-column mb-7">--}}
{{--                                            <span class="font-weight-bolder mb-4">Thời gian kết thúc</span>--}}
{{--                                            <span class="font-weight-bolder font-size-h5 pt-1"></span>--}}
{{--                                            <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text" id="metting_time_end"></span>--}}
{{--                                        </div>--}}
{{--                                    <div class="flex-shrink-0">--}}
{{--                                        <div class="font-weight-bold mb-2"><p>Công ty</p></div>--}}
{{--                                        <div class="row align-items-center">--}}
{{--                                            <div class="col-md-6 p-0">--}}
{{--                                                <div class="symbol symbol-65 symbol-circle" id="moment_lh"></div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6 company_name_gr px-1"><span id="company_name"></span></div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
                                        <div class="div-info-company w-100">
                                            <div class="info_company">
                                                <div class="compnay_name">
                                                    <span class="font-weight-bold">{{ __('left_company') }}</span>
                                                </div>
                                                <div class="row align-content-center">
                                                    <div class="col-md-3 col-mobile-company">
                                                        <div class="symbol symbol-65 symbol-circle"><x-logo/></div>
                                                    </div>
                                                    <div class="col-md-6 col-mobile-company-z row align-content-center p-0">
                                                        <div class="company_name_gr px-1"><span id="company_name"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="flex-column flex-lg-fill float-left mb-7 col-md-7 member_join">
                                        <span class="font-weight-bolder mb-4">{{ __('member_join_meetings') }}</span>
                                        <div class="symbol-group symbol-hover row pl-2" id="member_schelu">
                                            <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="" data-original-title="">

                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Blog-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dat lich hop -->
    <div class="modal fade p-0" id="model_add_event1"  tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_hrsale_meetings_calWiki')  }}</h5>
                    <button type="reset" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body1">
                    <div class="card card-custom">
                        <div class="modal_view_time modal-header" aria-modal="true">
                            <h5 class="modal-title"></h5>
                            <div class="view_time_body"></div>
                            <button type="button" class="close_view_time btn btn-secondary close_time exit float-right mr-3 cc_pointer" style="">{{ __('xin_close') }}</button>
                        </div>
                        <!--begin::Form-->
                        <form id="meeting_schedule">
                            @csrf
                            <div class="card-body pb-0">
                                <div class="form-group row mb-0">
                                    <div class="form-group col-md-6">
                                        <label>{{__('left_company')}}<span class="text-danger"> *</span></label>
                                        <select style="height: 37px" class="form-control kt-select2 company_select2" id="company_select2_ajax" name="company_id" data-placeholder="{{__('left_company')}}">
                                            <!--option value=""></option-->
                                            @foreach($all_companies as $company_id)
                                                <option value="{{ $company_id->company_id }}">{{ $company_id->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{__('xin_membership')}}<span class="text-danger"> *</span></label>
                                        <select class="form-control kt-select2 member_select2" id="member_select2" name="employee_select" data-placeholder="{{ __('xin_membership') }}"  multiple="multiple">
{{--                                            <option value="" selected></option>--}}
                                        </select>
                                    </div>
                                    <input type="hidden" id="employee_idc" name="empolyee_id" value="">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('xin_hr_meeting_title')  }}<span class="text-danger"> *</span></label>
                                    <input type="meeting_title" name="meeting_title" class="form-control form-control-lg" placeholder="{{ __('xin_hr_meeting_title')  }}">
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    --}}
{{--                                    --}}
{{--                                </div>--}}
                                <div class="row">
                                    <div class="form-group col-md-4 mb-0 pt_mobile">
                                        <label>{{__('xin_hr_meeting_date')}}<span class="text-danger"> *</span></label>
                                        <div class="input-group timepicker">
                                            <input type="" autocomplete="off" name="meeting_date" readonly id="meeting_datepicker" class="form-control" placeholder="{{__('xin_hr_meeting_date')}}">
{{--                                            <input class="form-control "   placeholder="{{ __('xin_project_timelogs_starttime') }}" name="meeting_time" type="text" id="m_meeting_time"/>--}}
{{--                                            <div class="input-group-append clickForDate">--}}
{{--                                                <span class="input-group-text"> <i class="la la-clock-o"></i> </span>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <label for="meeting_time">{{ __('xin_project_timelogs_starttime') }}<span class="text-danger"> *</span></label>
                                        <div class="input-group timepicker">
                                            <input class="form-control "   placeholder="{{ __('xin_project_timelogs_starttime') }}" name="meeting_time" type="text" id="m_meeting_time"/>
                                            <div class="input-group-append clickForDate">
                                                <span class="input-group-text"> <i class="la la-clock-o"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label for="meeting_end">{{ __('xin_project_timelogs_endtime') }}<span class="text-danger"> *</span></label>
                                            <div class="input-group timepicker">
                                                <input class="form-control"  placeholder="{{ __('xin_project_timelogs_endtime') }}" name="meeting_end" type="text" id="m_meeting_end"/>
                                                <div class="input-group-append clickForDate">
                                                    <span class="input-group-text"> <i class="la la-clock-o"></i> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meeting_room">{{ __('xin_meeting_room') }}<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="meeting_room" placeholder="{{ __('xin_meeting_room') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="event_note">{{ __('xin_hr_meeting_note') }}<span class="text-danger"> *</span></label>
                                            <textarea class="form-control textarea" placeholder="{{ __('xin_hr_meeting_note') }}" name="meeting_note" id="meeting_note"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 pt-2">
                                <div class="row">
                                    <div class="col-2">
                                    </div>
                                    <div class="col-10 text-right">
                                        <button  type="submit" class="add_meetting btn btn-success mr-2">{{ __('xin_save')  }}</button>
                                        <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close')  }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal show đơn xin nghỉ user -->
    <div class="modal fade" id="model_leave_user" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable dialog-leave" role="document">
            <div class="modal-content" id="ajax_modal_view"><div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                <form class="m-b-1">
                    <div class="modal-body">
                        <h4 class="text-center text-big mb-4"><strong>{{ __('left_leave') }}</strong></h4>
                        <table class="footable-details table table-striped table-hover toggle-circle">
                            <tbody id="user_leave_info">
                            <tr>
                                <th>{{ __('module_company_title') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th>{{ __('dashboard_employees') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th>{{ __('xin_leave_type') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th>{{ __('xin_start_date') }}</th>
                                <td style="display: table-cell;"</td>
                            </tr>
                            <tr>
                                <th>{{ __('xin_end_date') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('xin_hrsale_total_days') }}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th>{{ __('xin_remarks') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th>{{ __('xin_leave_reason') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            <tr>
                                <th>{{ __('kpi_status') }}</th>
                                <td style="display: table-cell;"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('xin_close') }}</button>
                    </div>
                </form></div>
        </div>
    </div>

</div>

@extends('layout.default')
@section('styles')
    <link rel="stylesheet" href="{{mix('/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}">
    <style>
        .hidden_drop{ display: none; } /* xóa phần tử kéo thả  (cuộc họp) xóa khi kéo chọn vào ngày trong lịch*/
        #kt_calendar .fc-view-container{
                box-sizing: content-box !important;
        }
        .member_meeting{
            display: inline-block;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
@endsection
@section('content')
        <!--begin::Example-->
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-3">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">{{ __('xin_hr_draggable_options') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="kt_calendar_external_events" class="fc-unthemed scroll-calendar">
{{--                            <div class="btn btn-block text-left font-weight-bold btn-light-primary fc-draggable-handle mb-5 cursor-move" data-record="1" data-color="fc-event-primary">Để lại yêu cầu</div>--}}
                            <div class="btn btn-block text-left font-weight-bold btn-light-primary fc-draggable-handle mb-5 cursor-move" data-record="2" data-color="fc-event-primary"> <i class="fas fa-briefcase"></i>{{ __('xin_hr_meeting') }}</div>
                            <div class="btn btn-block text-left font-weight-bold btn-light-warning" data-color="fc-event-primary"><i class="fas fa-gift"></i>{{ __('xin_hr_calendar_upc_birthday') }}</div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <div class="col-lg-9">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">{{$page_title}}</h3>
                        </div>
                        <div class="card-toolbar">
{{--                            <a href="#" class="btn btn-light-primary font-weight-bold">--}}
{{--                                <i class="ki ki-plus"></i>Thêm sự kiện</a>--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#model_add_event1">
                              <span>{{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}</span>  {{ __('add_calendar') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="kt_calendar"></div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Row-->
        <!--end::Example-->

        <!-- Button trigger modal-->
        <!-- Modal-->
        @include('pages.calendars.modal_add_event')
        <!-- Modal-->
        <div class="card-body pt-0 mx-auto" id="timelineEvent">
            <!--begin::Timeline-->
            <div class="timeline timeline-6 mt-3" id="showEvent">
            </div>
            <!--end::Timeline-->
        </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{mix('js/fullcalendar.bundle.js')}}" type="text/javascript"></script>
    <script src="{{mix('/js/pages/features/calendar/external-events.js')}}" type="text/javascript"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('.select2').select2();
            $(".company_select2").select2();
            $(".member_select2").select2({
                placeholder: "Chọn thành viên",
                templateResult: formatState
            });
            function formatState (state) {
                var img_url = ($(state.element).attr("url"));
                if (!state.id) { return state.text; }
                var $state = $('<span ><img width="30px" height="30px" class="member_meeting"  src="'+img_url+'" /> ' + state.text + '</span>');
                return $state;
            }
        });

    </script>
@endsection

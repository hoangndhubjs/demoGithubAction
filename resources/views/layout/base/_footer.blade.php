{{-- Footer --}}
<style>
    .select2 {
        width: 100% !important;
    }
    /*#model_add_event .dialog-leave {*/
    /*    max-width: 806px !important;*/
    /*    margin: 1.75rem auto;*/
    /*}*/
    .modal-dialog{
        /*max-width: 806px !important;*/
        /*margin: 1.75rem auto;*/
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
    #moment_lh_notifi img {
        width: 100%;
        max-width: 70px;
        height: 70px;
        border-radius: 50%;
    }
    .hiddenMemeber{
        display: none;
    }
</style>
<div class="container">
    <div class="modal fade" id="meeting_notification" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <input type="hidden" id="id-meeting" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                    <button type="reset" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                {{--            <div class="modal-body modal_leave" style="overflow-y: unset !important;">--}}
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Section-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Pic-->
                                    {{--<div --}}{{--id="moment_lh_notifi"--}}{{-- class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle col-mobile-company"><x-logo/></div>--}}
                                    <div class="col-md-3 col-mobile-company">
                                        <div class="symbol symbol-65 symbol-circle"><x-logo/></div>
                                    </div>
                                    <div class="d-flex flex-column mr-auto">
                                        <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1" id="metting_title_notifi"></a>
                                        <span class="font-weight-bold meeting_date_">Ngày họp: <span id="metting_company_notifi"></span></span>
                                    </div>
                                </div>
                                <p class="mb-7 mt-3" id="metting_note_notifi"></p>
                                <!--end::Text-->
                                <!--begin::Blog-->
                                <div class="d-flex flex-wrap">
                                    <!--begin: Item-->
                                    <div class="row col-md-6">
                                        <div class="mr-12 d-flex flex-column">
                                            <span class="font-weight-bolder mb-4">Thời gian bắt đầu</span>
                                            <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text" id="metting_start_time"></span>
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="mr-12 d-flex flex-column">
                                            <span class="font-weight-bolder mb-4">Thời gian kết thúc</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1"></span>
                                            <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text" id="metting_end_time"></span>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="flex-column flex-lg-fill float-left col-md-6">
                                        <span class="font-weight-bolder mb-4">Thành viên</span>
                                        <div class="symbol-group symbol-hover row" id="member_meeting">
                                            <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="" data-original-title="Mark Stone">

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
</div>

<div class="footer bg-white py-4 d-flex flex-lg-column {{ Metronic::printClasses('footer', false) }}" id="kt_footer">
    {{-- Container --}}
    <div class="{{ Metronic::printClasses('footer-container', false) }} d-flex flex-column flex-md-row align-items-center justify-content-between">
        {{-- Copyright --}}
        <div class="text-dark order-2 order-md-1">
            <x-footer />
        </div>
    </div>
</div>
<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script type="text/javascript">
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        // encrypted: true,
        cluster: '{{config('broadcasting.connections.pusher.options.cluster')}}'
    });
    var channel = pusher.subscribe('nhansu.hqgroups_{{\Auth::user()->user_id}}');
    channel.bind('nhansu.hqgroups', function(data) {
        // alert(data.message);
        Swal.fire(data.message);
        // toastr.success(data.message);
    });
</script>

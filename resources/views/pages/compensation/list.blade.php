@extends('layout.default')
@section('styles')
    <style>
        .dots_ps {
            position: relative;
        }
        .dots_as {
            position: absolute;
            width: 5px;
            height: 5px;
            content: 'a';
            left: -10px;
            top: 50%;
            transform: translate(-50%, -50%);
            border-radius: 100%;
        }
        .close_compens {
            position: absolute;
            top: 30%;
            right: 3%;

        }
        .element {
            position: relative;
        }
        .close_compens:hover {
            cursor: pointer;
        }
        .element .fv-plugins-message-container {
            display: none;
        }
        .element .fv-plugins-message-container:last-child {
            display: block;
        }
        .select_month {
            /*margin-right: 2rem;*/
            width: 38rem;
        }
        .dialog_compensations{
            max-width: 900px !important;
            margin: 1.75rem auto;
        }
        td.disabled.day {
            background: #7E8299;
            opacity: 0.20;
        }
        @media (max-width: 800px) {
            .select_month {
                margin-right: 2rem;
                width: 12rem;
            }
            .card-body-compensations{
                padding: 10px !important;
            }
            .form_compensations {
                margin-bottom: 20px;
            }
            .element_mobile {
                width: 90%;
                margin-bottom: 10px;
            }
            .close_compens {
                position: absolute;
                top: 35%;
                right: 0;
            }
        }
    </style>
@endsection
@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('my_list_compensations')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <div class="select_month">
                    <form action="" id="compensations_list_search_form" class="row">
                        <div class="col-md-6">
{{--                    <select id="compensations_filler_month" name="month" class="form-control" data-placement="Chọn tháng" id="exampleSelect1">--}}
{{--                        <option class="text-muted" value="0" class="" disabled selected>Chọn tháng</option>--}}
{{--                        @for($i=1; $i <= 12; $i++)--}}
{{--                             <option value="{{ $i < 10 ? '0'.$i : $i }}">Tháng {{ $i }}</option>--}}
{{--                        @endfor--}}
{{--                    </select>--}}
                            <input class="form-control" id="date_compensation" autocomplete="off" name="month"  placeholder="{{ __('Chọn tháng') }}" type="text" value="">

                        </div>
                        <div class="col-md-6 d-flex">
                            <button type="submit" class="btn btn-primary px-6 font-weight-bold mr-3">
                                <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                                {{ __('xin_search') }}
                            </button>
                            <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                        </div>
                    </form>
                </div>
                <!--begin::Button-->
                <div>
                    <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#update_overtime_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span>{{__('Thêm mới')}}</a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body card-body-compensations">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="overtime_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
    <!--begin::Modal-->
    {{--<div class="modal fade" id="add_leave_modal" tabindex="-1" role="dialog" aria-labelledby="add_leave_modal1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_leave_modal1">{{__('Xin nghỉ phép')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-xl-12">
                       --}}{{-- @include('pages.overtime.form_modal', ['type'=>'created'])--}}{{--
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
    <!--end::Modal-->
    <!-- begin: Modal -->
    <div class="modal fade" id="update_overtime_request" tabindex="-1" role="dialog" aria-labelledby="update_overtime_request" aria-hidden="true" data-form-url="{{ route('compensations.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered dialog_compensations modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("confirm_request_compensations") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->
@endsection

@section('scripts')
    <script>
        window._tables = {
            'overtime_list': {
                'url': '{{ route('compensations.ajax.lists') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 30,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    @if(Auth::user()->isAdmin())
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            return row.employee.last_name + ' ' + row.employee.first_name;
                        }
                    },
                    @endif
                    {
                        field: 'created_at',
                        title: '{{ __('compensation_date_request') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return moment(row.created_at).format('DD-MM-YYYY')
                        }
                    },
                    {
                        field: 'compensation_date',
                        title: '{{ __('compensation_date') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return moment(row.compensation_date).format('DD-MM-YYYY')
                        }
                    },
                    {
                        field: 'reason',
                        title: '{{ __('xin_reason') }}',
                        sortable: false,
                        // width: 180,
                        template: function (row) {
                            let string = row.reason;
                            if(row.reason.length > 50){
                                string = row.reason.substring(0, 50)+ '...';
                            }
                            return '<span class="" data-toggle="tooltip" data-trigger="focus" data-html="true" title="'+row.reason+'">'+string+ '</span>';
                        }
                    },
                    {
                        field: 'reason_compensation',
                        title: '{{ __('reason_compensation') }}',
                        sortable: false,
                        template: function (row) {
                            let string = row.comment_compensation;
                            if(row.comment_compensation.length > 50){
                                string = row.comment_compensation.substring(0, 50)+ '...';
                            }
                            return '<span class="" data-toggle="tooltip" data-trigger="focus" data-html="true" title="'+row.comment_compensation+'">'+string+ '</span>';
                        }
                    },
                    {
                        field: 'compensation_type',
                        title: '{{ __('compensation_type') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            let status = {
                                2: {'class': 'text-danger'},
                                1: {'class': 'text-success'},
                                0: {'class': 'text-warning'},
                            };
                            let status_color = {
                                2: {'class': 'bg-danger'},
                                1: {'class': 'bg-success'},
                                0: {'class': 'bg-warning'},
                            };
                            let status_type = {
                                1: {'lable':'Đủ công'},
                                2: {'lable':'Nửa công sáng'},
                                3: {'lable':'Nửa công chiều'},
                            };
                            return '<span class="font-weight-bold dots_ps ' + status[row.compensation_status].class + '">&nbsp;' + status_type[row.compensation_type].lable + '<span class="dots_as '+ status_color[row.compensation_status].class +'">&nbsp;</span></span>';
                        }
                    },
                    {
                        field: 'type_of_work',
                        title: '{{ __('Loại công') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            if (row.type_of_work == 'off') {
                                var typeWork = '<span class="label label-lg label-light-primary label-inline font-weight-bold">Offline</span>';
                            }
                            if (row.type_of_work == 'on') {
                                var typeWork = '<span class="label label-lg label-light-success label-inline font-weight-bold">Online</span>';
                            }
                            return typeWork;
                        }
                    },
                    {
                        field: 'compensation_status',
                        title: '{{ __('kpi_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 100,
                        // template: function (row) {
                        //     return row.compensation_type
                        // }
                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-success'},
                                0: {'class': ' label-light-warning'},
                            };
                            let status_type = {
                                2: {'lable':'Không duyệt'},
                                1: {'lable':'Đã duyệt'},
                                0: {'lable':'Đang đợi'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.compensation_status].class + ' label-inline">' + status_type[row.compensation_status].lable + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        // width: 90,
                        template: function (row) {
                            let compensationId = row.compensation_status == 0 ? row.compensation_id : '';
                            let html = "";
                            let iconsCheck  =  '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <polygon points="0 0 24 0 24 24 0 24"/>\n' +
                                '        <path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002) "/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let iconsClose  = '<span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Close.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">\n' +
                                '            <rect x="0" y="7" width="16" height="2" rx="1"/>\n' +
                                '            <rect opacity="0.3" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) " x="0" y="7" width="16" height="2" rx="1"/>\n' +
                                '        </g>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            var edit_compnesation = "";
                            @if(Auth::user()->isAdmin())
                                html = "<a href='javascript:;'  onclick='updateCompensations("+row.compensation_id+",1)' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+iconsCheck+"</a>";
                                html += "<a href='javascript:;'  onclick='updateCompensations("+row.compensation_id+",2)' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+iconsClose+"</a>";
                            {{--edit_compnesation = "<a href='javascript:;' onclick='updateCompensationApproved("+row.compensation_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";--}}
                            @endif
                            // data-toggle='modal' data-target='#update_overtime_request'
                            html += "<a  href='javascript:;' onclick='deleteRequest("+row.compensation_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return  row.compensation_status == 0 ? html : edit_compnesation;
                        }
                    }
                ],
                'search_from': '#compensations_list_search_form'
            }
        };

        $('#update_overtime_request').on('shown.bs.modal', function (e) {
            window._loading("#update_overtime_request .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#update_overtime_request').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            }).done(function (response) {
                    window._loading("#update_overtime_request .modal-body", false);
                    $("#update_overtime_request .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#update_overtime_request .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#update_overtime_request .modal-body", __("order_form_fetch_error"));
                });
        });

        $('#update_overtime_request').on('hidden.bs.modal', function (e) {
            $("#update_overtime_request .modal-body").html("");
        });
        // cập nhật đơn đã được duyệt => chưa sử dụng tới
        {{--var updateCompensationApproved = function (id){--}}
        {{--    if (id) {--}}
        {{--        const swalWithBootstrapButtons = Swal.mixin({--}}
        {{--            customClass: {--}}
        {{--                confirmButton: 'btn btn-success',--}}
        {{--                cancelButton: 'btn btn-danger'--}}
        {{--            },--}}
        {{--            buttonsStyling: false--}}
        {{--        })--}}
        {{--        swalWithBootstrapButtons.fire({--}}
        {{--            title: 'Hủy đơn bù công đã duyệt',--}}
        {{--            icon: 'warning',--}}
        {{--            showCancelButton: false,--}}
        {{--            confirmButtonText: 'Hủy đơn!',--}}
        {{--            cancelButtonText: 'Không duyệt!',--}}
        {{--            showCloseButton: true,--}}
        {{--            reverseButtons: true--}}
        {{--        }).then((result) => {--}}
        {{--            if (result.isConfirmed) {--}}
        {{--                $.ajax({--}}
        {{--                    method: 'get',--}}
        {{--                    url: '{{ route("compensations.setCompensation") }}',--}}
        {{--                    data: {id:id}--}}
        {{--                }).done(function (reponse) {--}}
        {{--                    if (reponse.success == true){--}}
        {{--                        window._tables.overtime_list.datatable.reload();--}}
        {{--                        Swal.fire(__('update_success'), '', 'success')--}}
        {{--                        // toastr.success(reponse.data);--}}
        {{--                    }else{--}}
        {{--                        Swal.fire(__('xin_error_msg'), '', 'error')--}}
        {{--                        // toastr.error(__("xin_error_msg"));--}}
        {{--                    }--}}
        {{--                }).fail(function (jqXHR, reponse){--}}
        {{--                    Swal.fire(__('xin_error_msg'), '', 'error')--}}
        {{--                    // toastr.error(__("error"));--}}
        {{--                });--}}
        {{--            }--}}
        {{--        })--}}
        {{--    }--}}
        {{--}--}}
        //1903
        var updateCompensations = function (id, status) {

            if (id && status == 1) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Duyệt đơn bù công?',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Duyệt đơn!',
                    cancelButtonText: 'Không duyệt!',
                    showCloseButton: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'get',
                            url: '{{ route("compensations.ajax.update_compensations") }}',
                            data: {id: id, status: 1}
                        }).done(function (reponse) {
                            if (reponse)
                                window._tables.overtime_list.datatable.reload();
                            Swal.fire(__('update_success'), '', 'success')
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $.ajax({
                            method: 'get',
                            url: '{{ route("compensations.ajax.update_compensations") }}',
                            data: {id: id, status: 2}
                        }).done(function (reponse) {
                            window._tables.overtime_list.datatable.reload();
                        });
                    }
                })
            }else if(id && status == 2){
                Swal.fire({
                    title: '<span class="text-danger">Xác nhận từ chối</span>',
                    input: 'textarea',
                    width: '35%',
                    text: 'Bạn chắc chắn muốn từ chối yêu cầu bù công?\n' +
                        'Hãy để lại lý do từ chối bên dưới',
                    inputPlaceholder: 'Lý do từ chối yêu cầu bù công (bắt buộc)',
                    inputAttributes: {
                        'aria-label': 'Lý do từ chối yêu cầu bù công (bắt buộc)'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    showLoaderOnConfirm: true,
                    cancelButtonText: 'Hủy',
                    // preConfirm: (login) => {
                    //     console.log();
                //         return fetch(`//api.github.com/users/${login}`)
                //             .then(response => {
                //                 if (!response.ok) {
                //                     throw new Error(response.statusText)
                //                 }
                //                 return response.json()
                //             })
                //             .catch(error => {
                //                 Swal.showValidationMessage(
                //                     `Request failed: ${error}`
                //                 )
                //             })
                //     },
                //     allowOutsideClick: () => !Swal.isLoading()
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         Swal.fire({
                //             title: `${result.value.login}'s avatar`,
                //             imageUrl: result.value.avatar_url
                //         })
                //     }
                }).then((function (result) {
                    let confirm_reson = result.value;
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'get',
                            url: '{{ route("compensations.ajax.update_compensations") }}',
                            data: {
                                id: id,
                                status: 2,
                                reason_confirm  : confirm_reson
                            }
                        }).done(function (reponse) {
                            if (reponse) window._tables.overtime_list.datatable.reload();
                            Swal.fire(__('update_success'), '', 'success')
                            setTimeout(function (e) {
                                Swal.close();
                            }, 1000);
                        }).fail(function (jqXHR, status){
                            toastr.error(__("update_fail"));
                        });
                    }

                }))
            }else{
                Swal.fire({
                    icon: 'warning',
                    text: 'Bạn đã duyệt đơn này!',
                });
            }

        }
        var deleteRequest = function (id) {
            if (id) {
                Swal.fire({
                    title: __("are_you_sure_delete_it"),
                    text: __("you_wont_able_revert_it"),
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        $.ajax({
                            method: 'DELETE',
                            url: '{{ route("compensations.ajax.delete_compensations") }}',
                            data: {id: id}
                        }).done(function (response) {
                            if (response.success) {
                                Swal.fire(
                                    __("deleted_title"),
                                    response.data ?? __("record_deleted"),
                                    "success",
                                    false,
                                    false
                                );
                            } else {
                                Swal.fire(
                                    __("error_title"),
                                    response.error ?? __("error"),
                                    "error",
                                    false,
                                    false
                                );
                            }
                            setTimeout(function () {
                                Swal.close()
                            }, 800);
                            window._tables.overtime_list.datatable.reload();
                            /*setTimeout(function () {window.location.reload()}, 400);*/
                        });
                        return false;
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading()
                    }
                });
            }
        }
        $('#resetForm').click(function(){
            $('#compensations_list_search_form').trigger("reset");
        });
        //
        $("#date_compensation").datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
            setDate: new Date(),
            endDate: "toDay",
            language: window._locale
        })
        // $("#compensations_filler_month").on('change', function (e) {
        //     e.preventDefault();
        //     window._tables.overtime_list.datatable.reload();
        // })
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

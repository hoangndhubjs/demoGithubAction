@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('Làm thêm giờ của tôi')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#update_overtime_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span>{{__('Thêm mới')}}</a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="overtime_search_form" autocomplete="off">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2">
                                    <label class="d-block required">{{ __("xin_date") }}</label>
                                    <input class="form-control datepicker-default"
                                           placeholder="{{__('xin_date')}}"
                                           name="created_at" type="text" value="">
                                </div>
                                <div class="col-md-3 my-2 my-md-0">
                                    <label class="d-block required">{{ __("dashboard_xin_status") }}</label>
                                    <select class="form-control selectpicker" id="status" name="status" style="width: 100%" >
                                        <option value="">{{__('xin_choose_status')}}</option>
                                        <option value="1">{{__('xin_pending')}}</option>
                                        <option value="2">{{__('xin_accepted')}}</option>
                                        <option value="0">{{__('xin_rejected')}}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-xl-4 mt-8">
                                    <button type="submit" class="btn btn-primary font-weight-bold">{{ __('search') }}</button>
                                    <button type="button" class="btn btn-light-primary font-weight-bold" id="reset_form">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="overtime_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
    <!-- begin: Modal -->
    <div class="modal fade" id="update_overtime_request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="update_overtime_request" aria-hidden="true" data-form-url="{{ route('overtime_request.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_overtime_request") }}</h5>
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
        {{--let employee_id = '{{auth()->user()->user_id}}';--}}
        $('.datepicker-default').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi',
            zIndexOffset:100
        });
        $('#reset_form').click(function () {
            $('#overtime_search_form').trigger("reset");
            $('#overtime_search_form select').trigger("change");
            $('#overtime_search_form').submit();
        });
        window._tables = {
            'overtime_list': {
                'url': '{{ route('overtime_request.ajax.admin_lists') }}',
                'columns': [
                    {
                        field: 'request_date',
                        title: '{{ __('Ngày') }}',
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return moment(row.request_date).format("DD-MM-YYYY")
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        template: function (row) {
                            return (row.employee)?row.employee.first_name+' '+row.employee.last_name:''
                        }
                    },
                    {
                        field: 'employee_id',
                        title: '{{ __('employee_id') }}',
                        sortable: false,
                        template: function (row) {
                            return (row.employee)?row.employee.employee_id:''
                        }
                    },
                    {
                        field: 'request_clock_in',
                        title: '{{ __('xin_project_timelogs_starttime') }}',
                        sortable: false,
                        template: function (row) {
                            return row.request_clock_in
                        }
                    },
                    {
                        field: 'request_clock_out',
                        title: '{{ __('xin_project_timelogs_endtime') }}',
                        sortable: false,
                        template: function (row) {
                            return row.request_clock_out
                        }
                    },
                   /* {
                        field: 'total_hours',
                        title: '{{ __('xin_overtime_thours') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.total_hours
                        }
                    },*/
                    {
                        field: 'request_reason',
                        title: '{{ __('xin_content') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return row.request_reason
                        }
                    },
                    {
                        field: 'is_approved',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-primary'},
                                1: {'class': ' label-light-warning'},
                                0: {'class': ' label-light-danger'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.is_approved].class + ' label-inline">' + row.status_label + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 90,
                        template: function (row) {
                            let html = "";
                            if (row.is_approved == 1) {
                                html += "<a href='#' onclick='approveRequest("+row.time_request_id+", 2)' class='btn btn-sm btn-clean btn-icon' title='{{ __('approve') }}'>"+window.iconCheck+"</a>";
                                html += "<a href='#' onclick='approveRequest("+row.time_request_id+", 0)' class='btn btn-sm btn-clean btn-icon' title='{{ __('not_approve') }}'>"+window.iconClose+"</a>";
                            }
                            if (row.employee_id == '{{auth()->user()->user_id}}' && row.is_approved == 1) {
                                html += "<a href='#' data-toggle='modal' data-target='#update_overtime_request' data-id='"+row.time_request_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                                html += "<a href='#' onclick='deleteRequest("+row.time_request_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            }
                            return html;
                        }
                    }
                ],
                'search_from': '#overtime_search_form'
            }
        };
        let approveRequest = function (id, approve) {
            Swal.fire({
                title: __("are_you_sure_action"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: __("yes"),
                cancelButtonText: __("no"),
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function (data) {
                    Swal.showLoading();
                    $.ajax({
                        method: 'POST',
                        url: '{{ route("overtime_request.ajax.approve") }}',
                        data: { id: id, approve: approve }
                    }).done(function(response) {
                        if (response.success) {
                            Swal.fire(
                                __("update_title"),
                                response.data ?? __("record_updated"),
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
                        setTimeout(function () {Swal.close()}, 800);
                        window._tables.overtime_list.datatable.reload();
                    }).fail(function (jqXHR, status) {
                        let response = jqXHR.responseJSON;
                        Swal.fire(
                            __("error_title"),
                            response.errors ?? __("error"),
                            "error",
                            false,
                            false
                        );
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
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
            })
                .done(function (response) {
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
        var deleteRequest = function (id) {
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
                        url: '{{ route("overtime_request.ajax.delete_overtime") }}',
                        data: { id: id }
                    }).done(function(response) {
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
                        setTimeout(function () {Swal.close()}, 800);
                        window._tables.overtime_list.datatable.reload();
                        /*setTimeout(function () {window.location.reload()}, 400);*/
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

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
        window._tables = {
            'overtime_list': {
                'url': '{{ route('overtime_request.ajax.lists') }}',
                'columns': [
                    {
                        field: 'request_date',
                        title: '{{ __('Ngày') }}',
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.request_date
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
                    /*{
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
                        autoHide: false,
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
                            if (row.is_approved === 1) {
                                html = "<a href='#' data-toggle='modal' data-target='#update_overtime_request' data-id='"+row.time_request_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                                html += "<a href='#' onclick='deleteRequest("+row.time_request_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            }
                            return html;
                        }
                    }
                ],
                'search_from': '#overtime_list_search_form'
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

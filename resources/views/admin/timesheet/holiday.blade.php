@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="d-flex flex-row">
        @include('admin.constants.nav')
        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{__('xin_view_all_holiday')}}
                            {{--<span class="d-block text-muted pt-2 font-size-sm">{{__('xin_manage_holidays')}}</span>--}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#holiday_request">
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
                    <div class="datatable datatable-bordered datatable-head-custom" id="holiday_list"></div>
                    <!--end: Datatable-->
                </div>
            </div>
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
    <div class="modal fade" id="holiday_request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="holiday_request" aria-hidden="true" data-form-url="{{ route('admin.timesheet.ajax.create_form_holiday')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_holiday") }}</h5>
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
            'holiday_list': {
                'url': '{{ route('admin.timesheet.ajax.list.holiday') }}',
                'columns': [
                    {
                        field: 'event_name',
                        title: '{{ __('xin_event_name') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return row.event_name
                        }
                    },
                    {
                        field: 'description',
                        title: '{{ __('xin_description') }}',
                        sortable: false,
                        template: function (row) {
                            return row.description
                        }
                    },
                    {
                        field: 'start_date',
                        title: '{{ __('xin_start_date') }}',
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.start_date
                        }
                    },
                    {
                        field: 'end_date',
                        title: '{{ __('xin_end_date') }}',
                        width: 100,
                        sortable: false,
                        template: function (row) {
                            return row.end_date
                        }
                    },
                    {
                        field: 'is_publish',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            let status = {
                                1: {'class': ' label-light-primary'},
                                0: {'class': ' label-light-danger'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.is_publish].class + ' label-inline">' + row.status + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 80,
                        template: function (row) {
                            let html = "";
                            html += "<a href='#' data-toggle='modal' data-target='#holiday_request' data-id='"+row.holiday_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteRequest("+row.holiday_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
                'search_from': '#holiday_list_search_form'
            }
        };
        $('#holiday_request').on('shown.bs.modal', function (e) {
            window._loading("#holiday_request .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#holiday_request').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
            .done(function (response) {
                window._loading("#holiday_request .modal-body", false);
                $("#holiday_request .modal-body").html(response);
            })
            .fail(function (jqXHR, status){
                window._loading("#holiday_request .modal-body", false);
                toastr.error(__("order_form_fetch_error"));
                window._display_alert("#holiday_request .modal-body", __("order_form_fetch_error"));
            });
        });
        $('#holiday_request').on('hidden.bs.modal', function (e) {
            $("#holiday_request .modal-body").html("");
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
                        url: '{{ route("admin.timesheet.ajax.delete_holiday") }}',
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
                        window._tables.holiday_list.datatable.reload();
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

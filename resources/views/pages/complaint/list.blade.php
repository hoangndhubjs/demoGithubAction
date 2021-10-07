@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('Khiếu nại của tôi')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#complaint_request">
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
                        <form id="complaint_search_form">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0">
                                    <select class="form-control selectpicker" id="status" name="status" style="width: 100%" >
                                        <option value="">{{__('xin_choose_status')}}</option>
                                        @foreach($listStatus as $key => $status)
                                            <option value="{{$key}}">{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                    <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">{{ __('search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="complaint_list"></div>
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
    <div class="modal fade" id="complaint_request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="complaint_request" aria-hidden="true" data-form-url="{{ route('complaint.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_complaint") }}</h5>
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
            'complaint_list': {
                'url': '{{ route('complaint.ajax.lists') }}',
                'columns': [
                    {
                        field: 'complaint_date',
                        title: '{{ __('xin_complaint_date') }}',
                        width: 120,
                        sortable: true,
                        template: function (row) {
                            return row.complaint_date
                        }
                    },
                    {
                        field: 'title',
                        title: '{{ __('xin_complaint_title') }}',
                        sortable: false,
                        template: function (row) {
                            return row.title
                        }
                    },
                    {
                        field: 'company_id',
                        title: '{{ __('xin_companies') }}',
                        sortable: false,
                        template: function (row) {
                            let company = '';
                            if(row.company){
                                company = row.company.name;
                            }
                            return company
                        }
                    },
                    {
                        field: 'complaint_against',
                        title: '{{ __('xin_complaint_against') }}',
                        sortable: false,
                        template: function (row) {
                            let content = "";
                            row.complaint_against.forEach(function (item) {
                                content += row.against[item].first_name + " " + row.against[item].last_name+"<br/>";
                            });
                            return content;
                        }
                    },
                    {
                        field: 'description',
                        title: '{{ __('xin_description') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.description
                        }
                    },
                    {
                        field: 'status',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                                0: {'class': ' label-light-warning'},
                            };
                            let html = "";
                            return '<span class="label label-lg font-weight-bold ' + status[row.status].class + ' label-inline">' + row.status_label + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            if (row.status === 0) {
                                html = "<a href='#' data-toggle='modal' data-target='#complaint_request' data-id='"+row.complaint_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                                html += "<a href='#' onclick='deleteRequest("+row.complaint_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            }
                            return html;
                        }
                    }
                ],
                'search_from': '#complaint_search_form'
            }
        };
        $('#complaint_request').on('shown.bs.modal', function (e) {
            window._loading("#update_overtime_request .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#complaint_request').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
            .done(function (response) {
                window._loading("#update_overtime_request .modal-body", false);
                $("#complaint_request .modal-body").html(response);
            })
            .fail(function (jqXHR, status){
                window._loading("#update_overtime_request .modal-body", false);
                toastr.error(__("order_form_fetch_error"));
                window._display_alert("#update_overtime_request .modal-body", __("order_form_fetch_error"));
            });
        });
        $('#complaint_request').on('hidden.bs.modal', function (e) {
            $("#complaint_request .modal-body").html("");
        });
        let deleteRequest = function (id) {
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
                        url: '{{ route("complaint.ajax.delete_complaint") }}',
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
                        window._tables.complaint_list.datatable.reload();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

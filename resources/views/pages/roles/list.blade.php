@extends('layout.default')
@section('styles')
    <link rel="stylesheet" href="{{ mix('/plugins/custom/jstree/jstree.bundle.css') }}" />
@endsection
@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('roles_list')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#role_modal">
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
                            {{--<div class="row align-items-center">
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
                            </div>--}}
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="role_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="role_modal" tabindex="-1" role="dialog" aria-labelledby="role_modal" aria-hidden="true" data-form-url="{{ route('role.ajax.create_form')}}">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_add_role") }}</h5>
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
            'role_list': {
                'url': '{{ route('role.ajax.lists') }}',
                'columns': [
                    {
                        field: 'company_id',
                        title: '{{ __('xin_companies') }}',
                        width: 120,
                        sortable: true,
                        template: function (row) {
                            return row.company?row.company.name:'';
                        }
                    },
                    {
                        field: 'name',
                        title: '{{ __('xin_role_name') }}',
                        sortable: false,
                        template: function (row) {
                            return row.name
                        }
                    },
                    {
                        field: 'role_access',
                        title: '{{ __('xin_role_access') }}',
                        sortable: false,
                        template: function (row) {
                            let role_acess = '';
                            if(row.role_access == 1){
                                role_acess = 'Truy cập tất cả menu';
                            } else if(row.role_access == 2){
                                role_acess = 'Truy cập menu tùy chỉnh';
                            }
                            return role_acess
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
                            let html = '';
                            html = "<a href='#' data-toggle='modal' data-target='#role_modal' data-id='"+row.id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+'<x-icon type="flaticon" icon="edit" class="text-warning"/>'+"</a>";
                            html += "<a href='#' onclick='deleteRequest("+row.id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+'<x-icon type="flaticon" icon="delete" class="text-danger"/>'+"</a>";
                            return html;
                        }
                    }
                ],
                'search_from': '#complaint_search_form'
            }
        };
        $('#role_modal').on('shown.bs.modal', function (e) {
            window._loading("#role_modal .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#role_modal').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#role_modal .modal-body", false);
                    $("#role_modal .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#role_modal .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#update_overtime_request .modal-body", __("order_form_fetch_error"));
                });
        });
        $('#role_modal').on('hidden.bs.modal', function (e) {
            $("#role_modal .modal-body").html("");
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
    <script src="{{ mix('js/pages/features/miscellaneous/treeview.js') }}"></script>
@endsection

@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('Nghỉ phép của tôi')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#add_leave_modal">
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
            <div class="datatable datatable-bordered datatable-head-custom" id="leaves_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
    <!--begin::Modal-->
    <div class="modal fade" id="add_leave_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add_leave_modal1" aria-hidden="true">
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
                        @include('pages.leaves.create', ['leaveTypes'=>$leaveTypes])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('scripts')
    <script>
        function htmlDecode(input) {
            let doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
        window._tables = {
            'leaves_list': {
                'url': '{{ route('leaves.ajax.lists') }}',
                'columns': [
                    {
                        field: 'leave_type_id',
                        title: '{{ __('Các lý do nghỉ') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return (row.leave_type != null)?row.leave_type.type_name:''
                        }
                    },
                    {
                        field: 'reason',
                        title: '{{ __('Lý do') }}',
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return row.reason
                        }
                    },
                    {
                        field: 'leave_types',
                        title: '{{ __('Loại nghỉ') }}',
                        width: 110,
                        sortable: false,
                        // autoHide: false,
                        template: function (row) {
                            var status = {
                                1: {'class': ' label-light-danger'},
                                2: {'class': ' label-light-warning'},
                                3: {'class': ' label-light-primary'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.leave_time_types].class + ' label-inline">' + row.leave_types + '</span>';
                        }
                    },
                    {
                        field: 'total_day_leave',
                        title: '{{ __('Thời lượng yêu cầu') }}',
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return htmlDecode(row.time_detail)
                        }
                    },
                    {
                        field: 'is_salary',
                        title: '{{ __('Tính lương') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 120,
                        template: function (row) {
                            var status = {
                                0: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            return '<span style="width: 110px" class="label label-lg font-weight-bold ' + status[row.is_salary].class + ' label-inline">' + row.salary + '</span>';
                        }
                    },
                    {
                        field: 'confirm',
                        title: '{{ __('Trạng thái') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 150,
                        template: function (row) {
                            var status = {
                                0: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let string = '';
                            if(row.status != 2){
                                string = ' label-light-danger';
                            } else {
                                string = status[row.confirm].class;
                            }
                            return '<span class="label label-lg font-weight-bold ' + string + ' label-inline">' + row.confirm_list + '</span>';
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
                            if (row.status == 1 && row.confirm == 0) {
                                html = "<a href='#' onclick='deleteRequest("+row.leave_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            }
                            return html;
                        }
                    }
                ],
                'search_from': '#leave_list_search_form'
            }
        };

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
                        url: '{{ route("leaves.ajax.delete") }}',
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
                        window._tables.leaves_list.datatable.reload();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

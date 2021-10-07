@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('admin.employee.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_e_details_contract')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Cập nhật hợp đồng') }}</span>
                    </div>
                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupEmployeeContract">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="employeeContract"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade px-0" id="popupEmployeeContract" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupEmployeeContract" aria-hidden="true" data-form-url="{{ route('employee_managements.ajax.create_form_contract', request()->route('id'))}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Hợp đồng') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body1">
                    <div class="card card-custom" style="min-height: 50px"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">

        window._tables = {
            'employeeContract': {
                'url': '{{ route('employee_managements.contractList', request()->route('id')) }}',
                'columns': [
                    {
                        field: 'stt',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        autoHide: false,
                        width: 40,
                        textAlign: "center",
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'contract_type_id',
                        title: '{{ __('xin_e_details_contract_type') }}',
                        sortable: false,
                        autoHide: false,
                        template: function (row) {
                            return row.contract_type.name;
                        }
                    },
                    {
                        field: 'title',
                        title: '{{ __('xin_e_details_contract_title') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.title;
                        }
                    },
                    {
                        field: 'from_date',
                        title: '{{ __('xin_start_date') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.from_date;
                        }
                    },
                    {
                        field: 'to_date',
                        title: '{{ __('xin_end_date') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.to_date;
                        }
                    },
                    {
                        field: 'designation_id',
                        title: '{{ __('dashboard_designation') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.designation.designation_name;
                        }
                    },
                    {
                        field: 'contract_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {

                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupEmployeeContract' data-id='"+row.contract_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.contract_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        $('#popupEmployeeContract').on('shown.bs.modal', function (e) {
            window._loading("#popupEmployeeContract .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupEmployeeContract').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupEmployeeContract .card-custom", false);
                    $("#popupEmployeeContract .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupEmployeeContract .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupEmployeeContract .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupEmployeeContract').on('hidden.bs.modal', function (e) {
            $("#popupEmployeeContract .card-custom").html("");
        });


        function reloadTable() {
            window._tables.employeeContract.datatable.reload();
        }

        function deleteRequest(id) {
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
                        url: '{{ route("employee_managements.deleteContract") }}',
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
                        reloadTable();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        };

    </script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

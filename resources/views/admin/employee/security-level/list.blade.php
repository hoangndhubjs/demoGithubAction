@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('admin.employee.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_esecurity_level_title')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Cập nhật cấp độ bảo mật') }}</span>
                    </div>
                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupEmployeeSecurityLevel">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="employeeSecurityLevel"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade px-0" id="popupEmployeeSecurityLevel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupEmployeeSecurityLevel" aria-hidden="true" data-form-url="{{ route('employee_managements.ajax.create_form_security-level', request()->route('id'))}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Cập nhật cấp độ bảo mật') }}</h5>
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
            'employeeSecurityLevel': {
                'url': '{{ route('employee_managements.securityList', request()->route('id')) }}',
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
                        field: 'security_type',
                        title: '{{ __('xin_esecurity_level_title') }}',
                        sortable: false,
                        autoHide: false,
                        template: function (row) {
                            return row.security_level.name;
                        }
                    },
                    {
                        field: 'date_of_clearance',
                        title: '{{ __('xin_start_date') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.date_of_clearance;
                        }
                    },
                    {
                        field: 'expiry_date',
                        title: '{{ __('xin_end_date') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 100,
                        template: function (row) {
                            return row.expiry_date;
                        }
                    },
                    {
                        field: 'work_experience_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {

                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupEmployeeSecurityLevel' data-id='"+row.security_level_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.security_level_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        $('#popupEmployeeSecurityLevel').on('shown.bs.modal', function (e) {
            window._loading("#popupEmployeeSecurityLevel .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupEmployeeSecurityLevel').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupEmployeeSecurityLevel .card-custom", false);
                    $("#popupEmployeeSecurityLevel .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupEmployeeSecurityLevel .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupEmployeeSecurityLevel .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupEmployeeSecurityLevel').on('hidden.bs.modal', function (e) {
            $("#popupEmployeeSecurityLevel .card-custom").html("");
        });


        function reloadTable() {
            window._tables.employeeSecurityLevel.datatable.reload();
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
                        url: '{{ route("employee_managements.deleteSecurityLevel") }}',
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

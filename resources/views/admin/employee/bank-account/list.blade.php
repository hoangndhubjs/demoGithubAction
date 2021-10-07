@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('admin.employee.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_e_details_baccount')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_e_details_baccount_update') }}</span>
                    </div>
                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupBankAccount">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="bankAccount"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade px-0" id="popupBankAccount" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupBankAccount" aria-hidden="true" data-form-url="{{ route('employee_managements.ajax.create_form_bank_account', request()->route('id'))}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_e_details_baccount') }}</h5>
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
            'bankAccount': {
                'url': '{{ route('employee_managements.baccountList', request()->route('id')) }}',
                'columns': [
                    {
                        field: 'stt',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width: 40,
                        textAlign: "center",
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'account_title',
                        title: '{{ __('xin_e_details_acc_title') }}',
                        sortable: false,
                        autoHide: false,
                        width: 120,
                        template: function (row) {
                            return row.account_title;
                        }
                    },
                    {
                        field: 'bank_name',
                        title: '{{ __('xin_e_details_bank_name') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return row.bank_name;
                        }
                    },
                    {
                        field: 'account_number',
                        title: '{{ __('xin_e_details_acc_number') }}',
                        textAlign: "center",
                        sortable: false,
                        autoHide: false,
                        width: 120,
                        template: function (row) {
                            return row.account_number;
                        }
                    },
                    {
                        field: 'bank_branch',
                        title: '{{ __('xin_e_details_bank_branch') }}',
                        width: 200,
                        sortable: false,
                        template: function (row) {
                            return row.bank_branch;
                        }
                    },
                    {
                        field: 'bankaccount_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        // autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            var status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupBankAccount' data-id='"+row.bankaccount_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.bankaccount_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        $('#popupBankAccount').on('shown.bs.modal', function (e) {
            window._loading("#popupBankAccount .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupBankAccount').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupBankAccount .card-custom", false);
                    $("#popupBankAccount .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupBankAccount .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupBankAccount .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupBankAccount').on('hidden.bs.modal', function (e) {
            $("#popupBankAccount .card-custom").html("");
        });

        function reloadTable() {
            window._tables.bankAccount.datatable.reload();
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
                        url: '{{ route("employee_managements.deleteBankAccount") }}',
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

@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('employees.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_employee_immigration')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_employee_img_info_updated') }}</span>
                    </div>
                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupImmigration">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="immigration"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade px-0" id="popupImmigration" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupImmigration" aria-hidden="true" data-form-url="{{ route('ajax.create_form_immigration')}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_employee_immigration') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body1">
                    <div class="card card-custom" style="min-height: 50px">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        var stt = 1;

        window._tables = {
            'immigration': {
                'url': '{{ route('immigrationList') }}',
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
                        field: 'document_type_id',
                        title: '{{ __('xin_e_details_documents') }}',
                        sortable: false,
                        autoHide: false,
                        width: 120,
                        template: function (row) {
                            return row.document_type ? row.document_type.document_type : '';
                        }
                    },
                    {
                        field: 'document_number',
                        title: '{{ __('xin_employee_document_number') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return row.document_number;
                        }
                    },
                    {
                        field: 'issue_date',
                        title: '{{ __('xin_issue_date') }}',
                        sortable: false,
                        width: 140,
                        textAlign: "center",
                        template: function (row) {
                            return row.issue_date;
                        }
                    },
                    {
                        field: 'document_file',
                        title: '{{ __('xin_e_details_document') }}',
                        sortable: false,
                        width: 140,
                        textAlign: "center",
                        template: function (row) {
                            let img = "";
                            img = "<a href='"+row.document_file+"' target='_blank'><img src='"+row.document_file+"' width='60px'></a>"
                            return img;
                        }
                    },
                    {
                        field: 'place_of_issue',
                        sortable: false,
                        autoHide: false,
                        title: '{{ __('place_of_issue') }}',
                        template: function (row) {
                            return row.place_of_issue;
                        }
                    },
                    {
                        field: 'immigration_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            var status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupImmigration' data-id='"+row.immigration_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.immigration_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        function reloadTable() {
            window._tables.immigration.datatable.reload();
        }

        $('#popupImmigration').on('shown.bs.modal', function (e) {
            window._loading("#popupImmigration .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupImmigration').data('form-url');
                $.ajax({
                    url: url,
                    data: {
                        id: id
                    }
                })
                .done(function (response) {
                    window._loading("#popupImmigration .card-custom", false);
                    $("#popupImmigration .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupImmigration .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupImmigration .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupImmigration').on('hidden.bs.modal', function (e) {
            $("#popupImmigration .card-custom").html("");
        });

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
                        url: '{{ route("deleteImmigration") }}',
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

@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('employees.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_employee_emergency_contacts')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_employee_emergency_contacts_update') }}</span>
                    </div>
                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupContact">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="contact"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade px-0" id="popupContact" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupContact" aria-hidden="true" data-form-url="{{ route('ajax.create_form_contact')}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_employee_emergency_contacts') }}</h5>
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
            'contact': {
                'url': '{{ route('contactList') }}',
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
                        field: 'contact_name',
                        title: '{{ __('xin_name') }}',
                        sortable: false,
                        autoHide: false,
                        width: 120,
                        template: function (row) {

                            if(row.is_primary == 1){
                                return row.contact_name + " <span class=" + "badge" + " style=color:#fff;background-color:#28a745"+">" + '{{ __('xin_theme_primary') }}' + "</span>";
                            } else {
                                return row.contact_name;
                            }
                        }
                    },
                    {
                        field: 'relation',
                        title: '{{ __('xin_e_details_relation') }}',
                        textAlign: "center",
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            if (row.relation == 'Self'){
                                var relationship = '{{ __('xin_self') }}'
                            } else if(row.relation == 'Parent') {
                                var relationship = '{{ __('xin_parent') }}'
                            } else if(row.relation == 'Spouse') {
                                var relationship = '{{ __('xin_spouse') }}'
                            } else if(row.relation == 'Child') {
                                var relationship = '{{ __('xin_child') }}'
                            } else if(row.relation == 'Sibling') {
                                var relationship = '{{ __('xin_sibling') }}'
                            } else if(row.relation == 'In Laws') {
                                var relationship = '{{ __('xin_in_laws') }}'
                            }

                            return relationship;
                        }
                    },
                    {
                        field: 'email',
                        title: '{{ __('dashboard_email') }}',
                        textAlign: "center",
                        sortable: false,
                        autoHide: false,
                        template: function (row) {
                            return row.work_email;
                        }
                    },
                    {
                        field: 'mobile_phone',
                        sortable: false,
                        // autoHide: false,
                        width: 110,
                        title: '{{ __('xin_phone') }}',
                        template: function (row) {
                            return row.mobile_phone;
                        }
                    },
                    {
                        field: 'contact_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            var status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupContact' data-id='"+row.contact_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.contact_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        $('#popupContact').on('shown.bs.modal', function (e) {
            window._loading("#popupContact .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupContact').data('form-url');
                $.ajax({
                    url: url,
                    data: {
                        id: id
                    }
                })
                .done(function (response) {
                    window._loading("#popupContact .card-custom", false);
                    $("#popupContact .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupContact .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupContact .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupContact').on('hidden.bs.modal', function (e) {
            $("#popupContact .card-custom").html("");
        });

        function reloadTable() {
            window._tables.contact.datatable.reload();
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
                        url: '{{ route("deleteContact") }}',
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

@extends('layout.default')

@section('content')
    <div class="d-flex flex-row">

        @include('admin.constants.nav')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_add_new')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_e_details_dtype') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_add_document_type">
                        <div class="form-group mb-2">
                            <label class="col-form-label">{{ __('xin_e_details_dtype') }}</label>
                            <div class="">
                               <input type="text" class="form-control" name="document_type" placeholder="Nhập tên loại tài liệu">
                            </div>
                        </div>
                        <div class="text-right m-0 p-0">
                            <button type="submit" class="btn btn-primary btn-sm" id="sm_add_document_type"> 
                                <x-icon type="svg" category="Navigation" icon="Double-check"/> {{ __("xin_save") }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-20">
                        <h6>{{ __("xin_role_list") }} {{ __("xin_e_details_dtype") }}</h6>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="document_type"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="show_document_type" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="show_document_type" aria-hidden="true" data-form-url="{{ route('config.constants.show_document_type')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_edit_document_type") }}</h5>
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
    <script type="text/javascript" src="{{ mix('js/sidebar_mobile.js') }}"></script>
    <script>
        window.document_type_remove_url = '{{ route("config.constants.delete_document_type") }}';
        window.document_type_update_url = '{{ route("config.constants.update_document_type") }}';
        window._tables = {
            'document_type': {
                'url': '{{ route('config.constants.list_document_type') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width: 100,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'name',
                        title: '{{ __('xin_e_details_dtype') }}',
                        autoHide: false,
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return row.document_type;
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        width: 90,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "";
                            html = "<a href='#' data-toggle='modal' data-target='#show_document_type' data-id='"+row.document_type_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteContractType("+row.document_type_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script>
        formAddDocumentType = document.getElementById('form_add_document_type');
        submitButton = formAddDocumentType.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formAddDocumentType,
            {
                fields: {
                    'document_type': {
                        validators: {
                            notEmpty: {
                                message: '{{ __('xin_employee_error_d_type') }}',
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    bootstrap: new FormValidation.plugins.Bootstrap({})
                }
            }
        ).on('core.form.valid', function () {
            $('#sm_add_document_type').prop('disabled', true);
            $.ajax({
                url: '{{ route('config.constants.add_document_type')}}',
                data: $('#form_add_document_type').serialize(),
                method: 'POST',
                success: function(response) {
                    $('#sm_add_document_type').prop('disabled', false);
                    formValidator.resetForm(true);
                    window._tables.document_type && window._tables.document_type.datatable.reload();
                    toastr.success(response.data);
                },
                error: function (response) {
                    $('#sm_add_document_type').prop('disabled', false);
                    toastr.error(response.error ?? __("error"));
                },
            })
        });

        $('#show_document_type').on('show.bs.modal', function (e) {
            window._loading("#show_document_type .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#show_document_type').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#show_document_type .modal-body", false);
                    $("#show_document_type .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#show_document_type .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#show_document_type .modal-body", __("order_form_fetch_error"));
                });
        });

        var deleteContractType = function (id) {
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
                        url: window.document_type_remove_url,
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
                        setTimeout(function () {window._tables.document_type.datatable.reload()}, 400);
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
@endsection

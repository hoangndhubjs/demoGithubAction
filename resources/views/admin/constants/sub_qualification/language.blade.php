@extends('admin.constants.qualification')
@section('body')
    <div class="card-title align-items-start flex-column">
        <h6 class="card-label font-weight-bolder text-dark">
            {{ __('xin_add_new')  }}
        </h6>
        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_e_details_language') }}</span>
    </div>
    <form id="form_add_language">
        <div class="form-group mb-2">
            <label class="col-form-label">{{ __('xin_e_details_language') }}</label>
            <div class="">
                <input type="text" class="form-control" name="language" placeholder="Nhập tên ngôn ngữ">
            </div>
        </div>
        <div class="text-right m-0 p-0">
            <button type="submit" class="btn btn-primary btn-sm" id="sm_add_language"> 
                <x-icon type="svg" category="Navigation" icon="Double-check"/> {{ __("xin_save") }}
            </button>
        </div>
    </form>

    <div class="mt-20">
        <h6>{{ __("xin_role_list") }} {{ __("xin_e_details_language") }}</h6>
    </div>
    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="language"></div>
    <div class="modal fade" id="show_language" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="show_language" aria-hidden="true" data-form-url="{{ route('config.constants.show_qualification_language')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_edit_language") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        window.language_remove_url = '{{ route("config.constants.delete_qualification_language") }}';
        window.language_update_url = '{{ route("config.constants.update_qualification_language") }}';
        window._tables = {
            'language': {
                'url': '{{ route('config.constants.list_qualification_language') }}',
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
                        field: 'language',
                        title: '{{ __('xin_e_details_language') }}',
                        autoHide: false,
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return row.name;
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
                            html = "<a href='#' data-toggle='modal' data-target='#show_language' data-id='"+row.language_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteLanguage("+row.language_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
    </script>
    <script>
        formAddLanguage = document.getElementById('form_add_language');
        submitButton = formAddLanguage.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formAddLanguage,
            {
                fields: {
                    'language': {
                        validators: {
                            notEmpty: {
                                message: '{{ __('xin_error_education_language') }}',
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
            $('#sm_add_language').prop('disabled', true);
            $.ajax({
                url: '{{ route('config.constants.add_qualification_language')}}',
                data: $('#form_add_language').serialize(),
                method: 'POST',
                success: function(response) {
                    $('#sm_add_language').prop('disabled', false);
                    formValidator.resetForm(true);
                    window._tables.language && window._tables.language.datatable.reload();
                    toastr.success(response.data);
                },
                error: function (response) {
                    $('#sm_add_language').prop('disabled', false);
                    toastr.error(response.error ?? __("error"));
                },
            })
        });

        $('#show_language').on('show.bs.modal', function (e) {
            window._loading("#show_language .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#show_language').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#show_language .modal-body", false);
                    $("#show_language .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#show_language .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#show_language .modal-body", __("order_form_fetch_error"));
                });
        });

        var deleteLanguage = function (id) {
            console.log(id);
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
                        url: window.language_remove_url,
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
                        setTimeout(function () {window._tables.language.datatable.reload()}, 400);
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
@endsection
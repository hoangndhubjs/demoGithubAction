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
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('xin_rec_job_categories') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_add_job_category">
                        <div class="form-group mb-2">
                            <label class="col-form-label">{{ __('xin_rec_job_categories') }}</label>
                            <div class="">
                               <input type="text" class="form-control" name="job_category" placeholder="Nhập danh mục công việc">
                            </div>
                        </div>
                        <div class="text-right m-0 p-0">
                            <button type="submit" class="btn btn-primary btn-sm" id="sm_add_job_category"> 
                                <x-icon type="svg" category="Navigation" icon="Double-check"/> {{ __("xin_save") }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-20">
                        <h6>{{ __("xin_role_list") }} {{ __("xin_rec_job_categories") }}</h6>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="job_category"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="show_job_category" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="show_job_category" aria-hidden="true" data-form-url="{{ route('config.constants.show_job_category')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_rec_edit_job_category") }}</h5>
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
        window.job_category_remove_url = '{{ route("config.constants.delete_job_category") }}';
        window.job_category_update_url = '{{ route("config.constants.update_job_category") }}';
        window._tables = {
            'job_category': {
                'url': '{{ route('config.constants.list_job_category') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width: 80,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'name',
                        title: '{{ __('xin_rec_job_categories') }}',
                        autoHide: false,
                        sortable: false,
                        width: 200,
                        template: function (row) {
                            return row.category_name;
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        width: 90,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "";
                            html = "<a href='#' data-toggle='modal' data-target='#show_job_category' data-id='"+row.category_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteJobCategory("+row.category_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script>
        formAddJobCategory = document.getElementById('form_add_job_category');
        submitButton = formAddJobCategory.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formAddJobCategory,
            {
                fields: {
                    'job_category': {
                        validators: {
                            notEmpty: {
                                message: '{{ __('xin_error_jobpost_type') }}',
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
            $('#sm_add_job_category').prop('disabled', true);
            $.ajax({
                url: '{{ route('config.constants.add_job_category')}}',
                data: $('#form_add_job_category').serialize(),
                method: 'POST',
                success: function(response) {
                    $('#sm_add_job_category').prop('disabled', false);
                    formValidator.resetForm(true);
                    window._tables.job_category && window._tables.job_category.datatable.reload();
                    toastr.success(response.data);
                },
                error: function (response) {
                    $('#sm_add_job_category').prop('disabled', false);
                    toastr.error(response.error ?? __("error"));
                },
            })
        });

        $('#show_job_category').on('show.bs.modal', function (e) {
            window._loading("#show_job_category .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#show_job_category').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#show_job_category .modal-body", false);
                    $("#show_job_category .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#show_job_category .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#show_job_category .modal-body", __("order_form_fetch_error"));
                });
        });

        var deleteJobCategory = function (id) {
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
                        url: window.job_category_remove_url,
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
                        setTimeout(function () {window._tables.job_category.datatable.reload()}, 400);
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
@endsection
